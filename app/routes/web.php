<?php
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\OrderController;

// ====================================================
// Router principal
// Recibe la ruta desde index.php en $uri
// ====================================================

switch ($uri) {
    case '/':
    case '/home':
        (new HomeController())->index();
        break;

    case '/productos':
        (new ProductController())->index();
        break;

    case '/carrito':
        (new CartController())->index();
        break;

    case '/checkout':
        (new OrderController())->checkout();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
