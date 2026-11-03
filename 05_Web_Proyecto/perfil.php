<?php
// Datos del comercio. Hasta completarlos no se puede pedir. Si cambia la razón social o el CUIT, la cuenta vuelve a revisión.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Mi comercio';
$activa = 'Mi cuenta';
$yo = exigir_login();
$error = '';
$v = ['empresa' => $yo['empresa'] ?? '', 'cuit' => $yo['cuit'] ?? '', 'ciudad' => $yo['ciudad'] ?? '', 'whatsapp' => $yo['whatsapp'] ?? ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $campo => $_) $v[$campo] = trim($_POST[$campo] ?? '');
    $solo_cuit = preg_replace('/\D/', '', $v['cuit']);
    if ($v['empresa'] === '' || mb_strlen($v['empresa']) > 120)  $error = 'Escribí la razón social o el nombre del comercio.';
    elseif (strlen($solo_cuit) !== 11)                          $error = 'El CUIT debe tener 11 números.';
    elseif ($v['ciudad'] === '' || mb_strlen($v['ciudad']) > 80) $error = 'Escribí la ciudad.';
    elseif (!preg_match('/^[0-9+() -]{8,25}$/', $v['whatsapp'])) $error = 'El WhatsApp no es válido.';
    else {
        $cambio = $v['empresa'] !== $yo['empresa'] || $solo_cuit !== $yo['cuit'];
        $cuenta = ($yo['rol'] !== 'admin' && ($cambio || !$yo['perfil_completo'])) ? 'pendiente' : $yo['cuenta'];
        $db->prepare('UPDATE usuarios SET empresa = ?, cuit = ?, ciudad = ?, whatsapp = ?, perfil_completo = 1, cuenta = ? WHERE id = ?')
           ->execute([$v['empresa'], $solo_cuit, $v['ciudad'], $v['whatsapp'], $cuenta, $yo['id']]);
        aviso('Datos guardados.');
        ir('panel.php');
    }
}
require 'inc/cabecera.php';
?>
<div class="pagina angosta">
  <h1>Datos de tu comercio</h1>
  <p>Los usamos para aprobar tu cuenta y emitir la proforma. Solo los ve el equipo de ASHAB.</p>
  <?php if ($error): ?><div class="aviso error"><?= h($error) ?></div><?php endif; ?>
  <form method="post" class="formulario">
    <?= csrf_campo() ?>
    <label>Razón social o nombre del comercio<input name="empresa" value="<?= h($v['empresa']) ?>" maxlength="120" required></label>
    <div class="dos">
      <label>CUIT<input name="cuit" value="<?= h($v['cuit']) ?>" maxlength="13" placeholder="30-12345678-9" required></label>
      <label>Ciudad<input name="ciudad" value="<?= h($v['ciudad']) ?>" maxlength="80" required></label>
    </div>
    <label>WhatsApp<input name="whatsapp" value="<?= h($v['whatsapp']) ?>" maxlength="25" placeholder="+54 9 11 ..." required><small>Te avisamos por acá cuando esté tu proforma.</small></label>
    <button>Guardar</button>
  </form>
</div>
<?php require 'inc/pie.php'; ?>
