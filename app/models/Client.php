<?php

class Client {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener datos del cliente por email (para login)
    public function findByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT u.id, u.name, u.email, u.password, c.address, c.phone, c.document_number
             FROM users u
             JOIN clients c ON c.user_id = u.id
             WHERE u.email = ? AND u.role = 'customer'"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Crear un nuevo cliente (registro)
    public function create($name, $email, $password, $address = null, $phone = null, $document_number = null) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insertar en tabla users
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insertar en tabla clients
            $stmt2 = $this->conn->prepare(
                "INSERT INTO clients (user_id, address, phone, document_number) VALUES (?, ?, ?, ?)"
            );
            $stmt2->bind_param("isss", $user_id, $address, $phone, $document_number);
            return $stmt2->execute();
        }

        return false;
    }

    // Actualizar datos del cliente
    public function update($user_id, $name, $email, $address, $phone, $document_number) {
        // Actualizar tabla users
        $stmt1 = $this->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt1->bind_param("ssi", $name, $email, $user_id);
        $stmt1->execute();

        // Actualizar tabla clients
        $stmt2 = $this->conn->prepare(
            "UPDATE clients SET address = ?, phone = ?, document_number = ? WHERE user_id = ?"
        );
        $stmt2->bind_param("sssi", $address, $phone, $document_number, $user_id);
        return $stmt2->execute();
    }
}
