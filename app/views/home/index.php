<div id="product-list">
    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $p): ?>
            <a href="/ecommerce/product/<?= $p['id'] ?>" class="product-card">

                <?php if (!empty($p['main_image'])): ?>
                    <img src="<?= htmlspecialchars($p['main_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-image">
                <?php endif; ?>
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p>Precio: <?= htmlspecialchars($p['currency']) . ' ' . number_format($p['price_cents'] / 100, 2) ?></p>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</div>


<style>

/* Contenedor de lista de productos */
#product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px 20px; /* separación adicional si hace falta */
    
}

/* Tarjeta de producto */
.product-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px; /* padding interno para separar contenido de bordes */
    width: calc(25% - 20px); /* 4 columnas */
    cursor: pointer;
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
}

/* Imagen de producto */
.product-card img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Nombre y precio */
.product-card h3 {
    font-size: 18px;
    margin-bottom: 5px;
}

.product-card p {
    font-size: 16px;
    color: #555;
}

/* Responsive */
@media (max-width: 1024px) {
    .product-card {
        width: calc(33.333% - 20px); /* 3 columnas */
    }
}

@media (max-width: 768px) {
    .product-card {
        width: calc(50% - 20px); /* 2 columnas */
    }
}

@media (max-width: 480px) {
    .product-card {
        width: 100%; /* 1 columna */
    }
}

</style>