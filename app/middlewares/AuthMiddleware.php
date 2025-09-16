<?php
namespace App\Middlewares;

class AuthMiddleware {
    public static function handle() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            // Si no hay sesión, redirige al login
            header("Location: /auth/login.php");
            exit();
        }
    }
}
