<?php
// Datos del negocio. Si algo cambia, se cambia solo acá.
const MARCA         = 'ASHAB';
const WHATSAPP      = '5491133486017';                 // atención al cliente
const ADMIN_EMAILS  = ['juancruzceresani@gmail.com'];  // estos Gmail entran como administrador
// Clave del administrador (guardada como hash bcrypt, no en texto). Se puede reemplazar con la variable ADMIN_CLAVE_HASH.
function admin_clave_hash() { return getenv('ADMIN_CLAVE_HASH') ?: '$2y$10$mfhCGT18ctWK7KFUtLKSxuitIkQBjALUveAtZPkqo.r1svZjHSsk6'; }
const MIN_KG        = 100;                             // pedido mayorista mínimo
const MAX_KG        = 5000;                            // pedido mayorista máximo
const MAX_ABIERTOS  = 3;                               // pedidos en curso por cliente
const EMAIL_PEDIDOS = 'juancruzceresani@gmail.com';   // a este mail también llegan los pedidos

// Estados de un pedido. El pago se acuerda por WhatsApp o mail; el sistema no lo gestiona.
const ESTADOS = [
    'solicitada'   => 'Solicitada',
    'en_produccion'=> 'En producción',
    'embarcada'    => 'Embarcada',
    'cerrada'      => 'Cerrada',
    'cancelada'    => 'Cancelada',
];
// Qué estado sigue después de cada uno (lo hace el administrador).
const SIGUIENTE = ['solicitada' => 'en_produccion', 'en_produccion' => 'embarcada', 'embarcada' => 'cerrada'];

// Mensaje ya redactado con el detalle del pedido, para mandarlo por WhatsApp o por mail.
function mensaje_pedido($p) {
    return "Hola, quiero confirmar el pedido " . $p['codigo'] . ":\n"
         . "Yerba: " . $p['producto'] . " (" . $p['presentacion'] . ")\n"
         . "Cantidad: " . (int)$p['cantidad_kg'] . " kg\n"
         . "Destino: " . $p['destino'] . "\n"
         . "Precio por kilo: " . pesos($p['precio_kg']) . "\n"
         . "Total: " . pesos($p['total']) . "\n"
         . "Empresa: " . $p['empresa'] . "\n"
         . "Quiero acordar la forma de pago.";
}
