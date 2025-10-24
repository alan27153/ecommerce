<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


class HomeController {
    private $productModel;

    public function __construct($conn) {
        // Inyectamos la conexión a la BD en el modelo
        $this->productModel = new Product($conn);
    }

    // Página principal con productos
    public function index() {
        // Obtener productos iniciales activos (ej: 12)
        $productos = $this->productModel->getAll();

        // $productos = $this->productModel->findByAttributes(['active' => 1]);

        require APP_PATH . '/views/layouts/header.php';
        require APP_PATH . '/views/home/index.php';
        require APP_PATH . '/views/layouts/footer.php';
    }

    // Endpoint para cargar más productos vía AJAX
    public function loadMore($offset = 0, $limit = 6) {
        // Para cargar más productos con paginación
        $allProducts = $this->productModel->findByAttributes(['active' => 1]);
        $productos = array_slice($allProducts, $offset, $limit);

        header('Content-Type: application/json');
        echo json_encode($productos);
        exit;
    }

    // Mostrar detalle de un producto
    public function show($id) {
        // Suponiendo que tienes un método getById en el modelo
        $producto = $this->productModel->getById($id);
        if (!$producto) {
            http_response_code(404);
            require APP_PATH . '/views/layouts/404.php';
            return;
        }
        require APP_PATH . '/views/products/show.php';
    }
}
