<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    // --------------------------
    // CRUD Básico
    // --------------------------
    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (category_id, name, slug, description, price_cents, currency, stock, active, image_url)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssiiiis",
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['price_cents'],
            $data['currency'],
            $data['stock'],
            $data['active'],
            $data['image_url']
        );
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET category_id=?, name=?, slug=?, description=?, price_cents=?, currency=?, stock=?, active=?, image_url=? WHERE id=?"
        );
        $stmt->bind_param(
            "isssiiiisi",
            $data['category_id'],
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['price_cents'],
            $data['currency'],
            $data['stock'],
            $data['active'],
            $data['image_url'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // --------------------------
    // Obtener todos los productos activos
    // --------------------------
    public function getAll($limit = 12, $offset = 0) {
        $stmt = $this->conn->prepare(
            "SELECT id, name, slug, description, price_cents, currency, stock, active, image_url
             FROM products
             WHERE active = 1
             ORDER BY created_at DESC
             LIMIT ?, ?"
        );
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // --------------------------
    // Buscar productos por atributos
    // --------------------------
    
    public function findByAttributes($filters = []) {
    $query = "SELECT p.*, c.name AS category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE 1=1";
    
    $params = [];

    if (isset($filters['name'])) {
        $query .= " AND p.name LIKE :name";
        $params[':name'] = '%' . $filters['name'] . '%';
    }
    if (isset($filters['stock'])) {
        $query .= " AND p.stock = :stock";
        $params[':stock'] = $filters['stock'];
    }
    if (isset($filters['active'])) {
        $query .= " AND p.active = :active";
        $params[':active'] = $filters['active'];
    }
    if (isset($filters['category_name'])) {
        $query .= " AND c.name LIKE :category_name";
        $params[':category_name'] = '%' . $filters['category_name'] . '%';
    }

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
