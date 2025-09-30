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
// 3️⃣ Conexión con MySQL usando MySQLi
// ===============================================
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// ===============================================
// 4️⃣ Opcional: establecer charset
// ===============================================
$conn->set_charset('utf8mb4');

// ===============================================
// DEBUG OPCIONAL
// ===============================================
// echo "Conexión exitosa a la base de datos {$db_name} en {$db_host}:{$db_port}";
