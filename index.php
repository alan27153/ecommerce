<?php
// ===============================================
// 1. Configuración inicial
// ===============================================

// Activar logs (en producción oculta errores en pantalla)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Rutas base
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('LIB_PATH', BASE_PATH . '/lib');
define('ASSETS_PATH', BASE_PATH . '/assets');

// Composer autoload
require_once BASE_PATH . '/vendor/autoload.php';

// ===============================================
// 2. Variables de entorno con Dotenv
// ===============================================
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Conectar a la BD usando .env
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_DATABASE'] ?? 'ecommerce';
$dbUser = $_ENV['DB_USERNAME'] ?? 'root';
$dbPass = $_ENV['DB_PASSWORD'] ?? '';


try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Error DB: " . $e->getMessage());
    die("Error al conectar con la base de datos.");
}

// ===============================================
// 3. Configuración global (timezone, locale, etc.)
// ===============================================
date_default_timezone_set('America/Lima'); // cambia según tu país

// ===============================================
// 4. Cargar helpers automáticamente
// ===============================================
foreach (glob(APP_PATH . '/helpers/*.php') as $helper) {
    require_once $helper;
}

// ===============================================
// 5. Iniciar el sistema de rutas (Front Controller)
// ===============================================
require_once APP_PATH . '/routes/web.php';
