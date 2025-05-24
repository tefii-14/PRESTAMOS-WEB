<?php
$titulo = $titulo ?? 'Prestamos Web';
$seccion = $seccion ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/estilos.css">
</head>
<body>  

<nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="/public/index.php?controller=pago&action=dashboard">💸 Prestamos Web</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?= $seccion === 'beneficiarios' ? 'active' : '' ?>" href="/public/dashboard.php?vista=beneficiarios">Beneficiarios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $seccion === 'contratos' ? 'active' : '' ?>" href="#">Contratos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $seccion === 'pagos' ? 'active' : '' ?>" href="/public/index.php?controller=pago&action=dashboard">Pagos</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">