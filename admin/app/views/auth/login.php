<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="/ecommerce/assets/css/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f3f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
            width: 300px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        .login-box input {
            width: 100%;
            margin-bottom: 1rem;
            padding: .5rem;
        }
        .login-box button {
            width: 100%;
            padding: .5rem;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: .5rem;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Acceso Admin</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="login">
            <input type="text" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
