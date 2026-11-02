<?php
// Formulario de contacto. La consulta se guarda en la base y la veterinaria responde por WhatsApp.
$raiz = '';
require 'inc/funciones.php';
$titulo  = 'Contacto';
$yo      = usuario_actual();
$error   = '';
$enviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre === '')                                      $error = 'Completá tu nombre.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))      $error = 'El email no es válido.';
    elseif (strlen(preg_replace('/\D/', '', $whatsapp)) < 8) $error = 'Ingresá tu número de WhatsApp.';
    elseif (strlen($mensaje) < 10)                           $error = 'El mensaje es muy corto.';
    else {
        $db->prepare('INSERT INTO consultas (nombre, email, whatsapp, mensaje, fecha) VALUES (?, ?, ?, ?, ?)')
           ->execute([$nombre, $email, $whatsapp, $mensaje, date('Y-m-d H:i:s')]);
        $enviado = true;
    }
}
require 'inc/cabecera.php';
?>
<section class="formulario">
  <h1>Contacto</h1>
  <?php if ($enviado): ?>
    <p class="ok">¡Gracias! Te respondemos por WhatsApp lo antes posible.</p>
  <?php else: ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
    <form method="post">
      <?= csrf_campo() ?>
      <label>Nombre <input name="nombre" required value="<?= h($_POST['nombre'] ?? $yo['nombre'] ?? '') ?>"></label>
      <label>Email <input type="email" name="email" required value="<?= h($_POST['email'] ?? $yo['email'] ?? '') ?>"></label>
      <label>WhatsApp <input name="whatsapp" required placeholder="11 1234-5678" value="<?= h($_POST['whatsapp'] ?? $yo['whatsapp'] ?? '') ?>"></label>
      <label>Mensaje <textarea name="mensaje" rows="5" required minlength="10"><?= h($_POST['mensaje'] ?? '') ?></textarea></label>
      <button class="boton">Enviar consulta</button>
    </form>
  <?php endif; ?>
  <p>Para urgencias escribinos directo por <a href="https://wa.me/<?= WHATSAPP ?>">WhatsApp</a>.</p>
</section>
<?php require 'inc/pie.php'; ?>
