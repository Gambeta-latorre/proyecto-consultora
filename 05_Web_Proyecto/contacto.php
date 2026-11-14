<?php
// Contacto: la atención al cliente es por WhatsApp. No hay formulario ni datos guardados.
require 'inc/config.php';
$titulo = 'Contacto';
$activa = 'Contacto';
require 'inc/cabecera.php';
?>
<div class="pagina">
  <h1>Contacto</h1>
  <div class="dos">
    <div class="tarjeta">
      <h2>WhatsApp</h2>
      <p>La atención al cliente es por WhatsApp, de lunes a viernes de 9 a 18 h.</p>
      <p><a class="boton dorado" href="<?= whatsapp('Hola ASHAB, quiero hacer una consulta.') ?>">Escribir al +54 9 11 3348-6017</a></p>
    </div>
    <div class="tarjeta">
      <h2>Compras por mayor</h2>
      <p>Para almacenes y distribuidoras. Elegí la yerba en <a href="productos.php">Productos</a> y pedila por WhatsApp: ahí acordamos cantidad, pago y entrega.</p>
      <p>Email: <a href="mailto:<?= EMAIL ?>"><?= EMAIL ?></a></p>
    </div>
  </div>
</div>
<?php require 'inc/pie.php'; ?>
