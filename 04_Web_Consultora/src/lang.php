<?php
declare(strict_types=1);
// Idiomas del sitio de la consultora. Los textos están en src/lang/<código>.php;
// español es el idioma base: si falta una clave en otro idioma se usa la de español.

function idioma_inicial(): string
{
    $ok = array_keys(IDIOMAS);
    if (isset($_GET['lang']) && in_array($_GET['lang'], $ok, true)) {
        setcookie('lang', $_GET['lang'], ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax', 'secure' => is_https()]);
        return $_SESSION['lang'] = $_GET['lang'];
    }
    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $ok, true)) {
        return $_SESSION['lang'];
    }
    if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], $ok, true)) {
        return $_SESSION['lang'] = $_COOKIE['lang'];
    }
    // primer idioma de la lista del navegador que el sitio ofrece (por ejemplo "pt-BR,pt;q=0.9" -> pt)
    foreach (explode(',', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '')) as $part) {
        $code = substr(trim($part), 0, 2);
        if (in_array($code, $ok, true)) {
            return $_SESSION['lang'] = $code;
        }
    }
    return $_SESSION['lang'] = 'en';
}

function lang(): string { return $GLOBALS['LANG']; }
function es_rtl(): bool { return lang() === 'ar'; }

function lang_table(string $code): array
{
    static $cache = [];
    if (!isset($cache[$code])) {
        $file = __DIR__ . '/lang/' . preg_replace('/[^a-z]/', '', $code) . '.php';
        $cache[$code] = is_file($file) ? (require $file) : [];
    }
    return $cache[$code];
}

function t(string $k, ...$args): string
{
    $s = lang_table(lang())[$k] ?? lang_table('es')[$k] ?? $k;
    if (is_array($s)) {
        return $k;
    }
    return $args ? vsprintf($s, $args) : $s;
}

/** Lista de párrafos traducidos. */
function tl(string $k): array
{
    $s = lang_table(lang())[$k] ?? lang_table('es')[$k] ?? [];
    return is_array($s) ? $s : [];
}
