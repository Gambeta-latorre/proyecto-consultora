<?php
$tituloClave = 'panel_title';
$u = requerir_login();
if (!$u['es_admin'] && !(int)$u['perfil_completo']) {
    redirect('/perfil');
}
vencer_proformas();
$st = db()->prepare('SELECT p.codigo, p.cantidad_kg, p.destino, p.estado, p.total_usd, p.creado_en, pr.nombre_es, pr.nombre_en, pr.nombre_ar
                     FROM pedidos p JOIN productos pr ON pr.id = p.producto_id WHERE p.usuario_id = ? ORDER BY p.creado_en DESC');
$st->execute([$u['id']]);
$pedidos = $st->fetchAll();
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1><?= h(t('p_hello')) ?>, <?= h($u['nombre'] ?: $u['email']) ?></h1>
  <p class="lead"><?= h($u['empresa'] ?? '') ?><?= !empty($u['pais']) ? ' · ' . h(nombre_pais($u['pais'])) : '' ?> · <bdi dir="ltr"><?= h($u['email']) ?></bdi></p>

  <div class="card status <?= h($u['cuenta']) ?>">
    <b><?= h(t('p_status')) ?>:</b> <?= h(t('acc_' . $u['cuenta'])) ?>
    <?php if ($u['cuenta'] === 'pendiente'): ?>
      <p><?= h(t('acc_pending_msg')) ?></p>
      <p><a class="btn small" target="_blank" rel="noopener" href="<?= h(whatsapp_url(t('wa_registered', (string)$u['empresa'], nombre_pais((string)$u['pais'])))) ?>"><?= h(t('acc_notify')) ?></a></p>
    <?php elseif ($u['cuenta'] === 'rechazada'): ?>
      <p><?= h(t('acc_rejected_msg')) ?></p>
    <?php endif; ?>
    <?php if (!$u['es_admin']): ?><p><a href="/perfil"><?= h(t('p_edit_profile')) ?></a></p><?php endif; ?>
  </div>

  <h2><?= h(t('p_orders')) ?></h2>
  <?php if (!$pedidos): ?>
    <p><?= h(t('p_none')) ?> <a href="/productos"><?= h(t('nav_products')) ?></a></p>
  <?php else: ?>
    <div class="tablewrap"><table>
      <tr><th><?= h(t('order')) ?></th><th><?= h(t('p_date')) ?></th><th><?= h(t('p_product')) ?></th><th><?= h(t('p_qty')) ?></th><th><?= h(t('p_dest')) ?></th><th><?= h(t('p_total')) ?></th><th></th></tr>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td dir="ltr"><?= h($p['codigo']) ?></td><td dir="ltr"><?= h(substr($p['creado_en'], 0, 10)) ?></td>
          <td><?= h(campo_idioma($p, 'nombre')) ?></td><td dir="ltr"><?= number_format((int)$p['cantidad_kg'], 0, '.', ',') ?></td>
          <td><?= h(nombre_pais($p['destino'])) ?></td><td dir="ltr"><?= $p['total_usd'] !== null ? h(usd($p['total_usd'])) : '—' ?></td>
          <td><span class="pill <?= h($p['estado']) ?>"><?= h(t('st_' . $p['estado'])) ?></span> <a href="/pedido?c=<?= h(rawurlencode($p['codigo'])) ?>"><?= h(t('p_open')) ?></a></td>
        </tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
