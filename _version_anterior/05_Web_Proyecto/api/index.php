<?php
declare(strict_types=1);
// Punto de entrada único (Vercel) y enrutador. Solo se ejecutan las páginas de la lista blanca.

const PAGES = ['index', 'mercados', 'productos', 'contacto', 'login', 'google', 'google_callback', 'perfil', 'panel', 'pedido', 'admin', 'admin_export', 'logout', 'terminos', 'privacidad', 'install'];

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$name = preg_replace('/\.php$/', '', trim($path, '/'));
if ($name === '' || $name === 'api/index') {
    $name = 'index';
}
$page = in_array($name, PAGES, true) ? $name : '404';
$GLOBALS['PAGE'] = $page;
if ($page === 'install') {
    define('NO_SESSION', true);
}

try {
    require __DIR__ . '/../src/bootstrap.php';
    require __DIR__ . '/../src/pages/' . $page . '.php';
} catch (Throwable $e) {
    error_log('[' . ($GLOBALS['PAGE'] ?? '?') . '] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
    }
    $debug = getenv('APP_DEBUG') === '1';
    echo '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Error</title>'
        . '<body style="font-family:sans-serif;max-width:560px;margin:15vh auto;padding:0 20px"><h1>Service temporarily unavailable</h1>'
        . '<p>Please try again in a few minutes · حاول مرة أخرى بعد دقائق · Intentá de nuevo en unos minutos.</p>'
        . ($debug ? '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>' : '') . '</body>';
}
