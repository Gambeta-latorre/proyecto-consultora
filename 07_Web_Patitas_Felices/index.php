<?php
// Página de inicio: presentación, servicios con precio (leídos de la base) y cómo sacar turno.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Inicio';
$servicios = $db->query('SELECT * FROM servicios ORDER BY id')->fetchAll();
require 'inc/cabecera.php';
?>
<section class="portada">
  <div>
    <p class="etiqueta">Veterinaria en Berazategui</p>
    <h1>Cuidamos a tu mascota como a la nuestra</h1>
    <p>Consultas, vacunas, baño y corte para perros y gatos. Sacá turno online en un minuto, sin llamar por teléfono.</p>
    <p>
      <a class="boton" href="turnos.php">Sacar turno</a>
      <a class="boton claro" href="https://wa.me/<?= WHATSAPP ?>">Consultar por WhatsApp</a>
    </p>
  </div>
  <img src="img/logo.svg" alt="Logo de Patitas Felices" class="portada-logo">
</section>

<section id="servicios">
  <h2>Servicios y precios</h2>
  <div class="grilla">
    <?php foreach ($servicios as $s): ?>
      <article class="tarjeta">
        <h3><?= h($s['nombre']) ?></h3>
        <p><?= h($s['detalle']) ?></p>
        <p class="precio"><?= pesos($s['precio']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <h2>Cómo sacar turno</h2>
  <div class="grilla">
    <article class="tarjeta"><span class="numero">1</span><h3>Creá tu cuenta</h3><p>Con tu email y tu WhatsApp.</p></article>
    <article class="tarjeta"><span class="numero">2</span><h3>Elegí el día</h3><p>De lunes a sábado, hasta 30 días adelante.</p></article>
    <article class="tarjeta"><span class="numero">3</span><h3>Elegí el horario</h3><p>Solo te mostramos los que están libres.</p></article>
    <article class="tarjeta"><span class="numero">4</span><h3>¡Listo!</h3><p>Si no podés venir, lo cancelás desde la web.</p></article>
  </div>
</section>

<section class="caso">
  <h2>Dónde estamos</h2>
  <p>Av. Mitre 1234, Berazategui · Lunes a viernes de 9 a 18 h · Sábados de 9 a 13 h</p>
</section>
<?php require 'inc/pie.php'; ?>
