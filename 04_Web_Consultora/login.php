<?php
// Inicio de sesión del socio: compara el email y la contraseña con la cuenta de inc/datos.php.
require_once 'inc/datos.php';
$titulo = 'Ingresar';
$activa = 'Ingresar';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // password_verify compara la contraseña escrita con el hash guardado
    if ($email === ADMIN_EMAIL && password_verify($contrasena, ADMIN_HASH)) {
        session_regenerate_id(true);   // nuevo id de sesión
        $_SESSION['admin'] = true;
        header('Location: panel.php');
        exit;
    }
    $error = 'Email o contraseña incorrectos.';
}
require 'inc/cabecera.php';
?>
<section class="tarjeta angosta">
  <h1>Ingresar</h1>
  <p>Acceso del socio al panel de Berasoft.</p>
  <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
  <form method="post">
    <label>Email <input type="email" name="email" required></label>
    <label>Contraseña <input type="password" name="contrasena" required></label>
    <button class="boton">Ingresar</button>
  </form>
</section>
<?php require 'inc/pie.php'; ?>
