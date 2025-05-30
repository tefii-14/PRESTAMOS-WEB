document.addEventListener('DOMContentLoaded', () => {
    // Cargar la lista de contratos al iniciar
    cargarContenido('contratos/listar.php');

    // Manejar los enlaces de la barra de navegación
    setupNavLinks();
});

// Función para cargar contenido dinámico
async function cargarContenido(url) {
    try {
        const response = await fetch(`/prestamos-web/public/views/${url}`);
        if (!response.ok) throw new Error(`Error al cargar: ${response.statusText}`);
        const html = await response.text();
        const contenidoDinamico = document.getElementById('contenido-dinamico');
        contenidoDinamico.innerHTML = html;
        // Limpiar validaciones de formularios
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => form.classList.remove('was-validated'));
        // Agregar eventos a elementos dinámicos
        agregarEventos();

        // Cargar los contratos si estamos en la vista de contratos
        if (url.includes('contratos/listar.php')) {
            cargarContratos();
        }
    } catch (error) {
        console.error('Error al cargar contenido:', error);
        document.getElementById('contenido-dinamico').innerHTML = 
            `<div class="alert alert-danger">No se pudo cargar el contenido: ${error.message}</div>`;
    }
}

// Función para cargar los contratos dinámicamente
async function cargarContratos() {
    try {
        const response = await fetch('/prestamos-web/app/controllers/ContratoController.php?action=listar');
        const html = await response.text();
        const tablaContratos = document.getElementById('tabla-contratos');
        if (tablaContratos) {
            tablaContratos.innerHTML = html;
            agregarEventos(); // Reaplicar eventos después de cargar
        }
    } catch (error) {
        console.error('Error al cargar contratos:', error);
        const tablaContratos = document.getElementById('tabla-contratos');
        if (tablaContratos) {
            tablaContratos.innerHTML = '<tr><td colspan="9" class="text-center">Error al cargar los contratos.</td></tr>';
        }
    }
}

