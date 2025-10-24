<?php
class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Crea una nueva orden validando los precios desde la base de datos
     */
    public function create($userId, $cartItems, $paymentMethod, $deliveryMethod, $shippingData = [], $currency = 'PEN') {
        try {
            $this->db->beginTransaction();

            $totalCents = 0;
            $validatedItems = [];

            // ✅ Validar precios desde la BD para evitar manipulación del cliente
            foreach ($cartItems as $item) {
                $stmt = $this->db->prepare("SELECT id, price_cents, stock FROM products WHERE id = ? AND active = 1");
                $stmt->execute([$item['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Producto no encontrado o inactivo (ID {$item['id']})");
                }

                if ($item['quantity'] > $product['stock']) {
                    throw new Exception("Stock insuficiente para {$item['name']}");
                }

                $price = (int) $product['price_cents'];
                $quantity = (int) $item['quantity'];
                $subtotal = $price * $quantity;

                $validatedItems[] = [
                    'product_id' => $product['id'],
                    'quantity' => $quantity,
                    'price_cents' => $price
                ];

                $totalCents += $subtotal;
            }

            // ✅ Insertar orden principal
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_cents, currency, status)
                VALUES (?, ?, ?, 'pending')
            ");
            $stmt->execute([$userId, $totalCents, $currency]);
            $orderId = $this->db->lastInsertId();

            // ✅ Insertar ítems del pedido
            $stmtItem = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price_cents)
                VALUES (?, ?, ?, ?)
            ");
            foreach ($validatedItems as $vi) {
                $stmtItem->execute([$orderId, $vi['product_id'], $vi['quantity'], $vi['price_cents']]);
            }

            // ✅ Insertar método de pago
            $stmtPay = $this->db->prepare("
                INSERT INTO payments (order_id, method, amount_cents, currency, status)
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmtPay->execute([$orderId, $paymentMethod, $totalCents, $currency]);

            // ✅ Registrar dirección de envío si aplica
            if ($deliveryMethod === 'envio') {
                $this->saveShippingInfo($orderId, $shippingData);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error al crear la orden: " . $e->getMessage());
        }
    }

    /**
     * Guarda la dirección de envío en una tabla aparte (si decides crearla)
     */
    private function saveShippingInfo($orderId, $data) {
 
        $stmt = $this->db->prepare("
            INSERT INTO order_shipping (order_id, address, region, province, city, postal_code)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $orderId,
            $data['address'] ?? null,
            $data['region'] ?? null,
            $data['province'] ?? null,
            $data['city'] ?? null,
            $data['postal_code'] ?? null
        ]);
    }

    /** Obtener pedido por ID con detalles */
    public function getOrderById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrdersByUser($userId) {
        $sql = "
            SELECT o.*, pay.voucher_path, pay.method AS payment_method
            FROM orders o
            LEFT JOIN payments pay ON o.id = pay.order_id
            WHERE o.user_id = :user_id
            ORDER BY 
                CASE o.status
                    WHEN 'shipped' THEN 1
                    WHEN 'paid' THEN 2
                    WHEN 'pending' THEN 3
                    WHEN 'completed' THEN 4
                    WHEN 'cancelled' THEN 5
                    ELSE 6
                END,
                o.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $stmtItems = $this->db->prepare("
                SELECT 
                    oi.*, 
                    pr.name AS product_name,
                    COALESCE(
                        (SELECT image_path FROM product_images WHERE product_id = pr.id AND is_main = 1 LIMIT 1),
                        (SELECT image_path FROM product_images WHERE product_id = pr.id LIMIT 1)
                    ) AS image_path
                FROM order_items oi
                JOIN products pr ON oi.product_id = pr.id
                WHERE oi.order_id = :order_id
            ");
            $stmtItems->execute(['order_id' => $order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }


    public function updateVoucherPath($orderId, $voucherUrl) {
        $stmt = $this->db->prepare("
            UPDATE payments 
            SET voucher_path = :voucher_path 
            WHERE order_id = :order_id
        ");
        $stmt->bindParam(':voucher_path', $voucherUrl, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
    }


}
?>
