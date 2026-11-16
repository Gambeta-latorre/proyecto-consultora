<?php
// SOLO para Vercel: todas las direcciones llegan a este archivo y acá se carga la página pedida.
// En XAMPP no se usa (Apache ejecuta cada .php directamente).
$ruta = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($ruta === '') $ruta = 'index.php';

// Archivos de diseño (css e imágenes)
$tipos = ['css' => 'text/css', 'svg' => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg'];
if (preg_match('#^(css|img)/[\w.-]+$#', $ruta) && is_file(__DIR__ . "/../$ruta")) {
    header('Content-Type: ' . $tipos[pathinfo($ruta, PATHINFO_EXTENSION)]);
    readfile(__DIR__ . "/../$ruta");
    exit;
}

// Páginas permitidas (lista blanca: nadie puede pedir inc/conexion.php ni otros archivos internos)
$paginas = ['index.php', 'productos.php', 'contacto.php', 'registro.php', 'login.php', 'salir.php'];
if (!in_array($ruta, $paginas)) {
    http_response_code(404);
    exit('Página no encontrada');
}
chdir(__DIR__ . '/..');
require $ruta;
