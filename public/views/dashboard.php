<?php
$titulo = "Dashboard";
$seccion = "";
include_once __DIR__ . '/../../public/partials/header.php';
?>

<h1 class="text-center">Dashboard</h1>
<p class="text-center">Bienvenido al sistema de préstamos 💸</p>

<div id="contenido-dinamico" class="mt-4 container"></div>

<script>
// Función para cargar contenido dinámico
function cargarContenido(url) {
    fetch('/prestamos-web/public/views/' + url)
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar');
            return res.text();
        })
        .then(html => {
            document.getElementById('contenido-dinamico').innerHTML = html;
            // Volver a agregar eventos después de cargar el contenido
            agregarEventos();
        })
        .catch(error => {
            document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-danger">No se pudo cargar el contenido.</div>';
        });
}

// Función para agregar eventos a los enlaces internos (como "Registrar")
function agregarEventos() {
    // Para los enlaces con clase "cargar-dinamico"
    document.querySelectorAll('.cargar-dinamico').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            cargarContenido(url);
        });
    });
}

// Capturar clics en los enlaces de la navbar
document.querySelector('a.nav-link[href*="beneficiarios"]').addEventListener('click', function (e) {
    e.preventDefault();
    cargarContenido('beneficiarios/listar.php');
});

document.querySelector('a.nav-link[href*="contratos"]').addEventListener('click', function (e) {
    e.preventDefault();
    cargarContenido('contratos/listar.php');
});

document.querySelector('a.nav-link[href*="pagos"]').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-info">Funcionalidad de pagos aún no implementada.</div>';
});
</script>

<?php include_once __DIR__ . '/../../public/partials/footer.php'; ?>