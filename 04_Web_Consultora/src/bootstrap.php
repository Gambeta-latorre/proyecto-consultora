<?php
declare(strict_types=1);
// Arranque del sitio de Rumbo Global Consultores S.A.

define('SITE_NAME', 'Rumbo Global Consultores S.A.');
define('MAX_INTENTOS', 5);
define('BLOQUEO_MINUTOS', 15);
define('PROYECTO_URL', getenv('PROYECTO_URL') ?: '#');
define('IDIOMAS', ['es' => 'Español', 'en' => 'English', 'pt' => 'Português', 'fr' => 'Français', 'de' => 'Deutsch', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文']);

require __DIR__ . '/core.php';
require __DIR__ . '/lang.php';

define('WHATSAPP_NUMBER', env('WHATSAPP_NUMBER', '5491133486017'));   // 54 9 11 3348-6017

send_security_headers();
if (defined('NO_SESSION')) {          // /install corre antes de que existan las tablas
    $_SESSION = [];
    $LANG = 'es';
} else {
    start_session('RGSID');
    $LANG = idioma_inicial();
}

function wa_display(): string
{
    return '+54 9 11 3348-6017';
}

function whatsapp_url(string $msg = ''): string
{
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($msg !== '' ? $msg : t('wa_default'));
}

function url_idioma(string $l): string
{
    $q = $_GET;
    $q['lang'] = $l;
    return '/' . ($GLOBALS['PAGE'] === 'index' ? '' : $GLOBALS['PAGE']) . '?' . http_build_query($q);
}

function normalizar_dni(string $s): string
{
    return preg_replace('/[.\s-]/', '', $s);
}

function dni_valido(string $d): bool
{
    return (bool)preg_match('/^\d{7,8}$/', $d);
}

function usuario_actual(): ?array
{
    static $cache = false;
    if ($cache !== false) {
        return $cache;
    }
    $cache = null;
    if (!empty($_SESSION['uid'])) {
        $st = db()->prepare('SELECT * FROM usuarios WHERE id = ? AND activo = 1');
        $st->execute([$_SESSION['uid']]);
        $u = $st->fetch() ?: null;
        if ($u) {
            // administrador solo si el email fue verificado por Google (el registro con DNI no verifica el email)
            $u['es_admin'] = !empty($u['google_sub']) && email_es_admin($u['email']);
            $cache = $u;
        }
    }
    return $cache;
}

function requerir_login(): array
{
    $u = usuario_actual();
    if (!$u) {
        flash('need_login', 'error');
        redirect('/login');
    }
    return $u;
}

function requerir_admin(): array
{
    $u = requerir_login();
    if (!$u['es_admin']) {
        http_response_code(403);
        exit('403');
    }
    return $u;
}

/** Ingreso con DNI y contraseña. Devuelve [bool exito, clave de mensaje]. */
function intentar_login(string $dniRaw, string $clave): array
{
    $dni = normalizar_dni($dniRaw);
    if (ip_bloqueada()) {
        log_acceso(null, $dni, 'dni', false, 'IP bloqueada');
        return [false, 'err_ip'];
    }
    if (!dni_valido($dni) || $clave === '') {
        log_acceso(null, $dni, 'dni', false, 'datos inválidos');
        return [false, 'err_login'];
    }
    $st = db()->prepare('SELECT * FROM usuarios WHERE dni = ?');
    $st->execute([$dni]);
    $u = $st->fetch();
    // se verifica siempre una contraseña (aunque el DNI no exista) para que el tiempo de respuesta no delate si el DNI está registrado
    $hashFicticio = '$2y$10$lyyLTVZg/FTOlRQ0XxjIE.k4EoUcQRvJLgWWrnA.rzK1BEJOlzG5y';
    $okClave = password_verify($clave, $u['password_hash'] ?? $hashFicticio);

    if ($u && $u['bloqueado_hasta'] && $u['bloqueado_hasta'] > now()) {
        log_acceso((int)$u['id'], $dni, 'dni', false, 'cuenta bloqueada');
        return [false, 'err_locked'];
    }
    if ($u && (int)$u['activo'] && !empty($u['password_hash']) && $okClave) {
        db()->prepare('UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL, ultimo_login = ? WHERE id = ?')->execute([now(), $u['id']]);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$u['id'];
        $_SESSION['lang'] = lang();
        log_acceso((int)$u['id'], $dni, 'dni', true, 'ok');
        return [true, 'ok'];
    }
    if ($u) {
        $n = (int)$u['intentos_fallidos'] + 1;
        $bloq = null;
        if ($n >= MAX_INTENTOS) {
            $bloq = date('Y-m-d H:i:s', time() + BLOQUEO_MINUTOS * 60);
            $n = 0;
        }
        db()->prepare('UPDATE usuarios SET intentos_fallidos = ?, bloqueado_hasta = ? WHERE id = ?')->execute([$n, $bloq, $u['id']]);
    }
    log_acceso($u ? (int)$u['id'] : null, $dni, 'dni', false, 'clave incorrecta');
    return [false, 'err_login'];
}

/** Registro con DNI. Devuelve lista de claves de error (vacía = registrado). */
function registrar_usuario(array $d): array
{
    $err = [];
    $dni = normalizar_dni((string)($d['dni'] ?? ''));
    $nombre = trim((string)($d['nombre'] ?? ''));
    $apellido = trim((string)($d['apellido'] ?? ''));
    $email = strtolower(trim((string)($d['email'] ?? '')));
    $clave = (string)($d['clave'] ?? '');
    $clave2 = (string)($d['clave2'] ?? '');
    if (!dni_valido($dni)) { $err[] = 'e_dni'; }
    if ($nombre === '' || mb_strlen($nombre) > 80) { $err[] = 'e_first'; }
    if ($apellido === '' || mb_strlen($apellido) > 80) { $err[] = 'e_last'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 160) { $err[] = 'e_email'; }
    if (strlen($clave) < 8) { $err[] = 'e_pass_len'; }
    if ($clave !== $clave2) { $err[] = 'e_pass_match'; }
    if ($err) {
        return $err;
    }
    if (email_es_admin($email)) {      // los correos de administrador solo se pueden usar entrando con Google
        return ['e_exists'];
    }
    $st = db()->prepare('SELECT 1 FROM usuarios WHERE dni = ? OR email = ?');
    $st->execute([$dni, $email]);
    if ($st->fetch()) {
        return ['e_exists'];
    }
    db()->prepare('INSERT INTO usuarios (dni, email, nombre, apellido, password_hash, activo, intentos_fallidos, creado_en) VALUES (?,?,?,?,?,?,?,?)')
        ->execute([$dni, $email, $nombre, $apellido, password_hash($clave, PASSWORD_DEFAULT), 1, 0, now()]);
    return [];
}

/** Crea o vincula el usuario a partir de los datos verificados por Google. */
function usuario_desde_google(array $claims): array
{
    $email = strtolower((string)$claims['email']);
    $sub = (string)$claims['sub'];
    $st = db()->prepare('SELECT * FROM usuarios WHERE google_sub = ? OR email = ?');
    $st->execute([$sub, $email]);
    $u = $st->fetch();
    $nombre = mb_substr((string)($claims['given_name'] ?? $claims['name'] ?? ''), 0, 120);
    $apellido = mb_substr((string)($claims['family_name'] ?? ''), 0, 80);
    if ($u) {
        if (empty($u['google_sub'])) {
            // La cuenta se creó con DNI y un email SIN verificar. Google acaba de verificar que este email es de quien entra:
            // se vincula y se anula la contraseña anterior, por si otra persona se había registrado con un email ajeno.
            db()->prepare('UPDATE usuarios SET google_sub = ?, password_hash = NULL, intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?')->execute([$sub, $u['id']]);
        }
        db()->prepare('UPDATE usuarios SET foto_url = ?, ultimo_login = ? WHERE id = ?')
            ->execute([mb_substr((string)($claims['picture'] ?? ''), 0, 300), now(), $u['id']]);
        return $u;
    }
    db()->prepare('INSERT INTO usuarios (email, google_sub, nombre, apellido, foto_url, activo, intentos_fallidos, creado_en, ultimo_login) VALUES (?,?,?,?,?,?,?,?,?)')
        ->execute([$email, $sub, $nombre, $apellido, mb_substr((string)($claims['picture'] ?? ''), 0, 300), 1, 0, now(), now()]);
    $st = db()->prepare('SELECT * FROM usuarios WHERE google_sub = ?');
    $st->execute([$sub]);
    return $st->fetch();
}
