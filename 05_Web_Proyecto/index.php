<?php
// Inicio: presentación de la marca, las yerbas (leídas de la base de datos) y cómo comprar.
require 'inc/config.php';
require 'inc/conexion.php';
$titulo = 'Inicio';
$activa = 'Inicio';

// Trae de la tabla productos solo las yerbas activas.
$productos = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id')->fetchAll();

require 'inc/cabecera.php';
?>
<section class="portada">
  <div>
    <p class="etiqueta">Yerba mate argentina</p>
    <h1>El mate de todos los días, con sabor a hogar</h1>
    <p>Elegimos la hoja, la estacionamos y la molemos con cuidado. Comprala en cualquier almacén o pedila por mayor para tu comercio, con entrega coordinada.</p>
    <p>
      <a class="boton dorado" href="productos.php">Ver productos</a>
      <a class="boton claro" href="<?= whatsapp('Hola ASHAB, quiero hacer una consulta.') ?>">Hablar por WhatsApp</a>
    </p>
  </div>
  <img src="img/logo.svg" alt="Logo de ASHAB" class="portada-logo">
</section>

<section>
  <h2>Nuestras yerbas</h2>
  <div class="grilla">
    <?php foreach ($productos as $p): ?>
      <article class="tarjeta producto">
        <h3><?= h($p['nombre']) ?></h3>
        <p><?= h($p['descripcion']) ?></p>
        <p class="precio">Al público: <?= pesos($p['precio_publico']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <h2>Cómo comprar</h2>
  <div class="grilla">
    <article class="tarjeta"><span class="numero">1</span><h3>Elegí tu yerba</h3><p>Mirá las yerbas y los precios en Productos.</p></article>
    <article class="tarjeta"><span class="numero">2</span><h3>Tocá WhatsApp</h3><p>Se abre el chat con el mensaje ya escrito.</p></article>
    <article class="tarjeta"><span class="numero">3</span><h3>Acordamos todo</h3><p>Cantidad, pago y entrega se arreglan directo con nosotros.</p></article>
  </div>
</section>
<?php require 'inc/pie.php'; ?>
