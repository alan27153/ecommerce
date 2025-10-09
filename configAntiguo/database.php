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
// 3️⃣ Conexión con MySQL usando PDO
// ===============================================
try {
    $conn = new PDO(
        "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass
    );
    // Configurar PDO para lanzar excepciones ante errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // DEBUG OPCIONAL:
    // echo "Conexión exitosa a la base de datos {$db_name} en {$db_host}:{$db_port}";
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
