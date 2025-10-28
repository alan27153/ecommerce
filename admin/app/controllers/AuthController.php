<?php
namespace Admin\Controllers;

use Admin\Models\User; // 👈 Importamos la clase correctamente
use PDO;               // 👈 Importamos también PDO global

require_once ADMIN_APP_PATH . '/models/User.php';
class AuthController {
    private User $userModel;
public function __construct(\PDO $conn){

        // Recibe la conexión PDO y crea el modelo User
        $this->userModel = new User($conn);
    }

    // --------------------------
    // Mostrar formulario de login
    // --------------------------
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /ecommerce/admin/dashboard');
            exit;
        }

        require ADMIN_APP_PATH . '/views/auth/login.php';
    }

    // --------------------------
    // Procesar formulario de login
    // --------------------------
    public function loginSubmit() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validar campos vacíos
        if (empty($email) || empty($password)) {
            $error = "Por favor ingrese su correo y contraseña.";
            require ADMIN_APP_PATH . '/views/auth/login.php';
            return;
        }

        // Buscar usuario por email
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $error = "Usuario o contraseña incorrectos.";
            require ADMIN_APP_PATH . '/views/auth/login.php';
            return;
        }

        // Validar contraseña
        if (!password_verify($password, $user['password'])) {
            $error = "Usuario o contraseña incorrectos.";
            require ADMIN_APP_PATH . '/views/auth/login.php';
            return;
        }

        // Verificar si está activo
        if (!$user['active']) {
            $error = "Tu cuenta está desactivada.";
            require ADMIN_APP_PATH . '/views/auth/login.php';
            return;
        }

        // Verificar rol
        if ($user['role'] !== 'admin') {
            $error = "No tienes permisos para acceder al panel.";
            require ADMIN_APP_PATH . '/views/auth/login.php';
            return;
        }
echo 1;
        // Iniciar sesión segura
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: /ecommerce/admin/dashboard');

        exit;
    }

    // --------------------------
    // Cerrar sesión
    // --------------------------
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /ecommerce/admin/login');
        exit;
    }
}
