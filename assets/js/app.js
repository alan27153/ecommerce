document.addEventListener("DOMContentLoaded", () => {
    const productList = document.getElementById("product-list");
    const loadMoreBtn = document.getElementById("load-more");

    let page = 1; // página inicial
    const limit = 5; // cuántos productos por petición

    async function loadProducts() {
        try {
            const response = await fetch(`/productos/listar?page=${page}&limit=${limit}`);
            if (!response.ok) {
                throw new Error("Error al cargar productos");
            }

            const data = await response.json();

            if (data.length === 0) {
                // No hay más productos
                loadMoreBtn.style.display = "none";
                return;
            }

            const fragment = document.createDocumentFragment();

            data.forEach(product => {
                const div = document.createElement("div");
                div.className = "product-card";
                div.innerHTML = `
                    <h3>${product.nombre}</h3>
                    <p>Precio: S/ ${product.precio}</p>
                    <button>Agregar al carrito</button>
                `;
                fragment.appendChild(div);
            });

            productList.appendChild(fragment);
            page++; // aumentar para la próxima carga
        } catch (error) {
            console.error("Error:", error);
        }
    }

    // Primera carga automática
    loadProducts();

    // Cargar más al hacer click
    loadMoreBtn.addEventListener("click", loadProducts);
});
