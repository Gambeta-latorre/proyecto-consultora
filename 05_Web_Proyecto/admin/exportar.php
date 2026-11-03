<?php
// Descarga una tabla en CSV para abrir con Excel (separador ";" y BOM para que respete los acentos).
$raiz = '../';
require '../inc/funciones.php';
exigir_admin();

$tablas = [
    'usuarios'  => "SELECT creado_en, nombre, email, empresa, cuit, ciudad, whatsapp, cuenta, activo FROM usuarios WHERE rol = 'cliente' ORDER BY id",
    'pedidos'   => 'SELECT p.codigo, p.creado_en, u.empresa, pr.nombre AS yerba, p.cantidad_kg, p.destino, p.precio_kg, p.total, p.sena, p.estado, p.embarcacion
                    FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id JOIN productos pr ON pr.id = p.producto_id ORDER BY p.id',
    'accesos'   => 'SELECT fecha, email, exito, motivo, ip FROM accesos ORDER BY id',
    'consultas' => 'SELECT fecha, nombre, email, whatsapp, mensaje FROM consultas ORDER BY id',
];
$que = isset($tablas[$_GET['que'] ?? '']) ? $_GET['que'] : 'pedidos';
$filas = $db->query($tablas[$que])->fetchAll();

header('Content-Type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=$que.csv");
$salida = fopen('php://output', 'w');
fwrite($salida, "\xEF\xBB\xBF"); // BOM: así Excel reconoce los acentos
if ($filas) fputcsv($salida, array_keys($filas[0]), ';', '"', '');
foreach ($filas as $f) fputcsv($salida, $f, ';', '"', ''); // '' = sin carácter de escape
