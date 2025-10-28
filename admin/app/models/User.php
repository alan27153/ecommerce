<?php
namespace Admin\Models;

class User
{
    private static ?\PDO $conn = null;

    /**
     * Configura la conexión (solo una vez)
     */
    public static function setConnection(\PDO $connection): void
    {
        self::$conn = $connection;
    }

    /**
     * Devuelve la conexión PDO global
     */
    private static function getConnection(): \PDO
    {
        if (!self::$conn) {
            throw new \RuntimeException("No hay conexión establecida. Llama a User::setConnection(\$conn) primero.");
        }
        return self::$conn;
    }

    /**
     * Busca un usuario por email
     */
    public static function findByEmail(string $email): array|false
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca un usuario por ID
     */
    public static function findById(int $id): array|false
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si el email ya existe
     */
    public static function emailExists(string $email): bool
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Crea un nuevo usuario
     */
    public static function create(string $name, string $email, string $hashedPassword, string $role = 'customer'): int|false
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, role, verified, active)
            VALUES (:name, :email, :password, :role, 1, 1)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return (int) $conn->lastInsertId();
        }
        return false;
    }

    /**
     * Autentica usuario y contraseña
     */
    public static function authenticate(string $email, string $password): array|false
    {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password']) && !empty($user['active'])) {
            return $user;
        }
        return false;
    }

    public static function updatePassword(int $id, string $hashedPassword): bool
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function setActive(int $id, bool $active): bool
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("UPDATE users SET active = :active WHERE id = :id");
        $stmt->bindValue(':active', $active ? 1 : 0, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function all(): array
    {
        $conn = self::getConnection();
        $stmt = $conn->query("SELECT id, name, email, role, active, verified, created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
