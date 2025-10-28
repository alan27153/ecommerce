<!-- 
// ============================
// Cargar controladores y middlewares solo una vez
// ============================
require_once ADMIN_APP_PATH . '/controllers/AuthController.php';
require_once ADMIN_APP_PATH . '/controllers/DashboardController.php';
require_once ADMIN_APP_PATH . '/middlewares/AuthMiddleware.php';
require_once ADMIN_APP_PATH . '/models/User.php';

$authController = new AuthController($conn);

// ============================
// Obtener la ruta actual
// ============================
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($baseDir, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$uri = trim($uri, '/');

// ============================
// RUTAS
// ============================

// ----------------------------
// Login
// ----------------------------
if ($uri === 'ecommerce/admin/login') {
    // Si es POST, procesar login; si no, mostrar formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->loginSubmit();
    } else {
        $authController->login();
    }
    exit;
}

// ----------------------------
// Logout
// ----------------------------
if ($uri === 'ecommerce/admin/logout') {
    (new AuthController())->logout();
    exit;
}

// ----------------------------
// Dashboard (protegido)
// ----------------------------
// if ($uri === 'ecommerce/admin' || $uri === 'ecommerce/admin/dashboard') {
//     AuthMiddleware::handle();
//     (new DashboardController())->index();
//     exit;
// }

exit;

if ($uri === 'ecommerce/admin/dashboard') {
    echo 4;
    AuthMiddleware::handle();
    (new DashboardController())->index();
    exit;
}

// ----------------------------
// Ruta general (catch-all)
// ----------------------------

if (true) {
    echo "login defecto";
        $authController->login();
    
    exit;
}

// ----------------------------
// 404
// ----------------------------

http_response_code(404);
echo "Página no encontrada";
exit; -->
