<?php
namespace App\Models;

use PDO;
use PDOException;

class Order
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Crear pedido
    public function create($userId, $totalCents, $currency = 'PEN')
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, total_cents, currency, status)
                VALUES (:user_id, :total_cents, :currency, 'pending')
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':total_cents' => $totalCents,
                ':currency' => $currency
            ]);

            return $this->pdo->lastInsertId(); // ID del pedido recién creado
        } catch (PDOException $e) {
            error_log("Error creando pedido: " . $e->getMessage());
            return false;
        }
    }

    // 2. Agregar producto al pedido
    public function addItem($orderId, $productId, $quantity, $priceCents)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price_cents)
                VALUES (:order_id, :product_id, :quantity, :price_cents)
            ");
            return $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $productId,
                ':quantity' => $quantity,
                ':price_cents' => $priceCents
            ]);
        } catch (PDOException $e) {
            error_log("Error agregando item al pedido: " . $e->getMessage());
            return false;
        }
    }

    // 3. Obtener pedido por ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM orders WHERE id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Obtener pedidos de un usuario
    public function findByUser($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Obtener items de un pedido
    public function getItems($orderId)
    {
        $stmt = $this->pdo->prepare("
            SELECT oi.*, p.name 
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Actualizar estado del pedido
    public function updateStatus($orderId, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE orders SET status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $orderId,
            ':status' => $status
        ]);
    }

    // 7. Relacionar pago
    public function setPayment($orderId, $paymentId)
    {
        $stmt = $this->pdo->prepare("
            UPDATE orders SET payment_id = :payment_id, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $orderId,
            ':payment_id' => $paymentId
        ]);
    }

    // 8. Eliminar pedido
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM orders WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }
}
