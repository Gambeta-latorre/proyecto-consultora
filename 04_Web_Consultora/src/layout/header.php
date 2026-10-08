<?php
$u = $u ?? usuario_actual();
$pagina = $GLOBALS['PAGE'];
$menu = ['index' => ['/', 'nav_home'], 'servicios' => ['/servicios', 'nav_services'], 'nosotros' => ['/nosotros', 'nav_about'], 'contacto' => ['/contacto', 'nav_contact']];
?><!DOCTYPE html>
<html lang="<?= h(lang()) ?>" dir="<?= es_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h(t($tituloClave ?? 'nav_home')) ?> · <?= h(SITE_NAME) ?></title>
<meta name="description" content="<?= h(t('meta_desc')) ?>">
<link rel="icon" href="/assets/img/isotipo.png">
<?php if (es_rtl()): ?><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&amp;display=swap"><?php endif; ?>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="top">
  <div class="wrap bar">
    <a href="/" class="brand"><img src="/assets/img/logo.png" alt="<?= h(SITE_NAME) ?>"></a>
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
      <label class="langsel"><span class="sr"><?= h(t('lang_label')) ?></span>
        <select aria-label="<?= h(t('lang_label')) ?>" onchange="location.href=this.value">
          <?php foreach (IDIOMAS as $code => $name): ?>
            <option value="<?= h(url_idioma($code)) ?>" lang="<?= $code ?>" <?= lang() === $code ? 'selected' : '' ?>><?= h($name) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </nav>
  </div>
</header>
<main>
<?php if ($f = flash()): ?>
  <div class="wrap"><div class="alert <?= h($f[1]) ?>"><?= h(t($f[0])) ?></div></div>
<?php endif; ?>
