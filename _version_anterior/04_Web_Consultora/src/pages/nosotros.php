<?php $tituloClave = 'nav_about'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page">
  <h1><?= h(t('about_title')) ?></h1>
  <p class="lead"><?= h(t('about_lead', SITE_NAME)) ?></p>
  <div class="grid2">
    <article class="card"><h3><?= h(t('mission_t')) ?></h3><p><?= h(t('mission_d')) ?></p></article>
    <article class="card"><h3><?= h(t('vision_t')) ?></h3><p><?= h(t('vision_d')) ?></p></article>
  </div>
  <h2><?= h(t('culture_title')) ?></h2>
  <div class="grid3">
    <?php for ($i = 1; $i <= 6; $i++): ?>
      <article class="card"><h3><?= h(t("v{$i}_t")) ?></h3><p><?= h(t("v{$i}_d")) ?></p></article>
    <?php endfor; ?>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
