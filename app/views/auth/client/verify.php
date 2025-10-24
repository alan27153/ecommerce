<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar cuenta</title>
</head>
    <div class="verify-container">
        <h1>Verificación de cuenta</h1>
        <p>Ingresa el código que te enviamos a tu correo electrónico.</p>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/ecommerce/client/verify">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            <input type="text" name="code" placeholder="Código de verificación" required>
            <button type="submit">Verificar</button>
        </form>
    </div>

<style>
    /* ==============================
   Estilo para la vista de verificación
   ============================== */
body {
    font-family: 'Poppins', sans-serif;

    
    
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.verify-container {
    background: white;
    padding: 2.5rem 3rem;
    border-radius: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    width: 400px;
    text-align: center;
}

.verify-container h1 {
    font-size: 1.6rem;
    margin-bottom: 0.5rem;
    color: #1e3a8a;
}

.verify-container p {
    font-size: 0.95rem;
    color: #555;
    margin-bottom: 1.5rem;
}

input[type="text"] {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #cbd5e1;
    border-radius: 0.75rem;
    font-size: 1rem;
    text-align: center;
    transition: 0.3s;
}

input[type="text"]:focus {
    border-color: #3b82f6;
    outline: none;
}

button {
    width: 100%;
    margin-top: 1.5rem;
    background: #2563eb;
    color: white;
    border: none;
    padding: 0.75rem;
    font-size: 1rem;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background: #1e40af;
}

.alert {
    padding: 0.8rem;
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

.alert.error {
    background: #fee2e2;
    color: #991b1b;
}

.alert.success {
    background: #dcfce7;
    color: #166534;
}

</style>
</html>
