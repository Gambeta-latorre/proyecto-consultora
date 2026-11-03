<?php
// Contacto: WhatsApp (atención al cliente) y un formulario cuyos mensajes se guardan en la base.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Contacto';
$activa = 'Contacto';
$error = '';
$v = ['nombre' => '', 'email' => '', 'whatsapp' => '', 'mensaje' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $campo => $_) $v[$campo] = trim($_POST[$campo] ?? '');
    if ($v['nombre'] === '' || mb_strlen($v['nombre']) > 80)               $error = 'Escribí tu nombre.';
    elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL))               $error = 'El email no es válido.';
    elseif (!preg_match('/^[0-9+() -]{8,25}$/', $v['whatsapp']))           $error = 'El WhatsApp no es válido.';
    elseif ($v['mensaje'] === '' || mb_strlen($v['mensaje']) > 1000)       $error = 'Escribí tu mensaje (hasta 1000 letras).';
    else {
        $db->prepare('INSERT INTO consultas (nombre, email, whatsapp, mensaje, fecha) VALUES (?, ?, ?, ?, ?)')
           ->execute([$v['nombre'], $v['email'], $v['whatsapp'], $v['mensaje'], date('Y-m-d H:i:s')]);
        aviso('¡Gracias! Recibimos tu mensaje y te respondemos por WhatsApp.');
        ir('contacto.php');
    }
}
require 'inc/cabecera.php';
?>
<div class="pagina">
  <h1>Contacto</h1>
  <div class="dos">
    <div>
      <p>La atención al cliente es por <strong>WhatsApp</strong>, de lunes a viernes de 9 a 18 h.</p>
      <p><a class="boton dorado" href="https://wa.me/<?= WHATSAPP ?>">Escribir al +54 9 11 3348-6017</a></p>
      <p class="nota">Para pedidos mayoristas ingresá con tu cuenta en <a href="productos.php">Productos</a>.</p>
    </div>
    <div>
      <?php if ($error): ?><div class="aviso error"><?= h($error) ?></div><?php endif; ?>
      <form method="post" class="formulario">
        <?= csrf_campo() ?>
        <label>Nombre<input name="nombre" value="<?= h($v['nombre']) ?>" maxlength="80" required></label>
        <label>Email<input type="email" name="email" value="<?= h($v['email']) ?>" maxlength="120" required></label>
        <label>WhatsApp<input name="whatsapp" value="<?= h($v['whatsapp']) ?>" maxlength="25" placeholder="+54 9 11 ..." required></label>
        <label>Mensaje<textarea name="mensaje" rows="4" maxlength="1000" required><?= h($v['mensaje']) ?></textarea></label>
        <button>Enviar</button>
      </form>
    </div>
  </div>
</div>
<?php require 'inc/pie.php'; ?>
