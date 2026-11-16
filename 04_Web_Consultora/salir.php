<?php
// Cerrar sesión: borra la sesión y vuelve al inicio.
require_once 'inc/datos.php';
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
