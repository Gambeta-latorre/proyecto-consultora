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
const PROFORMA_DIAS = 5;                               // validez del presupuesto (proforma)

// Estados de un pedido, en orden. La seña del 50 % se acredita antes de producir.
const ESTADOS = [
    'solicitada'      => 'Solicitada',
    'proforma_emitida'=> 'Proforma emitida',
    'sena_informada'  => 'Pago informado',
    'sena_acreditada' => 'Seña acreditada',
    'en_produccion'   => 'En producción',
    'embarcada'       => 'Embarcada',
    'cerrada'         => 'Cerrada',
    'cancelada'       => 'Cancelada',
    'vencida'         => 'Vencida',
];
// Qué estado sigue después de cada uno (lo hace el administrador).
const SIGUIENTE = ['sena_acreditada' => 'en_produccion', 'en_produccion' => 'embarcada', 'embarcada' => 'cerrada'];

// Datos para transferir la seña. Se pueden cambiar con la variable de entorno DATOS_BANCARIOS.
function datos_bancarios() {
    return str_replace('\n', "\n", getenv('DATOS_BANCARIOS') ?: "Titular: ASHAB\nBanco: (completar)\nCBU: (completar)\nAlias: (completar)");
}
