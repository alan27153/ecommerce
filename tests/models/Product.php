<?php
class Product {
    private PDO $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

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

public function delete(int $id): bool {
    $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}




}
