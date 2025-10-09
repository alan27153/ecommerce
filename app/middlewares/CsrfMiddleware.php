<?php
// /app/middlewares/CsrfMiddleware.php
namespace App\Middlewares;

/**
 * CsrfMiddleware
 * - Genera tokens criptográficos seguros (bin2hex(random_bytes(32)))
 * - Mantiene un pequeño set de tokens en la sesión (token => expiry)
 * - Verifica tokens y los invalida (one-time use)
 */
class CsrfMiddleware
{
    // Time to live del token (segundos)
    const TOKEN_TTL = 60 * 60; // 1 hora
    // Máximo de tokens a guardar en sesión (evitar crecimiento infinito)
    const MAX_TOKENS = 50;

    protected static function ensureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Genera un token nuevo y lo guarda en la sesión (con expiración)
    public static function generateToken(): string
    {
        self::ensureSession();

        if (!isset($_SESSION['csrf_tokens']) || !is_array($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }

        // Limpia expirados
        $now = time();
        foreach ($_SESSION['csrf_tokens'] as $tok => $expiry) {
            if ($expiry < $now) {
                unset($_SESSION['csrf_tokens'][$tok]);
            }
        }

        // Si hay demasiados tokens, elimina los más antiguos
        if (count($_SESSION['csrf_tokens']) >= self::MAX_TOKENS) {
            // ordenar por expiry asc y quitar el primero
            asort($_SESSION['csrf_tokens']);
            $firstKey = array_key_first($_SESSION['csrf_tokens']);
            unset($_SESSION['csrf_tokens'][$firstKey]);
        }

        $token = bin2hex(random_bytes(32)); // 64 hex chars = 256 bits
        $_SESSION['csrf_tokens'][$token] = $now + self::TOKEN_TTL;

        return $token;
    }

    // Verifica un token (lo invalida si es válido) — devuelve true/false
    public static function verifyToken(?string $token): bool
    {
        self::ensureSession();

        if (empty($token)) {
            return false;
        }

        if (!isset($_SESSION['csrf_tokens'][$token])) {
            return false;
        }

        $expiry = $_SESSION['csrf_tokens'][$token];
        if ($expiry < time()) {
            // expirado -> eliminar
            unset($_SESSION['csrf_tokens'][$token]);
            return false;
        }

        // token válido -> invalidarlo (one-time use)
        unset($_SESSION['csrf_tokens'][$token]);
        return true;
    }

    // Helper para insertar el input hidden en formularios (renderizar en la vista)
    public static function inputField(): string
    {
        $t = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '">';
    }

    // Helper para generar un meta tag (útil para JS / AJAX)
    public static function metaTag(): string
    {
        $t = self::generateToken();
        return '<meta name="csrf-token" content="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '">';
    }
}
