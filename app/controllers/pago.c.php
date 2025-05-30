<?php

use const Dom\NO_MODIFICATION_ALLOWED_ERR;

//Clase->Método
require_once '../models/helpers.php';

if (isset($_GET['operation'])){

  switch ($_GET['operation']){
    case 'creaCronograma':

      $fechaRecibida = $_GET['fechaRecibida'];
      $fechaInicio = new DateTime($fechaRecibida);

      $monto = floatval($_GET['monto']);
      $tasa = floatval($_GET['tasa'])/100;
      $numeroCuotas = floatval($_GET['numeroCuotas']);

      $cuota = round(Pago($tasa, $numeroCuotas, $monto), 2);

      //Fila 0
      echo "
      <tr>
        <td>0</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{$monto}</td>
      </tr>";

      //Operaciones básicas
      $saldoCapital = $monto;
      $interesPeriodo = 0;
      $abonoCapital = 0;

      //Acumuladores
      $sumatoriaInteres = 0;

      for ($i = 1; $i <= $numeroCuotas; $i++){

        $interesPeriodo = $saldoCapital * $tasa; //i = 1
        $abonoCapital = $cuota - $interesPeriodo;
        $saldoCapitalTemp = $saldoCapital - $abonoCapital;

        $sumatoriaInteres += $interesPeriodo;

        //Variable a renderizar (mostrar HTML)
        $interesPeriodoPrint = number_format($interesPeriodo, 2, '.', ',');
        $abonoCapitalPrint = number_format($abonoCapital, 2, ".", ",");
        $cuotaPrint = number_format($cuota, 2, ".", ",");
        $saldoCapitalTempPrint = number_format($saldoCapitalTemp, 2, ".", ",");

        //Última iteración
        if ($i == $numeroCuotas){
          $saldoCapitalTempPrint = 0.00;
        }

        echo "
        <tr>
          <td>{$i}</td>
          <td>{$fechaInicio->format('d-m-Y')}</td>
          <td>{$interesPeriodoPrint}</td>
          <td>{$abonoCapitalPrint}</td>
          <td>{$cuotaPrint}</td>
          <td>{$saldoCapitalTempPrint}</td>
        </tr>";

        //Incremenar el mes
        $fechaInicio->modify('+1 month');
        $saldoCapital = $saldoCapitalTemp;
      }

      $sumatoriaInteresPrint = number_format($sumatoriaInteres, 2, ".", ",");

      //Fila resumen (TOTALES)
      echo "
      <tr>
        <td></td>
        <td></td>
        <td>{$sumatoriaInteresPrint}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>";

      break;
  }

}

?>