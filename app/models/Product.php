<?php
class Product {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Obtener productos con su imagen principal
     */
    public function getAllProducts($limit = 12, $offset = 0) {
        $sql = "
            SELECT 
                p.id,
                p.name,
                p.slug,
                p.description,
                p.price_cents,
                p.currency,
                p.stock,
                COALESCE(pi.image_path, 'assets/images/default.png') AS image
            FROM products p
            LEFT JOIN product_images pi 
                ON p.id = pi.product_id AND pi.is_main = 1
            WHERE p.active = 1
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            // convertir el precio de centavos a decimal
            $row['price'] = number_format($row['price_cents'] / 100, 2);
            $productos[] = $row;
        }

        return $productos;
    }

    /**
     * Obtener un solo producto por slug
     */
    public function getBySlug($slug) {
        $sql = "
            SELECT 
                p.*,
                COALESCE(pi.image_path, 'assets/images/default.png') AS image
            FROM products p
            LEFT JOIN product_images pi 
                ON p.id = pi.product_id AND pi.is_main = 1
            WHERE p.slug = ? AND p.active = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
