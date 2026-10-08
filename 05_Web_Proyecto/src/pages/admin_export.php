<?php
// Exporta datos a CSV (solo administradores).
requerir_admin();
$tipo = $_GET['tipo'] ?? '';
$consultas = [
    'usuarios' => ['SELECT id, email, nombre, empresa, pais, ciudad, cargo, registro_comercial, whatsapp, sitio_web, cuenta, perfil_completo, activo, creado_en, ultimo_login FROM usuarios ORDER BY id', 'usuarios.csv'],
    'accesos' => ['SELECT fecha, identificador, metodo, exito, motivo, ip, pais, user_agent FROM log_accesos ORDER BY id DESC', 'accesos.csv'],
    'pedidos' => ['SELECT p.codigo, p.creado_en, u.empresa, u.email, p.producto_id, p.cantidad_kg, p.destino, p.incoterm, p.precio_kg, p.total_usd, p.sena_usd, p.estado, p.vence_en, p.pago_banco, p.pago_ref, p.pago_fecha, p.pago_monto, p.sena_acreditada_en FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id DESC', 'pedidos.csv'],
    'consultas' => ['SELECT fecha, nombre, empresa, email, telefono, idioma, mensaje FROM consultas ORDER BY id DESC', 'consultas.csv'],
];
if (!isset($consultas[$tipo])) {
    http_response_code(404);
    exit('404');
}
[$sql, $file] = $consultas[$tipo];
$rows = db()->query($sql)->fetchAll();
csv_download($file, $rows ? array_keys($rows[0]) : ['(sin datos)'], $rows);
