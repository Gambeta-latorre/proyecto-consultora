<?php
$tituloClave = 'reg_title';
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
        $errores = ['e_csrf'];
    } else {
        $errores = registrar_usuario($_POST);
        if (!$errores) {
            flash('reg_ok');
            redirect('/login');
        }
    }
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page narrow">
  <h1><?= h(t('reg_title')) ?></h1>
  <p class="lead"><?= h(t('reg_note')) ?></p>
  <?php foreach ($errores as $e): ?><div class="alert error"><?= h(t($e)) ?></div><?php endforeach; ?>
  <form method="post" class="form" novalidate>
    <?= csrf_field() ?>
    <label><?= h(t('f_dni')) ?><input name="dni" inputmode="numeric" maxlength="10" value="<?= h($v['dni']) ?>" required dir="ltr"></label>
    <div class="row2">
      <label><?= h(t('f_first')) ?><input name="nombre" value="<?= h($v['nombre']) ?>" required maxlength="80"></label>
      <label><?= h(t('f_last')) ?><input name="apellido" value="<?= h($v['apellido']) ?>" required maxlength="80"></label>
    </div>
    <label><?= h(t('f_email')) ?><input type="email" name="email" value="<?= h($v['email']) ?>" required dir="ltr"></label>
    <label><?= h(t('f_pass_min')) ?><input type="password" name="clave" required autocomplete="new-password" dir="ltr"></label>
    <label><?= h(t('f_pass2')) ?><input type="password" name="clave2" required autocomplete="new-password" dir="ltr"></label>
    <button class="btn"><?= h(t('reg_btn')) ?></button>
  </form>
  <p><?= h(t('reg_have')) ?> <a href="/login"><?= h(t('login_btn')) ?></a> · <a href="/privacidad"><?= h(t('foot_privacy')) ?></a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
