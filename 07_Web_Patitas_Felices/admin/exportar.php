<?php
// Descarga una tabla en CSV para abrir con Excel (separador ";" y BOM para que respete los acentos).
$raiz = '../';
require '../inc/funciones.php';
exigir_admin();

$tablas = [
    'turnos'    => 'SELECT t.fecha, t.hora, u.nombre AS cliente, u.whatsapp, t.mascota, s.nombre AS servicio, t.estado
                    FROM turnos t JOIN usuarios u ON u.id = t.usuario_id JOIN servicios s ON s.id = t.servicio_id
                    ORDER BY t.fecha, t.hora',
    'clientes'  => "SELECT nombre, email, whatsapp, activo, creado_en FROM usuarios WHERE rol = 'cliente' ORDER BY id",
    'servicios' => 'SELECT nombre, detalle, precio FROM servicios ORDER BY id',
    'consultas' => 'SELECT fecha, nombre, email, whatsapp, mensaje FROM consultas ORDER BY id',
    'accesos'   => 'SELECT fecha, email, exito, ip FROM accesos ORDER BY id',
];
$que = isset($tablas[$_GET['que'] ?? '']) ? $_GET['que'] : 'turnos';
$filas = $db->query($tablas[$que])->fetchAll();

header('Content-Type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=$que.csv");
$salida = fopen('php://output', 'w');
fwrite($salida, "\xEF\xBB\xBF"); // BOM: así Excel reconoce los acentos
if ($filas) fputcsv($salida, array_keys($filas[0]), ';');
foreach ($filas as $f) fputcsv($salida, $f, ';');
