<?php
// Simulación temporal si aún no pasas datos reales
$order = $order ?? [
    'id' => 35802,
    'date' => date('d/m/Y'),
    'total' => 1404.00,
    'method' => 'Transferencia bancaria directa',
    'shipping_method' => 'Recojo en Almacén',
    'customer_name' => 'a a',
    'email' => 'asdf@gmail.com',
    'phone' => '96123123',
    'document' => '92638173',
];
$items = $items ?? [
    ['name' => 'Módulo Relé con Retardo en Tiempo Real 5VDC', 'quantity' => 39, 'total' => 1404.00],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #<?= $order['id'] ?> - Confirmación</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f9fafb; color: #333; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 40px; }
        h1 { color: #28a745; margin-bottom: 10px; }
        h2 { margin-top: 40px; border-bottom: 2px solid #f0f0f0; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        .total { font-weight: bold; font-size: 1.1em; }
        .highlight { color: #28a745; font-weight: bold; }
        .qr-container { text-align: center; margin: 30px 0; }
        .qr-container img { width: 180px; height: 180px; border: 4px solid #eee; border-radius: 10px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .btn { background: #28a745; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn:hover { background: #218838; }
        .footer { text-align: center; margin-top: 40px; color: #777; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fa-solid fa-circle-check"></i> ¡Gracias! Tu pedido ha sido recibido.</h1>

    <div class="info-box">
        <p><strong>Número de pedido:</strong> <?= $order['id'] ?></p>
        <p><strong>Fecha:</strong> <?= $order['date'] ?></p>
        <p><strong>Total:</strong> S/<?= number_format($order['total'], 2) ?></p>
        <p><strong>Método de pago:</strong> <?= htmlspecialchars($order['method']) ?></p>
    </div>

    <h2>📱 Paga tu pedido</h2>
    <p>Paga con <b>YAPE</b>, <b>PLIN</b> o <b>Tarjeta de Crédito/Débito</b> escaneando este código QR:</p>

    <div class="qr-container">
        <img src="/ecommerce/public/img/qr_pago.png" alt="QR de pago">
    </div>

    <p>Luego de la transferencia o depósito, envíanos una <b>fotografía del voucher</b> al:</p>
    <ul>
        <li>📱 WhatsApp: <a href="https://wa.me/51935690826" target="_blank">935690826</a></li>
        <li>📧 Correo: <a href="mailto:ventas@tutienda.pe">ventas@tutienda.pe</a></li>
    </ul>

    <h2>🏦 Nuestros detalles bancarios</h2>
    <table>
        <tr><th>Banco</th><th>N° Cuenta</th><th>CCI</th></tr>
        <tr><td>BCP</td><td>1949965834087</td><td>00219400996583408794</td></tr>
        <tr><td>Interbank</td><td>379-3001533350</td><td>00337900300153335050</td></tr>
    </table>

    <h2>🧾 Detalles del pedido</h2>
    <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Total</th></tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>S/<?= number_format($item['total'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr><td colspan="2" class="total">Subtotal:</td><td>S/<?= number_format($order['total'], 2) ?></td></tr>
        <tr><td colspan="2" class="total">Envío:</td><td><?= htmlspecialchars($order['shipping_method']) ?></td></tr>
        <tr><td colspan="2" class="total">Total:</td><td class="highlight">S/<?= number_format($order['total'], 2) ?></td></tr>
    </table>

    <h2>👤 Información del cliente</h2>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>N° Documento:</strong> <?= htmlspecialchars($order['document']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($order['email']) ?></p>

    <a href="/ecommerce/orders" class="btn">Ver pedidos</a>
</div>



<script>
    // Vaciar el carrito tras el pedido
    localStorage.removeItem('cart');
</script>

</body>
</html>
