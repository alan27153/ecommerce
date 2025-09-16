<?php
namespace App\Models;

use PDO;
use PDOException;

class User
{
    private $pdo;

    // Recibe la conexión PDO (inyectada desde database.php)
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Registrar usuario
    public function create($name, $email, $password)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->pdo->prepare("
                INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)
            ");

            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);
        } catch (PDOException $e) {
            error_log("Error creando usuario: " . $e->getMessage());
            return false;
        }
    }

    // 2. Obtener usuario por email (para login)
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Verificar login
    public function verifyLogin($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // login correcto
        }
        return false; // credenciales inválidas
    }

    // 4. Obtener usuario por ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Actualizar perfil (ejemplo: nombre y teléfono)
    public function updateProfile($id, $name, $phone)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET name = :name, phone = :phone, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':phone' => $phone
        ]);
    }

    // 6. Cambiar contraseña
    public function changePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("
            UPDATE users SET password = :password, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':password' => $hashedPassword
        ]);
    }

    // 7. Eliminar usuario (opcional)
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM users WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }
}
