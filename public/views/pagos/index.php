<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
</head>

<body>
    <h3>CRONOGRAMA DE PAGOS</h3>
    <hr>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Fecha</th>
                    <th>Interes</th>
                    <th>Abono capital</th>
                    <th>Valor cuota</th>
                    <th>Saldo capital</th>
                </tr>
            </thead>
            <tbody id="tabla-pagos"></tbody>
        </table>
    </div>

    <SCript>
        document.addEventListener("DOMContentLoaded", async () => {
            async function obtenerCronograma() {
                const params = new URLSearchParams()
                params.append("operation", "creaCronograma")
                params.append("fechaRecibida", "2025-10-10")
                params.append("monto", 3000)
                params.append("tasa", 5)
                params.append("numeroCuaotas", 12)

                const response = await fetch(`../../../app/controllers/pago.c.php?${params}`, { method: 'GET' })
                return await response.text()
            }

            async function renderCronograma() {
                const tabla = document.querySelector("#tabla-pagos tbody")
                tabla.innerHTML = await obtenerCronograma()
            }

            renderCronograma()
        })
    </SCript>
</body>

</html>