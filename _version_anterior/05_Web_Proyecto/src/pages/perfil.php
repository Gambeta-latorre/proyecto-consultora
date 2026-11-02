<?php
$tituloClave = 'perfil_title';
$u = requerir_login();
$err = [];
$v = [
    'empresa' => (string)($u['empresa'] ?? ''), 'pais' => (string)($u['pais'] ?? 'AE'), 'ciudad' => (string)($u['ciudad'] ?? ''),
    'cargo' => (string)($u['cargo'] ?? ''), 'registro_comercial' => (string)($u['registro_comercial'] ?? ''),
    'whatsapp' => (string)($u['whatsapp'] ?? ''), 'sitio_web' => (string)($u['sitio_web'] ?? ''),
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok()) {
        $err[] = 'csrf';
    } else {
        if ($v['empresa'] === '' || mb_strlen($v['empresa']) > 120) { $err[] = 'e_company'; }
        if (!in_array($v['pais'], PAISES, true)) { $v['pais'] = 'OTRO'; }
        if ($v['ciudad'] === '' || mb_strlen($v['ciudad']) > 80) { $err[] = 'e_city'; }
        if ($v['registro_comercial'] === '' || mb_strlen($v['registro_comercial']) > 60) { $err[] = 'e_regnum'; }
        $wa = normalizar_whatsapp($v['whatsapp']);
        if ($wa === null) { $err[] = 'e_whatsapp'; }
        $web = $v['sitio_web'];
        if ($web !== '' && !preg_match('#^https?://[^\s]{3,150}$#i', $web)) { $web = ''; }
        if (!$err) {
            // si cambia el número de registro o la empresa, la cuenta vuelve a revisión (salvo administradores)
            $cambio = $v['empresa'] !== (string)$u['empresa'] || $v['registro_comercial'] !== (string)$u['registro_comercial'];
            $cuenta = (!$u['es_admin'] && ($cambio || !(int)$u['perfil_completo'])) ? 'pendiente' : $u['cuenta'];
            db()->prepare('UPDATE usuarios SET empresa=?, pais=?, ciudad=?, cargo=?, registro_comercial=?, whatsapp=?, sitio_web=?, perfil_completo=1, cuenta=? WHERE id=?')
                ->execute([$v['empresa'], $v['pais'], $v['ciudad'], mb_substr($v['cargo'], 0, 80), $v['registro_comercial'], $wa, $web, $cuenta, $u['id']]);
            flash('perfil_saved');
            redirect('/panel');
        }
    }
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page narrow">
  <h1><?= h(t('perfil_title')) ?></h1>
  <p class="lead"><?= h(t('perfil_lead')) ?></p>
  <?php foreach ($err as $e): ?><div class="alert error"><?= h(t($e)) ?></div><?php endforeach; ?>
  <form method="post" class="form" id="perfil" novalidate>
    <?= csrf_field() ?>
    <label><?= h(t('f_company')) ?><input name="empresa" value="<?= h($v['empresa']) ?>" required maxlength="120"></label>
    <div class="row2">
      <label><?= h(t('f_country')) ?>
        <select name="pais" id="pais"><?php foreach (PAISES as $c): ?><option value="<?= $c ?>" <?= $v['pais'] === $c ? 'selected' : '' ?>><?= h(nombre_pais($c)) ?></option><?php endforeach; ?></select>
      </label>
      <label><?= h(t('f_city')) ?><input name="ciudad" value="<?= h($v['ciudad']) ?>" required maxlength="80"></label>
    </div>
    <label><span id="reglabel"><?= h(t(clave_registro($v['pais']))) ?></span><input name="registro_comercial" value="<?= h($v['registro_comercial']) ?>" required maxlength="60" dir="ltr"></label>
    <label><?= h(t('f_role')) ?><input name="cargo" value="<?= h($v['cargo']) ?>" maxlength="80"></label>
    <label><?= h(t('f_whatsapp')) ?><input name="whatsapp" value="<?= h($v['whatsapp']) ?>" required maxlength="25" dir="ltr" inputmode="tel" placeholder="+971 50 123 4567"><small><?= h(t('f_whatsapp_help')) ?></small></label>
    <label><?= h(t('f_website')) ?><input name="sitio_web" value="<?= h($v['sitio_web']) ?>" maxlength="160" dir="ltr" placeholder="https://"></label>
    <button class="btn"><?= h(t('save')) ?></button>
  </form>
</section>
<script>
(function () {
  var labels = <?= json_encode(array_combine(PAISES, array_map(fn($c) => t(clave_registro($c)), PAISES)), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  var sel = document.getElementById('pais'), lab = document.getElementById('reglabel');
  sel.addEventListener('change', function () { lab.textContent = labels[sel.value] || labels.OTRO; });
})();
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
