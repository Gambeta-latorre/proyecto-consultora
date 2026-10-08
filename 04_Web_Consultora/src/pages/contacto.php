<?php
$titulo = 'Contacto';
$errores = [];
$v = ['nombre' => '', 'email' => '', 'telefono' => '', 'mensaje' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $k => $_) {
        $v[$k] = trim((string)($_POST[$k] ?? ''));
    }
    if (!csrf_ok()) { $errores[] = 'La sesión expiró. Volvé a intentar.'; }
    if ($v['nombre'] === '' || mb_strlen($v['nombre']) > 100) { $errores[] = 'Ingresá tu nombre.'; }
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) { $errores[] = 'El email no es válido.'; }
    if (mb_strlen($v['mensaje']) < 10 || mb_strlen($v['mensaje']) > 2000) { $errores[] = 'El mensaje debe tener entre 10 y 2000 caracteres.'; }
    if (!$errores) {
        db()->prepare('INSERT INTO consultas (nombre, email, telefono, mensaje, fecha) VALUES (?,?,?,?,?)')
            ->execute([$v['nombre'], $v['email'], mb_substr($v['telefono'], 0, 30), $v['mensaje'], now()]);
        flash('¡Gracias! Recibimos tu consulta y te respondemos en 24 horas hábiles.');
        redirect('/contacto');
    }
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1>Contacto</h1>
  <p class="lead">Contanos qué producto querés exportar. También podés escribirnos directo por WhatsApp.</p>
  <div class="grid2 contact">
    <article class="card wa-card">
      <h3>Escribinos por WhatsApp</h3>
      <p>Número en formato internacional (válido desde cualquier país)</p>
      <p class="bignum"><bdi dir="ltr" id="wanum"><?= h(wa_display()) ?></bdi></p>
      <p><a class="btn" target="_blank" rel="noopener" href="<?= h(whatsapp_url()) ?>">Abrir WhatsApp</a>
         <button type="button" class="btn ghost-dark" id="copywa">Copiar número</button></p>
      <p class="qr"><img src="/assets/img/qr_whatsapp.png" width="160" height="160" alt="Código QR de WhatsApp"><br><small>Desde una computadora: escaneá el código con tu teléfono.</small></p>
      <p><b>Horario:</b> lunes a viernes, de 9 a 18 h (Argentina).</p>
    </article>
    <div>
      <?php foreach ($errores as $e): ?><div class="alert error"><?= h($e) ?></div><?php endforeach; ?>
      <form method="post" class="form" novalidate>
        <?= csrf_field() ?>
        <label>Nombre<input name="nombre" value="<?= h($v['nombre']) ?>" required maxlength="100"></label>
        <label>Email<input type="email" name="email" value="<?= h($v['email']) ?>" required></label>
        <label>Teléfono (opcional)<input name="telefono" value="<?= h($v['telefono']) ?>" maxlength="30"></label>
        <label>Mensaje<textarea name="mensaje" rows="5" required><?= h($v['mensaje']) ?></textarea></label>
        <button class="btn">Enviar consulta</button>
      </form>
    </div>
  </div>
</section>
<script>
document.getElementById('copywa').addEventListener('click', function () {
  var b = this, txt = document.getElementById('wanum').textContent.trim(), old = b.textContent;
  (navigator.clipboard ? navigator.clipboard.writeText(txt) : Promise.reject()).then(function () {
    b.textContent = 'Copiado'; setTimeout(function () { b.textContent = old; }, 2000);
  }).catch(function () { window.prompt('WhatsApp', txt); });
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
