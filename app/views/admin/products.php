<?php
// $productos debe venir del controlador
// $user también debe venir para verificar permisos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/product.css">
</head>
<body>

<h1>Gestión de Productos</h1>

<a href="/admin/products/create" style="padding:10px 15px; background:#28a745; color:#fff; text-decoration:none; border-radius:5px;">+ Nuevo Producto</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $p): ?>
        <tr>
            <td data-label="ID"><?= htmlspecialchars($p['id']) ?></td>
            <td data-label="Imagen">
                <?php if (!empty($p['main_image'])): ?>
                    <img src="<?= htmlspecialchars($p['main_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <?php else: ?>
                    Sin imagen
                <?php endif; ?>
            </td>
            <td data-label="Nombre"><?= htmlspecialchars($p['name']) ?></td>
            <td data-label="Categoría"><?= htmlspecialchars($p['category_name']) ?></td>
            <td data-label="Precio">S/. <?= number_format($p['price_cents'] / 100, 2) ?></td>
            <td data-label="Stock"><?= $p['stock'] ?></td>
            <td data-label="Activo"><?= $p['active'] ? 'Sí' : 'No' ?></td>
            <td data-label="Acciones" class="actions">
                <a href="/admin/products/edit/<?= $p['id'] ?>"><button>Editar</button></a>
                <a href="/admin/products/delete/<?= $p['id'] ?>" onclick="return confirm('¿Eliminar este producto?')"><button style="background:#dc3545;color:#fff;">Eliminar</button></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
