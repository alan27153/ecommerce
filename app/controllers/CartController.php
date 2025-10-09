<?php
class CartController {
    private $db;

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->db = $db;
        // Iniciar la sesión si aún no está iniciada (para manejar el carrito)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Método para mostrar la vista del carrito
    public function index() {
        // Si no existe el carrito en la sesión, inicializarlo
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Pasar el carrito a la vista
        $cartItems = $_SESSION['cart'];
        require_once APP_PATH . '/views/cart/index.php';
    }

    // Método para agregar un producto al carrito
    public function add() {
        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $quantity  = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        if ($productId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid product ID']);
            exit;
        }

        // Consultar el producto en la BD
        $stmt = $this->db->prepare("SELECT id, name, price_cents FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            exit;
        }

        // Agregar el producto a la sesión
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price_cents'],
                'quantity' => $quantity
            ];
        } else {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
        exit;
    }

    // Método para eliminar un producto del carrito
    public function remove() {
        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
        exit;
    }

    // Método para vaciar todo el carrito
    public function clear() {
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart' => []]);
        exit;
    }
}
