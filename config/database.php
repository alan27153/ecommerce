<?php
// Detectar automáticamente la carpeta base del proyecto
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__)); // /ecommerce
}

// Intentar ubicar el helper de entorno de manera flexible
$possibleAppPaths = [
    BASE_PATH . '/app',           // frontend
    BASE_PATH . '/admin/app'      // panel admin
];

$envHelperPath = null;
foreach ($possibleAppPaths as $path) {
    if (file_exists($path . '/helpers/EnvHelper.php')) {
        $envHelperPath = $path . '/helpers/EnvHelper.php';
        break;
    }
}

if ($envHelperPath) {
    require_once $envHelperPath;
} else {
    die('❌ No se encontró EnvHelper.php en ninguna ruta esperada.');
}

// ===============================================
// 1️⃣ Cargar variables de entorno desde el .env
// ===============================================
EnvHelper::load(BASE_PATH . '/.env');

// ===============================================
// 2️⃣ Obtener valores de conexión
// ===============================================
$db_host = EnvHelper::get('DB_HOST', 'localhost');
$db_port = EnvHelper::get('DB_PORT', '3306');
$db_name = EnvHelper::get('DB_DATABASE', 'ecommerce');
$db_user = EnvHelper::get('DB_USERNAME', 'root');
$db_pass = EnvHelper::get('DB_PASSWORD', '');

// ===============================================
// 3️⃣ Función para crear conexión PDO
// ===============================================
if (!function_exists('conectarPDO')) {
    function conectarPDO($host, $port, $db, $user, $pass)
    {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $conn = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false
        ]);
        return $conn;
    }
}


// ===============================================
// 4️⃣ Intentar conectar con manejo de errores
// ===============================================
try {
    $conn = conectarPDO($db_host, $db_port, $db_name, $db_user, $db_pass);
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
