<?php
require_once __DIR__ . '/../../../app/config/Database.php';
require_once __DIR__ . '/../../../app/models/Contrato.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error al procesar la solicitud'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $model = new Contrato();

    if (isset($_POST['action']) && $_POST['action'] === 'eliminar' && isset($_POST['idcontrato'])) {
        // Manejar eliminación (cambiar estado a 'FIN')
        $idcontrato = $_POST['idcontrato'];
        $query = "UPDATE contratos SET estado = 'FIN' WHERE idcontrato = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $idcontrato);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Contrato eliminado con éxito';
        } else {
            $response['message'] = 'No se pudo eliminar el contrato: ' . $db->error;
        }
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'getEstado' && isset($_POST['idcontrato'])) {
        // Obtener el estado del contrato
        $idcontrato = $_POST['idcontrato'];
        $query = "SELECT estado FROM contratos WHERE idcontrato = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $idcontrato);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $response['success'] = true;
                // Mapear el estado a "Activo" o "Finalizado"
                $estado = strtoupper($row['estado']);
                $response['estado'] = ($estado === 'ACT') ? 'Activo' : (($estado === 'FIN') ? 'Finalizado' : 'Desconocido');
            } else {
                $response['estado'] = 'Desconocido';
            }
        } else {
            $response['message'] = 'Error al obtener el estado: ' . $db->error;
            $response['estado'] = 'Desconocido';
        }
        $stmt->close();
    } else {
        // Manejar creación de contrato
        $idbeneficiario = $_POST['idbeneficiario'] ?? null;
        $monto = $_POST['monto'] ?? null;
        $interes = $_POST['tasa_interes'] ?? null;
        $fechainicio = $_POST['fecha_inicio'] ?? null;
        $diapago = $_POST['diapago'] ?? null;
        $numcuotas = $_POST['numcuotas'] ?? null;

        // Validar datos
        $missingFields = [];
        if (!$idbeneficiario) $missingFields[] = 'idbeneficiario';
        if (!$monto) $missingFields[] = 'monto';
        if (!$interes) $missingFields[] = 'tasa_interes';
        if (!$fechainicio) $missingFields[] = 'fecha_inicio';
        if (!$diapago) $missingFields[] = 'diapago';
        if (!$numcuotas) $missingFields[] = 'numcuotas';

        if (!empty($missingFields)) {
            $response['message'] = 'Faltan datos para registrar el contrato: ' . implode(', ', $missingFields);
        } else {
            $data = [
                'idbeneficiario' => $idbeneficiario,
                'monto' => $monto,
                'tasa_interes' => $interes,
                'fecha_inicio' => $fechainicio,
                'diapago' => $diapago,
                'numcuotas' => $numcuotas,
                'estado' => 'ACT'
            ];

            if ($model->crear($data)) {
                $response['success'] = true;
                $response['message'] = 'Contrato registrado con éxito';
            } else {
                $response['message'] = 'No se pudo registrar el contrato: ' . $db->error;
            }
        }
    }
}

echo json_encode($response);
?>