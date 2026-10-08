<?php
$titulo = 'Registro';
if (usuario_actual()) {
    redirect('/panel');
}
$errores = [];
$v = ['dni' => '', 'nombre' => '', 'apellido' => '', 'email' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok()) {
        $errores = ['La sesión expiró. Volvé a intentar.'];
    } else {
        $errores = registrar_usuario($_POST);
        if (!$errores) {
            flash('Cuenta creada. Ya podés iniciar sesión con tu DNI.');
            redirect('/login');
        }
    }
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page narrow">
  <h1>Crear cuenta</h1>
  <?php foreach ($errores as $e): ?><div class="alert error"><?= h($e) ?></div><?php endforeach; ?>
  <form method="post" class="form" novalidate>
    <?= csrf_field() ?>
    <label>DNI (sin puntos)<input name="dni" inputmode="numeric" maxlength="10" value="<?= h($v['dni']) ?>" required></label>
    <div class="row2">
      <label>Nombre<input name="nombre" value="<?= h($v['nombre']) ?>" required maxlength="80"></label>
      <label>Apellido<input name="apellido" value="<?= h($v['apellido']) ?>" required maxlength="80"></label>
    </div>
    <label>Email<input type="email" name="email" value="<?= h($v['email']) ?>" required></label>
    <label>Contraseña (mínimo 8 caracteres)<input type="password" name="clave" required autocomplete="new-password"></label>
    <label>Repetir contraseña<input type="password" name="clave2" required autocomplete="new-password"></label>
    <button class="btn">Crear cuenta</button>
  </form>
  <p>¿Ya tenés cuenta? <a href="/login">Ingresá</a> · <a href="/privacidad">Cómo cuidamos tus datos</a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
