<?php
$lang = lang();
if (isset($_SESSION['uid'])) {
    session_regenerate_id(true);
}
$_SESSION = [];
$_SESSION['lang'] = $lang;
flash('logged_out');
redirect('/');
