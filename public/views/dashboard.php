<?php
$titulo = "Dashboard";
$seccion = "";
include_once __DIR__ . '/../../public/partials/header.php';
?>

<h1 class="text-center">Dashboard</h1>
<p class="text-center">Bienvenido al sistema de préstamos 💸</p>

<div id="contenido-dinamico" class="mt-4 container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script> <!-- Añadido para Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Añadido para íconos -->
<script>
// Función para cargar contenido dinámico
function cargarContenido(url) {
    fetch('/prestamos-web/public/views/' + url)
        .then(res => {
            if (!res.ok) {
                throw new Error(`Error al cargar: ${res.status} - ${res.statusText}`);
            }
            return res.text();
        })
        .then(html => {
            document.getElementById('contenido-dinamico').innerHTML = html;
            // Volver a agregar eventos después de cargar el contenido
            agregarEventos();
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-danger">No se pudo cargar el contenido. Error: ' + error.message + '</div>';
        });
}

// Función para agregar eventos a los enlaces internos y formularios
function agregarEventos() {
    // Para los enlaces con clase "cargar-dinamico"
    document.querySelectorAll('.cargar-dinamico').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href').replace('/prestamos-web/public/views/', '');
            cargarContenido(url);
        });
    });

    // Validación del formulario
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Manejar el envío del formulario de beneficiarios con AJAX
    const formCrearBeneficiario = document.getElementById('formCrearBeneficiario');
    if (formCrearBeneficiario) {
        formCrearBeneficiario.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) return;

            if (!confirm('¿Estás seguro de registrar este beneficiario?')) {
                return;
            }

            const formData = new FormData(this);
            fetch('/prestamos-web/public/views/beneficiarios/crear.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Error al registrar');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    const modalElement = document.getElementById('crearBeneficiarioModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        } else {
                            modalElement.classList.remove('show');
                            modalElement.style.display = 'none';
                            document.body.classList.remove('modal-open');
                        }
                        const backdrops = document.getElementsByClassName('modal-backdrop');
                        while (backdrops.length) {
                            backdrops[0].parentNode.removeChild(backdrops[0]);
                        }
                    }

                    fetch('/prestamos-web/public/views/beneficiarios/listar.php')
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('contenido-dinamico').innerHTML = html;
                            agregarEventos();
                        })
                        .catch(error => {
                            alert('Error al actualizar la lista: ' + error.message);
                        });
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Error al registrar el beneficiario: ' + error.message);
            });
        });
    }

    // Manejar el envío del formulario de contratos con AJAX
    const formCrearContrato = document.getElementById('formCrearContrato');
    if (formCrearContrato) {
        formCrearContrato.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) return;

            if (!confirm('¿Estás seguro de registrar este contrato?')) {
                return;
            }

            const formData = new FormData(this);
            fetch('/prestamos-web/public/views/contratos/crear.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Error al registrar');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('contratoForm').style.display = 'none';
                    fetch('/prestamos-web/public/views/contratos/listar.php')
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('contenido-dinamico').innerHTML = html;
                            agregarEventos();
                        })
                        .catch(error => {
                            alert('Error al actualizar la lista: ' + error.message);
                        });
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Error al registrar el contrato: ' + error.message);
            });
        });
    }

    // Mostrar formulario de contrato al hacer clic
    const crearContratoBtn = document.getElementById('crearContratoBtn');
    if (crearContratoBtn) {
        crearContratoBtn.addEventListener('click', function () {
            document.getElementById('contratoForm').style.display = 'block';
        });
    }

    // Cancelar formulario de contrato
    const cancelarContrato = document.getElementById('cancelarContrato');
    if (cancelarContrato) {
        cancelarContrato.addEventListener('click', function () {
            document.getElementById('contratoForm').style.display = 'none';
            fetch('/prestamos-web/public/views/contratos/listar.php')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('contenido-dinamico').innerHTML = html;
                    agregarEventos();
                })
                .catch(error => {
                    alert('Error al actualizar la lista: ' + error.message);
                });
        });
    }

    // Manejar el clic en "Ver Detalles"
    document.querySelectorAll('.ver-detalles').forEach(button => {
        button.addEventListener('click', function () {
            const idcontrato = this.getAttribute('data-id');
            const row = this.closest('tr');
            const cells = row.getElementsByTagName('td');

            document.getElementById('modalIdContrato').textContent = cells[0].textContent;
            document.getElementById('modalBeneficiario').textContent = cells[1].textContent;
            document.getElementById('modalMonto').textContent = cells[2].textContent;
            document.getElementById('modalTasaInteres').textContent = cells[3].textContent;
            document.getElementById('modalFechaInicio').textContent = cells[4].textContent;
            document.getElementById('modalDiaPago').textContent = cells[5].textContent;
            document.getElementById('modalNumCuotas').textContent = cells[6].textContent;
            document.getElementById('modalEstado').textContent = cells[7].textContent;

            const modal = new bootstrap.Modal(document.getElementById('detalleContratoModal'));
            modal.show();

            // Manejar eliminación
            const eliminarBtn = document.getElementById('eliminarContrato');
            eliminarBtn.onclick = function () {
                if (confirm('¿Estás seguro de eliminar este contrato?')) {
                    fetch('/prestamos-web/public/views/contratos/eliminar.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'idcontrato=' + encodeURIComponent(idcontrato)
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Error al eliminar');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            fetch('/prestamos-web/public/views/contratos/listar.php')
                                .then(res => res.text())
                                .then(html => {
                                    document.getElementById('contenido-dinamico').innerHTML = html;
                                    agregarEventos();
                                })
                                .catch(error => {
                                    alert('Error al actualizar la lista: ' + error.message);
                                });
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error al eliminar el contrato: ' + error.message);
                    });
                }
            };
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