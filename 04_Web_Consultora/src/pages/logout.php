<?php
if (isset($_SESSION['uid'])) {
    session_regenerate_id(true);
}
$_SESSION = [];
flash('Cerraste sesión.');
redirect('/');
