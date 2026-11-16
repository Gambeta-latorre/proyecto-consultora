<?php
// Datos de la empresa que se usan en varias páginas. Si algo cambia, se cambia solo acá.
if (session_status() === PHP_SESSION_NONE) session_start(); // abre la sesión (una sola vez)

const EMPRESA      = 'Berasoft S.R.L.';
const WHATSAPP     = '5491133486017';
const EMAIL        = 'juancruzceresani@gmail.com';
const URL_ASHAB    = 'https://ashab-yerba.vercel.app'; // sitio publicado de nuestro cliente

// Cuenta del socio para entrar al panel. La web no tiene base de datos: la contraseña se guarda
// encriptada con password_hash (no se puede leer). Contraseña: berasoft2026
const ADMIN_EMAIL  = 'juancruzceresani@gmail.com';
const ADMIN_HASH   = '$2y$10$ocY7Fmr5tH699YFcBsvLGOhqPCBxcRZWK15VAMJ0uwtkauezk8Una';

// Servicios: nombre => [descripción, precio de referencia]
const SERVICIOS = [
    'Sitios web'              => ['Página para tu comercio o profesión: quiénes son, qué ofrecen, dónde están y cómo contactarlos.', 'desde $ 350.000'],
    'Ventas y pedidos online' => ['Catálogo con los productos y precios guardados en una base de datos MySQL, y pedidos por WhatsApp con el mensaje ya escrito.', 'desde $ 5.000.000'],
    'Tiendas online'          => ['Catálogo con fotos y precios que el cliente arma y envía por WhatsApp.', 'desde $ 600.000'],
    'Hosting y mantenimiento' => ['Dominio, publicación, copias de seguridad y cambios mensuales.', '$ 35.000 por mes'],
];

// Escapa el texto antes de mostrarlo en la página.
function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// Arma el enlace de WhatsApp. Si se pasa un texto, el mensaje aparece ya escrito.
function whatsapp($texto = '') {
    $enlace = 'https://wa.me/' . WHATSAPP;
    if ($texto !== '') $enlace .= '?text=' . rawurlencode($texto);
    return $enlace;
}

// true si el socio inició sesión.
function logueado() {
    return !empty($_SESSION['admin']);
}
