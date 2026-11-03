<?php
// Inicio: presentación de la marca, las yerbas (leídas de la base) y cómo comprar por mayor.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Inicio';
$activa = 'Inicio';
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
      <a class="boton claro" href="https://wa.me/<?= WHATSAPP ?>">Hablar por WhatsApp</a>
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
  <h2>Comprá por mayor</h2>
  <div class="grilla">
    <article class="tarjeta"><span class="numero">1</span><h3>Ingresá con Google</h3><p>Con tu cuenta de Gmail, sin crear contraseñas.</p></article>
    <article class="tarjeta"><span class="numero">2</span><h3>Completá tu comercio</h3><p>Razón social, CUIT y WhatsApp. Aprobamos tu cuenta.</p></article>
    <article class="tarjeta"><span class="numero">3</span><h3>Pedí tu presupuesto</h3><p>Elegís la yerba y los kilos. Te enviamos la proforma.</p></article>
    <article class="tarjeta"><span class="numero">4</span><h3>Seña del 50 %</h3><p>Con la seña acreditada producimos y embarcamos tu pedido.</p></article>
  </div>
</section>

<section class="caso">
  <h2>Cómo es la seña</h2>
  <p>En los pedidos mayoristas se paga el <strong>50 % al confirmar</strong> y el resto antes de la entrega. Así reservamos la producción y vos tenés el precio asegurado durante la validez de la proforma (<?= PROFORMA_DIAS ?> días). Pedidos de <?= MIN_KG ?> a <?= number_format(MAX_KG, 0, ',', '.') ?> kg.</p>
</section>
<?php require 'inc/pie.php'; ?>
