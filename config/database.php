<?php
require_once APP_PATH . '/helpers/EnvHelper.php';

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
function conectarPDO($host, $port, $db, $user, $pass)
{
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => false // evita conexiones que expiran
    ]);
    return $conn;
}

// ===============================================
// 4️⃣ Intentar conectar (con reconexión automática)
// ===============================================
try {
    $conn = conectarPDO($db_host, $db_port, $db_name, $db_user, $db_pass);
} catch (PDOException $e) {
    // Verificar si el error es "MySQL server has gone away" o similar
    $errorMsg = $e->getMessage();
    if (strpos($errorMsg, 'MySQL server has gone away') !== false ||
        strpos($errorMsg, 'Lost connection') !== false) {
        // Intentar reconectar una vez
        try {
            $conn = conectarPDO($db_host, $db_port, $db_name, $db_user, $db_pass);
        } catch (PDOException $e2) {
            // Si sigue fallando, redirigir al usuario
            header("Location: /error.php?msg=db_connection");
            exit();
        }
    } else {
        // Otro tipo de error → redirigir también
        header("Location: /error.php?msg=" . urlencode($errorMsg));
        exit();
    }
}
