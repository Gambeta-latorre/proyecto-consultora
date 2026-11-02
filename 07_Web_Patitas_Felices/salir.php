<?php
// Cierra la sesión y vuelve al inicio.
$raiz = '';
require 'inc/funciones.php';
session_destroy();
ir('index.php');
