<?php
// Inicia el ingreso con Google (el botón de /login envía un POST con token CSRF).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok()) {
    flash('e_csrf', 'error');
    redirect('/login');
}
if (!google_enabled()) {
    flash('login_unavailable', 'error');
    redirect('/login');
}
if (ip_bloqueada()) {
    flash('login_blocked', 'error');
    redirect('/login');
}
google_start(lang());
