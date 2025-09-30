<?php
require_once APP_PATH . '/models/Client.php';

class ClientAuthController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function showLogin() {
        require APP_PATH . '/views/auth/client/clientLogin.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $client = Client::findByEmail($this->conn, $email); // método heredado de User

            if ($client && password_verify($password, $client['password']) && $client['role'] === 'customer') {
                session_start();
                $_SESSION['user_id'] = $client['id'];
                $_SESSION['role']    = $client['role'];
                header("Location: /ecommerce/");
                exit;
            } else {
                $error = "Credenciales inválidas.";
                require APP_PATH . '/views/auth/client/clientLogin.php';
            }
        }
    }

    public function showRegister() {
        require APP_PATH . '/views/auth/client/clientRegister.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = $_POST['name'] ?? '';
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $address  = $_POST['address'] ?? null;
            $phone    = $_POST['phone'] ?? null;
            $doc      = $_POST['document_number'] ?? null;

            $client = new Client($this->conn);

            if ($client->create($name, $email, $password, $address, $phone, $doc)) {
                header("Location: /ecommerce/client/login");
                exit;
            } else {
                $error = "Error al registrar cliente.";
                require APP_PATH . '/views/auth/client/clientRegister.php';
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /ecommerce/client/login");
        exit;
    }
}
