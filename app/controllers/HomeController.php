<?php
require_once APP_PATH . '/models/Product.php';

class HomeController {
    private $productModel;

    public function __construct($conn) {
        // Inyectamos la conexión a la BD en el modelo
        $this->productModel = new Product($conn);
    }

    // Página principal con productos
    public function index() {
        // Obtener productos desde el modelo
        $productos = $this->productModel->getAllProducts(12);

        // Pasar productos a la vista
        // require APP_PATH . '/views/home/index.php';
        require APP_PATH . '/views/layouts/header.php';
        // require APP_PATH . '/views/layouts/footer.php';
    }

    // Endpoint para cargar más productos vía AJAX
    public function loadMore($offset = 0, $limit = 6) {
        $productos = $this->productModel->getAllProducts($limit, $offset);

        header('Content-Type: application/json');
        echo json_encode($productos);
        exit;
    }
}
