<?php
// Exporta datos a CSV (solo administradores).
requerir_admin();
$tipo = $_GET['tipo'] ?? '';
$consultas = [
    'usuarios' => ['SELECT id, dni, email, nombre, apellido, activo, intentos_fallidos, creado_en, ultimo_login FROM usuarios ORDER BY id', 'usuarios.csv'],
    'accesos' => ['SELECT fecha, identificador, metodo, exito, motivo, ip, pais, user_agent FROM log_accesos ORDER BY id DESC', 'accesos.csv'],
    'proyectos' => ['SELECT p.id, u.dni, u.email, p.nombre, p.estado, p.avance, p.fecha_inicio FROM proyectos p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id', 'proyectos.csv'],
    'consultas' => ['SELECT fecha, nombre, email, telefono, mensaje FROM consultas ORDER BY id DESC', 'consultas.csv'],
];
if (!isset($consultas[$tipo])) {
    http_response_code(404);
    exit('404');
}
[$sql, $file] = $consultas[$tipo];
$rows = db()->query($sql)->fetchAll();
csv_download($file, $rows ? array_keys($rows[0]) : ['(sin datos)'], $rows);
