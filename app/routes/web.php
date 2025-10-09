<?php
// require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/controllers/HomeController.php';
require_once APP_PATH . '/controllers/ProductController.php';
require_once APP_PATH . '/controllers/CartController.php';
require_once APP_PATH . '/controllers/AuthController.php';
require_once APP_PATH . '/controllers/ClientAuthController.php'; // NUEVO
require_once APP_PATH . '/middlewares/AuthMiddleware.php';

// Detectar la URI solicitada
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = parse_url($uri, PHP_URL_PATH);

// Detectar el subdirectorio base dinámicamente (ej. /ecommerce)
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
$homeController       = new HomeController($conn);
$productController    = new ProductController($conn);
$cartController       = new CartController($conn);
$authController       = new AuthController($conn);
$clientAuthController = new ClientAuthController($conn); // NUEVO

// ==========================
// RUTAS DE FRONTEND
// ==========================
switch ($uri) {
    // Home
    case '/':
    case '/index.php':
        $homeController->index();
        break;

    // Ver producto por ID
    case (preg_match('/^\/product\/(\d+)$/', $uri, $matches) ? true : false):
        $productController->show($matches[1]);
        break;

    // Listar todos los productos (para AJAX o frontend)
    case '/products/load':
        $productController->list();
        break;

    // Carrito
    case '/cart':
        $cartController->index();
        break;

    // ==========================
    // LOGIN / REGISTRO ADMIN
    // ==========================
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

    // ==========================
    // LOGIN / REGISTRO CLIENTE
    // ==========================
    case '/client/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientAuthController->login();
        } else {
            $clientAuthController->showLogin();
        }
        break;

    case '/client/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientAuthController->register();
        } else {
            $clientAuthController->showRegister();
        }
        break;

    case '/client/logout':
        $clientAuthController->logout();
        break;

    // ==========================
    // VERIFICACIÓN DE CUENTA CLIENTE
    // ==========================
    case '/client/verify':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientAuthController->verifyAccount();
        } else {
            $clientAuthController->showVerifyForm();
        }
        break;


    // ==========================
    // ADMIN PANEL
    // ==========================
    case '/admin/products':
        AuthMiddleware::checkRole(['admin', 'editor']);
        $productController->index();
        break;

    case '/admin/products/create':
        AuthMiddleware::checkRole(['admin', 'editor']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productController->create($_POST['user'], $_POST);
        } else {
            require APP_PATH . '/views/admin/products/create.php';
        }
        break;

    case (preg_match('/^\/admin\/products\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        AuthMiddleware::checkRole(['admin', 'editor']);
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productController->update($_POST['user'], $id, $_POST);
        } else {
            $producto = $productController->show($id);
            require APP_PATH . '/views/admin/products/edit.php';
        }
        break;

    case (preg_match('/^\/admin\/products\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        AuthMiddleware::checkRole(['admin', 'editor']);
        $id = $matches[1];
        $productController->delete($_POST['user'] ?? null, $id);
        break;

    case '/admin/dashboard':
        AuthMiddleware::checkRole(['admin','editor']);
        require APP_PATH . '/views/admin/dashboard.php';
        break;

    default:
        // Página 404
        http_response_code(404);
        require APP_PATH . '/views/layouts/404.php';
        break;
}
