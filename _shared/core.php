<?php
declare(strict_types=1);
/**
 * Núcleo compartido de los dos sitios (Rumbo Global y ASHAB).
 * Esta es la ÚNICA copia editable: `python _build/sync_core.py` la copia a cada sitio.
 */

const SESSION_TTL = 604800; // 7 días

function env(string $k, ?string $d = null): ?string
{
    $v = getenv($k);
    if ($v === false || $v === '') {
        $v = $_ENV[$k] ?? $_SERVER[$k] ?? null;
    }
    return ($v === null || $v === '' || $v === false) ? $d : (string)$v;
}

function h($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function base_url(): string
{
    if ($b = env('APP_URL')) {
        return rtrim($b, '/');
    }
    if ($v = env('VERCEL_PROJECT_PRODUCTION_URL')) {
        return 'https://' . $v;
    }
    return (is_https() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/* ---------------------------------------------------------------- Base de datos */

function db_connection_params(): array
{
    if ($dsn = env('DB_DSN')) {                       // pruebas locales: sqlite:ruta
        return [$dsn, env('DB_USER'), env('DB_PASS'), false];
    }
    $url = env('DATABASE_URL_UNPOOLED') ?: env('POSTGRES_URL_NON_POOLING') ?: env('DATABASE_URL') ?: env('POSTGRES_URL');
    if ($url && preg_match('#^postgres(ql)?://#', $url)) {   // Vercel + Neon/Supabase (Postgres)
        $p = parse_url($url);
        parse_str($p['query'] ?? '', $q);
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;sslmode=%s', $p['host'] ?? 'localhost', (int)($p['port'] ?? 5432),
            ltrim($p['path'] ?? '/postgres', '/'), $q['sslmode'] ?? 'require');
        $host = $p['host'] ?? '';
        if (str_ends_with($host, '.neon.tech')) {         // Neon identifica el proyecto por el nombre del host (SNI) y por 'endpoint'
            $dsn .= ";options='endpoint=" . preg_replace('/-pooler$/', '', explode('.', $host)[0]) . "'";
        }
        $pooled = str_contains($host, '-pooler') || str_contains($host, 'pgbouncer');
        return [$dsn, urldecode($p['user'] ?? ''), urldecode($p['pass'] ?? ''), $pooled];
    }
    $dsn = 'mysql:host=' . env('DB_HOST', 'localhost') . ';port=' . env('DB_PORT', '3306')
        . ';dbname=' . env('DB_NAME', 'app') . ';charset=utf8mb4';
    return [$dsn, env('DB_USER', 'root'), env('DB_PASS', ''), false];
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        [$dsn, $user, $pass, $emulate] = db_connection_params();
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => $emulate,
        ]);
    }
    return $pdo;
}

function db_driver(): string
{
    return db()->getAttribute(PDO::ATTR_DRIVER_NAME);
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/* ---------------------------------------------------------------- Sesiones en base de datos
 * Vercel ejecuta cada página en una función distinta: las sesiones de archivo se perderían. */

final class DbSessions implements SessionHandlerInterface
{
    private array $seen = [];

    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }

    public function read(string $id): string|false
    {
        $st = db()->prepare('SELECT data, updated_at FROM sessions WHERE id = ? AND updated_at > ?');
        $st->execute([$id, time() - SESSION_TTL]);
        $row = $st->fetch();
        $this->seen[$id] = $row ? [$row['data'], (int)$row['updated_at']] : null;
        return $row ? (string)base64_decode($row['data']) : '';
    }

    public function write(string $id, string $data): bool
    {
        $enc = base64_encode($data);
        $prev = $this->seen[$id] ?? null;
        if ($prev && $prev[0] === $enc && time() - $prev[1] < 300) {
            return true;                                  // sin cambios: no escribir
        }
        $up = db()->prepare('UPDATE sessions SET data = ?, updated_at = ? WHERE id = ?');
        $up->execute([$enc, time(), $id]);
        if ($up->rowCount() === 0) {
            try {
                db()->prepare('INSERT INTO sessions (id, data, updated_at) VALUES (?,?,?)')->execute([$id, $enc, time()]);
            } catch (PDOException $e) {
                $up->execute([$enc, time(), $id]);        // la fila ya existía (mismo segundo)
            }
        }
        if (random_int(1, 100) === 1) {
            $this->gc(SESSION_TTL);
        }
        return true;
    }

    public function destroy(string $id): bool
    {
        db()->prepare('DELETE FROM sessions WHERE id = ?')->execute([$id]);
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        $st = db()->prepare('DELETE FROM sessions WHERE updated_at < ?');
        $st->execute([time() - $max_lifetime]);
        return $st->rowCount();
    }
}

