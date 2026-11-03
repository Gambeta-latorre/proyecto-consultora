<?php
// Se incluye al principio de TODAS las páginas.
date_default_timezone_set('America/Argentina/Buenos_Aires');
require __DIR__ . '/config.php';
require __DIR__ . '/conexion.php';
session_start();

// Escapa texto antes de mostrarlo en HTML (evita XSS).
function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// Redirige a otra página y corta la ejecución. $raiz es '' o '../' según la carpeta de la página.
function ir($pagina) {
    global $raiz;
    header('Location: ' . $raiz . $pagina);
    exit;
}

function pesos($n) {
    return '$ ' . number_format((float)$n, 0, ',', '.');
}

// Mensaje que se muestra una sola vez en la página siguiente.
function aviso($texto = null, $tipo = 'ok') {
    if ($texto !== null) { $_SESSION['aviso'] = [$texto, $tipo]; return null; }
    $a = $_SESSION['aviso'] ?? null;
    unset($_SESSION['aviso']);
    return $a;
}

// ---- Protección CSRF: cada formulario lleva un código secreto de la sesión ----
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

function csrf_campo() {
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

// Si llega un POST sin el código correcto, se rechaza antes de hacer nada.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Formulario vencido. Volvé atrás y recargá la página.');
}

// ---- Usuarios ----
function usuario_actual() {
    global $db;
    if (empty($_SESSION['uid'])) return null;
    $st = $db->prepare('SELECT * FROM usuarios WHERE id = ? AND activo = 1');
    $st->execute([$_SESSION['uid']]);
    return $st->fetch() ?: null;
}

function exigir_login() {
    $u = usuario_actual();
    if (!$u) ir('login.php');
    return $u;
}

// Un cliente debe completar los datos de su empresa antes de usar el sitio.
function exigir_perfil() {
    $u = exigir_login();
    if ($u['rol'] !== 'admin' && !$u['perfil_completo']) ir('perfil.php');
    return $u;
}

function exigir_admin() {
    $u = exigir_login();
    if ($u['rol'] !== 'admin') ir('panel.php');
    return $u;
}

function ip_cliente() {
    return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
}

// Cada intento de ingreso queda guardado con su IP (auditoría).
function registrar_acceso($usuario_id, $email, $exito, $motivo) {
    global $db;
    $db->prepare('INSERT INTO accesos (usuario_id, email, exito, motivo, ip, fecha) VALUES (?, ?, ?, ?, ?, ?)')
       ->execute([$usuario_id, $email, $exito ? 1 : 0, $motivo, ip_cliente(), date('Y-m-d H:i:s')]);
}

// Bloqueo: 20 intentos fallidos desde la misma IP en 15 minutos.
function ip_bloqueada() {
    global $db;
    $st = $db->prepare('SELECT COUNT(*) FROM accesos WHERE ip = ? AND exito = 0 AND fecha > ?');
    $st->execute([ip_cliente(), date('Y-m-d H:i:s', time() - 15 * 60)]);
    return $st->fetchColumn() >= 20;
}

// Busca al usuario por su email o lo crea (las cuentas nuevas quedan "pendientes" hasta que el admin las apruebe).
function usuario_desde_email($email, $nombre, $google_sub = null) {
    global $db;
    $email = strtolower($email);
    $es_admin = in_array($email, ADMIN_EMAILS, true);
    $st = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
    $st->execute([$email]);
    $u = $st->fetch();
    if (!$u) {
        $db->prepare('INSERT INTO usuarios (email, google_sub, nombre, rol, cuenta, perfil_completo, activo, creado_en) VALUES (?, ?, ?, ?, ?, ?, 1, ?)')
           ->execute([$email, $google_sub, $nombre, $es_admin ? 'admin' : 'cliente', $es_admin ? 'aprobada' : 'pendiente', $es_admin ? 1 : 0, date('Y-m-d H:i:s')]);
        $st->execute([$email]);
        $u = $st->fetch();
    }
    $db->prepare('UPDATE usuarios SET nombre = ?, rol = ?, ultimo_ingreso = ? WHERE id = ?')
       ->execute([$nombre ?: $u['nombre'], $es_admin ? 'admin' : $u['rol'], date('Y-m-d H:i:s'), $u['id']]);
    return $u;
}

// Abre la sesión de un usuario ya validado.
function iniciar_sesion($u, $metodo) {
    if (!$u['activo']) {
        registrar_acceso($u['id'], $u['email'], false, 'cuenta desactivada');
        aviso('Tu cuenta está desactivada. Escribinos por WhatsApp.', 'error');
        ir('login.php');
    }
    session_regenerate_id(true); // nuevo id de sesión: evita robo de sesión
    $_SESSION['uid'] = $u['id'];
    registrar_acceso($u['id'], $u['email'], true, $metodo);
    ir($u['rol'] === 'admin' ? 'admin/index.php' : 'panel.php');
}

// ---- Ingreso con Google (OAuth 2.0 / OpenID Connect) ----
function google_activo() {
    return getenv('GOOGLE_CLIENT_ID') && getenv('GOOGLE_CLIENT_SECRET');
}

// Dirección completa de google.php: es la que se registra en Google Cloud como "URI de redireccionamiento".
function url_google() {
    $https = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' || !empty($_SERVER['HTTPS']);
    $carpeta = rtrim(dirname(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
    return getenv('SITIO_URL') ? rtrim(getenv('SITIO_URL'), '/') . '/google.php'
                               : ($https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $carpeta . '/google.php';
}

// Prueba local: sin claves de Google, solo en esta computadora se puede entrar escribiendo un email.
function modo_prueba() {
    return !google_activo() && !getenv('DATABASE_URL') && in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
}

// ---- Pedidos ----
// Total y seña (50 %) calculados en centavos para no tener errores de redondeo.
function calcular_totales($kg, $precio_kg) {
    $total = (int)round($kg * $precio_kg * 100);
    $sena = intdiv($total + 1, 2);
    return [$total / 100, $sena / 100];
}

// Las proformas que pasaron su fecha de vencimiento pasan a "vencida".
function vencer_proformas() {
    global $db;
    $db->prepare("UPDATE pedidos SET estado = 'vencida' WHERE estado = 'proforma_emitida' AND vence_en < ?")
       ->execute([date('Y-m-d H:i:s')]);
}

function cargar_pedido($codigo, $usuario_id = null) {
    global $db;
    $sql = 'SELECT p.*, pr.nombre AS producto, pr.presentacion, u.empresa, u.email, u.whatsapp
            FROM pedidos p JOIN productos pr ON pr.id = p.producto_id JOIN usuarios u ON u.id = p.usuario_id WHERE p.codigo = ?';
    $args = [$codigo];
    if ($usuario_id !== null) { $sql .= ' AND p.usuario_id = ?'; $args[] = $usuario_id; }
    $st = $db->prepare($sql);
    $st->execute($args);
    return $st->fetch() ?: null;
}

function etiqueta_estado($estado) {
    return '<span class="estado ' . h($estado) . '">' . h(ESTADOS[$estado] ?? $estado) . '</span>';
}
