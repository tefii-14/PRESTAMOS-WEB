<?php
// Definimos el título de la página y la sección activa para el header
$titulo = "Dashboard";
$seccion = "";
// Incluimos el archivo de cabecera (header) desde el directorio público
include_once __DIR__ . '/../../public/partials/header.php';
?>

<h1 class="text-center">Dashboard</h1>
<p class="text-center">Bienvenido al sistema de préstamos 💸</p>

<div id="contenido-dinamico" class="mt-4 container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
// Función para cargar contenido dinámico desde una URL específica
function cargarContenido(url) {
    fetch('/prestamos-web/public/views/' + url)
        .then(res => {
            // Verificamos si la respuesta del servidor es exitosa
            if (!res.ok) {
                throw new Error(`Error al cargar: ${res.status} - ${res.statusText}`);
            }
            return res.text();
        })
        .then(html => {
            // Insertamos el contenido HTML en el contenedor dinámico
            const contenidoDinamico = document.getElementById('contenido-dinamico');
            contenidoDinamico.innerHTML = html;
            // Limpiamos la clase 'was-validated' de los formularios para reiniciar validaciones
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => form.classList.remove('was-validated'));
            // Agregamos eventos a los elementos cargados dinámicamente
            agregarEventos();
        })
        .catch(error => {
            // Mostramos un mensaje de error si falla la carga
            console.error('Error en fetch:', error);
            document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-danger">No se pudo cargar el contenido. Error: ' + error.message + '</div>';
        });
}

