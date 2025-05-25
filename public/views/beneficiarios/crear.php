<?php
// Conexión a la base de datos y modelo
require_once __DIR__ . '/../../../app/config/Database.php';
require_once __DIR__ . '/../../../app/models/Beneficiario.php';

$database = new Database();
$db = $database->getConnection();
$model = new Beneficiario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'apellidos' => $_POST['apellidos'],
        'nombres' => $_POST['nombres'],
        'dni' => $_POST['dni'],
        'telefono' => $_POST['telefono'],
        'direccion' => $_POST['direccion'] ?? null
    ];

    if ($model->crear($data)) {
        echo "Beneficiario registrado con éxito";
        exit;
    } else {
        http_response_code(500);
        echo "Error al registrar el beneficiario";
        exit;
    }
}
?>