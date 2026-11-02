<?php
// Contacto (boceto 5): el formulario arma el pedido de presupuesto y lo abre en WhatsApp.
// No necesita base de datos: la atención al cliente se hace por WhatsApp.
$titulo = 'Contacto';
$activa = 'Contacto';
require_once 'inc/datos.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $negocio  = trim($_POST['negocio'] ?? '');
    $servicio = $_POST['servicio'] ?? '';
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre === '')                        $error = 'Completá tu nombre.';
    elseif (!isset(SERVICIOS[$servicio]))      $error = 'Elegí un servicio.';
    elseif (strlen($mensaje) < 10)             $error = 'Contanos un poco más (mínimo 10 letras).';
    else {
        $texto = "Hola Berasoft, soy $nombre" . ($negocio ? " de $negocio" : '') . ".\n"
               . "Quiero presupuesto de: $servicio.\n$mensaje";
        header('Location: https://wa.me/' . WHATSAPP . '?text=' . rawurlencode($texto));
        exit;
    }
}
$elegido = $_POST['servicio'] ?? $_GET['servicio'] ?? '';
require 'inc/cabecera.php';
?>
<h1>Contanos tu proyecto</h1>
<p>Completá el formulario y se abre WhatsApp con tu pedido listo para enviar.</p>

<div class="dos-columnas">
  <section class="tarjeta">
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
      <label>Nombre <input name="nombre" required value="<?= h($_POST['nombre'] ?? '') ?>"></label>
      <label>Negocio (opcional) <input name="negocio" value="<?= h($_POST['negocio'] ?? '') ?>"></label>
      <label>Servicio
        <select name="servicio">
          <?php foreach (SERVICIOS as $nombre => $x): ?>
            <option <?= $nombre === $elegido ? 'selected' : '' ?>><?= h($nombre) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>¿Qué necesitás? <textarea name="mensaje" rows="5" required minlength="10"><?= h($_POST['mensaje'] ?? '') ?></textarea></label>
      <button class="boton">Enviar por WhatsApp</button>
    </form>
  </section>
  <section>
    <article class="tarjeta">
      <h2>WhatsApp</h2>
      <p>Respondemos en el día.</p>
      <a class="boton" href="https://wa.me/<?= WHATSAPP ?>">Abrir chat</a>
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
