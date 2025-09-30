<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce - Home</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h1>Productos</h1>

    <div id="product-list">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $p): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p>Precio: S/ <?= htmlspecialchars($p['precio']) ?></p>
                    <button>Agregar al carrito</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>

    <button id="load-more">Cargar más</button>

    <!-- Tu JS que maneja fetch -->
    <script src="/assets/js/home.js"></script>
</body>
</html>
