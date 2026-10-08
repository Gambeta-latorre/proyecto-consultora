<?php $tituloClave = 'privacy_title'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page narrow">
  <h1><?= h(t('privacy_title')) ?></h1>
  <ol class="legal"><?php foreach (tl('privacy_list') as $p): ?><li><?= h($p) ?></li><?php endforeach; ?></ol>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
