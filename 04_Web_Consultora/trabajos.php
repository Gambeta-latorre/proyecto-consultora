<?php
// Trabajos (boceto 3): caso completo de ASHAB.
$titulo = 'Trabajos';
$activa = 'Trabajos';
require 'inc/cabecera.php';
?>
<h1>Trabajos</h1>

<section class="destacado">
  <img src="img/ashab_pedido.jpg" alt="Pedido en ASHAB">
  <div>
    <h2>ASHAB · yerba mate</h2>
    <p><small>Alimentos · 2026</small></p>
    <h3>El problema</h3>
    <p>No tenía un lugar donde mostrar sus yerbas y precios: cada cliente preguntaba lo mismo por WhatsApp.</p>
    <h3>Lo que hicimos</h3>
    <ul>
      <li>Sitio con las yerbas y sus precios al público y por mayor.</li>
      <li>Los productos se guardan en una base de datos MySQL: la empresa cambia un precio y se actualiza la página.</li>
      <li>Cada yerba tiene un botón para pedirla por WhatsApp con el mensaje ya escrito.</li>
      <li>Diseño adaptado al celular y botón fijo de atención al cliente.</li>
    </ul>
    <a class="boton" href="<?= URL_ASHAB ?>">Visitar el sitio</a>
  </div>
</section>

<div class="grilla">
  <article class="tarjeta numero-grande"><strong>1 toque</strong><p>para enviar el pedido por WhatsApp</p></article>
  <article class="tarjeta numero-grande"><strong>4</strong><p>yerbas en el catálogo</p></article>
  <article class="tarjeta numero-grande"><strong>100 %</strong><p>adaptado al celular</p></article>
</div>
<?php require 'inc/pie.php'; ?>
