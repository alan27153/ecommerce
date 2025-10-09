<?php
require_once __DIR__ . '/../../config/database.php'; // conexión PDO
require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/helpers/MailHelper.php';

class ClientAuthController {

    private PDO $conn;

    public function __construct() {
        global $conn;

        if (!isset($conn)) {
            die("❌ Error: No se pudo establecer la conexión a la base de datos.");
        }

        $this->conn = $conn;
    }

    /** Mostrar formulario de login */
    public function showLogin() {
        require_once APP_PATH . '/views/auth/client/clientLogin.php';
    }

    /** Mostrar formulario de registro */
    public function showRegister() {
        require_once APP_PATH . '/views/auth/client/clientRegister.php';
    }

    /** Registro del cliente */
    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $document_number = $_POST['document_number'] ?? '';
        $captchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // Validar campos y CAPTCHA
        if (empty($name) || empty($email) || empty($password)) {
            $error = "Todos los campos son obligatorios.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        if (!$this->verifyCaptcha($captchaResponse)) {
            $error = "Por favor completa el captcha correctamente.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Verificar si el correo ya existe
        if (User::emailExists($this->conn, $email)) {
            $error = "Este correo ya está registrado.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        // Crear usuario y cliente
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verificationCode = rand(100000, 999999);

        $userId = User::create($this->conn, $name, $email, $hashedPassword, $verificationCode, 'customer');

        if (!$userId) {
            $error = "Error al registrar el usuario.";
            require_once APP_PATH . '/views/auth/client/clientRegister.php';
            return;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO clients (user_id, address, phone, document_number)
            VALUES (:user_id, :address, :phone, :document_number)
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':document_number', $document_number);
        $stmt->execute();

        // Enviar código de verificación
        MailHelper::sendVerificationEmail($email, $verificationCode);

        // Redirigir a la vista de verificación con correo
        header("Location: /ecommerce/client/verify?email=" . urlencode($email));
        exit;
    }

    /** Mostrar formulario de verificación */
    public function showVerifyForm() {
        $email = $_GET['email'] ?? null;
        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;

        require APP_PATH . '/views/auth/client/verify.php';
    }

    /** Verificar el código enviado */
    public function verifyAccount() {
        $email = $_POST['email'] ?? '';
        $code = $_POST['code'] ?? '';

        if (empty($email) || empty($code)) {
            $error = "Faltan datos para verificar la cuenta.";
            header("Location: /ecommerce/client/verify?email=" . urlencode($email) . "&error=" . urlencode($error));
            exit;
        }

        $user = User::findByEmail($this->conn, $email);

        if (!$user) {
            $error = "No se encontró una cuenta con ese correo.";
            header("Location: /ecommerce/client/verify?email=" . urlencode($email) . "&error=" . urlencode($error));
            exit;
        }

        if ($user['verified'] == 1) {
            header("Location: /ecommerce/client/login?verified=1");
            exit;
        }

        if ($user['verification_code'] != $code) {
            $error = "Código incorrecto.";
            header("Location: /ecommerce/client/verify?email=" . urlencode($email) . "&error=" . urlencode($error));
            exit;
        }

        // Marcar como verificado
        $stmt = $this->conn->prepare("UPDATE users SET verified = 1 WHERE id = :id");
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir a login con éxito
        header("Location: /ecommerce/client/login?verified=1");
        exit;
    }

    /** Login del cliente */
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($this->conn, $email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['verified'] == 0) {
                $error = "Tu cuenta no está verificada.";
                require APP_PATH . '/views/auth/client/clientLogin.php';
                return;
            }

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: /ecommerce/");
            exit;
        } else {
            $error = "Correo o contraseña incorrectos.";
            require APP_PATH . '/views/auth/client/clientLogin.php';
        }
    }

    /** Logout */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /ecommerce/");
        exit;
    }

    /** Verificar Google reCAPTCHA */
    private function verifyCaptcha($captchaResponse): bool {
        $secretKey = "6Le-_uArAAAAAKgl5wzRdYpx7nkE2roMZaAxG_le";
        if (empty($captchaResponse)) return false;

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
        $response = json_decode($verify);
        return $response->success ?? false;
    }


}
?>
