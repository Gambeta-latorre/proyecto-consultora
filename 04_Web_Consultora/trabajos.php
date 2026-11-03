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
    <p>Los pedidos mayoristas se anotaban por WhatsApp, sin control de quién compraba ni del estado de cada pedido.</p>
    <h3>Lo que hicimos</h3>
    <ul>
      <li>Sitio con las yerbas y sus precios, que la empresa cambia sola.</li>
      <li>Ingreso con Google y aprobación de cada comercio antes de ver precios mayoristas.</li>
      <li>Pedidos con total automático, mensaje listo para WhatsApp o mail y seguimiento hasta que se embarca.</li>
      <li>Panel de administración con descarga a Excel y registro de ingresos.</li>
      <li>Atención al cliente por WhatsApp.</li>
    </ul>
    <a class="boton" href="<?= URL_ASHAB ?>">Visitar el sitio</a>
  </div>
</section>

<div class="grilla">
  <article class="tarjeta numero-grande"><strong>1 toque</strong><p>para enviar el pedido por WhatsApp</p></article>
  <article class="tarjeta numero-grande"><strong>0</strong><p>pedidos sin control</p></article>
  <article class="tarjeta numero-grande"><strong>100 %</strong><p>adaptado al celular</p></article>
</div>
<?php require 'inc/pie.php'; ?>
