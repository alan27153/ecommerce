<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/product.css">
</head>
<body>

    <form method="POST" action="">
    <h2>Login Cliente</h2>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">Iniciar Sesión</button>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <p>¿No tienes cuenta?<a href="/ecommerce/client/register">Regístrate</a></p>

    </form>
</body>
</html>

<style>
    /* Reset básico */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

form {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: 0.3s;
}

input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #0056b3;
}

p {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
    color: #555;
}

p a {
    color: #007bff;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

/* Mensaje de error */
p[style*="color:red"] {
    text-align: center;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 500px) {
    form {
        padding: 20px;
        width: 90%;
    }
}

</style>