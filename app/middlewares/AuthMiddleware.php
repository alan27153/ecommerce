<?php

class AuthMiddleware
{
    // Verifica si el usuario está autenticado
    public static function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
    }

    // Verifica que el usuario tenga uno de los roles permitidos
    public static function checkRole(array $allowedRoles)
    {
        self::checkAuth();

        $user = $_SESSION['user'];
        if (!in_array($user['role'], $allowedRoles)) {
            http_response_code(403);
            echo "🚫 No tienes permiso para acceder a esta sección.";
            exit;
        }
    }
}
