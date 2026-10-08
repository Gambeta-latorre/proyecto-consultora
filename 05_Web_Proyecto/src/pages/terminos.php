<?php $tituloClave = 'terms_title'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page narrow">
  <h1><?= h(t('terms_title')) ?></h1>
  <ol class="legal"><?php foreach (tl('terms_list') as $p): ?><li><?= h($p) ?></li><?php endforeach; ?></ol>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
