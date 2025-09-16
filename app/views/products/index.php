<div id="products" class="products-grid"></div>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Tienda</title>
  
  <!-- Importar CSS -->
  <link rel="stylesheet" href="/assets/css/reset.css"> <!-- Reset básico -->
  <link rel="stylesheet" href="/assets/css/styles.css"> <!-- Estilos principales -->
</head>
<body>

  <header class="header">
    <h1 class="header__logo">MiTienda</h1>
    <nav class="header__nav">
      <ul class="header__menu">
        <li class="header__menu-item"><a href="#">Inicio</a></li>
        <li class="header__menu-item"><a href="#">Productos</a></li>
        <li class="header__menu-item"><a href="#">Carrito</a></li>
      </ul>
    </nav>
  </header>

  <main class="main">
    <section class="product-list">
      <article class="product-card product-card--featured">
        <img src="/uploads/producto1.jpg" alt="Producto 1" class="product-card__image">
        <h2 class="product-card__title">Producto 1</h2>
        <p class="product-card__price">S/ 150.00</p>
        <button class="product-card__btn">Añadir al carrito</button>
      </article>
    </section>
  </main>

  <!-- Importar JS -->
  <script src="/assets/js/app.js"></script>
</body>
</html>
