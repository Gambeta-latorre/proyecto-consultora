<?php
// Datos del negocio y funciones chicas que usan todas las páginas.
session_start(); // abre la sesión: así el sitio recuerda quién inició sesión entre una página y otra

const MARCA    = 'ASHAB';
const WHATSAPP = '5491133486017'; // número de atención al cliente (sin + ni espacios)
const EMAIL    = 'juancruzceresani@gmail.com';

// Escapa el texto antes de mostrarlo en la página (evita que se inyecte HTML).
function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// 7500 -> "$ 7.500"
function pesos($numero) {
    return '$ ' . number_format((float)$numero, 0, ',', '.');
}

// Arma el enlace de WhatsApp. Si se pasa un texto, el mensaje aparece ya escrito.
function whatsapp($texto = '') {
    $enlace = 'https://wa.me/' . WHATSAPP;
    if ($texto !== '') $enlace .= '?text=' . rawurlencode($texto);
    return $enlace;
}

// Devuelve el usuario que inició sesión (id, nombre y email) o null si nadie inició sesión.
function usuario_actual() {
    return $_SESSION['usuario'] ?? null;
}
