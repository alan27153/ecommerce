<?php
// require_once APP_PATH . '/middlewares/AuthMiddleware.php';
class ProductController {
    private $db;

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->db = $db;
    }

    // Método para listar productos con paginación (para AJAX)
    public function list() {
        $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit  = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT id, name, price_cents 
                FROM products 
                ORDER BY id DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Devolver respuesta JSON al frontend
        header('Content-Type: application/json');
        echo json_encode($products);
        exit;
    }

    // Método para mostrar la vista de productos
    public function index() {
        require_once APP_PATH . '/views/products/index.php';
    }
}
