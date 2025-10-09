<?php
namespace App\Models;

use PDO;
use PDOException;

class Payment
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Crear pago
    public function create($orderId, $method, $amountCents, $currency = 'PEN', $voucherPath = null, $providerTxnId = null)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO payments (order_id, method, amount_cents, currency, voucher_path, provider_txn_id, status)
                VALUES (:order_id, :method, :amount_cents, :currency, :voucher_path, :provider_txn_id, 'pending')
            ");

            $stmt->execute([
                ':order_id' => $orderId,
                ':method' => $method,
                ':amount_cents' => $amountCents,
                ':currency' => $currency,
                ':voucher_path' => $voucherPath,
                ':provider_txn_id' => $providerTxnId
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creando pago: " . $e->getMessage());
            return false;
        }
    }

    // 2. Obtener pago por ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Obtener pago por pedido
    public function findByOrderId($orderId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE order_id = :order_id LIMIT 1");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Actualizar estado del pago
    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE payments SET status = :status, updated_at = NOW() WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    // 5. Actualizar voucher (si el cliente sube otro comprobante)
    public function updateVoucher($id, $voucherPath)
    {
        $stmt = $this->pdo->prepare("
            UPDATE payments SET voucher_path = :voucher_path, updated_at = NOW() WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':voucher_path' => $voucherPath
        ]);
    }

    // 6. Eliminar pago (solo admin)
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
