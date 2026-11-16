<?php
// Cerrar sesión: borra los datos de la sesión y vuelve al inicio.
require 'inc/config.php';
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
