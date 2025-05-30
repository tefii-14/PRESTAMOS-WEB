<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">

</head>
<body>
  
  <h3>Cronograma de pagos</h3>
  <hr>

  <div class="container">
    <table class="table table-sm table-bordered" id="tabla-pagos">
      <thead>
        <tr>
          <th>Item</th>
          <th>Fecha pago</th>
          <th>Interés</th>
          <th>Abono capital</th>
          <th>Valor cuota</th>
          <th>Saldo capital</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
  
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      
      async function obtenerCronograma(){
        const params = new URLSearchParams()
        params.append("operation", "creaCronograma")
        params.append("fechaRecibida", "2025-10-10")
        params.append("monto", 3000)
        params.append("tasa", 5)
        params.append("numeroCuotas", 12)

        const response = await fetch(`../../../app/controllers/pago.c.php?${params}`, {method: 'GET'})
        return await response.text() //text() => HTML | json()
      }

      async function renderCronograma(){
        const tabla = document.querySelector("#tabla-pagos tbody")
        tabla.innerHTML = await obtenerCronograma()
      }

      renderCronograma()
    })
  </script>

</body>
</html>