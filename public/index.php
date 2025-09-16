<?php
// ====================================================
// 1. Autoload de Composer (carga librerías externas y PSR-4)
// ====================================================
require_once __DIR__ . '/../vendor/autoload.php';

// ====================================================
// 2. Cargar variables de entorno (.env)
// ====================================================
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// ====================================================
// 3. Configuración global
// ====================================================
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// ====================================================
// 4. Obtener la URI actual
// ====================================================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalizar (en InfinityFree no hay subcarpeta tipo /ecommerce/public)
if ($uri === '' || $uri === null) {
    $uri = '/';
}

// ====================================================
// 5. Router principal
// ====================================================
require_once __DIR__ . '/../app/routes/web.php';
