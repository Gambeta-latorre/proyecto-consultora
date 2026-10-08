<?php
// Regreso de Google: valida, crea o vincula el usuario y abre la sesión.
[$ok, $res] = google_handle_callback();
if (!$ok) {
    log_acceso(null, '(google)', 'google', false, (string)$res);
    $msgs = ['login_err_denied' => 'Cancelaste el ingreso con Google.', 'login_err_unverified' => 'Tu cuenta de Google debe tener el email verificado.'];
    flash($msgs[$res] ?? 'No pudimos verificar el inicio de sesión. Intentá de nuevo.', 'error');
    redirect('/login');
}
$u = usuario_desde_google($res);
if (!(int)$u['activo']) {
    log_acceso((int)$u['id'], (string)$u['email'], 'google', false, 'cuenta desactivada');
    flash('Tu cuenta está desactivada. Escribinos por WhatsApp.', 'error');
    redirect('/login');
}
session_regenerate_id(true);
$_SESSION['uid'] = (int)$u['id'];
log_acceso((int)$u['id'], (string)$u['email'], 'google', true, 'ok');
redirect(email_es_admin((string)$u['email']) ? '/admin' : '/panel');
