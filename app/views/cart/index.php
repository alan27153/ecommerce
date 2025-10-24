<div class="cart-container">
    <h1>🛒 Mi Carrito</h1>

    <div id="cart-content"></div>

    <div class="cart-actions">
        <form id="checkout-form" method="POST" action="/ecommerce/checkout">
            <input type="hidden" name="cart_data" id="cart_data">
            <button type="submit" class="btn checkout">Comprar</button>
        </form>
    </div>
</div>

<script>
/* ---------- Helpers ---------- */
function getCart() { return JSON.parse(localStorage.getItem('cart')) || []; }
function saveCart(cart) { localStorage.setItem('cart', JSON.stringify(cart)); }

/* ---------- Render ---------- */
function renderCart() {
    const container = document.getElementById('cart-content');
    const cart = getCart();

    if (cart.length === 0) {
        container.innerHTML = '<p class="empty">Tu carrito está vacío.</p>';
        document.querySelector('.cart-actions').style.display = 'none';
        return;
    } else {
        document.querySelector('.cart-actions').style.display = 'flex';
    }

    let total = 0;
    const rows = cart.map((item, index) => {
        const imageSrc = item.image_path || item.main_image || item.image || '';
        const price = (item.price_cents || 0) / 100;
        const qty = Number(item.quantity || 1);
        const subtotal = price * qty;
        total += subtotal;

        return `
        <tr>
            <td class="product-cell">
                ${ imageSrc ? `<img src="${encodeURI(imageSrc)}" alt="${escapeHtml(item.name)}">` : `<div class="no-img">No image</div>` }
                <span class="product-name">${escapeHtml(item.name)}</span>
            </td>
            <td>${escapeHtml(item.currency || 'PEN')} ${price.toFixed(2)}</td>
            <td class="qty-cell">
                <button class="qty-btn" data-index="${index}" data-action="decrease">−</button>
                <input type="number" min="1" max="${item.stock || 9999}" value="${qty}" data-index="${index}" class="qty-input">
                <button class="qty-btn" data-index="${index}" data-action="increase">+</button>
            </td>
            <td>${escapeHtml(item.currency || 'PEN')} ${subtotal.toFixed(2)}</td>
            <td><button class="remove-btn" data-index="${index}">✕</button></td>
        </tr>`;
    }).join('');

    container.innerHTML = `
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>
        <div class="cart-total">
            <p><strong>Total:</strong> <span id="cart-total">${(total).toFixed(2)}</span></p>
        </div>
    `;
}

/* ---------- Eventos (delegación) ---------- */
document.addEventListener('click', function(e) {
    const cart = getCart();

    // + / - botones
    if (e.target.matches('.qty-btn')) {
        const index = Number(e.target.dataset.index);
        const action = e.target.dataset.action;
        if (!cart[index]) return;

        const max = Number(cart[index].stock || Infinity);
        if (action === 'increase' && cart[index].quantity < max) cart[index].quantity++;
        if (action === 'decrease' && cart[index].quantity > 1) cart[index].quantity--;
        saveCart(cart);
        renderCart();
    }

    // eliminar item
    if (e.target.matches('.remove-btn')) {
        const index = Number(e.target.dataset.index);
        cart.splice(index, 1);
        saveCart(cart);
        renderCart();
    }
});

// Cambios manuales en input cantidad
document.addEventListener('change', function(e) {
    if (e.target.matches('.qty-input')) {
        const index = Number(e.target.dataset.index);
        const val = parseInt(e.target.value, 10);
        const cart = getCart();
        if (!cart[index]) return;
        const max = Number(cart[index].stock || Infinity);
        cart[index].quantity = Math.max(1, Math.min(val, max));
        saveCart(cart);
        renderCart();
    }
});

/* ---------- Checkout ---------- */
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const cart = getCart();
    if (cart.length === 0) {
        e.preventDefault();
        alert('El carrito está vacío.');
        return;
    }

    document.getElementById('cart_data').value = JSON.stringify(cart);
});

function escapeHtml(text) {
    if (!text && text !== 0) return '';
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
}

renderCart();
</script>

<style>
.cart-container {
    max-width: 980px;
    margin: 2.4rem auto;
    background: #fff;
    padding: 1.6rem;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    font-family: Inter, system-ui, Arial;
    color: #222;
}
h1 { text-align:center; margin-bottom:1rem; }

.cart-table { width:100%; border-collapse:collapse; margin-bottom:1rem; }
.cart-table th, .cart-table td { padding:0.9rem; border-bottom:1px solid #f0f0f0; text-align:center; vertical-align:middle; }
.cart-table th { background:#fafafa; font-weight:600; }

.product-cell { display:flex; align-items:center; gap:0.85rem; text-align:left; }
.product-cell img { width:72px; height:72px; object-fit:cover; border-radius:8px; border:1px solid #eee; }
.no-img { width:72px; height:72px; background:#f4f4f4; color:#888; border-radius:8px; border:1px dashed #ccc; display:flex; justify-content:center; align-items:center; }

.qty-cell { display:flex; align-items:center; justify-content:center; gap:0.5rem; }
.qty-input { width:64px; padding:0.28rem; text-align:center; border-radius:6px; border:1px solid #ddd; }
.qty-btn { background:#f5f5f5; border:none; padding:0.3rem 0.6rem; border-radius:6px; cursor:pointer; }
.remove-btn { background:#ff6b6b; color:#fff; border:none; padding:0.35rem 0.6rem; border-radius:6px; cursor:pointer; }

.cart-total { text-align:right; font-size:1.15rem; margin-top:0.8rem; }

.cart-actions { display:flex; justify-content:flex-end; gap:1rem; margin-top:1rem; }
.btn { padding:0.7rem 1.1rem; border-radius:8px; cursor:pointer; border:none; font-weight:600; }
.btn.checkout { background:#2b9f3b; color:white; }

.empty { text-align:center; padding:2rem; color:#666; font-size:1.05rem; }
@media (max-width:720px) {
    .product-cell { flex-direction:column; align-items:center; }
}
</style>
