<?php
require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/helpers.php';

class ContratoController {
    private $model;

    public function __construct() {
        $this->model = new Contrato();
    }

    public function listar() {
        $contratos = $this->model->listar();
        ob_start();
        ?>
        <tbody id="tabla-contratos">
            <?php if (empty($contratos)): ?>
                <tr>
                    <td colspan="9" class="text-center">No hay contratos registrados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($contratos as $contrato): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contrato['idcontrato']); ?></td>
                        <td><?php echo htmlspecialchars($contrato['beneficiario_nombre']); ?></td>
                        <td><?php echo number_format($contrato['monto'], 2); ?></td>
                        <td><?php echo htmlspecialchars($contrato['tasa_interes']); ?></td>
                        <td><?php echo htmlspecialchars($contrato['fecha_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($contrato['diapago']); ?></td>
                        <td><?php echo htmlspecialchars($contrato['numcuotas']); ?></td>
                        <td><?php echo htmlspecialchars($contrato['estado']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info ver-detalles" data-id="<?php echo $contrato['idcontrato']; ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                            <?php if ($contrato['estado'] === 'ACT'): ?>
                                <button class="btn btn-sm btn-primary ver-cronograma" data-id="<?php echo $contrato['idcontrato']; ?>">
                                    <i class="bi bi-calendar"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php
        return ob_get_clean();
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(['success' => false, 'message' => 'Método no permitido']);
        }

        $data = [
            'idbeneficiario' => $_POST['idbeneficiario'] ?? '',
            'monto' => $_POST['monto'] ?? '',
            'tasa_interes' => $_POST['tasa_interes'] ?? '',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'diapago' => $_POST['diapago'] ?? '',
            'numcuotas' => $_POST['numcuotas'] ?? ''
        ];

        if (empty($data['idbeneficiario']) || empty($data['monto']) || empty($data['tasa_interes']) || 
            empty($data['fecha_inicio']) || empty($data['diapago']) || empty($data['numcuotas'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        }

        if ($this->model->tieneContratoVigente($data['idbeneficiario'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'El beneficiario ya tiene un contrato vigente']);
        }

        $success = $this->model->crear($data);
        if ($success) {
            return json_encode(['success' => true, 'message' => 'Contrato registrado con éxito']);
        } else {
            http_response_code(500);
            return json_encode(['success' => false, 'message' => 'Error al registrar el contrato']);
        }
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(['success' => false, 'message' => 'Método no permitido']);
        }

        $idcontrato = $_POST['idcontrato'] ?? '';
        if (empty($idcontrato)) {
            http_response_code(400);
            return json_encode(['success' => false, 'message' => 'ID del contrato es obligatorio']);
        }

        $success = $this->model->eliminar($idcontrato);
        if ($success) {
            return json_encode(['success' => true, 'message' => 'Contrato eliminado con éxito']);
        } else {
            http_response_code(500);
            return json_encode(['success' => false, 'message' => 'Error al eliminar el contrato']);
        }
    }

    public function cronograma() {
        $monto = floatval($_GET['monto'] ?? 0);
        $tasaMensual = floatval($_GET['tasa'] ?? 0) / 100; // Tratar la tasa como mensual (5 o 0.05 -> 0.05)
        $fechaInicio = new DateTime($_GET['fecha'] ?? 'now');
        $numeroCuotas = intval($_GET['cuotas'] ?? 0);

        if ($monto <= 0 || $tasaMensual < 0 || $numeroCuotas <= 0) {
            http_response_code(400);
            return '<tr><td colspan="8" class="text-center">Datos inválidos para generar el cronograma.</td></tr>';
        }

        $cuota = round(Pago($tasaMensual, $numeroCuotas, $monto), 2);

        ob_start();
        // Fila inicial
        echo "<tr><td>0</td><td></td><td></td><td></td><td></td><td>" . number_format($monto, 2, '.', ',') . "</td><td>-</td><td>-</td></tr>";

        $saldoCapital = $monto;
        $sumatoriaInteres = 0;

        for ($i = 1; $i <= $numeroCuotas; $i++) {
            $interesPeriodo = $saldoCapital * $tasaMensual;
            $abonoCapital = $cuota - $interesPeriodo;
            $saldoCapitalTemp = $saldoCapital - $abonoCapital;

            $sumatoriaInteres += $interesPeriodo;

            $interesPeriodoPrint = number_format($interesPeriodo, 2, '.', ',');
            $abonoCapitalPrint = number_format($abonoCapital, 2, '.', ',');
            $cuotaPrint = number_format($cuota, 2, '.', ',');
            $saldoCapitalTempPrint = number_format($saldoCapitalTemp, 2, '.', ',');

            if ($i == $numeroCuotas) {
                $saldoCapitalTempPrint = '0.00';
            }

            // Simulación de estado de pago basado en la fecha actual (10:56 AM -05, 30-05-2025)
            $estado = "Pendiente";
            $medioPago = "-";
            $penalidad = "0.00";
            $fechaPagoEsperada = clone $fechaInicio;
            $fechaActual = new DateTime('2025-05-30 10:56:00 -05:00');

            $fechaPagoEsperada->modify("+$i months");
            $fechaVencimiento = clone $fechaPagoEsperada;
            $fechaVencimiento->modify('+5 days'); // 5 días de gracia

            if ($fechaActual > $fechaVencimiento) {
                $estado = "Pagado";
                $medioPago = ($i % 2 == 0) ? "dep" : "efc"; // Alternar medio de pago
                $diasAtraso = $fechaActual->diff($fechaPagoEsperada)->days;
                $mesesAtraso = floor(($diasAtraso - 5) / 30); // Meses completos de atraso
                $penalidad = number_format($cuota * 0.05 * $mesesAtraso, 2, '.', ','); // 5% por mes completo
            }

            // Caso específico: Si pagaste el 17-05-2025 para la cuota del 10-03-2025
            if ($i == 1) {
                $fechaPagoReal = new DateTime('2025-05-17 00:00:00 -05:00');
                $diasAtrasoReal = $fechaPagoReal->diff($fechaPagoEsperada)->days;
                $mesesAtrasoReal = floor(($diasAtrasoReal - 5) / 30);
                $penalidad = number_format($cuota * 0.05 * $mesesAtrasoReal, 2, '.', ','); // 5% por mes completo
                $medioPago = "efc"; // Ejemplo de medio de pago
            }

            echo "<tr><td>$i</td><td>" . $fechaInicio->format('d-m-Y') . "</td><td>$interesPeriodoPrint</td><td>$abonoCapitalPrint</td><td>$cuotaPrint</td><td>$saldoCapitalTempPrint</td><td>$penalidad</td><td>$medioPago</td></tr>";

            $fechaInicio->modify('+1 month');
            $saldoCapital = $saldoCapitalTemp;
        }

        $sumatoriaInteresPrint = number_format($sumatoriaInteres, 2, '.', ',');
        echo "<tr><td></td><td></td><td>$sumatoriaInteresPrint</td><td></td><td></td><td></td><td></td><td></td></tr>";
        return ob_get_clean();
    }
}

// Manejo de acciones
$action = $_GET['action'] ?? '';
$controller = new ContratoController();

switch ($action) {
    case 'listar':
        header('Content-Type: text/html');
        echo $controller->listar();
        break;
    case 'crear':
        header('Content-Type: application/json');
        echo $controller->crear();
        break;
    case 'eliminar':
        header('Content-Type: application/x-www-form-urlencoded');
        echo $controller->eliminar();
        break;
    case 'cronograma':
        header('Content-Type: text/html');
        echo $controller->cronograma();
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
?>