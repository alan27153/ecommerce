<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar cuenta</title>
    <link rel="stylesheet" href="/assets/css/styles.css"> <!-- Opcional -->
</head>
<body>

    <h2>Verificar tu cuenta</h2>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST" action="">
        <label>Correo electrónico:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Código de verificación:</label><br>
        <input type="text" name="code" maxlength="6" required>
        <br><br>

        <button type="submit">Verificar cuenta</button>
    </form>

    <p>¿No recibiste el código? <a href="/ecommerce/client/register">Regístrate nuevamente</a></p>

</body>
</html>
