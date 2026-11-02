<?php
// Datos de la empresa que se usan en varias páginas. Si algo cambia, se cambia solo acá.
const EMPRESA      = 'Berasoft S.R.L.';
const WHATSAPP     = '5491133486017';
const EMAIL        = 'contacto@berasoft.com.ar';
const URL_PATITAS  = 'https://ashab-omega.vercel.app'; // sitio publicado de nuestro cliente

// Servicios: nombre => [descripción, precio de referencia]
const SERVICIOS = [
    'Sitios web'              => ['Página para tu comercio o profesión: quiénes son, qué ofrecen, dónde están y cómo contactarlos.', 'desde $ 350.000'],
    'Turnos online'           => ['Tus clientes reservan solos y el sistema solo muestra los horarios libres. Incluye panel para la recepción.', 'desde $ 500.000'],
    'Tiendas online'          => ['Catálogo con fotos y precios que el cliente arma y envía por WhatsApp.', 'desde $ 600.000'],
    'Hosting y mantenimiento' => ['Dominio, publicación, copias de seguridad y cambios mensuales.', '$ 35.000 por mes'],
];

function h($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
