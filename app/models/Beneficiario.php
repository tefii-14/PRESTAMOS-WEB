<?php
require_once __DIR__ . '/../config/Database.php';

class Beneficiario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function listar() {
        $result = $this->conn->query("SELECT * FROM beneficiarios");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function crear($data) {
        $stmt = $this->conn->prepare("INSERT INTO beneficiarios (apellidos, nombres, dni, telefono, direccion)
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssss",
            $data['apellidos'],
            $data['nombres'],
            $data['dni'],
            $data['telefono'],
            $data['direccion']
        );
        return $stmt->execute();
    }
}
