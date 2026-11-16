<?php
// Productos: el catálogo completo leído de MySQL. Cada yerba tiene su botón de WhatsApp.
require 'inc/config.php';
require 'inc/conexion.php';
$titulo = 'Productos';
$activa = 'Productos';
$yo = usuario_actual(); // null si no inició sesión

$productos = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id')->fetchAll();

require 'inc/cabecera.php';
?>
<h1 style="margin-top:1em">Productos</h1>
<p>Precio al público por paquete. Tocá el botón de la yerba que te interesa y mandanos el mensaje.</p>
<?php if (!$yo): ?><p class="aviso"><a href="login.php">Ingresá</a> o <a href="registro.php">creá una cuenta</a> para ver el precio por mayor.</p><?php endif; ?>
<div class="grilla">
<?php foreach ($productos as $p): ?>
  <?php
    $mensaje = 'Hola ASHAB, quiero pedir ' . $p['nombre'] . ' (' . $p['presentacion'] . ').';
    if ($yo) $mensaje .= ' Soy ' . $yo['nombre'] . ' (' . $yo['email'] . ').';   // con sesión, el mensaje lleva sus datos
  ?>
  <article class="tarjeta producto">
    <h3><?= h($p['nombre']) ?></h3>
    <p><?= h($p['descripcion']) ?></p>
    <p><small><?= h($p['presentacion']) ?></small></p>
    <p class="precio">Al público: <?= pesos($p['precio_publico']) ?></p>
    <?php if ($yo): ?><p class="precio">Por mayor: <?= pesos($p['precio_mayorista_kg']) ?> / kg</p><?php endif; ?>
    <a class="boton chico" href="<?= whatsapp($mensaje) ?>">Pedir por WhatsApp</a>
  </article>
<?php endforeach; ?>
</div>
<?php require 'inc/pie.php'; ?>
