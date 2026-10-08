<?php
$u = $u ?? usuario_actual();
$pagina = $GLOBALS['PAGE'];
$menu = ['index' => ['/', 'nav_home'], 'productos' => ['/productos', 'nav_products'], 'mercados' => ['/mercados', 'nav_markets'], 'contacto' => ['/contacto', 'nav_contact']];
?><!DOCTYPE html>
<html lang="<?= h(lang()) ?>" dir="<?= es_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h(t($tituloClave ?? 'nav_home')) ?> · ASHAB أعشاب</title>
<meta name="description" content="<?= h(t('hero_lead')) ?>">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="top">
  <div class="wrap bar">
    <a href="/" class="brand"><span class="mark" aria-hidden="true">&#10086;</span> <b>ASHAB</b> <span class="ar">أعشاب</span></a>
    <button class="burger" aria-label="Menu" onclick="document.body.classList.toggle('nav-open')">&#9776;</button>
    <nav>
      <?php foreach ($menu as $key => [$href, $k]): ?>
        <a href="<?= $href ?>" class="<?= $pagina === $key ? 'on' : '' ?>"><?= h(t($k)) ?></a>
      <?php endforeach; ?>
      <?php if ($u): ?>
        <?php if ($u['es_admin']): ?><a href="/admin"><?= h(t('nav_admin')) ?></a><?php endif; ?>
        <a href="/panel" class="btn small"><?= h(t('nav_panel')) ?></a>
        <a href="/logout"><?= h(t('nav_logout')) ?></a>
      <?php else: ?>
        <a href="/login" class="btn small"><?= h(t('nav_login')) ?></a>
      <?php endif; ?>
      <span class="langs">
        <?php foreach (IDIOMAS as $code => $name): ?>
          <a href="<?= h(url_idioma($code)) ?>" class="<?= lang() === $code ? 'on' : '' ?>" hreflang="<?= $code ?>" lang="<?= $code ?>"><?= h($name) ?></a>
        <?php endforeach; ?>
      </span>
    </nav>
  </div>
</header>
<main>
<?php if ($f = flash()): ?>
  <div class="wrap"><div class="alert <?= h($f[1]) ?>"><?= h(t($f[0])) ?></div></div>
<?php endif; ?>
