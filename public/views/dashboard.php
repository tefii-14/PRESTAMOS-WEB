<?php
$titulo = "Dashboard";
$seccion = "";
include_once __DIR__ . '/../../public/partials/header.php';
?>

<h1 class="text-center">Dashboard</h1>
<p class="text-center">Bienvenido al sistema de préstamos 💸</p>

<div id="contenido-dinamico" class="mt-4 container"></div>

<!-- Incluir dependencias -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/prestamos-web/public/js/dashboard.js"></script>

<?php
include_once __DIR__ . '/../../public/partials/footer.php';
?>