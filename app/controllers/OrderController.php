<?php

require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/helpers/AuthHelper.php';


class OrderController {
    private $db;
    private $authHelper;

    // Constructor: recibe la conexión a la base de datos
    public function __construct($db) {
        $this->db = $db;
        $this->authHelper = new AuthHelper(); // inicializamos el helper
    }

    // ===========================
    // Vista principal del checkout
    // ===========================
    public function index() {
        $this->authHelper->require_login();
        require_once APP_PATH . '/views/layouts/header.php';
        // Mostrar la vista del checkout
        require_once APP_PATH . '/views/checkout/index.php';

    }

    // ===========================
    // Vista principal de ordenes
    // ===========================
    public function viewOrders() {
        $this->authHelper->require_login();
        require_once APP_PATH . '/views/layouts/header.php';
        // Mostrar la vista del checkout
        require_once APP_PATH . '/views/order/index.php';

    }

    // ===========================
    // Guardar pedido (POST)
    // ===========================
    public function store() {
        $this->authHelper->require_login();

        $userId = $_SESSION['user_id'];
        $cartItems = json_decode($_POST['cart_data'] ?? '[]', true);
        $paymentMethod = $_POST['payment_method'] ?? 'transfer';
        $deliveryMethod = $_POST['delivery_method'] ?? 'recojo';

        $shippingData = [
            'address' => $_POST['address'] ?? '',
            'region' => $_POST['region'] ?? '',
            'province' => $_POST['province'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? ''
        ];

        try {
            $order = new Order($this->db);
            $orderId = $order->create($userId, $cartItems, $paymentMethod, $deliveryMethod, $shippingData);
            header("Location: /ecommerce/checkout/success?id=$orderId");
        } catch (Exception $e) {
            echo "Error al procesar pedido: " . $e->getMessage();
        }
    }

    public function success() {
        require APP_PATH . '/views/checkout/success.php';
    }

    public function myOrders() {
        $this->authHelper->require_login();
        $userId = $_SESSION['user_id'];
        // Obtener las órdenes del usuario
        $orderModel = new Order($this->db);
        $orders = $orderModel->getOrdersByUser($userId);

        // Cargar vista
        require_once APP_PATH . '/views/layouts/header.php';
        require_once APP_PATH . '/views/order/index.php';   
    }

    public function uploadVoucher() {
    $this->authHelper->require_login();

    if (!isset($_POST['order_id']) || empty($_FILES['voucher']['tmp_name'])) {
        die("Datos incompletos.");
    }

    $orderId = (int) $_POST['order_id'];
    $userId = $_SESSION['user_id'];

    // Validar que el pedido pertenece al usuario
    $orderModel = new Order($this->db);
    $order = $orderModel->getOrderById($orderId);
    if (!$order || $order['user_id'] != $userId) {
        die("No tienes permiso para modificar este pedido.");
    }

    // Carpeta de destino (fuera de app/)
    $uploadDir = dirname(APP_PATH) . '/uploads/vouchers/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Nombre y ruta final del archivo
    $ext = pathinfo($_FILES['voucher']['name'], PATHINFO_EXTENSION);
    $fileName = 'voucher_' . $orderId . '_' . time() . '.' . strtolower($ext);
    $filePath = $uploadDir . $fileName;

    // Mover archivo subido
    if (move_uploaded_file($_FILES['voucher']['tmp_name'], $filePath)) {
        // Ruta pública (por ejemplo, https://projex321.free.nf/ecommerce/uploads/vouchers/...)
        $publicPath = "https://projex321.free.nf/ecommerce/uploads/vouchers/" . $fileName;

        // Actualizar base de datos
        $orderModel->updateVoucherPath($orderId, $publicPath);

        header("Location: /ecommerce/orders");
        exit;
    } else {
        echo "Error al subir el archivo.";
    }
}



}
