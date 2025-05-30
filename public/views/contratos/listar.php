<h1 class="mb-4">Lista de Contratos</h1>

<?php if (isset($_GET['idbeneficiario'])): ?>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearContratoModal">Crear Contrato para Beneficiario</button>
    </div>

    <!-- Modal para Crear Contrato -->
    <div class="modal fade" id="crearContratoModal" tabindex="-1" aria-labelledby="crearContratoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearContratoModalLabel">Crear Nuevo Contrato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearContrato" class="needs-validation" novalidate>
                        <input type="hidden" id="idbeneficiario" name="idbeneficiario" value="<?php echo htmlspecialchars($_GET['idbeneficiario']); ?>">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
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
                <p><strong>Interés (%):</strong> <span id="modalTasaInteres"></span></p>
                <p><strong>Fecha de Inicio:</strong> <span id="modalFechaInicio"></span></p>
                <p><strong>Día de Pago:</strong> <span id="modalDiaPago"></span></p>
                <p><strong>Número de Cuotas:</strong> <span id="modalNumCuotas"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="eliminarContrato">
                    <i class="bi bi-trash"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Cronograma -->
<div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pagoModalLabel">Cronograma de Pagos - Contrato #<span id="pagoModalContratoId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <table class="table table-sm table-bordered" id="tabla-pagos">
                        <thead class="table-dark">
                            <tr>
                                <th>Item</th>
                                <th>Fecha de Pago</th>
                                <th>Interés</th>
                                <th>Abono Capital</th>
                                <th>Valor Cuota</th>
                                <th>Saldo Capital</th>
                                <th>Penalidad</th>
                                <th>Medio de Pago</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
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
            <th>Interés (%)</th>
            <th>Fecha de Inicio</th>
            <th>Día de Pago</th>
            <th>Número de Cuotas</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tabla-contratos">
        <!-- Los datos se cargan dinámicamente por JavaScript -->
    </tbody>
</table>