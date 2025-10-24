<?php
// Reordenar el array para mostrar primero los pedidos "shipped"
usort($orders, function ($a, $b) {
    $orderPriority = ['shipped' => 1, 'paid' => 2, 'pending' => 3, 'completed' => 4, 'cancelled' => 5];
    $pa = $orderPriority[$a['status']] ?? 99;
    $pb = $orderPriority[$b['status']] ?? 99;
    return $pa <=> $pb;
});
?>

<section class="orders">
  <h2>Mis pedidos</h2>

  <?php if (!empty($orders)): ?>
    <?php $contador = 1; ?>
    <?php foreach ($orders as $index => $order): ?>
<div class="order-card">
  <div class="order-header">
    <h3>Pedido #<?= $contador ?></h3>
    <?php $contador++; ?>
    <p><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
    <p><strong>Total:</strong> S/ <?= number_format($order['total_cents'] / 100, 2) ?></p>
  </div>

  <div class="order-body">
    <!-- Productos -->
    <div class="order-products">
      <?php if (!empty($order['items'])): ?>
        <?php foreach ($order['items'] as $item): ?>
          <div class="product-card">
            <img 
              src="<?= htmlspecialchars($item['image_path'] ?? 'https://projex321.free.nf/ecommerce/uploads/no-image.png') ?>" 
              alt="<?= htmlspecialchars($item['product_name']) ?>">
            <div class="product-info">
              <p><?= htmlspecialchars($item['product_name']) ?></p>
              <p>Cantidad: <?= (int) $item['quantity'] ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Voucher -->
    <div class="voucher-section">
      <p><strong>Voucher:</strong></p>

      <?php if (!empty($order['voucher_path'])): ?>
        <a href="<?= htmlspecialchars($order['voucher_path']) ?>" target="_blank">
          <img src="<?= htmlspecialchars($order['voucher_path']) ?>" alt="Voucher" class="voucher-img">
        </a>
      <?php else: ?>
        <p class="no-voucher">No se ha subido ningún voucher aún.</p>
      <?php endif; ?>

      <form action="/ecommerce/order/uploadVoucher" method="POST" enctype="multipart/form-data" class="voucher-form">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
        <input type="file" name="voucher" accept="image/*" required>
        <button type="submit">
          <?= empty($order['voucher_path']) ? 'Subir voucher' : 'Actualizar voucher' ?>
        </button>
      </form>
    </div>
  </div>
</div>

    <?php endforeach; ?>
  <?php else: ?>
    <p>No tienes pedidos aún.</p>
  <?php endif; ?>
</section>

<style>
.orders {
  padding: 20px;
  max-width: 900px;
  margin: auto;
}
.order-card {
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 20px;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-body {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  margin-top: 15px;
}

.order-products {
  flex: 2;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.product-card {
  width: 120px;
  text-align: center;
}

.product-card img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
}

.voucher-section {
  flex: 1;
  text-align: center;
}

.voucher-img {
  width: 100%;
  max-width: 200px;
  margin-top: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.voucher-form {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.voucher-form button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
}

.voucher-form button:hover {
  background-color: #0056b3;
}

.no-voucher {
  color: #777;
  font-size: 0.9em;
}


</style>
