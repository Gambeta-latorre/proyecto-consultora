<?php
// Datos de la empresa que se usan en varias páginas. Si algo cambia, se cambia solo acá.
const EMPRESA      = 'Berasoft S.R.L.';
const WHATSAPP     = '5491133486017';
const EMAIL        = 'juancruzceresani@gmail.com';
const URL_ASHAB    = 'https://ashab-yerba.vercel.app'; // sitio publicado de nuestro cliente

// Servicios: nombre => [descripción, precio de referencia]
const SERVICIOS = [
    'Sitios web'              => ['Página para tu comercio o profesión: quiénes son, qué ofrecen, dónde están y cómo contactarlos.', 'desde $ 350.000'],
    'Ventas y pedidos online' => ['Ingreso con Google, pedidos con seña del 50 % y panel de administración para aprobar clientes y seguir cada pedido.', 'desde $ 5.000.000'],
    'Tiendas online'          => ['Catálogo con fotos y precios que el cliente arma y envía por WhatsApp.', 'desde $ 600.000'],
    'Hosting y mantenimiento' => ['Dominio, publicación, copias de seguridad y cambios mensuales.', '$ 35.000 por mes'],
];

function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
