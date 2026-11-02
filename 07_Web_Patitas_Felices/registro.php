<?php
// Registro de clientes nuevos. Al terminar entra directo a sacar turno.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Crear cuenta';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $clave    = $_POST['clave'] ?? '';

    if ($nombre === '')                                      $error = 'Completá tu nombre.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))      $error = 'El email no es válido.';
    elseif (strlen(preg_replace('/\D/', '', $whatsapp)) < 8) $error = 'Ingresá tu número de WhatsApp.';
    elseif (strlen($clave) < 8)                              $error = 'La contraseña debe tener al menos 8 caracteres.';
    elseif ($clave !== ($_POST['clave2'] ?? ''))             $error = 'Las contraseñas no coinciden.';
    else {
        $st = $db->prepare('SELECT id FROM usuarios WHERE email = ?');
        $st->execute([$email]);
        if ($st->fetch()) {
            $error = 'Ya existe una cuenta con ese email.';
        } else {
            $db->prepare('INSERT INTO usuarios (email, clave_hash, nombre, whatsapp) VALUES (?, ?, ?, ?)')
               ->execute([$email, password_hash($clave, PASSWORD_DEFAULT), $nombre, $whatsapp]);
            intentar_ingreso($email, $clave); // entra directo después de registrarse
            ir('turnos.php');
        }
    }
}
require 'inc/cabecera.php';
?>
<section class="formulario">
  <h1>Crear cuenta</h1>
  <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_campo() ?>
    <label>Nombre y apellido <input name="nombre" required value="<?= h($_POST['nombre'] ?? '') ?>"></label>
    <label>Email <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>"></label>
    <label>WhatsApp <input name="whatsapp" required placeholder="11 1234-5678" value="<?= h($_POST['whatsapp'] ?? '') ?>"></label>
    <label>Contraseña (mínimo 8) <input type="password" name="clave" required minlength="8"></label>
    <label>Repetir contraseña <input type="password" name="clave2" required></label>
    <button class="boton">Crear cuenta</button>
  </form>
  <p>¿Ya tenés cuenta? <a href="ingresar.php">Ingresá</a></p>
</section>
<?php require 'inc/pie.php'; ?>
