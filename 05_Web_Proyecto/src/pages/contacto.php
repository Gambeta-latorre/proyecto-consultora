<?php
$tituloClave = 'nav_contact';
$err = '';
$v = ['nombre' => '', 'empresa' => '', 'email' => '', 'telefono' => '', 'mensaje' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok()) {
        $err = 'csrf';
    } elseif ($v['nombre'] === '' || !filter_var($v['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($v['mensaje']) < 10 || mb_strlen($v['mensaje']) > 2000
        || mb_strlen($v['nombre']) > 100 || mb_strlen($v['empresa']) > 120) {
        $err = 'c_bad';
    } else {
        db()->prepare('INSERT INTO consultas (nombre, empresa, email, telefono, mensaje, idioma, fecha) VALUES (?,?,?,?,?,?,?)')
            ->execute([$v['nombre'], $v['empresa'], $v['email'], mb_substr($v['telefono'], 0, 30), $v['mensaje'], lang(), now()]);
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
      <p>
        <a class="btn" target="_blank" rel="noopener" href="<?= h(whatsapp_url()) ?>"><?= h(t('wa_open')) ?></a>
        <button type="button" class="btn ghost-dark" id="copywa" data-ok="<?= h(t('wa_copied')) ?>"><?= h(t('wa_copy')) ?></button>
      </p>
      <p class="qr"><img src="/assets/img/qr_whatsapp.png" width="160" height="160" alt="QR WhatsApp"><br><small><?= h(t('wa_qr')) ?></small></p>
      <p><b><?= h(t('wa_hours_t')) ?>:</b> <?= h(t('wa_hours')) ?></p>
      <p class="note"><?= h(t('wa_tip')) ?></p>
    </article>
    <div>
      <?php if ($err): ?><div class="alert error"><?= h(t($err)) ?></div><?php endif; ?>
      <form method="post" class="form" novalidate>
        <?= csrf_field() ?>
        <label><?= h(t('c_name')) ?><input name="nombre" value="<?= h($v['nombre']) ?>" required maxlength="100"></label>
        <label><?= h(t('c_company')) ?><input name="empresa" value="<?= h($v['empresa']) ?>" maxlength="120"></label>
        <label><?= h(t('c_email')) ?><input type="email" name="email" value="<?= h($v['email']) ?>" required dir="ltr"></label>
        <label><?= h(t('c_phone')) ?><input name="telefono" value="<?= h($v['telefono']) ?>" maxlength="30" dir="ltr"></label>
        <label><?= h(t('c_msg')) ?><textarea name="mensaje" rows="5" required><?= h($v['mensaje']) ?></textarea></label>
        <button class="btn"><?= h(t('c_send')) ?></button>
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
