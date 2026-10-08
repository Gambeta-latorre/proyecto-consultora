<?php
$titulo = 'Mi panel';
$u = requerir_login();
$st = db()->prepare('SELECT nombre, estado, avance, fecha_inicio FROM proyectos WHERE usuario_id = ? ORDER BY fecha_inicio DESC');
$st->execute([$u['id']]);
$proyectos = $st->fetchAll();
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page">
  <h1>Hola, <?= h($u['nombre'] ?: $u['email']) ?></h1>
  <p class="lead"><?= $u['dni'] ? 'DNI ' . h($u['dni']) . ' · ' : '' ?><bdi dir="ltr"><?= h((string)$u['email']) ?></bdi></p>
  <h2>Mis proyectos</h2>
  <?php if (!$proyectos): ?>
    <p>Todavía no tenés proyectos asignados. <a href="<?= h(whatsapp_url('Hola, soy ' . $u['nombre'] . '. Quiero iniciar un proyecto de exportación.')) ?>" target="_blank" rel="noopener">Escribinos por WhatsApp</a> para empezar.</p>
  <?php else: ?>
    <div class="tablewrap"><table>
      <tr><th>Proyecto</th><th>Estado</th><th>Avance</th><th>Inicio</th></tr>
      <?php foreach ($proyectos as $p): ?>
        <tr><td><?= h($p['nombre']) ?></td><td><?= h($p['estado']) ?></td>
            <td><div class="bar-prog"><i style="width:<?= (int)$p['avance'] ?>%"></i></div> <?= (int)$p['avance'] ?>%</td>
            <td><?= h($p['fecha_inicio']) ?></td></tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
