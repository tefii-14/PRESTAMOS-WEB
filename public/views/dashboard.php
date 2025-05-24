<?php
$titulo = "Dashboard";
$seccion = "";
include_once __DIR__ . '/../partials/header.php';
?>

<div id="contenido-dinamico" class="mt-4 container"></div>

<script>
// Capturar clic en "Beneficiarios"
document.querySelector('a.nav-link[href*="beneficiarios"]').addEventListener('click', function (e) {
  e.preventDefault();
  fetch('beneficiarios/listar.php')
    .then(res => {
      if (!res.ok) throw new Error('Error al cargar');
      return res.text();
    })
    .then(html => {
      document.getElementById('contenido-dinamico').innerHTML = html;
    })
    .catch(error => {
      document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-danger">No se pudo cargar.</div>';
    });
});
</script>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>
