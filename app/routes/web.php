<?php
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/controllers/HomeController.php';
require_once APP_PATH . '/controllers/ProductController.php';
require_once APP_PATH . '/controllers/CartController.php';
require_once APP_PATH . '/controllers/AuthController.php';

// Detectar la URI solicitada
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = parse_url($uri, PHP_URL_PATH);

// Detectar el subdirectorio base dinámicamente (ej. /ecommerce en XAMPP)
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($baseDir === '/' || $baseDir === '.' || $baseDir === '\\') {
    $baseDir = '';
}
if ($baseDir && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}
if ($uri === '' || $uri === false) {
    $uri = '/';
}

// Instanciar controladores con conexión a la BD
$homeController    = new HomeController($conn);
$productController = new ProductController($conn);
$cartController    = new CartController($conn);
$authController    = new AuthController($conn);

// Ruteo simple
switch ($uri) {
    case '/':
    case '/index.php':
        $homeController->index();
        break;

    case '/products':
        $productController->index();
        break;

    case '/products/load':
        $productController->list();
        break;

    case '/cart':
        $cartController->index();
        break;

    // Rutas de autenticación
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;

    case '/logout':
        $authController->logout();
        break;

    default:
        http_response_code(404);
        require APP_PATH . '/views/layouts/404.php';
        break;
}
