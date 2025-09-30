<?php
// ===============================================
// 1. Configuración inicial
// ===============================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir rutas absolutas
define('BASE_PATH', __DIR__);              // /htdocs/ecommerce
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('LIB_PATH', BASE_PATH . '/lib');
define('ASSETS', BASE_PATH . '/assets');


// ===============================================
// 2. Cargar configuración global
// ===============================================
require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/payment.php';

// ===============================================
// 3. Cargar librerías externas (PHPMailer, etc.)
// ===============================================
require_once LIB_PATH . '/phpmailer/src/PHPMailer.php';
require_once LIB_PATH . '/phpmailer/src/SMTP.php';
require_once LIB_PATH . '/phpmailer/src/Exception.php';

// ===============================================
// 4. Cargar helpers automáticamente
// ===============================================
foreach (glob(APP_PATH . '/helpers/*.php') as $helper) {
    require_once $helper;
}

// ===============================================
// 5. Iniciar Router
// ===============================================
require_once APP_PATH . '/routes/web.php';
