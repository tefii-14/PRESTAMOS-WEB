# Prestamos Web - Documentación del Desarrollo

Este proyecto es mi aplicación web para gestionar préstamos, contratos y beneficiarios. La armé con PHP, MySQL y un poco de Bootstrap para que se vea decente. Aquí les dejo cómo la fui construyendo y en qué estoy hasta ahora.

## Progreso del Desarrollo

### Última Actualización: 25 de mayo de 2025, 11:50 PM -05

#### Pasos Realizados

1. **Configuración Inicial**
   - Me puse a configurar una base de datos con las tablas `beneficiarios`, `contratos` y `pagos` usando PHP y MySQL. Todo lo organicé en carpetas como `public/views`, `app/models`, `app/config`, y metí archivos como `header.php` y `footer.php` para que todo quede bien estructurado.
   - Agarré Bootstrap 5.3.2 y Bootstrap Icons para darle un buen estilo a la interfaz.

2. **Implementación de la Vista Dashboard**
   - Hice el `dashboard.php` como mi página principal, donde cargo contenido dinámico (beneficiarios, contratos, pagos) con JavaScript y `fetch`. Añadí eventos en la navbar para navegar entre secciones, ¡y quedó interactivo!

3. **Gestión de Beneficiarios**
   - Creé el `beneficiarios/listar.php` para mostrar una tabla con los beneficiarios. Agregué un modal en ese archivo para registrar nuevos con validación de formulario y lo conecté con AJAX en `dashboard.php` para que se guarde y actualice la lista.
   - ¡Funciona, y eso me dio un buen empujón!

4. **Gestión de Contratos**
   - Desarrollé el `contratos/listar.php` para mostrar los contratos en una tabla, filtrando solo los que tienen estado `'ACT'`. Puse un modal para crear contratos con validación y lo hice con AJAX para guardarlos en la base.
   - Añadí un modal de detalles (`detalleContratoModal`) donde puedo ver info, eliminar contratos (cambiándolos a `'FIN'`) y otro modal para registrar pagos (`pagoModal`), aunque este último aún lo estoy puliendo.

5. **Corrección de Problemas de Estilo**
   - Me di cuenta de que al registrar contratos o beneficiarios, las filas de la tabla se ponían grises por esa clase `was-validated` de Bootstrap. Le metí CSS en `listar.php` para forzar un fondo blanco (`#ffffff`) con selectores como `#tabla-contratos tr` y ajusté el JavaScript en `dashboard.php` para reiniciar los formularios y quitar esa clase después de registrar.
   - Todavía no lo controlo del todo, pero voy por buen camino.

6. **Gestión del Estado en el Modal de Detalles**
   - Implementé un `fetch` en `dashboard.php` para traer el estado del contrato desde `contratos/crear.php` y mostrarlo en el modal. Ajusté `crear.php` para que `'ACT'` se vea como "Activo" y `'FIN'` como "Finalizado", con "Desconocido" si no encuentra nada.

#### Problemas Pendientes
- **Fondo Gris Persistente**: A pesar de mi CSS, el fondo gris sigue apareciendo al registrar un contrato. Mañana voy a usar las herramientas de desarrollo (F12 > Elements) para cazar de dónde viene ese estilo.
- **Estado "Desconocido" en el Modal**: A veces el estado en el modal me sale "Desconocido" en lugar de "Activo" o "Finalizado". Creo que es un lío con la consulta SQL o el `fetch`, así que lo revisaré.
- **Comportamiento al Registrar**: Al registrar un contrato, me manda bien a la lista, pero el estilo no se aplica como quiero.


#### Notas Adicionales
- Todo lo estoy haciendo en mi local (`localhost/prestamos-web`).
- Uso PHP 7+ con MySQL y Bootstrap 5.3.2 para el frente.
- Los archivos están en la estructura que armé, y voy ajustando de a poco.

## Instrucciones de Ejecución
1. Copia los archivos a tu servidor local o clona de mi repositorio.
2. Configura la base de datos con las tablas (mira `app/config/Database.php`).
3. Prende el servidor (yo uso XAMPP) y entra a `http://localhost/prestamos-web/public/views/dashboard.php`.
4. Navega con la navbar entre beneficiarios, contratos y pagos.

## Contribuciones
- Voy a ir anotando todo aquí en el `README.txt` cada vez que avance.

¡Gracias por checar mi *Prestamos Web*! Mañana le meto más ganas.