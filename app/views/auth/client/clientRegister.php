
<body>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST" action="">
        <h2>Registro Cliente</h2>

        <label>Nombre:</label>
        <input type="text" name="name" required>
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <br><br>

        <label>Dirección:</label>
        <input type="text" name="address">
        <br><br>

        <label>Teléfono:</label>
        <input type="text" name="phone">
        <br><br>

        <label>DNI / RUC:</label>
        <input type="text" name="document_number">
        <br><br>

        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6Le-_uArAAAAADNE989VTDWT7mbxHUM7vdwL5vIy"></div>
        <br>

        <button type="submit">Registrar</button>
        <p>¿Ya tienes cuenta? <a href="/ecommerce/client/login">Inicia Sesión</a></p>
    </form>

    <!-- Carga del script de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>


<style>
    /* Reset básico */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

html, body {
    height: 100%;
    width: 100%;
}

body {
    background: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

/* Contenedor principal */
.container {
    width: 100%;
    max-width: 360px; /* ancho compacto */
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Título */
.container h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.6rem;
    color: #333;
}

/* Labels */
label {
    display: block;
    /* margin-bottom: 5px; */
    font-weight: bold;
    color: #555;
    font-size: 0.9rem;
}

/* Inputs */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 8px;
    /* margin-bottom: 15px; */
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 0.9rem;
}

input:focus {
    border-color: #007bff;
    outline: none;
}

/* Botón */
button {
    width: 100%;
    padding: 10px;
    background: #28a745;
    border: none;
    color: white;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #218838;
}

/* Texto inferior */
p {
    text-align: center;
    margin-top: 12px;
    font-size: 0.85rem;
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
    margin-bottom: 10px;
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 400px) {
    .container {
        padding: 15px;
        max-width: 95%;
    }

    .container h2 {
        font-size: 1.4rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 7px;
        font-size: 0.85rem;
    }

    button {
        padding: 9px;
        font-size: 0.95rem;
    }

    p {
        font-size: 0.8rem;
    }
}

</style>