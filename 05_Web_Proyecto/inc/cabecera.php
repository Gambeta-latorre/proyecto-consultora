<?php
// Cabecera común: antes de incluirla, cada página define $titulo y $activa.
$menu = ['index.php' => 'Inicio', 'productos.php' => 'Productos', 'contacto.php' => 'Contacto'];
$yo = usuario_actual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($titulo) ?> · ASHAB</title>
  <meta name="description" content="ASHAB: yerba mate argentina. Venta por menor y por mayor, pedidos por WhatsApp.">
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="icon" href="img/logo.svg">
</head>
<body>
<header>
  <div class="barra">
    <a class="marca" href="index.php"><img src="img/logo.svg" alt=""><span><b>ASHAB</b><small>Yerba mate</small></span></a>
    <!-- Menú para celular sin JavaScript: el checkbox abre y cierra la lista -->
    <input type="checkbox" id="abrir-menu">
    <label for="abrir-menu" class="hamburguesa" aria-label="Abrir menú">☰</label>
    <nav>
      <?php foreach ($menu as $archivo => $nombre): ?>
        <a href="<?= $archivo ?>" class="<?= $nombre === $activa ? 'activa' : '' ?>"><?= $nombre ?></a>
      <?php endforeach; ?>
      <?php if ($yo): ?>
        <span class="hola">Hola, <?= h($yo['nombre']) ?></span>
        <a href="salir.php">Salir</a>
      <?php else: ?>
        <a href="login.php" class="<?= $activa === 'Ingresar' ? 'activa' : '' ?>">Ingresar</a>
      <?php endif; ?>
      <a href="<?= whatsapp('Hola ASHAB, quiero hacer una consulta.') ?>" class="boton chico">Pedir por WhatsApp</a>
    </nav>
  </div>
</header>
<main>
