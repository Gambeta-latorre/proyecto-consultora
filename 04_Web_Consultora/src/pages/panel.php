<?php
$tituloClave = 'panel_title';
$u = requerir_login();
$st = db()->prepare('SELECT nombre, estado, avance, fecha_inicio FROM proyectos WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$st->execute([$u['id']]);
$proyectos = $st->fetchAll();
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1><?= h(t('p_hello')) ?>, <?= h($u['nombre'] ?: $u['email']) ?></h1>
  <p class="lead"><?= $u['dni'] ? 'DNI ' . h($u['dni']) . ' · ' : '' ?><bdi dir="ltr"><?= h((string)$u['email']) ?></bdi></p>
  <h2><?= h(t('p_projects')) ?></h2>
  <?php if (!$proyectos): ?>
    <p><?= h(t('p_none')) ?> <a href="<?= h(whatsapp_url(t('wa_project', (string)($u['nombre'] ?: $u['email'])))) ?>" target="_blank" rel="noopener"><?= h(t('p_wa')) ?></a></p>
  <?php else: ?>
    <div class="tablewrap"><table>
      <tr><th><?= h(t('p_project')) ?></th><th><?= h(t('p_status')) ?></th><th><?= h(t('p_progress')) ?></th><th><?= h(t('p_start')) ?></th></tr>
      <?php foreach ($proyectos as $p): ?>
        <tr><td><?= h($p['nombre']) ?></td><td><?= h($p['estado']) ?></td>
            <td><div class="bar-prog"><i style="width:<?= (int)$p['avance'] ?>%"></i></div> <?= (int)$p['avance'] ?>%</td>
            <td dir="ltr"><?= h($p['fecha_inicio']) ?></td></tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
