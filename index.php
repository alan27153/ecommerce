<?php
// ===============================================
// 1. Configuración inicial
// ===============================================
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('ADMIN_APP_PATH', BASE_PATH . '/admin/app');
define('LIB_PATH', BASE_PATH . '/lib');
define('ASSETS_PATH', BASE_PATH . '/assets');


// Composer autoload
require_once BASE_PATH . '/vendor/autoload.php';

// ===============================================
// 2. Variables de entorno y conexión BD
// ===============================================
// Esto ya lo hace config/database.php
require_once CONFIG_PATH . '/database.php';

// En este punto, ya tienes $conn disponible (desde database.php)

// ===============================================
// 3. Configuración global
// ===============================================
date_default_timezone_set('America/Lima');

// ===============================================
// 4. Helpers
// ===============================================
foreach (glob(APP_PATH . '/helpers/*.php') as $helper) {
    require_once $helper;
}
// ===============================================
// 5. Rutas (Front Controller)
// ===============================================
require_once APP_PATH . '/routes/web.php';