function start_session(string $name): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'httponly' => true,
        'samesite' => 'Lax', 'secure' => is_https(),
    ]);
    session_name($name);
    session_set_save_handler(new DbSessions(), true);
    session_start();
}

/* ---------------------------------------------------------------- Seguridad básica */

function send_security_headers(): void
{
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    if (is_https()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https://lh3.googleusercontent.com; "
        . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; "
        . "script-src 'self' 'unsafe-inline'; form-action 'self' https://accounts.google.com; frame-ancestors 'none'; base-uri 'self'");
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_ok(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

/** Guarda un mensaje (o una clave de traducción) para mostrarlo en la próxima página. */
function flash(?string $msg = null, string $tipo = 'ok')
{
    if ($msg !== null) {
        $_SESSION['flash'] = [$msg, $tipo];
        return null;
    }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function ip_cliente(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (env('VERCEL') || env('TRUST_PROXY')) {
        $fwd = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? '';
        $first = trim(explode(',', $fwd)[0] ?? '');
        if ($first !== '' && filter_var($first, FILTER_VALIDATE_IP)) {
            $ip = $first;
        }
    }
    return substr($ip, 0, 45);
}

function pais_cliente(): string
{
    return substr(strtoupper($_SERVER['HTTP_X_VERCEL_IP_COUNTRY'] ?? $_SERVER['HTTP_CF_IPCOUNTRY'] ?? ''), 0, 2);
}

function log_acceso(?int $uid, string $identificador, string $metodo, bool $ok, string $motivo = ''): void
{
    db()->prepare('INSERT INTO log_accesos (usuario_id, identificador, metodo, exito, motivo, ip, pais, user_agent, fecha) VALUES (?,?,?,?,?,?,?,?,?)')
        ->execute([$uid, substr($identificador, 0, 120), $metodo, $ok ? 1 : 0, substr($motivo, 0, 120), ip_cliente(),
            pais_cliente(), substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250), now()]);
}

/** Más de 20 intentos fallidos desde la misma IP en 15 minutos. */
function ip_bloqueada(): bool
{
    $st = db()->prepare('SELECT COUNT(*) FROM log_accesos WHERE ip = ? AND exito = 0 AND fecha > ?');
    $st->execute([ip_cliente(), date('Y-m-d H:i:s', time() - 900)]);
    return (int)$st->fetchColumn() >= 20;
}

function admin_emails(): array
{
    return array_values(array_filter(array_map(fn($e) => strtolower(trim($e)), explode(',', env('ADMIN_EMAILS', '') ?? ''))));
}

function email_es_admin(?string $email): bool
{
    return $email !== null && in_array(strtolower($email), admin_emails(), true);
}

/* ---------------------------------------------------------------- Inicio de sesión con Google (OpenID Connect) */

function google_cfg(): array
{
    return [
        'id' => env('GOOGLE_CLIENT_ID'), 'secret' => env('GOOGLE_CLIENT_SECRET'),
        'auth' => env('GOOGLE_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth'),
        'token' => env('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token'),
    ];
}

function google_enabled(): bool
{
    $c = google_cfg();
    return !empty($c['id']) && !empty($c['secret']);
}

function google_redirect_uri(): string
{
    return base_url() . '/google_callback';
}

function google_start(string $lang = 'en'): never
{
    $c = google_cfg();
    $_SESSION['g_state'] = bin2hex(random_bytes(24));
    $_SESSION['g_nonce'] = bin2hex(random_bytes(16));
    $_SESSION['g_time'] = time();
    redirect($c['auth'] . '?' . http_build_query([
        'client_id' => $c['id'], 'redirect_uri' => google_redirect_uri(), 'response_type' => 'code',
        'scope' => 'openid email profile', 'state' => $_SESSION['g_state'], 'nonce' => $_SESSION['g_nonce'],
        'prompt' => 'select_account', 'hl' => $lang,
    ]));
}

function http_post_form(string $url, array $fields): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($fields), CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    $j = is_string($body) ? json_decode($body, true) : null;
    return is_array($j) ? $j : null;
}

function jwt_payload(string $jwt): ?array
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return null;
    }
    $json = base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', (4 - strlen($parts[1]) % 4) % 4));
    $j = $json ? json_decode($json, true) : null;
    return is_array($j) ? $j : null;
}

