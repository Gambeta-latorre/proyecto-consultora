<?php
// Nosotros (boceto 4): la empresa, misión, visión, valores, equipo y ubicación.
$titulo = 'Nosotros';
$activa = 'Nosotros';
require 'inc/cabecera.php';
?>
<section class="portada">
  <div>
    <h1>Somos Berasoft</h1>
    <p>Una <strong>Sociedad de Responsabilidad Limitada</strong> de Berazategui que desarrolla sitios web para comercios y profesionales de la zona sur.</p>
    <p>Elegimos ser S.R.L. porque somos una empresa chica: los socios responden solo con lo que aportaron y los trámites son más simples que en una S.A.</p>
  </div>
  <img src="img/logo.svg" alt="Escudo de Bera Soft" class="escudo">
</section>

<div class="grilla">
  <article class="tarjeta"><h2>Misión</h2><p>Que cualquier negocio del barrio tenga una web útil, simple y a un precio justo.</p></article>
  <article class="tarjeta"><h2>Visión</h2><p>Ser la empresa de desarrollo web de referencia en Berazategui y la zona sur.</p></article>
  <article class="tarjeta"><h2>Valores</h2><p>Claridad, compromiso con los plazos y atención cercana.</p></article>
</div>

<h2>El equipo</h2>
<div class="grilla">
  <article class="tarjeta equipo">
    <span class="avatar">JC</span>
    <h3>Juan Cruz Ceresani</h3>
    <p>Socio · Desarrollo y diseño</p>
  </article>
</div>

<section class="destacado">
  <div>
    <h2>Dónde estamos</h2>
    <p>Berazategui, Provincia de Buenos Aires.</p>
    <p>Atendemos de lunes a viernes de 9 a 18 h. También vamos a tu negocio.</p>
  </div>
</section>
<?php require 'inc/pie.php'; ?>
