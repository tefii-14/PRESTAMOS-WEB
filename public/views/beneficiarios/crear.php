<?php
require_once __DIR__ . '/../../../app/models/Beneficiario.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = new Beneficiario();
    $model->crear($_POST);
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Método no permitido']);
exit;