// Función para agregar eventos a enlaces y formularios dinámicos
function agregarEventos() {
    // Manejo de enlaces con clase 'cargar-dinamico' para cargar contenido sin recargar la página
    document.querySelectorAll('.cargar-dinamico').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            // Extraemos la URL del enlace y cargamos el contenido
            const url = this.getAttribute('href').replace('/prestamos-web/public/views/', '');
            cargarContenido(url);
        });
    });

    // Validación de formularios con Bootstrap
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                // Si el formulario no es válido, prevenimos el envío y mostramos errores
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Manejo del formulario para crear beneficiarios con AJAX
    const formCrearBeneficiario = document.getElementById('formCrearBeneficiario');
    if (formCrearBeneficiario) {
        formCrearBeneficiario.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validamos el formulario antes de enviarlo
            if (!this.checkValidity()) {
                alert('Por favor, completa todos los campos requeridos.');
                return;
            }

            // Confirmación antes de registrar el beneficiario
            if (!confirm('¿Estás seguro de registrar este beneficiario?')) {
                return;
            }

            // Enviamos los datos del formulario mediante AJAX
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
                    // Cerramos el modal de creación de beneficiario
                    const modalElement = document.getElementById('crearBeneficiarioModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        } else {
                            // Limpieza manual del modal si no hay instancia
                            modalElement.classList.remove('show');
                            modalElement.style.display = 'none';
                            document.body.classList.remove('modal-open');
                        }
                        const backdrops = document.getElementsByClassName('modal-backdrop');
                        while (backdrops.length) {
                            backdrops[0].parentNode.removeChild(backdrops[0]);
                        }
                    }

                    // Reiniciamos el formulario
                    formCrearBeneficiario.reset();
                    formCrearBeneficiario.classList.remove('was-validated');

                    // Preguntamos si se desea crear un contrato
                    if (confirm('Beneficiario registrado con éxito. ¿Desea crear un contrato para este beneficiario?')) {
                        const idbeneficiario = data.idbeneficiario;
                        cargarContenido('contratos/listar.php?idbeneficiario=' + idbeneficiario);
                    } else {
                        // Actualizamos la lista de beneficiarios
                        fetch('/prestamos-web/public/views/beneficiarios/listar.php')
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('contenido-dinamico').innerHTML = html;
                                agregarEventos();
                            })
                            .catch(error => {
                                alert('Error al actualizar la lista: ' + error.message);
                            });
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Error al registrar el beneficiario: ' + error.message);
            });
        });
    }

    // Manejo del formulario para crear contratos con AJAX
    const formCrearContrato = document.getElementById('formCrearContrato');
    if (formCrearContrato) {
        formCrearContrato.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validamos el formulario
            if (!this.checkValidity()) {
                alert('Por favor, completa todos los campos requeridos.');
                return;
            }

            // Confirmación antes de registrar el contrato
            if (!confirm('¿Estás seguro de registrar este contrato?')) {
                return;
            }

            // Depuramos los datos enviados
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Enviamos los datos del contrato mediante AJAX
            fetch('/prestamos-web/public/views/contratos/crear.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Error al registrar: ' + res.statusText);
                return res.json();
            })
            .then(data => {
                console.log('Respuesta del servidor:', data);
                if (data.success) {
                    // Cerramos el modal de creación de contrato
                    const modalElement = document.getElementById('crearContratoModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                        modalElement.classList.remove('show');
                        modalElement.setAttribute('aria-hidden', 'true');
                        modalElement.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        const backdrops = document.getElementsByClassName('modal-backdrop');
                        while (backdrops.length > 0) {
                            backdrops[0].parentNode.removeChild(backdrops[0]);
                        }
                    }

                    // Reiniciamos el formulario
                    formCrearContrato.reset();
                    formCrearContrato.classList.remove('was-validated');

                    // Recargamos la lista de contratos
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

    // Manejo de botones "Ver Detalles" para contratos
    document.querySelectorAll('.ver-detalles').forEach(button => {
        button.addEventListener('click', function () {
            const idcontrato = this.getAttribute('data-id');
            const row = this.closest('tr');
            const cells = row.getElementsByTagName('td');

            // Rellenamos el modal con los datos del contrato
            document.getElementById('modalIdContrato').textContent = cells[0].textContent;
            document.getElementById('modalBeneficiario').textContent = cells[1].textContent;
            document.getElementById('modalMonto').textContent = cells[2].textContent;
            document.getElementById('modalTasaInteres').textContent = cells[3].textContent;
            document.getElementById('modalFechaInicio').textContent = cells[4].textContent;
            document.getElementById('modalDiaPago').textContent = cells[5].textContent;
            document.getElementById('modalNumCuotas').textContent = cells[6].textContent;

            // Consultamos el estado del contrato
            fetch('/prestamos-web/public/views/contratos/crear.php?action=getEstado&idcontrato=' + encodeURIComponent(idcontrato))
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('modalEstado').textContent = data.estado;
                    } else {
                        document.getElementById('modalEstado').textContent = 'Desconocido';
                    }
                    const modal = new bootstrap.Modal(document.getElementById('detalleContratoModal'));
                    modal.show();
                })
                .catch(error => {
                    document.getElementById('modalEstado').textContent = 'Error';
                    const modal = new bootstrap.Modal(document.getElementById('detalleContratoModal'));
                    modal.show();
                    console.error('Error al obtener el estado:', error);
                });

            // Manejo del botón de eliminación en el modal
            const eliminarBtn = document.getElementById('eliminarContrato');
            eliminarBtn.onclick = function () {
                if (confirm('¿Estás seguro de eliminar este contrato?')) {
                    fetch('/prestamos-web/public/views/contratos/crear.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'idcontrato=' + encodeURIComponent(idcontrato) + '&action=eliminar'
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Error al eliminar');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('detalleContratoModal'));
                            if (modal) {
                                modal.hide();
                            }
                            // Recargamos la lista de contratos
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

    // Manejo de botones "Registrar Pago"
    document.querySelectorAll('.registrar-pago').forEach(button => {
        button.addEventListener('click', function () {
            const idcontrato = this.getAttribute('data-id');
            document.getElementById('pagoIdContrato').value = idcontrato;

            // Mostramos el modal para registrar el pago
            const modal = new bootstrap.Modal(document.getElementById('pagoModal'));
            modal.show();
        });
    });

    // Manejo del formulario para registrar pagos
    const formRegistrarPago = document.getElementById('formRegistrarPago');
    if (formRegistrarPago) {
        formRegistrarPago.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validamos el formulario
            if (!this.checkValidity()) {
                alert('Por favor, completa todos los campos requeridos.');
                return;
            }

            // Confirmación antes de registrar el pago
            if (!confirm('¿Estás seguro de registrar este pago?')) {
                return;
            }

            // Enviamos los datos del pago mediante AJAX
            const formData = new FormData(this);
            fetch('/prestamos-web/public/views/pagos/crear.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Error al registrar el pago');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('pagoModal'));
                    modal.hide();
                    // Recargamos la lista de contratos
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
                alert('Error al registrar el pago: ' + error.message);
            });
        });
    }
}

// Manejo de los enlaces de la barra de navegación
const linkBeneficiarios = document.querySelector('a.nav-link[href*="beneficiarios"]');
if (linkBeneficiarios) {
    linkBeneficiarios.addEventListener('click', function (e) {
        e.preventDefault();
        // Cargamos la lista de beneficiarios
        cargarContenido('beneficiarios/listar.php');
    });
}

const linkContratos = document.querySelector('a.nav-link[href*="contratos"]');
if (linkContratos) {
    linkContratos.addEventListener('click', function (e) {
        e.preventDefault();
        // Cargamos la lista de contratos
        cargarContenido('contratos/listar.php');
    });
}

const linkPagos = document.querySelector('a.nav-link[href*="pagos"]');
if (linkPagos) {
    linkPagos.addEventListener('click', function (e) {
        e.preventDefault();
        // Mostramos un mensaje indicando que la funcionalidad de pagos no está implementada
        document.getElementById('contenido-dinamico').innerHTML = '<div class="alert alert-info">Funcionalidad de pagos aún no implementada.</div>';
    });
}
</script>

<?php
// Incluimos el archivo de pie de página (footer)
include_once __DIR__ . '/../../public/partials/footer.php';
?>