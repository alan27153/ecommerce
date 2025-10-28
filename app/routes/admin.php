<?php
// ============================
// USAR CONTROLADORES CON NAMESPACES
// ============================

// Importar los namespaces correctos
use Admin\Controllers\AuthController;
use Admin\Controllers\DashboardController;
use Admin\Middlewares\AuthMiddleware;
use Admin\Models\User;

// ============================
// Cargar archivos de clases
// ============================
require_once ADMIN_APP_PATH . '/controllers/AuthController.php';
require_once ADMIN_APP_PATH . '/controllers/DashboardController.php';
require_once ADMIN_APP_PATH . '/middlewares/AuthMiddleware.php';
require_once ADMIN_APP_PATH . '/models/User.php';

// ============================
// Instanciar controladores
// ============================
$authController = new AuthController($conn);
$dashboardController = new DashboardController($conn);

// ============================
// Normalizar URI
// ============================
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// ============================
// RUTAS DEL PANEL ADMIN
// ============================
switch ($uri) {

    // ----------------------------
    // LOGIN ADMIN
    // ----------------------------
    case '/admin/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->loginSubmit();
        } else {
            $authController->login();
        }
        break;

    // ----------------------------
    // LOGOUT ADMIN
    // ----------------------------
    case '/admin/logout':
        $authController->logout();
        break;

    // ----------------------------
    // DASHBOARD ADMIN (protegido)
    // ----------------------------
    case '/admin':
    case '/admin/dashboard':
        AuthMiddleware::handle();
        $dashboardController->index();
        break;

    // ----------------------------
    // PRODUCTOS ADMIN
    // ----------------------------
    case '/admin/products':
        AuthMiddleware::checkRole(['admin', 'editor']);
        $productController->index();
        break;

    case '/admin/products/create':
        AuthMiddleware::checkRole(['admin', 'editor']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productController->create($_POST['user'], $_POST);
            header('Location: /admin/products');
            exit;
        } else {
            require APP_PATH . '/views/admin/products/create.php';
        }
        break;

    case (preg_match('/^\/admin\/products\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        AuthMiddleware::checkRole(['admin', 'editor']);
        $id = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productController->update($_POST['user'], $id, $_POST);
            header('Location: /admin/products');
            exit;
        } else {
            $producto = $productController->show($id);
            require APP_PATH . '/views/admin/products/edit.php';
        }
        break;

    case (preg_match('/^\/admin\/products\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        AuthMiddleware::checkRole(['admin', 'editor']);
        $id = $matches[1];
        $productController->delete($_POST['user'] ?? null, $id);
        header('Location: /admin/products');
        exit;
        break;

    // ----------------------------
    // CATCH-ALL
    // ----------------------------
    default:
        if (!isset($_SESSION['user'])) {
            $authController->login();
            exit;
        }

        http_response_code(404);
        echo "Página no encontrada en el panel admin.";
        break;
}
