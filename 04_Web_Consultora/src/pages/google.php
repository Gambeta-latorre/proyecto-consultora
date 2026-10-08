<?php
// Inicia el ingreso con Google (el botón de /login envía un POST con token CSRF).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok()) {
    flash('La sesión expiró. Volvé a intentar.', 'error');
    redirect('/login');
}
if (!google_enabled()) {
    flash('El ingreso con Google todavía no está configurado en este sitio.', 'error');
    redirect('/login');
}
if (ip_bloqueada()) {
    flash('Demasiados intentos. Probá más tarde.', 'error');
    redirect('/login');
}
google_start('es');
