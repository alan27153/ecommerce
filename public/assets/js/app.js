document.addEventListener("DOMContentLoaded", () => {
    const productList = document.getElementById("product-list");
    const loadMoreBtn = document.getElementById("load-more");

    // Simulación de productos (normalmente vendrían de PHP/MySQL vía fetch)
    let products = [
        { id: 1, name: "Producto A", price: "S/ 50" },
        { id: 2, name: "Producto B", price: "S/ 70" },
        { id: 3, name: "Producto C", price: "S/ 100" },
        { id: 4, name: "Producto D", price: "S/ 120" },
        { id: 5, name: "Producto E", price: "S/ 200" }
    ];

    let index = 0;
    const step = 2; // cuántos productos cargar por vez

    function renderProducts() {
        const fragment = document.createDocumentFragment();

        for (let i = 0; i < step && index < products.length; i++, index++) {
            const product = products[index];

            const div = document.createElement("div");
            div.className = "product-card";
            div.innerHTML = `
                <h3>${product.name}</h3>
                <p>Precio: ${product.price}</p>
                <button>Agregar al carrito</button>
            `;

            fragment.appendChild(div);
        }

        productList.appendChild(fragment);

        // Si no hay más productos, ocultamos el botón
        if (index >= products.length) {
            loadMoreBtn.style.display = "none";
        }
    }

    // Cargar primeros productos al inicio
    renderProducts();

    // Cargar más cuando el usuario presione el botón
    loadMoreBtn.addEventListener("click", renderProducts);
});
