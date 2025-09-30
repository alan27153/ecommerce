<div class="product-detail">
    <?php if (!empty($producto['image_path'])): ?>
        <img src="<?= htmlspecialchars($producto['image_path']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>" class="product-image">
    <?php endif; ?>

    <h1><?= htmlspecialchars($producto['name']) ?></h1>
    <p>Categoría: <?= htmlspecialchars($producto['category_name']) ?></p>
    <p>Precio: <?= htmlspecialchars($producto['currency']) . ' ' . number_format($producto['price_cents'] / 100, 2) ?></p>
    <p>Stock disponible: <?= intval($producto['stock']) ?></p>
    <p>Descripción: <?= htmlspecialchars($producto['description']) ?></p>

    <!-- Botón de agregar al carrito -->
    <form method="POST" action="/cart/add">
        <input type="hidden" name="product_id" value="<?= $producto['id'] ?>">
        <label>Cantidad:</label>
        <input type="number" name="quantity" value="1" min="1" max="<?= intval($producto['stock']) ?>">
        <button type="submit">Agregar al carrito</button>
    </form>
</div>
