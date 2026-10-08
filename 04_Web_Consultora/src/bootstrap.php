<?php
declare(strict_types=1);
// Arranque del sitio de Rumbo Global Consultores S.A.

define('SITE_NAME', 'Rumbo Global Consultores S.A.');
define('MAX_INTENTOS', 5);
define('BLOQUEO_MINUTOS', 15);
define('PROYECTO_URL', getenv('PROYECTO_URL') ?: '#');

require __DIR__ . '/core.php';

define('WHATSAPP_NUMBER', env('WHATSAPP_NUMBER', '5491133486017'));   // 54 9 11 3348-6017

send_security_headers();
if (defined('NO_SESSION')) {          // /install corre antes de que existan las tablas
    $_SESSION = [];
} else {
    start_session('RGSID');
}

function wa_display(): string
{
    return '+54 9 11 3348-6017';
}

function whatsapp_url(string $msg = ''): string
{
    $msg = $msg !== '' ? $msg : 'Hola Rumbo Global, quiero consultar por un proyecto de exportación.';
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($msg);
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
            $u['es_admin'] = email_es_admin($u['email']);
            $cache = $u;
        }
    }
    return $cache;
}

function requerir_login(): array
{
    $u = usuario_actual();
    if (!$u) {
        flash('Iniciá sesión para continuar.', 'error');
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

/** Ingreso con DNI y contraseña. Devuelve [bool exito, string mensaje]. */
function intentar_login(string $dniRaw, string $clave): array
{
    $dni = normalizar_dni($dniRaw);
    if (ip_bloqueada()) {
        log_acceso(null, $dni, 'dni', false, 'IP bloqueada');
        return [false, 'Demasiados intentos desde esta conexión. Probá más tarde.'];
    }
    if (!dni_valido($dni) || $clave === '') {
        log_acceso(null, $dni, 'dni', false, 'datos inválidos');
        return [false, 'DNI o contraseña incorrectos.'];
    }
    $st = db()->prepare('SELECT * FROM usuarios WHERE dni = ?');
    $st->execute([$dni]);
    $u = $st->fetch();
    // se verifica siempre una contraseña (aunque el DNI no exista) para que el tiempo de respuesta no delate si el DNI está registrado
    $hashFicticio = '$2y$10$lyyLTVZg/FTOlRQ0XxjIE.k4EoUcQRvJLgWWrnA.rzK1BEJOlzG5y';
    $okClave = password_verify($clave, $u['password_hash'] ?? $hashFicticio);

    if ($u && $u['bloqueado_hasta'] && $u['bloqueado_hasta'] > now()) {
        log_acceso((int)$u['id'], $dni, 'dni', false, 'cuenta bloqueada');
        return [false, 'Cuenta bloqueada temporalmente por intentos fallidos. Probá más tarde.'];
    }
    if ($u && (int)$u['activo'] && !empty($u['password_hash']) && $okClave) {
        db()->prepare('UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL, ultimo_login = ? WHERE id = ?')->execute([now(), $u['id']]);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$u['id'];
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
    return [false, 'DNI o contraseña incorrectos.'];
}

/** Registro con DNI. Devuelve lista de errores (vacía = registrado). */
function registrar_usuario(array $d): array
{
    $err = [];
    $dni = normalizar_dni((string)($d['dni'] ?? ''));
    $nombre = trim((string)($d['nombre'] ?? ''));
    $apellido = trim((string)($d['apellido'] ?? ''));
    $email = strtolower(trim((string)($d['email'] ?? '')));
    $clave = (string)($d['clave'] ?? '');
    $clave2 = (string)($d['clave2'] ?? '');
    if (!dni_valido($dni)) { $err[] = 'El DNI debe tener 7 u 8 números.'; }
    if ($nombre === '' || mb_strlen($nombre) > 80) { $err[] = 'Ingresá tu nombre.'; }
    if ($apellido === '' || mb_strlen($apellido) > 80) { $err[] = 'Ingresá tu apellido.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 160) { $err[] = 'El email no es válido.'; }
    if (strlen($clave) < 8) { $err[] = 'La contraseña debe tener al menos 8 caracteres.'; }
    if ($clave !== $clave2) { $err[] = 'Las contraseñas no coinciden.'; }
    if ($err) {
        return $err;
    }
    $st = db()->prepare('SELECT 1 FROM usuarios WHERE dni = ? OR email = ?');
    $st->execute([$dni, $email]);
    if ($st->fetch()) {
        return ['Ya existe una cuenta con ese DNI o email.'];
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
        db()->prepare('UPDATE usuarios SET google_sub = ?, foto_url = ?, ultimo_login = ? WHERE id = ?')
            ->execute([$sub, mb_substr((string)($claims['picture'] ?? ''), 0, 300), now(), $u['id']]);
        return $u;
    }
    db()->prepare('INSERT INTO usuarios (email, google_sub, nombre, apellido, foto_url, activo, intentos_fallidos, creado_en, ultimo_login) VALUES (?,?,?,?,?,?,?,?,?)')
        ->execute([$email, $sub, $nombre, $apellido, mb_substr((string)($claims['picture'] ?? ''), 0, 300), 1, 0, now(), now()]);
    $st = db()->prepare('SELECT * FROM usuarios WHERE google_sub = ?');
    $st->execute([$sub]);
    return $st->fetch();
}
