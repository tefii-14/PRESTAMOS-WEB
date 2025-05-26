<?php
require_once __DIR__ . '/../../app/config/Database.php';

class Contrato {
    private $conn;
    private $table_name = "contratos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar() {
        $query = "SELECT c.idcontrato, c.idbeneficiario, c.monto, c.interes AS tasa_interes, c.fechainicio AS fecha_inicio, c.diapago, c.numcuotas, c.estado,
                         CONCAT(b.nombres, ' ', b.apellidos) AS beneficiario_nombre
                  FROM " . $this->table_name . " c
                  JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario
                  WHERE c.estado = 'ACT'
                  ORDER BY c.idcontrato DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $contratos = [];
        while ($row = $result->fetch_assoc()) {
            $contratos[] = $row;
        }
        $stmt->close();
        return $contratos;
    }

    public function tieneContratoVigente($idbeneficiario) {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name . " WHERE idbeneficiario = ? AND estado = 'ACT'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $idbeneficiario);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] > 0;
    }

    public function crear($data) {
        // Verificar si el beneficiario ya tiene un contrato vigente
        if ($this->tieneContratoVigente($data['idbeneficiario'])) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (idbeneficiario, monto, interes, fechainicio, diapago, numcuotas, estado, creado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return false;
        }

        $diapago = isset($data['diapago']) ? $data['diapago'] : 15;
        $numcuotas = isset($data['numcuotas']) ? $data['numcuotas'] : 12;
        $estado = isset($data['estado']) ? $data['estado'] : 'ACT';

        $stmt->bind_param('iddsiis', 
            $data['idbeneficiario'],
            $data['monto'],
            $data['tasa_interes'],
            $data['fecha_inicio'],
            $diapago,
            $numcuotas,
            $estado
        );

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>