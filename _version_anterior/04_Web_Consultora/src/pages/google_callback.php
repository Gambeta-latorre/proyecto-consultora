<?php
// Regreso de Google: valida, crea o vincula el usuario y abre la sesión.
[$ok, $res] = google_handle_callback();
if (!$ok) {
    log_acceso(null, '(google)', 'google', false, (string)$res);
    flash((string)$res, 'error');
    redirect('/login');
}
$u = usuario_desde_google($res);
if (!(int)$u['activo']) {
    log_acceso((int)$u['id'], (string)$u['email'], 'google', false, 'cuenta desactivada');
    flash('account_off', 'error');
    redirect('/login');
}
session_regenerate_id(true);
$_SESSION['uid'] = (int)$u['id'];
$_SESSION['lang'] = lang();
log_acceso((int)$u['id'], (string)$u['email'], 'google', true, 'ok');
redirect(email_es_admin((string)$u['email']) ? '/admin' : '/panel');
