<?php
require_once __DIR__ . '/../../config/database.php'; // conexión PDO sin clase
require_once APP_PATH . '/models/User.php';

class ClientAuthController {

    private PDO $conn;

    public function __construct() {
        global $conn;

        if (!isset($conn)) {
            die("❌ Error: No se pudo establecer la conexión a la base de datos.");
        }

        $this->conn = $conn;
    }

    /**
     * Muestra el formulario de login.
     */
    public function showLogin() {
        require_once APP_PATH . '/views/auth/client/clientLogin.php';
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegister() {
        require_once APP_PATH . '/views/auth/client/clientRegister.php';
    }

    /**
     * Registro del cliente.
     */
    public function register() {
        // Datos del formulario
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $document_number = $_POST['document_number'] ?? '';
        $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // Verificar CAPTCHA
        if (empty($captchaResponse)) {
            $error = "Por favor completa el captcha correctamente.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Validar con Google reCAPTCHA
        $secretKey = "6Le-_uArAAAAAKgl5wzRdYpx7nkE2roMZaAxG_le"; // tu clave secreta real
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
        $response = json_decode($verify);

        if (!$response->success) {
            $error = "Por favor completa el captcha correctamente.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Validar campos vacíos
        if (empty($name) || empty($email) || empty($password)) {
            $error = "Por favor completa todos los campos obligatorios.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Validar si el correo ya está registrado
        if (User::emailExists($this->conn, $email)) {
            $error = "Este correo ya está registrado.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Crear usuario
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verificationCode = rand(100000, 999999);

        $userId = User::create($this->conn, $name, $email, $hashedPassword, $verificationCode, 'customer');

        if (!$userId) {
            $error = "Error al registrar el usuario.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Crear cliente asociado
        $stmt = $this->conn->prepare("
            INSERT INTO clients (user_id, address, phone, document_number)
            VALUES (:user_id, :address, :phone, :document_number)
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':document_number', $document_number);
        $stmt->execute();

        // Enviar código de verificación por correo (puedes implementar PHPMailer)
        // mail($email, "Código de verificación", "Tu código es: $verificationCode");

        $success = "Registro exitoso. Revisa tu correo para verificar tu cuenta.";
        require_once APP_PATH . '/views/auth/client/clientLogin.php';
    }

    /**
     * Inicio de sesión del cliente.
     */
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($this->conn, $email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['verified'] == 0) {
                $error = "Tu cuenta no está verificada.";
                require_once APP_PATH . '/views/auth/client/clientLogin.php';
                return;
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: /ecommerce/");
            exit;
        } else {
            $error = "Correo o contraseña incorrectos.";
            require_once APP_PATH . '/views/auth/client/clientLogin.php';
        }
    }

    /**
     * Cierra la sesión.
     */
    public function logout() {
        session_destroy();
        header("Location: /ecommerce/");
        exit;
    }
}
?>
