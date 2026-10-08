<?php
$u = $u ?? usuario_actual();
$pagina = $GLOBALS['PAGE'];
$menu = ['index' => ['/', 'Inicio'], 'servicios' => ['/servicios', 'Servicios'], 'nosotros' => ['/nosotros', 'Nosotros'], 'contacto' => ['/contacto', 'Contacto']];
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($titulo ?? 'Inicio') ?> · <?= h(SITE_NAME) ?></title>
<meta name="description" content="Consultora de comercio exterior: llevamos a las pymes argentinas al mundo.">
<link rel="icon" href="/assets/img/isotipo.png">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="top">
  <div class="wrap bar">
    <a href="/" class="brand"><img src="/assets/img/logo.png" alt="<?= h(SITE_NAME) ?>"></a>
    <button class="burger" aria-label="Menú" onclick="document.body.classList.toggle('nav-open')">&#9776;</button>
    <nav>
      <?php foreach ($menu as $key => [$href, $txt]): ?>
        <a href="<?= $href ?>" class="<?= $pagina === $key ? 'on' : '' ?>"><?= $txt ?></a>
      <?php endforeach; ?>
      <?php if ($u): ?>
        <?php if ($u['es_admin']): ?><a href="/admin">Administración</a><?php endif; ?>
        <a href="/panel" class="btn small">Mi panel</a>
        <a href="/logout">Salir</a>
      <?php else: ?>
        <a href="/login" class="btn small">Ingresar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
<?php if ($f = flash()): ?>
  <div class="wrap"><div class="alert <?= h($f[1]) ?>"><?= h($f[0]) ?></div></div>
<?php endif; ?>
