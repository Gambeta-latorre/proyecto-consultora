<?php
$tituloClave = 'nav_contact';
$errores = [];
$v = ['nombre' => '', 'email' => '', 'telefono' => '', 'mercado' => '', 'mensaje' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok()) { $errores[] = 'e_csrf'; }
    if ($v['nombre'] === '' || mb_strlen($v['nombre']) > 100) { $errores[] = 'e_name'; }
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) { $errores[] = 'e_email'; }
    if (mb_strlen($v['mensaje']) < 10 || mb_strlen($v['mensaje']) > 2000) { $errores[] = 'e_msg'; }
    if (!$errores) {
        // el mercado de interés y el idioma del sitio se guardan al comienzo del mensaje (sin cambiar la base de datos)
        $texto = '[' . lang() . ']' . ($v['mercado'] !== '' ? ' [' . mb_substr($v['mercado'], 0, 80) . ']' : '') . "\n" . $v['mensaje'];
        db()->prepare('INSERT INTO consultas (nombre, email, telefono, mensaje, fecha) VALUES (?,?,?,?,?)')
            ->execute([$v['nombre'], $v['email'], mb_substr($v['telefono'], 0, 30), $texto, now()]);
        flash('c_thanks');
        redirect('/contacto');
    }
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1><?= h(t('contact_title')) ?></h1>
  <p class="lead"><?= h(t('contact_lead')) ?></p>
  <div class="grid2 contact">
    <article class="card wa-card">
      <h3><?= h(t('wa_title')) ?></h3>
      <p><?= h(t('wa_intl')) ?></p>
      <p class="bignum"><bdi dir="ltr" id="wanum"><?= h(wa_display()) ?></bdi></p>
      <p><a class="btn" target="_blank" rel="noopener" href="<?= h(whatsapp_url()) ?>"><?= h(t('wa_open')) ?></a>
         <button type="button" class="btn ghost-dark" id="copywa" data-ok="<?= h(t('wa_copied')) ?>"><?= h(t('wa_copy')) ?></button></p>
      <p class="qr"><img src="/assets/img/qr_whatsapp.png" width="160" height="160" alt="QR WhatsApp"><br><small><?= h(t('wa_qr')) ?></small></p>
      <p><b><?= h(t('wa_hours_t')) ?>:</b> <?= h(t('wa_hours')) ?></p>
    </article>
    <div>
      <?php foreach ($errores as $e): ?><div class="alert error"><?= h(t($e)) ?></div><?php endforeach; ?>
      <form method="post" class="form" novalidate>
        <?= csrf_field() ?>
        <label><?= h(t('f_name')) ?><input name="nombre" value="<?= h($v['nombre']) ?>" required maxlength="100"></label>
        <label><?= h(t('f_email')) ?><input type="email" name="email" value="<?= h($v['email']) ?>" required dir="ltr"></label>
        <label><?= h(t('f_phone')) ?><input name="telefono" value="<?= h($v['telefono']) ?>" maxlength="30" dir="ltr"></label>
        <label><?= h(t('f_market')) ?><input name="mercado" value="<?= h($v['mercado']) ?>" maxlength="80"></label>
        <label><?= h(t('f_msg')) ?><textarea name="mensaje" rows="5" required><?= h($v['mensaje']) ?></textarea></label>
        <button class="btn"><?= h(t('f_send')) ?></button>
      </form>
    </div>
  </div>
</section>
<script>
document.getElementById('copywa').addEventListener('click', function () {
  var b = this, txt = document.getElementById('wanum').textContent.trim(), old = b.textContent;
  (navigator.clipboard ? navigator.clipboard.writeText(txt) : Promise.reject()).then(function () {
    b.textContent = b.dataset.ok; setTimeout(function () { b.textContent = old; }, 2000);
  }).catch(function () { window.prompt('WhatsApp', txt); });
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
