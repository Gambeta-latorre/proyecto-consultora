<?php
// Productos: el catálogo completo leído de MySQL. Cada yerba tiene su botón de WhatsApp.
require 'inc/config.php';
require 'inc/conexion.php';
$titulo = 'Productos';
$activa = 'Productos';

$productos = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id')->fetchAll();

require 'inc/cabecera.php';
?>
<h1 style="margin-top:1em">Productos</h1>
<p>Precio al público por paquete y precio por mayor por kilo. Tocá el botón de la yerba que te interesa y mandanos el mensaje.</p>
<div class="grilla">
<?php foreach ($productos as $p): ?>
  <?php $mensaje = 'Hola ASHAB, quiero pedir ' . $p['nombre'] . ' (' . $p['presentacion'] . ').'; ?>
  <article class="tarjeta producto">
    <h3><?= h($p['nombre']) ?></h3>
    <p><?= h($p['descripcion']) ?></p>
    <p><small><?= h($p['presentacion']) ?></small></p>
    <p class="precio">Al público: <?= pesos($p['precio_publico']) ?></p>
    <p class="precio">Por mayor: <?= pesos($p['precio_mayorista_kg']) ?> / kg</p>
    <a class="boton chico" href="<?= whatsapp($mensaje) ?>">Pedir por WhatsApp</a>
  </article>
<?php endforeach; ?>
</div>
<?php require 'inc/pie.php'; ?>
