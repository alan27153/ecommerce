<?php
require_once APP_PATH . '/models/Product.php';

class ProductController {
    private $productModel;

    public function __construct($conn) {
        $this->productModel = new Product($conn);
    }

    // Verificar rol
    private function authorize($user) {
        if (!in_array($user['role'], ['admin', 'editor'])) {
            http_response_code(403);
            echo "No tienes permisos para realizar esta acción";
            exit;
        }
    }

    // Listar todos los productos
    public function index($user) {
        $this->authorize($user);
        $productos = $this->productModel->getAllWithCategory();
        require APP_PATH . '/views/admin/products/index.php';
    }

    // Mostrar detalle de un producto
    public function show($id) {
        $producto = $this->productModel->getById($id);

        if (!$producto) {
            http_response_code(404);
            echo "Producto no encontrado";
            exit;
        }
        require APP_PATH . '/views/layouts/header.php';
        require APP_PATH . '/views/products/show.php';
    }

    // Crear un nuevo producto
    public function create($user, $data) {
        $this->authorize($user);

        // Validaciones básicas
        if (empty($data['name']) || empty($data['category_id']) || !isset($data['price_cents'])) {
            echo "Faltan datos obligatorios";
            exit;
        }

        $this->productModel->create($data);
        header('Location: /admin/products');
    }

    // Actualizar producto existente
    public function update($user, $id, $data) {
        $this->authorize($user);

        $producto = $this->productModel->getById($id);
        if (!$producto) {
            http_response_code(404);
            echo "Producto no encontrado";
            exit;
        }

        $this->productModel->update($id, $data);
        header('Location: /admin/products');
    }

    // Eliminar producto
    public function delete($user, $id) {
        $this->authorize($user);

        $producto = $this->productModel->getById($id);
        if (!$producto) {
            http_response_code(404);
            echo "Producto no encontrado";
            exit;
        }

        $this->productModel->delete($id);
        header('Location: /admin/products');
    }
}
