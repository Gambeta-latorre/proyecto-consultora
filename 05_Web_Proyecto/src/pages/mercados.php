<?php $tituloClave = 'nav_markets'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page">
  <h1><?= h(t('markets_title')) ?></h1>
  <p class="lead"><?= h(t('markets_lead')) ?></p>
  <div class="grid2">
    <?php foreach (['sy', 'lb', 'jo', 'ae'] as $c): ?>
      <article class="card flag"><h3><?= h(t("m_$c")) ?></h3><p><?= h(t("m_{$c}_d")) ?></p></article>
    <?php endforeach; ?>
  </div>
  <p class="note"><?= h(t('markets_note')) ?></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
