<?php
// Servicios (boceto 2): un bloque por servicio con precio de referencia.
$titulo = 'Servicios';
$activa = 'Servicios';
require 'inc/cabecera.php';
?>
<h1>Servicios</h1>
<p>Precios de referencia en pesos. El presupuesto final depende de cada negocio.</p>

<?php foreach (SERVICIOS as $nombre => [$detalle, $precio]): ?>
  <article class="tarjeta servicio">
    <div>
      <h2><?= h($nombre) ?></h2>
      <p><?= h($detalle) ?></p>
    </div>
    <div class="precio-caja">
      <p class="precio"><?= h($precio) ?></p>
      <!-- El botón lleva al formulario con este servicio ya elegido -->
      <a class="boton" href="contacto.php?servicio=<?= urlencode($nombre) ?>">Consultar</a>
    </div>
  </article>
<?php endforeach; ?>
<?php require 'inc/pie.php'; ?>
