<?php $tituloClave = 'nav_home'; require __DIR__ . '/../layout/header.php'; ?>
<section class="hero">
  <div class="wrap">
    <p class="eyebrow"><?= h(t('hero_eyebrow')) ?></p>
    <h1><?= h(t('hero_title')) ?></h1>
    <p class="lead"><?= h(t('hero_lead')) ?></p>
    <p><a class="btn gold" href="/productos"><?= h(t('hero_cta1')) ?></a> <a class="btn ghost" href="/productos"><?= h(t('hero_cta2')) ?></a></p>
  </div>
</section>

<section class="wrap">
  <h2><?= h(t('why_title')) ?></h2>
  <div class="grid4">
    <?php for ($i = 1; $i <= 4; $i++): ?>
      <article class="card"><span class="num"><?= $i ?></span><h3><?= h(t("why{$i}_t")) ?></h3><p><?= h(t("why{$i}_d")) ?></p></article>
    <?php endfor; ?>
  </div>
</section>

<section class="wrap">
  <h2><?= h(t('markets_title')) ?></h2>
  <p class="lead"><?= h(t('markets_lead')) ?></p>
  <div class="grid4">
    <?php foreach (['sy', 'lb', 'jo', 'ae'] as $c): ?>
      <article class="card flag"><h3><?= h(t("m_$c")) ?></h3><p><?= h(t("m_{$c}_d")) ?></p></article>
    <?php endforeach; ?>
  </div>
  <p class="note"><?= h(t('markets_note')) ?></p>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
