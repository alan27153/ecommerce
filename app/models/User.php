<?php
class User {

    /**
     * Busca un usuario por email.
     */
    public static function findByEmail($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario (sin verificar aún).
     * Retorna el ID del nuevo usuario si se inserta correctamente.
     */
    public static function create($conn, $name, $email, $hashedPassword, $verificationCode, $role = 'customer') {
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, verification_code, verified, role)
            VALUES (:name, :email, :password, :verification_code, 0, :role)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verification_code', $verificationCode);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return $conn->lastInsertId();
        }
        return false;
    }

    /**
     * Marca un usuario como verificado.
     */
    public static function verifyAccount($conn, $email, $code) {
        $stmt = $conn->prepare("
            SELECT id FROM users
            WHERE email = :email AND verification_code = :code AND verified = 0
        ");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $update = $conn->prepare("UPDATE users SET verified = 1 WHERE id = :id");
            $update->bindParam(':id', $user['id']);
            return $update->execute();
        }

        return false;
    }

    /**
     * Actualiza el código de verificación (por si se quiere reenviar).
     */
    public static function updateVerificationCode($conn, $email, $newCode) {
        $stmt = $conn->prepare("UPDATE users SET verification_code = :code WHERE email = :email");
        $stmt->bindParam(':code', $newCode);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    /**
     * Verifica si el correo ya está registrado.
     */
    public static function emailExists($conn, $email) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Retorna un usuario por ID.
     */
    public static function findById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Activa o desactiva la cuenta (opcional, por administrador).
     */
    public static function setActive($conn, $id, $active) {
        $stmt = $conn->prepare("UPDATE users SET active = :active WHERE id = :id");
        $stmt->bindParam(':active', $active, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
