<?php
// Ingreso con Google (OAuth 2.0). Esta página hace dos cosas:
//   1) Si llega por POST desde el botón: manda al visitante a Google.
//   2) Si vuelve de Google con un "code": lo cambia por los datos de la cuenta y abre la sesión.
$raiz = '';
require 'inc/funciones.php';

$id     = getenv('GOOGLE_CLIENT_ID');
$secret = getenv('GOOGLE_CLIENT_SECRET');
$urlGoogle = getenv('GOOGLE_AUTH_URL')  ?: 'https://accounts.google.com/o/oauth2/v2/auth';
$urlToken  = getenv('GOOGLE_TOKEN_URL') ?: 'https://oauth2.googleapis.com/token';

function fallo($motivo) {
    registrar_acceso(null, '(google)', false, $motivo);
    aviso('No pudimos validar tu cuenta de Google. Probá de nuevo.', 'error');
    ir('login.php');
}

if (!google_activo()) ir('login.php');

// Paso 1: ir a Google
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (ip_bloqueada()) { aviso('Demasiados intentos. Esperá 15 minutos.', 'error'); ir('login.php'); }
    $_SESSION['g_state'] = bin2hex(random_bytes(16)); // evita que alguien nos mande una respuesta ajena
    header('Location: ' . $urlGoogle . '?' . http_build_query([
        'client_id' => $id, 'redirect_uri' => url_google(), 'response_type' => 'code',
        'scope' => 'openid email profile', 'state' => $_SESSION['g_state'], 'prompt' => 'select_account',
    ]));
    exit;
}

// Paso 2: Google nos devuelve al visitante con un código
if (isset($_GET['error']) || empty($_GET['code'])) fallo('Google canceló');
$state = $_SESSION['g_state'] ?? '';
unset($_SESSION['g_state']);
if ($state === '' || !hash_equals($state, $_GET['state'] ?? '')) fallo('state inválido');

// Cambiamos el código por los datos de la cuenta (el servidor habla directo con Google, por HTTPS)
$respuesta = @file_get_contents($urlToken, false, stream_context_create(['http' => [
    'method' => 'POST', 'timeout' => 10, 'ignore_errors' => true,
    'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
    'content' => http_build_query(['code' => $_GET['code'], 'client_id' => $id, 'client_secret' => $secret,
                                   'redirect_uri' => url_google(), 'grant_type' => 'authorization_code']),
]]));
$token = json_decode((string)$respuesta, true);
$partes = explode('.', $token['id_token'] ?? '');
$datos = count($partes) === 3 ? json_decode(base64_decode(strtr($partes[1], '-_', '+/')), true) : null;

// Controles de seguridad: emisor, destinatario, vencimiento y email verificado
if (!$datos || !in_array($datos['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true)
    || ($datos['aud'] ?? '') !== $id || ($datos['exp'] ?? 0) < time() - 60
    || empty($datos['sub']) || empty($datos['email'])) fallo('token inválido');
if (!in_array($datos['email_verified'] ?? false, [true, 'true'], true)) fallo('email sin verificar');

iniciar_sesion(usuario_desde_email($datos['email'], $datos['name'] ?? '', $datos['sub']), 'google');
