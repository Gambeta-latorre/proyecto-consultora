<?php
// Cierra la sesión y vuelve al inicio.
$raiz = '';
require 'inc/funciones.php';
$_SESSION = [];
session_destroy();
ir('index.php');
