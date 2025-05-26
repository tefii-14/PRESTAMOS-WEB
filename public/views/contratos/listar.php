<?php
// Conexión a la base de datos y modelo
require_once __DIR__ . '/../../../app/config/Database.php';
require_once __DIR__ . '/../../../app/models/Contrato.php';

$database = new Database();
$db = $database->getConnection();
$model = new Contrato();
$contratos = $model->listar();

// Verificar si hay un idbeneficiario en la URL para crear un contrato
$idbeneficiario = isset($_GET['idbeneficiario']) ? $_GET['idbeneficiario'] : null;
?>

<h1 class="mb-4">Lista de Contratos</h1>

<?php if ($idbeneficiario): ?>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" id="crearContratoBtn">Crear Contrato para Beneficiario</button>
    </div>

    <div id="contratoForm" style="display: none;">
        <form id="formCrearContrato" class="needs-validation" novalidate>
            <input type="hidden" id="idbeneficiario" name="idbeneficiario" value="<?php echo htmlspecialchars($idbeneficiario); ?>">
            <div class="mb-3">
                <label for="monto" class="form-label">Monto (S/)</label>
                <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                <div class="invalid-feedback">Por favor, ingresa el monto.</div>
            </div>
            <div class="mb-3">
                <label for="tasa_interes" class="form-label">Tasa de Interés (%)</label>
                <input type="number" step="0.01" class="form-control" id="tasa_interes" name="tasa_interes" required>
                <div class="invalid-feedback">Por favor, ingresa la tasa de interés.</div>
            </div>
            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                <div class="invalid-feedback">Por favor, selecciona la fecha de inicio.</div>
            </div>
            <div class="mb-3">
                <label for="diapago" class="form-label">Día de Pago</label>
                <input type="number" min="1" max="31" class="form-control" id="diapago" name="diapago" required>
                <div class="invalid-feedback">Por favor, ingresa el día de pago (1-31).</div>
            </div>
            <div class="mb-3">
                <label for="numcuotas" class="form-label">Número de Cuotas</label>
                <input type="number" min="1" class="form-control" id="numcuotas" name="numcuotas" required>
                <div class="invalid-feedback">Por favor, ingresa el número de cuotas.</div>
            </div>
            <button type="submit" class="btn btn-primary">Confirmar Registro</button>
            <button type="button" class="btn btn-secondary" id="cancelarContrato">Cancelar</button>
        </form>
    </div>
<?php endif; ?>

<!-- Modal para Ver Detalles -->
<div class="modal fade" id="detalleContratoModal" tabindex="-1" aria-labelledby="detalleContratoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleContratoModalLabel">Detalles del Contrato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong># Contrato:</strong> <span id="modalIdContrato"></span></p>
                <p><strong>Beneficiario:</strong> <span id="modalBeneficiario"></span></p>
                <p><strong>Monto (S/):</strong> <span id="modalMonto"></span></p>
                <p><strong>Tasa de Interés (%):</strong> <span id="modalTasaInteres"></span></p>
                <p><strong>Fecha de Inicio:</strong> <span id="modalFechaInicio"></span></p>
                <p><strong>Día de Pago:</strong> <span id="modalDiaPago"></span></p>
                <p><strong>Número de Cuotas:</strong> <span id="modalNumCuotas"></span></p>
                <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="eliminarContrato">
                    <i class="bi bi-trash"></i> <!-- Ícono de eliminar -->
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Beneficiario</th>
            <th>Monto (S/)</th>
            <th>Tasa de Interés (%)</th>
            <th>Fecha de Inicio</th>
            <th>Día de Pago</th>
            <th>Número de Cuotas</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tabla-contratos">
        <?php if (empty($contratos)): ?>
            <tr>
                <td colspan="9" class="text-center">No hay contratos registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($contratos as $contrato): ?>
                <tr>
                    <td><?php echo $contrato['idcontrato']; ?></td>
                    <td><?php echo $contrato['beneficiario_nombre']; ?></td>
                    <td><?php echo number_format($contrato['monto'], 2); ?></td>
                    <td><?php echo $contrato['tasa_interes']; ?></td>
                    <td><?php echo $contrato['fecha_inicio']; ?></td>
                    <td><?php echo $contrato['diapago']; ?></td>
                    <td><?php echo $contrato['numcuotas']; ?></td>
                    <td><?php echo $contrato['estado']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-info ver-detalles" data-id="<?php echo $contrato['idcontrato']; ?>">
                            <i class="bi bi-eye"></i> <!-- Ícono de ver detalles -->
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>