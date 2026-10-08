<?php http_response_code(404); $tituloClave = 'e_404'; require __DIR__ . '/../layout/header.php'; ?>
<section class="wrap page narrow"><h1>404</h1><p class="lead"><?= h(t('e_404')) ?></p><p><a class="btn" href="/"><?= h(t('back_home')) ?></a></p></section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
