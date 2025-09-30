// ✅ Buena práctica: usar DocumentFragment
function renderProducts(productos) {
    const fragment = document.createDocumentFragment();

    productos.forEach(p => {
        const card = document.createElement('div');
        card.classList.add('product-card');
        card.innerHTML = `
            <img src="${p.image}" alt="${p.name}" loading="lazy">
            <h3>${p.name}</h3>
            <p>S/ ${(p.price_cents / 100).toFixed(2)}</p>
        `;
        fragment.appendChild(card);
    });

    // Insertar TODO de golpe en el DOM
    document.getElementById('products').appendChild(fragment);
}


let page = 1;
let loading = false;

async function cargarMasProductos() {
    if (loading) return;
    loading = true;

    const res = await fetch(`/api/products?page=${page}`);
    const productos = await res.json();

    renderProducts(productos);
    page++;
    loading = false;
}

window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
        cargarMasProductos();
    }
});
