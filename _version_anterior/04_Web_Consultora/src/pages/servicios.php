<?php $tituloClave = 'nav_services'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page">
  <h1><?= h(t('services_title')) ?></h1>
  <p class="lead"><?= h(t('services_lead')) ?></p>
  <div class="grid2">
    <?php for ($i = 1; $i <= 6; $i++): ?>
      <article class="card"><span class="num"><?= $i ?></span><h3><?= h(t("s{$i}_t")) ?></h3><p><?= h(t("s{$i}_d")) ?></p></article>
    <?php endfor; ?>
  </div>
  <p><a class="btn" href="/contacto"><?= h(t('services_cta')) ?></a></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
