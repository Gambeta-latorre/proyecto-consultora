<?php $tituloClave = 'nav_home'; require __DIR__ . '/../layout/header.php'; ?>
<section class="hero">
  <div class="wrap">
    <p class="eyebrow"><?= h(t('home_eyebrow')) ?></p>
    <h1><?= h(t('home_title_a')) ?> <span class="red"><?= h(t('home_title_b')) ?></span>.</h1>
    <p class="lead"><?= h(t('home_lead')) ?></p>
    <p><a class="btn" href="/contacto"><?= h(t('home_cta1')) ?></a> <a class="btn ghost" href="/servicios"><?= h(t('home_cta2')) ?></a></p>
  </div>
</section>

<section class="wrap stats">
  <?php for ($i = 1; $i <= 3; $i++): ?>
    <div><b><?= h(t("stat{$i}_n")) ?></b><span><?= h(t("stat{$i}_t")) ?></span></div>
  <?php endfor; ?>
</section>

<section class="wrap">
  <h2><?= h(t('what_title')) ?></h2>
  <div class="grid3">
    <?php for ($i = 1; $i <= 3; $i++): ?>
      <article class="card"><h3><?= h(t("svc{$i}_t")) ?></h3><p><?= h(t("svc{$i}_d")) ?></p></article>
    <?php endfor; ?>
  </div>
</section>

<section class="wrap">
  <h2><?= h(t('markets_title')) ?></h2>
  <p class="lead"><?= h(t('markets_lead')) ?></p>
  <ul class="chips">
    <?php for ($i = 1; $i <= 6; $i++): ?><li><?= h(t("reg{$i}")) ?></li><?php endfor; ?>
  </ul>
</section>

<section class="wrap case">
  <div>
    <p class="eyebrow"><?= h(t('case_eyebrow')) ?></p>
    <h2><?= h(t('case_title')) ?></h2>
    <p><?= h(t('case_text')) ?></p>
    <p><a class="btn" href="<?= h(PROYECTO_URL) ?>"><?= h(t('case_btn')) ?></a></p>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
