<?php
$tituloClave = 'nav_products';
$u = usuario_actual();
$aprobado = $u && $u['cuenta'] === 'aprobada' && ($u['es_admin'] || (int)$u['perfil_completo']);
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = requerir_perfil();
    $aprobado = $u['cuenta'] === 'aprobada';
    $pid = (int)($_POST['producto_id'] ?? 0);
    $kg = (int)($_POST['kg'] ?? 0);
    $dest = in_array($_POST['destino'] ?? '', PAISES, true) ? $_POST['destino'] : 'OTRO';
    $inc = ($_POST['incoterm'] ?? 'FOB') === 'CIF' ? 'CIF' : 'FOB';
    if (!csrf_ok()) {
        $msg = 'csrf';
    } elseif (!$aprobado) {
        $msg = 'need_approval';
    } elseif ($kg < MIN_KG || $kg > MAX_KG) {
        $msg = 'qty_bad';
    } elseif (pedidos_abiertos((int)$u['id']) >= MAX_ABIERTOS) {
        $msg = 'too_many';
    } else {
        $ok = db()->prepare('SELECT 1 FROM productos WHERE id = ? AND activo = 1');
        $ok->execute([$pid]);
        if ($ok->fetch()) {
            db()->prepare('INSERT INTO pedidos (codigo, usuario_id, producto_id, cantidad_kg, destino, incoterm, estado, creado_en, actualizado_en) VALUES (?,?,?,?,?,?,?,?,?)')
                ->execute([pedido_codigo(), $u['id'], $pid, $kg, $dest, $inc, 'solicitada', now(), now()]);
            flash('quote_sent');
            redirect('/panel');
        }
    }
}
$productos = db()->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id')->fetchAll();
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1><?= h(t('products_title')) ?></h1>
  <p class="lead"><?= h(t('products_lead')) ?></p>
  <?php if ($msg): ?><div class="alert error"><?= h(t($msg)) ?></div><?php endif; ?>
  <div class="grid3">
  <?php foreach ($productos as $p): $nombre = campo_idioma($p, 'nombre'); ?>
    <article class="card prod">
      <h3><?= h($nombre) ?></h3>
      <p><?= h(campo_idioma($p, 'descripcion')) ?></p>
      <p class="meta"><?= h(t('pack')) ?>: <b><?= h($p['presentacion']) ?></b></p>
      <?php if ($aprobado): ?>
        <p class="price"><?= h(t('price_fob')) ?>: <b dir="ltr"><?= h(usd($p['precio_fob_kg'])) ?> / kg</b></p>
        <form method="post" class="quote">
          <?= csrf_field() ?><input type="hidden" name="producto_id" value="<?= (int)$p['id'] ?>">
          <label><?= h(t('qty_kg')) ?><input type="number" name="kg" min="<?= MIN_KG ?>" max="<?= MAX_KG ?>" step="500" value="<?= MIN_KG ?>" dir="ltr" required></label>
          <small><?= h(t('qty_help')) ?></small>
          <label><?= h(t('dest')) ?>
            <select name="destino"><?php foreach (PAISES as $c): ?><option value="<?= $c ?>"><?= h(nombre_pais($c)) ?></option><?php endforeach; ?></select>
          </label>
          <label><?= h(t('incoterm')) ?><select name="incoterm"><option>FOB</option><option>CIF</option></select></label>
          <button class="btn small"><?= h(t('send_req')) ?></button>
        </form>
      <?php elseif ($u): ?>
        <p class="price locked"><a href="/panel"><?= h(t('price_locked_pending')) ?></a></p>
      <?php else: ?>
        <p class="price locked"><a href="/login"><?= h(t('price_locked_login')) ?></a></p>
      <?php endif; ?>
      <p><a class="wa-link" target="_blank" rel="noopener" href="<?= h(whatsapp_url(t('wa_product', $nombre))) ?>"><?= h(t('wa_ask')) ?> &rarr;</a></p>
    </article>
  <?php endforeach; ?>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
