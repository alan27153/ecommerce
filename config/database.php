<?php
require_once APP_PATH . '/helpers/EnvHelper.php';

// Cargar variables de entorno
EnvHelper::load(BASE_PATH . '/.env');

// Obtener valores
$db_host = EnvHelper::get('DB_HOST', 'localhost');
$db_port = EnvHelper::get('DB_PORT', '3306');
$db_name = EnvHelper::get('DB_DATABASE', 'ecommerce');
$db_user = EnvHelper::get('DB_USERNAME', 'root');
$db_pass = EnvHelper::get('DB_PASSWORD', '');

// Conexión con MySQL (MySQLi)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la BD: " . $conn->connect_error);
}
