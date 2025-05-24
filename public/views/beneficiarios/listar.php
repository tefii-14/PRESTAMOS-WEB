<?php
require_once __DIR__ . '/../../../app/models/Beneficiario.php';
$model = new Beneficiario();
$lista = $model->listar();
?>

<h2>Lista de Beneficiarios</h2>

<!-- Botón para abrir el modal -->
<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrear">+ Registrar Nuevo</button>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Dirección</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $b): ?>
            <tr>
                <td><?= $b['idbeneficiario'] ?></td>
                <td><?= $b['apellidos'] ?></td>
                <td><?= $b['nombres'] ?></td>
                <td><?= $b['dni'] ?></td>
                <td><?= $b['telefono'] ?></td>
                <td><?= $b['direccion'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal para crear beneficiario -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formCrear">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearLabel">Registrar Beneficiario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Apellidos:</label>
            <input name="apellidos" type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nombres:</label>
            <input name="nombres" type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">DNI:</label>
            <input name="dni" type="text" class="form-control" maxlength="8" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Teléfono:</label>
            <input name="telefono" type="text" class="form-control" maxlength="9" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Dirección:</label>
            <input name="direccion" type="text" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script AJAX para guardar -->
<script>
document.getElementById('formCrear').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('beneficiarios/crear.php', {
    method: 'POST',
    body: formData
  })
  .then(res => {
    if (res.ok) {
      // Cierra el modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrear'));
      modal.hide();
      // Recarga la página para ver la nueva fila
      location.reload();
    } else {
      alert('Error al guardar el beneficiario.');
    }
  })
  .catch(err => {
    console.error(err);
    alert('Error en la solicitud.');
  });
});
</script>
