<?php
require_once '../config/Database.php';
require_once '../models/Beneficiario.php';

class BeneficiariosController {
    private $model;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->model = new Beneficiario($db);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $apellidos = $_POST['apellidos'];
            $nombres = $_POST['nombres'];
            $dni = $_POST['dni'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'] ?? null;

            if ($this->model->create($apellidos, $nombres, $dni, $telefono, $direccion)) {
                header("Location: /public/index.php?controller=beneficiarios&action=listar");
                exit;
            } else {
                echo "Error al registrar el beneficiario.";
            }
        }
        $seccion = 'beneficiarios';
        require_once '../views/beneficiarios/crear.php';
    }

    public function listar() {
        $beneficiarios = $this->model->getAll();
        $seccion = 'beneficiarios';
        require_once '../views/beneficiarios/listar.php';
    }
}
?>