/**
 * Procesa el regreso de Google. Devuelve [true, claims] o [false, claveDeError].
 * El id_token llega directo desde el endpoint de Google por TLS, por lo que se validan
 * emisor, audiencia, vencimiento, nonce y email verificado (guía oficial de Google).
 */
function google_handle_callback(): array
{
    $c = google_cfg();
    if (isset($_GET['error'])) {
        return [false, 'login_err_denied'];
    }
    $state = (string)($_GET['state'] ?? '');
    $ok = $state !== '' && hash_equals((string)($_SESSION['g_state'] ?? ''), $state) && time() - (int)($_SESSION['g_time'] ?? 0) < 600;
    $nonce = (string)($_SESSION['g_nonce'] ?? '');
    unset($_SESSION['g_state'], $_SESSION['g_nonce'], $_SESSION['g_time']);
    $code = (string)($_GET['code'] ?? '');
    if (!$ok || $code === '') {
        return [false, 'login_err_state'];
    }
    $tok = http_post_form($c['token'], [
        'code' => $code, 'client_id' => $c['id'], 'client_secret' => $c['secret'],
        'redirect_uri' => google_redirect_uri(), 'grant_type' => 'authorization_code',
    ]);
    $claims = isset($tok['id_token']) ? jwt_payload((string)$tok['id_token']) : null;
    if (!$claims) {
        return [false, 'login_err_state'];
    }
    $aud = $claims['aud'] ?? '';
    $audOk = is_array($aud) ? in_array($c['id'], $aud, true) : $aud === $c['id'];
    $issOk = in_array($claims['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true);
    $expOk = (int)($claims['exp'] ?? 0) > time() - 60;
    $nonceOk = $nonce !== '' && hash_equals($nonce, (string)($claims['nonce'] ?? ''));
    if (!$audOk || !$issOk || !$expOk || !$nonceOk || empty($claims['sub']) || empty($claims['email'])) {
        return [false, 'login_err_state'];
    }
    $verified = $claims['email_verified'] ?? false;
    if (!($verified === true || $verified === 'true')) {
        return [false, 'login_err_unverified'];
    }
    return [true, $claims];
}

/* ---------------------------------------------------------------- Exportar CSV (para el administrador) */

function csv_download(string $filename, array $header, array $rows): never
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");                        // BOM para que Excel lea las tildes
    fputcsv($out, $header);
    foreach ($rows as $r) {
        // evita inyección de fórmulas en Excel
        fputcsv($out, array_map(fn($v) => is_string($v) && $v !== '' && strpos("=+-@\t\r", $v[0]) !== false ? "'" . $v : $v, array_values($r)));
    }
    fclose($out);
    exit;
}

/* ---------------------------------------------------------------- Instalador de tablas */

function run_sql_file(string $file): int
{
    $sql = (string)file_get_contents($file);
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    $n = 0;
    foreach (preg_split('/;\s*(\r?\n|$)/', $sql) as $stmt) {
        $stmt = trim($stmt);
        if ($stmt !== '') {
            db()->exec($stmt);
            $n++;
        }
    }
    return $n;
}
