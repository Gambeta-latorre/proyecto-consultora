<?php
// Se incluye al principio de TODAS las páginas.
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');
require __DIR__ . '/conexion.php';

const WHATSAPP = '5491133486017'; // número de atención al cliente
const MAX_TURNOS = 2;             // turnos reservados a la vez por cliente
const ESPECIES = ['Perro', 'Gato', 'Ovino', 'Bovino']; // tipos de animal que atendemos

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

// Devuelve el usuario que inició sesión (leído de la base) o null.
function usuario_actual() {
    global $db;
    if (empty($_SESSION['uid'])) return null;
    $st = $db->prepare('SELECT * FROM usuarios WHERE id = ? AND activo = 1');
    $st->execute([$_SESSION['uid']]);
    return $st->fetch() ?: null;
}

function exigir_login() {
    $u = usuario_actual();
    if (!$u) ir('ingresar.php');
    return $u;
}

function exigir_admin() {
    $u = exigir_login();
    if ($u['rol'] !== 'admin') ir('turnos.php');
    return $u;
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

// Intenta iniciar sesión. Devuelve '' si salió bien o el mensaje de error.
function intentar_ingreso($email, $clave) {
    global $db;

    // Bloqueo: 5 intentos fallidos con el mismo email en 15 minutos.
    $st = $db->prepare('SELECT COUNT(*) FROM accesos WHERE email = ? AND exito = 0 AND fecha > NOW() - INTERVAL 15 MINUTE');
    $st->execute([$email]);
    if ($st->fetchColumn() >= 5) return 'Demasiados intentos. Esperá 15 minutos.';

    $st = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
    $st->execute([$email]);
    $u = $st->fetch();
    $ok = $u && password_verify($clave, $u['clave_hash']);

    // Se guarda cada intento con su IP (auditoría)
    $db->prepare('INSERT INTO accesos (usuario_id, email, exito, ip) VALUES (?, ?, ?, ?)')
       ->execute([$u['id'] ?? null, $email, $ok ? 1 : 0, $_SERVER['REMOTE_ADDR'] ?? '']);

    if (!$ok) return 'Email o contraseña incorrectos.';
    if (!$u['activo']) return 'Tu cuenta está bloqueada. Escribinos por WhatsApp.';

    session_regenerate_id(true); // nuevo id de sesión: evita robo de sesión
    $_SESSION['uid'] = $u['id'];
    return '';
}

function pesos($n) {
    return '$ ' . number_format((float)$n, 0, ',', '.');
}

// Un día sirve para sacar turno si es desde mañana, dentro de 30 días y no es domingo.
function fecha_valida($fecha) {
    $d = DateTime::createFromFormat('!Y-m-d', $fecha);
    if (!$d || $d->format('Y-m-d') !== $fecha) return false;    // formato incorrecto
    $dias = (int)(new DateTime('today'))->diff($d)->format('%r%a'); // días desde hoy (negativo si ya pasó)
    return $dias >= 1 && $dias <= 30 && $d->format('N') != 7;  // N = 7 es domingo
}

// Horarios de atención: lunes a viernes de 9 a 18, sábados de 9 a 13. Un turno cada 30 minutos.
function horarios_del_dia($fecha) {
    $cierre = date('N', strtotime($fecha)) == 6 ? 13 : 18;
    $horas = [];
    for ($t = 9 * 60; $t < $cierre * 60; $t += 30) {
        $horas[] = sprintf('%02d:%02d', intdiv($t, 60), $t % 60);
    }
    return $horas;
}

// Horarios todavía libres de un día (los que no tienen un turno reservado o atendido).
function horarios_libres($fecha) {
    global $db;
    $st = $db->prepare("SELECT TIME_FORMAT(hora, '%H:%i') FROM turnos WHERE fecha = ? AND estado <> 'cancelado'");
    $st->execute([$fecha]);
    return array_values(array_diff(horarios_del_dia($fecha), $st->fetchAll(PDO::FETCH_COLUMN)));
}
