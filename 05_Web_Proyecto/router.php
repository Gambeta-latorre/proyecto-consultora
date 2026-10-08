<?php
// Servidor de desarrollo:  php -S localhost:8000 -t public router.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/' && is_file(__DIR__ . '/public' . $path)) {
    return false;           // archivos estáticos (css, imágenes)
}
require __DIR__ . '/api/index.php';
