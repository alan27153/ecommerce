<?php
namespace App\Middlewares;

class RateLimitMiddleware {
    public static function check($key, $limit = 5, $seconds = 60) {
        if (!isset($_SESSION)) session_start();

        if (!isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = ['count' => 0, 'time' => time()];
        }

        $data = &$_SESSION['rate_limit'][$key];

        if (time() - $data['time'] > $seconds) {
            $data = ['count' => 0, 'time' => time()];
        }

        $data['count']++;

        if ($data['count'] > $limit) {
            die("Demasiados intentos. Intenta de nuevo más tarde.");
        }
    }
}
