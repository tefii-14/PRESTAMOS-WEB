<?php

require_once '../models/helpers.php';

if(isset($_GET['operation'])){

  switch ($_GET['operation']){
    case 'creaCronograma':

      $fechaRecibida = $_GET['fechaRecibida'];
      $fechaInicio = new DateTime($fechaRecibida);

      $monto = floatval($_GET['monto']);
      $tasa = floatval($_GET['tasa'])/100;
      $numeroCuotas = floatval($_GET['numeroCuotas']);

      $tasaMensual = pow((1 + $tasa), (1 / 12)) - 1;
      $cuota = round(Pago($tasa, $numeroCuotas, $monto),2);

      echo "
      <tr>
        <td>0</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{$monto}</td>
      </tr>
      ";
      $saldoCapital = $monto;
      $interesPeriodo = 0;
      $abonoCapital = 0;

      for ($i = 1; $i <= $numeroCuotas; $i++){
        $interesPeriodo = $saldoCapital * $tasa;
        $abonoCapital = $cuota  - $interesPeriodo;
        $saldoCapitalTemp = $saldoCapital - $abonoCapital;

        $interesPeriodoPrint = number_format($interesPeriodo, 2, '.', ',');
        $abonoCapitalPrint = number_format($abonoCapital, 2, '.', ',');
        $cuotaPrint = number_format($cuota, 2, '.', ',');
        $saldoCapitalTempPrint = number_format($saldoCapitalTemp, 2, '.', ',');

        if($i == $num)

        echo "
        <tr>
          <td>{$i}</td>
          <td>dd-mm-aaaa</td>
          <td>{$interesPeriodo}</td>
          <td>{$abonoCapital}</td>
          <td>{$cuota}</td>
          <td>{$saldoCapitalTemp}</td>
        </tr>
      ";

      }

      /* var_dump($cuota); */
      echo json_encode(
        [
          "cuota" => $cuota,
          "numeroCuotas" => $numeroCuotas,
          "monto" => $monto,
          "tasaMensual" => $tasaMensual
        ]
      );

      break;
  }

}

?>