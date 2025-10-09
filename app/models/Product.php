<?php
class Product {
    private PDO $conn;
    private string $table = 'products';

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // --------------------------
    // Crear producto
    // --------------------------
    public function create(array $data): bool {
        $stmt = $this->conn->prepare("
            INSERT INTO products 
            (category_id, name, slug, description, price_cents, currency, stock, active)
            VALUES (:category_id, :name, :slug, :description, :price_cents, :currency, :stock, :active)
        ");
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'],
            ':price_cents' => $data['price_cents'],
            ':currency' => $data['currency'],
            ':stock' => $data['stock'],
            ':active' => $data['active']
        ]);
    }

    // --------------------------
    // Actualizar producto
    // --------------------------
    public function update(int $id, array $data): bool {
        $stmt = $this->conn->prepare("
            UPDATE products SET
                category_id = :category_id,
                name = :name,
                slug = :slug,
                description = :description,
                price_cents = :price_cents,
                currency = :currency,
                stock = :stock,
                active = :active
            WHERE id = :id
        ");
        $data['id'] = $id;
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'],
            ':price_cents' => $data['price_cents'],
            ':currency' => $data['currency'],
            ':stock' => $data['stock'],
            ':active' => $data['active'],
            ':id' => $data['id']
        ]);
    }

    // --------------------------
    // Eliminar producto
    // --------------------------
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --------------------------
    // Obtener un producto por ID (con imagen principal)
    // --------------------------
    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("
            SELECT p.*, pi.image_path AS main_image
            FROM products p
            LEFT JOIN product_images pi 
            ON pi.product_id = p.id AND pi.is_main = 1
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // --------------------------
    // Obtener todos los productos activos (con imagen principal)
    // --------------------------
    public function getAll(int $limit = 12, int $offset = 0): array {
        $stmt = $this->conn->prepare("
            SELECT p.*, pi.image_path AS main_image
            FROM products p
            LEFT JOIN product_images pi
            ON pi.product_id = p.id AND pi.is_main = 1
            WHERE p.active = 1
            ORDER BY p.created_at DESC
            LIMIT :offset, :limit
        ");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------
    // Buscar productos por filtros (con imagen principal)
    // --------------------------
    public function findByAttributes(array $filters = []): array {
        $query = "
            SELECT p.*, pi.image_path AS main_image, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['name'])) {
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
        if (!empty($filters['category_name'])) {
            $query .= " AND c.name LIKE :category_name";
            $params[':category_name'] = '%' . $filters['category_name'] . '%';
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
