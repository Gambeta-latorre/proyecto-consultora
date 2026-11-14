<?php
// Contacto (boceto 5): la atención es por WhatsApp. Cada botón abre el chat con el pedido ya escrito.
// No hay formulario ni base de datos.
$titulo = 'Contacto';
$activa = 'Contacto';
require 'inc/cabecera.php';
?>
<h1>Contanos tu proyecto</h1>
<p>Tocá el servicio que te interesa y se abre WhatsApp con el mensaje listo para enviar.</p>

<div class="dos-columnas">
  <section class="tarjeta">
    <h2>¿Qué necesitás?</h2>
    <?php foreach (SERVICIOS as $nombre => $x): ?>
      <p><a class="boton" href="<?= whatsapp("Hola Berasoft, quiero presupuesto de: $nombre.") ?>"><?= h($nombre) ?></a></p>
    <?php endforeach; ?>
  </section>
  <section>
    <article class="tarjeta">
      <h2>WhatsApp</h2>
      <p>Respondemos en el día.</p>
      <a class="boton" href="<?= whatsapp('Hola Berasoft, quiero hacer una consulta.') ?>">Abrir chat</a>
    </article>
    <article class="tarjeta">
      <h3>Email</h3>
      <p><a href="mailto:<?= EMAIL ?>"><?= EMAIL ?></a></p>
      <h3>Horario</h3>
      <p>Lunes a viernes de 9 a 18 h</p>
    </article>
  </section>
</div>
<?php require 'inc/pie.php'; ?>
