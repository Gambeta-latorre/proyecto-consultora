<?php
// Inicio de sesión con email y contraseña (clientes y administrador).
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Ingresar';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = intentar_ingreso(trim($_POST['email'] ?? ''), $_POST['clave'] ?? '');
    if ($error === '') {
        ir(usuario_actual()['rol'] === 'admin' ? 'admin/index.php' : 'turnos.php');
    }
}
require 'inc/cabecera.php';
?>
<section class="formulario">
  <h1>Ingresar</h1>
  <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_campo() ?>
    <label>Email <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>"></label>
    <label>Contraseña <input type="password" name="clave" required></label>
    <button class="boton">Ingresar</button>
  </form>
  <p>¿No tenés cuenta? <a href="registro.php">Registrate</a></p>
</section>
<?php require 'inc/pie.php'; ?>
