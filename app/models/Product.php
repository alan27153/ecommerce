<?php
// /app/models/Product.php
namespace App\Models;

use PDO;

class Product
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Obtener todos los productos activos
    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener producto por ID
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // Crear producto
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO products 
            (name, slug, description, total_cents, currency, active, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['total_cents'],
            $data['currency'] ?? 'PEN',
            $data['active'] ?? 1
        ]);

        return (int)$this->db->lastInsertId();
    }

    // Actualizar producto
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE products SET
            name = ?, slug = ?, description = ?, total_cents = ?, currency = ?, active = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['total_cents'],
            $data['currency'] ?? 'PEN',
            $data['active'] ?? 1,
            $id
        ]);
    }

    // Eliminar producto (marcar como inactivo)
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE products SET active = 0, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Buscar productos por nombre o descripción
    public function search(string $term): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM products 
            WHERE (name LIKE ? OR description LIKE ?) AND active = 1
        ");
        $like = "%{$term}%";
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }
}
