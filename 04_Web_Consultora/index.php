<?php
// Inicio (boceto 1): portada, servicios, último trabajo y cómo trabajamos.
$titulo = 'Inicio';
$activa = 'Inicio';
require 'inc/cabecera.php';
?>
<section class="portada">
  <div>
    <p class="etiqueta">Desarrollo web en Berazategui</p>
    <h1>Hacemos la web de <span>tu negocio</span></h1>
    <p>Sitios, turnos online y tiendas para comercios del barrio. Simples de usar, rápidos en el celular y con atención por WhatsApp.</p>
    <a class="boton" href="contacto.php">Pedir presupuesto</a>
    <a class="boton claro" href="trabajos.php">Ver trabajos</a>
  </div>
  <img src="img/patitas_turnos.jpg" alt="Turnos online de Patitas Felices, hecho por Berasoft" class="escudo">
</section>

<section>
  <h2>Servicios</h2>
  <div class="grilla">
    <?php foreach (SERVICIOS as $nombre => [$detalle, $precio]): ?>
      <article class="tarjeta">
        <h3><?= h($nombre) ?></h3>
        <p><?= h($detalle) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="destacado">
  <img src="img/patitas.jpg" alt="Sitio de Veterinaria Patitas Felices">
  <div>
    <h2>Último trabajo: Veterinaria Patitas Felices</h2>
    <p>Un sitio donde los clientes sacan turno para su perro, gato, oveja o vaca sin llamar por teléfono, y la recepción organiza el día desde un panel.</p>
    <a class="boton claro" href="trabajos.php">Ver el caso</a>
  </div>
</section>

<section>
  <h2>Cómo trabajamos</h2>
  <div class="grilla">
    <article class="tarjeta"><span class="numero">1</span><h3>Charlamos</h3><p>Nos contás qué necesita tu negocio.</p></article>
    <article class="tarjeta"><span class="numero">2</span><h3>Diseñamos</h3><p>Te mostramos bocetos antes de programar.</p></article>
    <article class="tarjeta"><span class="numero">3</span><h3>Programamos</h3><p>Con PHP y MySQL, probado en computadora y celular.</p></article>
    <article class="tarjeta"><span class="numero">4</span><h3>Publicamos</h3><p>Lo subimos a internet y te enseñamos a usarlo.</p></article>
  </div>
</section>
<?php require 'inc/pie.php'; ?>
