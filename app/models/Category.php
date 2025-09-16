<?php
namespace App\Models;

use PDO;
use PDOException;

class Category
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Crear categoría
    public function create($name, $slug, $description = null)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO categories (name, slug, description)
                VALUES (:name, :slug, :description)
            ");
            return $stmt->execute([
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description
            ]);
        } catch (PDOException $e) {
            error_log("Error creando categoría: " . $e->getMessage());
            return false;
        }
    }

    // 2. Obtener todas las categorías
    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Obtener categoría por ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM categories WHERE id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Obtener categoría por slug
    public function findBySlug($slug)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM categories WHERE slug = :slug LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Actualizar categoría
    public function update($id, $name, $slug, $description = null)
    {
        $stmt = $this->pdo->prepare("
            UPDATE categories
            SET name = :name, slug = :slug, description = :description, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug,
            ':description' => $description
        ]);
    }

    // 6. Eliminar categoría
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM categories WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    // 7. Obtener productos de una categoría
    public function getProducts($categoryId)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.* FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            WHERE c.id = :category_id
        ");
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
