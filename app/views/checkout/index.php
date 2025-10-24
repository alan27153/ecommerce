<div class="checkout-container">

    <h1>Finalizar Compra</h1>

    <form id="checkout-form" method="POST" action="/ecommerce/checkout/confirm">
        <!-- Datos del carrito -->
    <input type="hidden" name="cart_data" id="cartDataInput">

        <!-- 🧍 Datos del cliente -->
        <section class="checkout-section">
            <h2>Detalles de Facturación</h2>

            <label>Nombre completo:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

            <label>Correo electrónico:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>">

            <label>Número de documento:</label>
            <input type="text" name="document_number" value="<?= htmlspecialchars($client['document_number'] ?? '') ?>">
        </section>

        <!-- 🚚 Tipo de entrega -->
        <section class="checkout-section">
            <h2>Envío o Recojo</h2>

            <label for="delivery-method">Método de Entrega:</label>
            <select id="delivery-method" name="delivery_method" required>
                <option value="envio">Envío a domicilio</option>
                <option value="recojo">Recojo en tienda</option>
            </select>

            <!-- Si selecciona envío -->
            <div id="shipping-fields">

                <h3>Dirección de Envío</h3>
                
                <label>Dirección:</label>
                <input type="text" name="address" value="<?= htmlspecialchars($client['address'] ?? '') ?>">

                <label>Región:</label>
                <input type="text" name="region">

                <label>Provincia:</label>
                <input type="text" name="province">

                <label>Ciudad / Distrito:</label>
                <input type="text" name="city">

                <label>Código Postal:</label>
                <input type="text" name="postal_code">

                <label>Referencia:</label>
                <input type="text" name="reference" placeholder="Ejemplo: Frente al parque, casa azul, 2do piso">

            </div>

            <!-- Si selecciona recojo -->
            <div id="pickup-info" class="hidden">
                <h3>Punto de Recojo</h3>
                <p><strong>Horario de Atención:</strong></p>
                <ul>
                    <li>Lunes - Viernes: 9:00am a 6:00pm</li>
                    <li>Domingo: 9:30am a 1:00pm</li>
                </ul>
                <p><strong>Dirección:</strong> Calle Juan Mendizábal 1106, San Juan de Miraflores</p>
            </div>
        </section>

        <!-- 🧾 Resumen del pedido -->
        <section class="checkout-section">
            <h2>Resumen del Pedido</h2>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="order-summary">
                    <!-- Se llena con JS -->
                </tbody>
            </table>
            <p><strong>Total:</strong> <span id="total-amount">S/ 0.00</span></p>
        </section>

        <!-- 💰 Método de pago -->
        <section class="checkout-section">
            <h2>Método de Pago</h2>

            <label><input type="radio" name="payment_method" value="transfer" required> Transferencia Bancaria</label>
            <div class="payment-detail hidden" id="transfer-info">
                <p><strong>Cuenta BCP:</strong> 123-45678901-0-12</p>
                <p>Titular: PROJEX S.A.C</p>
            </div>

            <label><input type="radio" name="payment_method" value="yape"> Yape / Plin</label>
            <div class="payment-detail hidden" id="yape-info">
                <p><strong>Número Yape:</strong> 999 999 999</p>
                <p>O escanee este QR:</p>
                <img src="" alt="Yape QR" width="150">
            </div>

            <!-- <label><input type="radio" name="payment_method" value="contraentrega"> Pago contra entrega</label> -->
        </section>

        <!-- 🟢 Confirmar -->
        <div class="checkout-actions">
            <button type="submit" class="confirm-btn">Realizar Pedido</button>
        </div>
    </form>
</div>

<!-- ===================== JS ===================== -->
<script>
// Obtener datos del carrito y almacenarlos en un input de tipo hidden
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.getElementById('cartDataInput').value = JSON.stringify(cart);

// Mostrar campos según el método de entrega
document.getElementById("delivery-method").addEventListener("change", function() {
    const envio = this.value === "envio";
    document.getElementById("shipping-fields").classList.toggle("hidden", !envio);
    document.getElementById("pickup-info").classList.toggle("hidden", envio);
});

// Mostrar detalles de pago
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-detail').forEach(div => div.classList.add('hidden'));
        if (this.value === 'transfer') document.getElementById('transfer-info').classList.remove('hidden');
        if (this.value === 'yape') document.getElementById('yape-info').classList.remove('hidden');
    });
});

// Cargar productos desde localStorage
document.addEventListener("DOMContentLoaded", () => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const tbody = document.getElementById("order-summary");
    const totalSpan = document.getElementById("total-amount");

    let total = 0;

    cart.forEach(item => {
        const subtotal = (item.price_cents * item.quantity) / 100;
        total += subtotal;

        const row = document.createElement("tr");
        row.innerHTML = `
            <td><img src="${item.image_path}" width="60" style="border-radius:8px;vertical-align:middle;margin-right:10px;"> ${item.name}</td>
            <td>${item.quantity}</td>
            <td>${item.currency} ${(subtotal).toFixed(2)}</td>
        `;
        tbody.appendChild(row);
    });

    totalSpan.textContent = `S/ ${total.toFixed(2)}`;
});
</script>

<!-- ===================== ESTILOS ===================== -->
<style>
.checkout-container {
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 25px;
    font-family: 'Arial', sans-serif;
}
.checkout-container h1 {
    text-align: center;
    margin-bottom: 25px;
}
.checkout-section {
    margin-bottom: 25px;
}
.checkout-section h2 {
    font-size: 18px;
    border-bottom: 2px solid #eee;
    padding-bottom: 5px;
    margin-bottom: 15px;
}
.checkout-section label {
    display: block;
    margin-top: 10px;
    font-weight: 600;
}
.checkout-section input, .checkout-section select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-top: 4px;
}
.checkout-table {
    width: 100%;
    border-collapse: collapse;
}
.checkout-table th, .checkout-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
.confirm-btn {
    background-color: #27ae60;
    color: #fff;
    padding: 12px 25px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}
.confirm-btn:hover {
    background-color: #219150;
}
.hidden { display: none; }
.payment-detail {
    margin-left: 20px;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 6px;
}
</style>
