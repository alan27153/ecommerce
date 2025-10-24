<?php
require_once APP_PATH . '/models/User.php';
require_once BASE_PATH . '/vendor/autoload.php'; // Cargar PHPMailer desde Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;

    }

    /* ==========================================================
       MOSTRAR FORMULARIOS
    ========================================================== */
    public function showLogin() {
        require APP_PATH . '/views/auth/login.php';
    }

    public function showRegister() {
        require APP_PATH . '/views/auth/register.php';
    }

    /* ==========================================================
       LOGIN ADMIN/EDITOR
    ========================================================== */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = User::findByEmail($this->conn, $email);

            if ($user && password_verify($password, $user['password'])) {

                if ($user['verified'] == 0) {
                    $error = "Tu cuenta aún no está verificada. Revisa tu correo.";
                    require APP_PATH . '/views/auth/login.php';
                    return;
                }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];

                // Redirigir según rol
                if (in_array($user['role'], ['admin', 'editor'])) {
                    header("Location: /ecommerce/admin/dashboard");
                } else {
                    header("Location: /ecommerce/");
                }
                exit;
            } else {
                $error = "Credenciales inválidas.";
                require APP_PATH . '/views/auth/login.php';
            }
        }
    }

    /* ==========================================================
       REGISTRO ADMIN/EDITOR CON VERIFICACIÓN Y CAPTCHA
    ========================================================== */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $captcha  = $_POST['g-recaptcha-response'] ?? '';

            // Validación de campos
            if (empty($name) || empty($email) || empty($password)) {
                $error = "Todos los campos obligatorios deben completarse.";
                require APP_PATH . '/views/auth/register.php';
                return;
            }

            // Validar CAPTCHA
            if (!$this->verifyCaptcha($captcha)) {
                $error = "Por favor completa el captcha correctamente.";
                require APP_PATH . '/views/auth/register.php';
                return;
            }

            // Verificar si el usuario ya existe
            $user = User::findByEmail($this->conn, $email);
            if ($user) {
                $error = "El correo ya está registrado.";
                require APP_PATH . '/views/auth/register.php';
                return;
            }

            // Crear usuario
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $verificationCode = rand(100000, 999999);

            $stmt = $this->conn->prepare("
                INSERT INTO users (name, email, password, verification_code, verified, role)
                VALUES (?, ?, ?, ?, 0, 'editor')
            ");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $verificationCode);

            if ($stmt->execute()) {
                // Enviar correo de verificación
                $this->sendVerificationEmail($email, $name, $verificationCode);
                $success = "Registro exitoso. Revisa tu correo para verificar tu cuenta.";
                require APP_PATH . '/views/auth/register.php';
            } else {
                $error = "Error al registrar usuario.";
                require APP_PATH . '/views/auth/register.php';
            }
        }
    }

    /* ==========================================================
       VERIFICAR CAPTCHA (usa tu clave secreta aquí)
    ========================================================== */
    private function verifyCaptcha($captchaResponse) {
        // 🔒 Coloca AQUÍ tu CLAVE SECRETA de reCAPTCHA
        $secretKey = 'TU_CLAVE_SECRETA_AQUI';

        if (empty($captchaResponse)) {
            return false;
        }

        // Usamos cURL (más confiable en InfinityFree)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'secret' => $secretKey,
            'response' => $captchaResponse
        ]);
        $output = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($output, true);
        return $responseData['success'] ?? false;
    }

    /* ==========================================================
       ENVIAR CORREO DE VERIFICACIÓN
    ========================================================== */
    private function sendVerificationEmail($email, $name, $verificationCode) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tu_correo@gmail.com'; // 🔒 tu correo Gmail
            $mail->Password = 'tu_app_password';     // 🔒 tu contraseña o app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@tudominio.com', 'E-commerce');
            $mail->addAddress($email, $name);
            $mail->Subject = 'Verifica tu cuenta - E-commerce';
            $mail->Body = "
                Hola $name,<br><br>
                Tu código de verificación es: <b>$verificationCode</b><br><br>
                Ingresa este código en:<br>
                <a href='https://tudominio.com/ecommerce/verify'>Verificar cuenta</a>
            ";
            $mail->isHTML(true);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $mail->ErrorInfo);
            return false;
        }
    }

    /* ==========================================================
       LOGOUT
    ========================================================== */
    public function logout() {
        session_destroy();
        header("Location: /ecommerce/");
        exit;
    }
}