// Función para agregar eventos a elementos dinámicos
function agregarEventos() {
    // Enlaces dinámicos
    document.querySelectorAll('.cargar-dinamico').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const url = link.getAttribute('href').replace('/prestamos-web/public/views/', '');
            cargarContenido(url);
        });
    });

    // Validación de formularios
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                alert('Por favor, completa todos los campos requeridos.');
            }
        }, false);
    });

    // Formulario de creación de beneficiarios
    const formCrearBeneficiario = document.getElementById('formCrearBeneficiario');
    if (formCrearBeneficiario) {
        formCrearBeneficiario.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!formCrearBeneficiario.checkValidity()) return;

            if (!confirm('¿Estás seguro de registrar este beneficiario?')) return;

            const formData = new FormData(formCrearBeneficiario);
            try {
                const response = await fetch('/prestamos-web/public/views/beneficiarios/crear.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('crearBeneficiarioModal'));
                    modal.hide();
                    formCrearBeneficiario.reset();
                    formCrearBeneficiario.classList.remove('was-validated');

                    if (confirm('Beneficiario registrado con éxito. ¿Desea crear un contrato?')) {
                        cargarContenido(`contratos/listar.php?idbeneficiario=${data.idbeneficiario}`);
                    } else {
                        cargarContenido('beneficiarios/listar.php');
                    }
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error al registrar el beneficiario: ' + error.message);
            }
        });
    }

    // Formulario de creación de contratos
    const formCrearContrato = document.getElementById('formCrearContrato');
    if (formCrearContrato) {
        formCrearContrato.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!formCrearContrato.checkValidity()) return;

            if (!confirm('¿Estás seguro de registrar este contrato?')) return;

            const formData = new FormData(formCrearContrato);
            try {
                const response = await fetch('/prestamos-web/app/controllers/ContratoController.php?action=crear', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('crearContratoModal'));
                    modal.hide();
                    formCrearContrato.reset();
                    formCrearContrato.classList.remove('was-validated');
                    cargarContenido('contratos/listar.php');
                    cargarContratos(); // Recargar contratos después de crear
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error al registrar el contrato: ' + error.message);
            }
        });
    }

    // Botones "Ver Detalles"
    document.querySelectorAll('.ver-detalles').forEach(button => {
        button.addEventListener('click', (e) => {
            const row = button.closest('tr');
            const cells = row.getElementsByTagName('td');

            document.getElementById('modalIdContrato').textContent = cells[0].textContent;
            document.getElementById('modalBeneficiario').textContent = cells[1].textContent;
            document.getElementById('modalMonto').textContent = cells[2].textContent;
            document.getElementById('modalTasaInteres').textContent = cells[3].textContent;
            document.getElementById('modalFechaInicio').textContent = cells[4].textContent;
            document.getElementById('modalDiaPago').textContent = cells[5].textContent;
            document.getElementById('modalNumCuotas').textContent = cells[6].textContent;

            const modal = new bootstrap.Modal(document.getElementById('detalleContratoModal'));
            modal.show();
        });
    });

    // Botones "Ver Cronograma"
    document.querySelectorAll('.ver-cronograma').forEach(button => {
        button.addEventListener('click', async () => {
            const idcontrato = button.getAttribute('data-id');
            const row = button.closest('tr');
            const cells = row.getElementsByTagName('td');

            const monto = parseFloat(cells[2].textContent.replace(',', ''));
            const tasa = parseFloat(cells[3].textContent);
            const fechaInicio = cells[4].textContent;
            const numeroCuotas = parseInt(cells[6].textContent);

            document.getElementById('pagoModalContratoId').textContent = idcontrato;

            try {
                const response = await fetch(`/prestamos-web/app/controllers/ContratoController.php?action=cronograma&monto=${monto}&tasa=${tasa}&fecha=${fechaInicio}&cuotas=${numeroCuotas}`);
                const cronogramaHtml = await response.text();
                document.querySelector('#tabla-pagos tbody').innerHTML = cronogramaHtml;
                const modal = new bootstrap.Modal(document.getElementById('pagoModal'));
                modal.show();
            } catch (error) {
                alert('Error al cargar el cronograma: ' + error.message);
            }
        });
    });

    // Botón de eliminación
    const eliminarBtn = document.getElementById('eliminarContrato');
    if (eliminarBtn) {
        eliminarBtn.addEventListener('click', async () => {
            if (!confirm('¿Estás seguro de eliminar este contrato?')) return;

            const idcontrato = document.getElementById('modalIdContrato').textContent;
            try {
                const response = await fetch('/prestamos-web/app/controllers/ContratoController.php?action=eliminar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `idcontrato=${idcontrato}`
                });
                const data = await response.json();
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('detalleContratoModal'));
                    modal.hide();
                    cargarContenido('contratos/listar.php');
                    cargarContratos(); // Recargar contratos después de eliminar
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error al eliminar el contrato: ' + error.message);
            }
        });
    }
}

// Configurar los enlaces de navegación
function setupNavLinks() {
    const linkBeneficiarios = document.querySelector('a.nav-link[href*="beneficiarios"]');
    if (linkBeneficiarios) {
        linkBeneficiarios.addEventListener('click', (e) => {
            e.preventDefault();
            cargarContenido('beneficiarios/listar.php');
        });
    }

    const linkContratos = document.querySelector('a.nav-link[href*="contratos"]');
    if (linkContratos) {
        linkContratos.addEventListener('click', (e) => {
            e.preventDefault();
            cargarContenido('contratos/listar.php');
        });
    }

    const linkPagos = document.querySelector('a.nav-link[href*="pagos"]');
    if (linkPagos) {
        linkPagos.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('contenido-dinamico').innerHTML = 
                '<div class="alert alert-info">Funcionalidad de pagos aún no implementada.</div>';
        });
    }
}