<?php
class Client {

    /**
     * Crea un nuevo registro en la tabla clients enlazado a un user_id
     */
    public static function create($conn, $userId, $address = null, $phone = null, $documentNumber = null) {
        $stmt = $conn->prepare("INSERT INTO clients (user_id, address, phone, document_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $address, $phone, $documentNumber);
        return $stmt->execute();
    }

    /**
     * Obtiene datos del cliente por user_id
     */
    public static function findByUserId($conn, $userId) {
        $stmt = $conn->prepare("SELECT * FROM clients WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Actualiza los datos de un cliente
     */
    public static function update($conn, $userId, $address, $phone, $documentNumber) {
        $stmt = $conn->prepare("UPDATE clients SET address = ?, phone = ?, document_number = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $address, $phone, $documentNumber, $userId);
        return $stmt->execute();
    }

    /**
     * Elimina un cliente (por si se borra el usuario)
     */
    public static function delete($conn, $userId) {
        $stmt = $conn->prepare("DELETE FROM clients WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>
