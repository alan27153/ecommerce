<?php
namespace Admin\Middlewares;

class AuthMiddleware {
    public static function handle() {
        session_start();

        // Si no está logueado, redirigir al login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /ecommerce/admin/login');
            exit;
        }

        // Si no es admin, denegar acceso
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado.";
            exit;
        }
    }
}
