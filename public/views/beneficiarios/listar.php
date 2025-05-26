<?php
// Conexión a la base de datos y modelo
require_once __DIR__ . '/../../../app/config/Database.php';
require_once __DIR__ . '/../../../app/models/Beneficiario.php';

$database = new Database();
$db = $database->getConnection();
$model = new Beneficiario();
$beneficiarios = $model->listar();
?>

<h1 class="mb-4">Lista de Beneficiarios</h1>

<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearBeneficiarioModal">
    + Registrar Nuevo
</button>

<!-- Modal para registrar beneficiario -->
<div class="modal fade" id="crearBeneficiarioModal" tabindex="-1" aria-labelledby="crearBeneficiarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearBeneficiarioModalLabel">Registrar Beneficiario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearBeneficiario" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        <div class="invalid-feedback">Por favor, ingresa los apellidos.</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                        <div class="invalid-feedback">Por favor, ingresa los nombres.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" maxlength="8" required>
                        <div class="invalid-feedback">Por favor, ingresa un DNI válido (8 dígitos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="9" required>
                        <div class="invalid-feedback">Por favor, ingresa un teléfono válido (9 dígitos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección (Opcional)</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar Registro</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tabla-beneficiarios">
        <?php if (empty($beneficiarios)): ?>
            <tr>
                <td colspan="7" class="text-center">No hay beneficiarios registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($beneficiarios as $beneficiario): ?>
                <tr>
                    <td><?php echo $beneficiario['idbeneficiario']; ?></td>
                    <td><?php echo $beneficiario['apellidos']; ?></td>
                    <td><?php echo $beneficiario['nombres']; ?></td>
                    <td><?php echo $beneficiario['dni']; ?></td>
                    <td><?php echo $beneficiario['telefono']; ?></td>
                    <td><?php echo $beneficiario['direccion']; ?></td>
                    <td>
                        <?php if ($beneficiario['contratos_vigentes'] == 0): ?>
                            <a href="/prestamos-web/public/views/contratos/listar.php?idbeneficiario=<?php echo $beneficiario['idbeneficiario']; ?>" class="btn btn-sm btn-success cargar-dinamico">Crear Contrato</a>
                        <?php else: ?>
                            <span class="text-muted">Contrato existente</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>