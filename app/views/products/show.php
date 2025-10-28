<div class="product-detail">
    <?php if (!empty($producto['image_path'])): ?>
        <img src="<?= htmlspecialchars($producto['image_path']) ?>" 
             alt="<?= htmlspecialchars($producto['name']) ?>" 
             class="product-image">
    <?php endif; ?>

    <h1><?= htmlspecialchars($producto['name']) ?></h1>
    <p>Categoría: <?= htmlspecialchars($producto['category_name']) ?></p>
    <p>Precio: <?= htmlspecialchars($producto['currency']) . ' ' . number_format($producto['price_cents'] / 100, 2) ?></p>
    <p>Stock disponible: <?= intval($producto['stock']) ?></p>
    <p>Descripción: <?= htmlspecialchars($producto['description']) ?></p>

    <form id="add-to-cart-form" method="POST" action="/ecommerce/cart">
        <label>Cantidad:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= intval($producto['stock']) ?>">
        <button type="submit">Agregar al carrito</button>
    </form>

        <a href="https://hotmart.com/es/marketplace/productos/capacitacion-en-seguridad-y-prevencion-de-robos/A85688968J "><button type="submit">Comprar</button></a>
    


</div>

<!-- 🛒 Modal -->
<div id="cart-modal" class="modal">
  <div class="modal-content">
    <p>✅ Producto añadido al carrito exitosamente</p>
    <a href="/ecommerce/cart">Ver carrito</a>
    <button id="close-modal">Cerrar</button>
  </div>
</div>

<style>
.product-detail {
  max-width: 600px;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 12px;
  text-align: center;
  background: #fff;
}
.product-image {
  width: 100%;
  max-height: 350px;
  object-fit: contain;
  margin-bottom: 20px;
}
.product-detail button {
  background-color: #28a745;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
.product-detail button:hover {
  background-color: #218838;
}

/* Modal estilos */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
}
.modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 25px;
  border-radius: 10px;
  text-align: center;
  width: 300px;
}
.modal-content button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
}
.modal-content button:hover {
  background-color: #0069d9;
}
</style>

<script>
    console.log("📦 Contenido del carrito:", JSON.parse(localStorage.getItem("cart")));

document.getElementById("add-to-cart-form").addEventListener("submit", function(e) {
    e.preventDefault();

    const product = {
        id: <?= json_encode($producto['id']) ?>,
        name: <?= json_encode($producto['name']) ?>,
        category_name: <?= json_encode($producto['category_name']) ?>,
        price_cents: <?= json_encode($producto['price_cents']) ?>,
        currency: <?= json_encode($producto['currency']) ?>,
        stock: <?= json_encode($producto['stock']) ?>,
        description: <?= json_encode($producto['description']) ?>,
        image_path: <?= json_encode($producto['image_path'] ?? '') ?>,
        quantity: parseInt(document.getElementById("quantity").value)
    };

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existing = cart.find(p => p.id === product.id);
    if (existing) {
        existing.quantity += product.quantity;
    } else {
        cart.push(product);
    }
    localStorage.setItem("cart", JSON.stringify(cart));

    // Mostrar modal
    const modal = document.getElementById("cart-modal");
    modal.style.display = "block";

    // Cerrar modal al hacer click en el botón
    document.getElementById("close-modal").onclick = () => {
        modal.style.display = "none";
    };

    // También cerrar si se hace click fuera del modal
    window.onclick = (event) => {
        if (event.target === modal) modal.style.display = "none";
    };
});
</script>
