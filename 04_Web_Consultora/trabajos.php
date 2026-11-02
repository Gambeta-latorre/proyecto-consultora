<?php
// Trabajos (boceto 3): caso completo de Veterinaria Patitas Felices.
$titulo = 'Trabajos';
$activa = 'Trabajos';
require 'inc/cabecera.php';
?>
<h1>Trabajos</h1>

<section class="destacado">
  <img src="img/patitas_turnos.jpg" alt="Pantalla de turnos de Patitas Felices">
  <div>
    <h2>Veterinaria Patitas Felices</h2>
    <p><small>Berazategui · 2026</small></p>
    <h3>El problema</h3>
    <p>Los turnos se daban por teléfono, se superponían horarios y la recepción no sabía qué animal venía.</p>
    <h3>Lo que hicimos</h3>
    <ul>
      <li>Sitio con servicios y precios que la veterinaria cambia sola.</li>
      <li>Turnos online: el cliente elige el día y ve solo los horarios libres.</li>
      <li>Cada turno indica si es perro, gato, ovino o bovino.</li>
      <li>Panel para la recepción: turnos del día, clientes, consultas y descarga a Excel.</li>
      <li>Atención al cliente por WhatsApp.</li>
    </ul>
    <a class="boton" href="<?= URL_PATITAS ?>">Visitar el sitio</a>
  </div>
</section>

<div class="grilla">
  <article class="tarjeta numero-grande"><strong>24/7</strong><p>turnos online</p></article>
  <article class="tarjeta numero-grande"><strong>0</strong><p>turnos superpuestos</p></article>
  <article class="tarjeta numero-grande"><strong>100 %</strong><p>adaptado al celular</p></article>
</div>
<?php require 'inc/pie.php'; ?>
