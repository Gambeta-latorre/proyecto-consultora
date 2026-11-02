<?php
$tituloClave = 'order';
$u = requerir_perfil();
$codigo = (string)($_GET['c'] ?? $_POST['c'] ?? '');
$p = preg_match('/^ASH-[A-Z0-9]{6}$/', $codigo) ? cargar_pedido($codigo, (int)$u['id']) : null;
if (!$p) {
    flash('o_not_found', 'error');
    redirect('/panel');
}
$err = '';
$v = ['pago_banco' => '', 'pago_ref' => '', 'pago_fecha' => date('Y-m-d'), 'pago_monto' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string)($_POST['accion'] ?? '');
    if (!csrf_ok()) {
        $err = 'csrf';
    } elseif ($accion === 'cancelar' && in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida'], true)) {
        db()->prepare("UPDATE pedidos SET estado = 'cancelada', actualizado_en = ? WHERE id = ?")->execute([now(), $p['id']]);
        flash('o_cancelled');
        redirect('/panel');
    } elseif ($accion === 'informar_pago' && $p['estado'] === 'proforma_emitida') {
        foreach ($v as $k => $_) {
            $v[$k] = trim((string)($_POST[$k] ?? ''));
        }
        $fecha = DateTime::createFromFormat('Y-m-d', $v['pago_fecha']);
        $fechaOk = $fecha && $fecha->format('Y-m-d') === $v['pago_fecha'] && $v['pago_fecha'] <= date('Y-m-d', time() + 86400)
            && $v['pago_fecha'] >= substr($p['creado_en'], 0, 10);
        $monto = (float)str_replace(',', '.', $v['pago_monto']);
        $ok = $v['pago_banco'] !== '' && mb_strlen($v['pago_banco']) <= 120 && $v['pago_ref'] !== '' && mb_strlen($v['pago_ref']) <= 120
            && $fechaOk && $monto > 0 && $monto <= (float)$p['total_usd'] * 1.1 && !empty($_POST['acepto'])
            && $p['vence_en'] !== null && $p['vence_en'] >= now();
        if (!$ok) {
            $err = 'o_pay_bad';
        } else {
            db()->prepare("UPDATE pedidos SET estado = 'sena_informada', pago_banco = ?, pago_ref = ?, pago_fecha = ?, pago_monto = ?, pago_informado_en = ?,
                           terminos_aceptados_en = ?, terminos_ip = ?, actualizado_en = ? WHERE id = ?")
                ->execute([$v['pago_banco'], $v['pago_ref'], $v['pago_fecha'], $monto, now(), now(), ip_cliente(), now(), $p['id']]);
            flash('o_pay_sent');
            redirect('/pedido?c=' . rawurlencode($codigo));
        }
    }
    $p = cargar_pedido($codigo, (int)$u['id']);
}
$nombre = campo_idioma($p, 'nombre');
$saldo = $p['total_usd'] !== null ? (float)$p['total_usd'] - (float)$p['sena_usd'] : null;
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page narrow-lg">
  <p><a href="/panel">&larr; <?= h(t('p_orders')) ?></a></p>
  <h1><?= h(t('order')) ?> <bdi dir="ltr"><?= h($p['codigo']) ?></bdi></h1>
  <p><span class="pill <?= h($p['estado']) ?>"><?= h(t('st_' . $p['estado'])) ?></span></p>
  <?php if ($err): ?><div class="alert error"><?= h(t($err)) ?></div><?php endif; ?>

  <div class="card">
    <table class="kv">
      <tr><th><?= h(t('p_product')) ?></th><td><?= h($nombre) ?> (<?= h($p['presentacion']) ?>)</td></tr>
      <tr><th><?= h(t('p_qty')) ?></th><td dir="ltr"><?= number_format((int)$p['cantidad_kg'], 0, '.', ',') ?> kg</td></tr>
      <tr><th><?= h(t('p_dest')) ?></th><td><?= h(nombre_pais($p['destino'])) ?> · <?= h($p['incoterm']) ?></td></tr>
      <?php if ($p['total_usd'] !== null): ?>
        <tr><th><?= h(t('o_price')) ?></th><td dir="ltr"><?= h(usd($p['precio_kg'])) ?></td></tr>
        <tr><th><?= h(t('o_total')) ?></th><td dir="ltr"><b><?= h(usd($p['total_usd'])) ?></b></td></tr>
        <tr><th><?= h(t('o_sena')) ?></th><td dir="ltr"><b><?= h(usd($p['sena_usd'])) ?></b></td></tr>
        <tr><th><?= h(t('o_saldo')) ?></th><td dir="ltr"><?= h(usd($saldo)) ?></td></tr>
        <?php if ($p['vence_en'] && in_array($p['estado'], ['proforma_emitida', 'vencida'], true)): ?>
          <tr><th><?= h(t('o_valid')) ?></th><td dir="ltr"><?= h($p['vence_en']) ?></td></tr>
        <?php endif; ?>
      <?php endif; ?>
    </table>
  </div>

  <?php if ($p['estado'] === 'solicitada'): ?>
    <p class="alert ok"><?= h(t('o_waiting')) ?></p>
  <?php elseif ($p['estado'] === 'vencida'): ?>
    <p class="alert error"><?= h(t('o_expired')) ?> <a href="/productos"><?= h(t('nav_products')) ?></a></p>
  <?php elseif ($p['estado'] === 'proforma_emitida'): ?>
    <h2><?= h(t('o_how')) ?></h2>
    <ol class="steps"><li><?= h(t('o_step1')) ?> <a href="/terminos" target="_blank"><?= h(t('f_terms')) ?></a></li><li><?= h(t('o_step2')) ?></li><li><?= h(t('o_step3')) ?></li><li><?= h(t('o_step4')) ?></li></ol>
    <h3><?= h(t('o_bank')) ?></h3>
    <?php if (info_bancaria() !== ''): ?><pre class="bank" dir="ltr"><?= h(info_bancaria()) ?></pre><?php else: ?><p><?= h(t('o_bank_missing')) ?></p><?php endif; ?>
    <h3><?= h(t('o_pay_t')) ?></h3>
    <form method="post" class="form" novalidate>
      <?= csrf_field() ?><input type="hidden" name="c" value="<?= h($p['codigo']) ?>"><input type="hidden" name="accion" value="informar_pago">
      <label><?= h(t('o_pay_bank')) ?><input name="pago_banco" value="<?= h($v['pago_banco']) ?>" required maxlength="120"></label>
      <label><?= h(t('o_pay_ref')) ?><input name="pago_ref" value="<?= h($v['pago_ref']) ?>" required maxlength="120" dir="ltr"></label>
      <div class="row2">
        <label><?= h(t('o_pay_date')) ?><input name="pago_fecha" value="<?= h($v['pago_fecha']) ?>" required maxlength="10" dir="ltr" placeholder="2026-01-31"></label>
        <label><?= h(t('o_pay_amount')) ?><input name="pago_monto" value="<?= h($v['pago_monto']) ?>" required inputmode="decimal" dir="ltr" placeholder="<?= h(number_format((float)$p['sena_usd'], 2, '.', '')) ?>"></label>
      </div>
      <label class="check"><input type="checkbox" name="acepto" value="1" required> <span><?= h(t('o_accept')) ?></span></label>
      <button class="btn"><?= h(t('o_pay_btn')) ?></button>
    </form>
  <?php elseif ($p['estado'] === 'sena_informada'): ?>
    <p class="alert ok"><?= h(t('o_pay_sent')) ?></p>
    <p dir="ltr"><?= h($p['pago_banco']) ?> · <?= h($p['pago_ref']) ?> · <?= h($p['pago_fecha']) ?> · <?= h(usd($p['pago_monto'])) ?></p>
  <?php endif; ?>

  <?php if (in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida'], true)): ?>
    <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="c" value="<?= h($p['codigo']) ?>"><input type="hidden" name="accion" value="cancelar">
      <button class="btn ghost-dark small"><?= h(t('o_cancel')) ?></button></form>
  <?php endif; ?>
  <p><a class="wa-link" target="_blank" rel="noopener" href="<?= h(whatsapp_url(t('wa_order', $p['codigo']))) ?>"><?= h(t('wa_ask')) ?> &rarr;</a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
