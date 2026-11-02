<?php
// Cabecera común. Antes de incluirla cada página define $titulo y $raiz ('' o '../').
$yo = usuario_actual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($titulo) ?> · Patitas Felices</title>
  <link rel="stylesheet" href="<?= $raiz ?>css/estilos.css">
  <link rel="icon" href="<?= $raiz ?>img/logo.svg">
</head>
<body>
<header class="barra">
  <a class="marca" href="<?= $raiz ?>index.php">
    <img src="<?= $raiz ?>img/logo.svg" alt="">
    <span>Patitas Felices <small>Veterinaria</small></span>
  </a>
  <nav>
    <a href="<?= $raiz ?>index.php#servicios">Servicios</a>
    <a href="<?= $raiz ?>turnos.php">Turnos</a>
    <a href="<?= $raiz ?>contacto.php">Contacto</a>
    <?php if ($yo): ?>
      <?php if ($yo['rol'] === 'admin'): ?><a href="<?= $raiz ?>admin/index.php" class="boton">Administración</a><?php endif; ?>
      <a href="<?= $raiz ?>salir.php">Salir</a>
    <?php else: ?>
      <a href="<?= $raiz ?>ingresar.php" class="boton">Ingresar</a>
    <?php endif; ?>
  </nav>
</header>
<main>
