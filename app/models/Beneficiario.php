<?php
class Beneficiario {
    private $conn;
    private $table_name = "beneficiarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar() {
        $query = "SELECT b.*, 
                         (SELECT COUNT(*) FROM contratos c WHERE c.idbeneficiario = b.idbeneficiario AND c.estado = 'ACT') AS contratos_vigentes
                  FROM " . $this->table_name . " b
                  ORDER BY b.idbeneficiario DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $beneficiarios = [];
        while ($row = $result->fetch_assoc()) {
            $beneficiarios[] = $row;
        }
        $stmt->close();
        return $beneficiarios;
    }

    public function crear($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (apellidos, nombres, dni, telefono, direccion) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $direccion = $data['direccion'] ?? null;
        $stmt->bind_param('sssss', 
            $data['apellidos'],
            $data['nombres'],
            $data['dni'],
            $data['telefono'],
            $direccion
        );

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>