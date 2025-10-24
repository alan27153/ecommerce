<div class="container">
    <form method="POST" action="/ecommerce/client/register">
        <h2>Registro de Cliente</h2>

        <label>Nombre:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Dirección:</label>
        <input type="text" name="address">

        <label>Teléfono:</label>
        <input type="text" name="phone">

        <label>DNI / RUC:</label>
        <input type="text" name="document_number">

        <!-- reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6Le-_uArAAAAADNE989VTDWT7mbxHUM7vdwL5vIy"></div>

        <!-- MENSAJES -->
        <?php if(isset($error)) echo "<p class='msg error'>$error</p>"; ?>
        <?php if(isset($success)) echo "<p class='msg success'>$success</p>"; ?>
<!-- <a href="/ecommerce/client/register"></a> -->
        <button type="submit">Registrar</button>
        <p>¿Ya tienes cuenta? <a href="/ecommerce/client/login">Inicia sesión</a></p>
    </form>
</div>


    <!-- Script de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

<style>
/* ------------------ RESET BÁSICO ------------------ */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
}

html, body {
    height: 100%;
    width: 100%;
}

/* ------------------ FONDO Y CENTRADO ------------------ */
body {
    background: linear-gradient(135deg, #e8f0fe, #f5f5f5);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 15px;
}

/* ------------------ CONTENEDOR PRINCIPAL ------------------ */
.container {
    width: 100%;
    max-width: 420px;
    background: #fff;
    padding: 30px 25px;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.container:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* ------------------ TÍTULO ------------------ */
.container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.7rem;
    color: #333;
}

/* ------------------ ETIQUETAS Y CAMPOS ------------------ */
label {
    display: block;
    font-weight: bold;
    color: #555;
    font-size: 0.9rem;
    margin-bottom: 6px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    margin-bottom: 18px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.3);
    outline: none;
}

/* ------------------ BOTÓN ------------------ */
button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    border: none;
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #0056b3;
}

/* ------------------ TEXTO INFERIOR ------------------ */
p {
    text-align: center;
    margin-top: 15px;
    font-size: 0.9rem;
    color: #555;
}

p a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

p a:hover {
    text-decoration: underline;
}

/* ------------------ MENSAJES ------------------ */
p[style*="color:red"],
p[style*="color:green"] {
    text-align: center;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

/* ------------------ RESPONSIVE ------------------ */
@media (max-width: 480px) {
    .container {
        padding: 20px;
        max-width: 90%;
    }

    .container h2 {
        font-size: 1.4rem;
    }

    input, button {
        font-size: 0.9rem;
    }

    button {
        padding: 10px;
    }

    p {
        font-size: 0.8rem;
    }
}
</style>
