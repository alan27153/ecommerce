<?php
require_once APP_PATH . '/models/User.php';

class AuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Mostrar formulario de login
    public function showLogin() {
        require APP_PATH . '/views/auth/login.php';
    }

    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($this->conn, $email);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];

                // Redirigir según rol
                if (in_array($user['role'], ['admin','editor'])) {
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

    // Mostrar formulario de registro
    public function showRegister() {
        require APP_PATH . '/views/auth/register.php';
    }

    // Procesar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = $_POST['name'] ?? '';
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                $error = "Todos los campos son obligatorios.";
                require APP_PATH . '/views/auth/register.php';
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: /ecommerce/login");
                exit;
            } else {
                $error = "Error al registrar usuario.";
                require APP_PATH . '/views/auth/register.php';
            }
        }
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /ecommerce/");
        exit;
    }
}
