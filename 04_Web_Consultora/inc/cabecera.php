<?php
// Cabecera común. Cada página define $titulo y $activa (el nombre del menú que se subraya).
require_once __DIR__ . '/datos.php';
$menu = ['index.php' => 'Inicio', 'servicios.php' => 'Servicios', 'trabajos.php' => 'Trabajos', 'nosotros.php' => 'Nosotros', 'contacto.php' => 'Contacto'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($titulo) ?> · Berasoft</title>
  <meta name="description" content="Berasoft S.R.L.: sitios web, turnos online y tiendas para comercios y profesionales.">
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="icon" href="img/icono.svg">
</head>
<body>
<header class="barra">
  <a class="marca" href="index.php"><img src="img/icono.svg" alt="Monograma BS"><span>bera<b>soft</b></span></a>
  <!-- Menú para celular sin JavaScript: el checkbox abre y cierra la lista -->
  <input type="checkbox" id="abrir-menu">
  <label for="abrir-menu" class="hamburguesa" aria-label="Abrir menú">☰</label>
  <nav>
    <?php foreach ($menu as $archivo => $nombre): ?>
      <a href="<?= $archivo ?>" class="<?= $nombre === $activa ? 'activa' : '' ?>"><?= $nombre ?></a>
    <?php endforeach; ?>
    <a href="contacto.php" class="boton">Pedir presupuesto</a>
  </nav>
</header>
<main>
