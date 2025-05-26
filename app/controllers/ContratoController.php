<?php
require_once __DIR__ . '/../models/Contrato.php';

class ContratoController {
    private $model;

    public function __construct() {
        $this->model = new Contrato();
    }

    public function listar() {
        $contratos = $this->model->listar();
        return $contratos;
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idbeneficiario' => $_POST['idbeneficiario'],
                'monto' => $_POST['monto'],
                'tasa_interes' => $_POST['tasa_interes'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'diapago' => $_POST['diapago'],
                'numcuotas' => $_POST['numcuotas']
            ];

            if (empty($data['idbeneficiario']) || empty($data['monto']) || empty($data['tasa_interes']) || 
                empty($data['fecha_inicio']) || empty($data['diapago']) || empty($data['numcuotas'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                exit;
            }

            $model = new Contrato();
            if ($model->tieneContratoVigente($data['idbeneficiario'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'El beneficiario ya tiene un contrato vigente']);
                exit;
            }

            if ($this->model->crear($data)) {
                echo json_encode(['success' => true, 'message' => 'Contrato registrado con éxito']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al registrar el contrato']);
            }
            exit;
        }
    }
}

if (isset($_GET['action'])) {
    $controller = new ContratoController();
    switch ($_GET['action']) {
        case 'listar':
            $contratos = $controller->listar();
            break;
        case 'crear':
            $controller->crear();
            break;
    }
}
?>