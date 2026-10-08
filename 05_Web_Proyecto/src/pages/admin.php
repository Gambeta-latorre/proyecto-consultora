<?php
// Panel de administración (solo español). Acceso: emails en la variable ADMIN_EMAILS.
$admin = requerir_admin();
$tituloClave = 'nav_admin';
vencer_proformas();
$tab = in_array($_GET['tab'] ?? '', ['resumen', 'usuarios', 'accesos', 'pedidos', 'consultas'], true) ? $_GET['tab'] : 'resumen';
$ESTADOS = ['solicitada', 'proforma_emitida', 'sena_informada', 'sena_acreditada', 'en_produccion', 'embarcada', 'cerrada', 'cancelada', 'vencida'];
$LABEL = ['solicitada' => 'Solicitada', 'proforma_emitida' => 'Proforma emitida', 'sena_informada' => 'Pago informado', 'sena_acreditada' => 'Seña acreditada', 'en_produccion' => 'En producción',
          'embarcada' => 'Embarcada', 'cerrada' => 'Cerrada', 'cancelada' => 'Cancelada', 'vencida' => 'Vencida'];
$SIGUIENTE = ['sena_acreditada' => 'en_produccion', 'en_produccion' => 'embarcada', 'embarcada' => 'cerrada'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $msg = 'La sesión expiró. Volvé a intentar.';
    } else {
        $a = (string)($_POST['accion'] ?? '');
        if ($a === 'user_cuenta') {
            $cuenta = $_POST['cuenta'] ?? '';
            if (in_array($cuenta, ['aprobada', 'rechazada', 'pendiente'], true)) {
                db()->prepare('UPDATE usuarios SET cuenta = ?, nota_admin = ? WHERE id = ?')->execute([$cuenta, mb_substr(trim((string)($_POST['nota'] ?? '')), 0, 500), (int)$_POST['id']]);
                $msg = 'Cuenta actualizada.';
            }
        } elseif ($a === 'user_activo') {
            db()->prepare('UPDATE usuarios SET activo = ? WHERE id = ?')->execute([!empty($_POST['activo']) ? 1 : 0, (int)$_POST['id']]);
            $msg = 'Usuario actualizado.';
        } elseif ($a === 'proforma') {
            $p = cargar_pedido((string)$_POST['c']);
            $precio = (float)str_replace(',', '.', (string)($_POST['precio'] ?? '0'));
            $dias = max(1, min(30, (int)($_POST['dias'] ?? PROFORMA_DIAS)));
            if ($p && in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida'], true) && $precio > 0 && $precio < 100) {
                [$total, $sena] = calcular_totales((int)$p['cantidad_kg'], $precio);
                db()->prepare("UPDATE pedidos SET precio_kg = ?, total_usd = ?, sena_usd = ?, estado = 'proforma_emitida', vence_en = ?, actualizado_en = ? WHERE id = ?")
                    ->execute([$precio, $total, $sena, date('Y-m-d H:i:s', time() + $dias * 86400), now(), $p['id']]);
                $msg = 'Proforma emitida: avisale al cliente por WhatsApp.';
            } else {
                $msg = 'No se pudo emitir la proforma (revisá el estado y el precio).';
            }
        } elseif ($a === 'pedido_estado') {
            $p = cargar_pedido((string)$_POST['c']);
            $nuevo = (string)($_POST['nuevo'] ?? '');
            $permitido = false;
            if ($p) {
                if ($nuevo === 'sena_acreditada') { $permitido = in_array($p['estado'], ['proforma_emitida', 'sena_informada'], true) && $p['total_usd'] !== null; }
                elseif ($nuevo === 'cancelada') { $permitido = !in_array($p['estado'], ['cerrada', 'cancelada', 'embarcada'], true); }
                else { $permitido = ($SIGUIENTE[$p['estado']] ?? '') === $nuevo; }
            }
            if ($permitido) {
                if ($nuevo === 'sena_acreditada') {
                    db()->prepare('UPDATE pedidos SET estado = ?, sena_acreditada_en = ?, actualizado_en = ? WHERE id = ?')->execute([$nuevo, now(), now(), $p['id']]);
                } else {
                    db()->prepare('UPDATE pedidos SET estado = ?, actualizado_en = ? WHERE id = ?')->execute([$nuevo, now(), $p['id']]);
                }
                $msg = 'Pedido actualizado a: ' . ($LABEL[$nuevo] ?? $nuevo) . '.';
            } else {
                $msg = 'Ese cambio de estado no está permitido.';
            }
        }
    }
}

/* ---------- datos de cada pestaña ---------- */
$data = [];
if ($tab === 'resumen') {
    $data['usuarios'] = db()->query("SELECT cuenta, COUNT(*) AS n FROM usuarios GROUP BY cuenta")->fetchAll();
    $data['pedidos'] = db()->query("SELECT estado, COUNT(*) AS n FROM pedidos GROUP BY estado")->fetchAll();
    $st = db()->prepare('SELECT exito, COUNT(*) AS n FROM log_accesos WHERE fecha > ? GROUP BY exito');
    $st->execute([date('Y-m-d H:i:s', time() - 86400)]);
    $data['accesos24'] = $st->fetchAll();
} elseif ($tab === 'usuarios') {
    $data['rows'] = db()->query('SELECT * FROM usuarios ORDER BY creado_en DESC LIMIT 500')->fetchAll();
} elseif ($tab === 'accesos') {
    $data['rows'] = db()->query('SELECT * FROM log_accesos ORDER BY fecha DESC LIMIT 300')->fetchAll();
} elseif ($tab === 'pedidos') {
    $cod = (string)($_GET['c'] ?? '');
    if ($cod !== '') {
        $data['detalle'] = cargar_pedido($cod);
    }
    $data['rows'] = db()->query('SELECT p.codigo, p.cantidad_kg, p.destino, p.estado, p.total_usd, p.sena_usd, p.creado_en, u.empresa, u.email FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.creado_en DESC LIMIT 300')->fetchAll();
} elseif ($tab === 'consultas') {
    $data['rows'] = db()->query('SELECT * FROM consultas ORDER BY fecha DESC LIMIT 300')->fetchAll();
}
$GLOBALS['LANG'] = 'es';
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page admin">
  <h1>Administración</h1>
  <p class="tabs">
    <?php foreach (['resumen' => 'Resumen', 'usuarios' => 'Usuarios', 'accesos' => 'Inicios de sesión', 'pedidos' => 'Pedidos y seña', 'consultas' => 'Consultas'] as $k => $n): ?>
      <a href="/admin?tab=<?= $k ?>" class="<?= $tab === $k ? 'on' : '' ?>"><?= $n ?></a>
    <?php endforeach; ?>
  </p>
  <?php if ($msg): ?><div class="alert ok"><?= h($msg) ?></div><?php endif; ?>

  <?php if ($tab === 'resumen'): ?>
    <div class="grid3">
      <article class="card"><h3>Cuentas</h3><?php foreach ($data['usuarios'] as $r): ?><p><?= h($r['cuenta']) ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="/admin?tab=usuarios">Revisar cuentas pendientes &rarr;</a></p></article>
      <article class="card"><h3>Pedidos</h3><?php foreach ($data['pedidos'] as $r): ?><p><?= h($LABEL[$r['estado']] ?? $r['estado']) ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="/admin?tab=pedidos">Ver pedidos &rarr;</a></p></article>
      <article class="card"><h3>Ingresos últimas 24 h</h3><?php foreach ($data['accesos24'] as $r): ?><p><?= (int)$r['exito'] ? 'Correctos' : 'Fallidos' ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="/admin?tab=accesos">Ver registro &rarr;</a></p></article>
    </div>
    <h2>Exportar todo (CSV para Excel)</h2>
    <p><a class="btn small" href="/admin_export?tipo=usuarios">Usuarios</a> <a class="btn small" href="/admin_export?tipo=accesos">Inicios de sesión</a>
       <a class="btn small" href="/admin_export?tipo=pedidos">Pedidos</a> <a class="btn small" href="/admin_export?tipo=consultas">Consultas</a></p>
    <p class="note">Las contraseñas nunca se guardan en texto: este sitio no usa contraseñas (ingreso con Google), y si alguna vez se agrega otro método se guardaría solo el hash.</p>

  <?php elseif ($tab === 'usuarios'): ?>
    <div class="tablewrap"><table class="small">
      <tr><th>Alta</th><th>Email / nombre</th><th>Empresa</th><th>País / ciudad</th><th>Registro comercial</th><th>WhatsApp</th><th>Último ingreso</th><th>Estado</th><th>Acción</th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr>
          <td dir="ltr"><?= h(substr($r['creado_en'], 0, 10)) ?></td>
          <td><bdi dir="ltr"><?= h($r['email']) ?></bdi><br><?= h($r['nombre']) ?></td>
          <td><?= h($r['empresa']) ?><br><small><?= h($r['cargo']) ?></small></td>
          <td><?= h($r['pais']) ?> · <?= h($r['ciudad']) ?></td>
          <td dir="ltr"><?= h($r['registro_comercial']) ?></td>
          <td dir="ltr"><?= h($r['whatsapp']) ?></td>
          <td dir="ltr"><?= h((string)$r['ultimo_login']) ?></td>
          <td><b><?= h($r['cuenta']) ?></b><?= (int)$r['perfil_completo'] ? '' : '<br><small>perfil incompleto</small>' ?><?= (int)$r['activo'] ? '' : '<br><small>DESACTIVADO</small>' ?></td>
          <td>
            <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="user_cuenta"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <select name="cuenta"><?php foreach (['aprobada', 'pendiente', 'rechazada'] as $c): ?><option <?= $r['cuenta'] === $c ? 'selected' : '' ?>><?= $c ?></option><?php endforeach; ?></select>
              <input name="nota" value="<?= h((string)$r['nota_admin']) ?>" placeholder="nota interna" maxlength="500"><button class="btn small">OK</button></form>
            <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="user_activo"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <input type="hidden" name="activo" value="<?= (int)$r['activo'] ? '' : '1' ?>"><button class="btn small ghost-dark"><?= (int)$r['activo'] ? 'Desactivar' : 'Reactivar' ?></button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table></div>

  <?php elseif ($tab === 'accesos'): ?>
    <p><a class="btn small" href="/admin_export?tipo=accesos">Descargar todo en CSV</a></p>
    <div class="tablewrap"><table class="small">
      <tr><th>Fecha</th><th>Quién</th><th>Método</th><th>Resultado</th><th>Motivo</th><th>IP</th><th>País</th><th>Navegador</th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td dir="ltr"><?= h($r['fecha']) ?></td><td><bdi dir="ltr"><?= h($r['identificador']) ?></bdi></td><td><?= h($r['metodo']) ?></td>
            <td><?= (int)$r['exito'] ? 'OK' : '<b>FALLÓ</b>' ?></td><td><?= h((string)$r['motivo']) ?></td><td dir="ltr"><?= h($r['ip']) ?></td><td><?= h((string)$r['pais']) ?></td><td><small><?= h(mb_substr((string)$r['user_agent'], 0, 60)) ?></small></td></tr>
      <?php endforeach; ?>
    </table></div>

  <?php elseif ($tab === 'pedidos'): ?>
    <?php if (!empty($data['detalle'])): $d = $data['detalle']; ?>
      <div class="card">
        <h3>Pedido <?= h($d['codigo']) ?> · <?= h($LABEL[$d['estado']] ?? $d['estado']) ?></h3>
        <p><b><?= h($d['u_empresa']) ?></b> · <bdi dir="ltr"><?= h($d['u_email']) ?></bdi> · WhatsApp <bdi dir="ltr"><?= h((string)$d['u_whatsapp']) ?></bdi></p>
        <p><?= h($d['nombre_es']) ?> · <?= number_format((int)$d['cantidad_kg'], 0, '.', ',') ?> kg · <?= h($d['destino']) ?> · <?= h($d['incoterm']) ?></p>
        <?php if ($d['total_usd'] !== null): ?><p>Total <b><?= h(usd($d['total_usd'])) ?></b> · seña 50 % <b><?= h(usd($d['sena_usd'])) ?></b> · vence <?= h((string)$d['vence_en']) ?></p><?php endif; ?>
        <?php if ($d['pago_ref']): ?><p><b>Pago informado:</b> <?= h($d['pago_banco']) ?> · ref. <?= h($d['pago_ref']) ?> · <?= h($d['pago_fecha']) ?> · <?= h(usd($d['pago_monto'])) ?> · términos aceptados <?= h((string)$d['terminos_aceptados_en']) ?> desde IP <?= h((string)$d['terminos_ip']) ?></p><?php endif; ?>
        <?php if (in_array($d['estado'], ['solicitada', 'proforma_emitida', 'vencida'], true)): ?>
          <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="proforma"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>">
            Precio USD/kg <input name="precio" size="6" inputmode="decimal" value="<?= h((string)($d['precio_kg'] ?? '')) ?>" required> Validez (días) <input name="dias" size="3" value="<?= PROFORMA_DIAS ?>">
            <button class="btn small"><?= $d['estado'] === 'solicitada' ? 'Emitir proforma' : 'Reemitir proforma' ?></button></form>
        <?php endif; ?>
        <?php if (in_array($d['estado'], ['proforma_emitida', 'sena_informada'], true) && $d['total_usd'] !== null): ?>
          <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="pedido_estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="sena_acreditada">
            <button class="btn small">Confirmar: seña acreditada en el banco</button></form>
        <?php endif; ?>
        <?php if (isset($SIGUIENTE[$d['estado']])): ?>
          <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="pedido_estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="<?= h($SIGUIENTE[$d['estado']]) ?>">
            <button class="btn small">Pasar a: <?= h($LABEL[$SIGUIENTE[$d['estado']]]) ?></button></form>
        <?php endif; ?>
        <?php if (!in_array($d['estado'], ['cerrada', 'cancelada', 'embarcada'], true)): ?>
          <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="pedido_estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="cancelada">
            <button class="btn small ghost-dark">Cancelar pedido</button></form>
        <?php endif; ?>
        <p><a target="_blank" rel="noopener" href="https://wa.me/<?= h(preg_replace('/\D/', '', (string)$d['u_whatsapp'])) ?>">Escribirle por WhatsApp</a></p>
      </div>
    <?php endif; ?>
    <div class="tablewrap"><table class="small">
      <tr><th>Código</th><th>Fecha</th><th>Empresa</th><th>Kg</th><th>Destino</th><th>Total</th><th>Seña</th><th>Estado</th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td><a href="/admin?tab=pedidos&amp;c=<?= h(rawurlencode($r['codigo'])) ?>"><?= h($r['codigo']) ?></a></td><td dir="ltr"><?= h(substr($r['creado_en'], 0, 10)) ?></td><td><?= h($r['empresa']) ?></td>
            <td dir="ltr"><?= number_format((int)$r['cantidad_kg'], 0, '.', ',') ?></td><td><?= h($r['destino']) ?></td><td dir="ltr"><?= $r['total_usd'] !== null ? h(usd($r['total_usd'])) : '—' ?></td>
            <td dir="ltr"><?= $r['sena_usd'] !== null ? h(usd($r['sena_usd'])) : '—' ?></td><td><span class="pill <?= h($r['estado']) ?>"><?= h($LABEL[$r['estado']] ?? $r['estado']) ?></span></td></tr>
      <?php endforeach; ?>
    </table></div>

  <?php elseif ($tab === 'consultas'): ?>
    <div class="tablewrap"><table class="small">
      <tr><th>Fecha</th><th>Nombre / empresa</th><th>Email</th><th>Teléfono</th><th>Idioma</th><th>Mensaje</th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td dir="ltr"><?= h($r['fecha']) ?></td><td><?= h($r['nombre']) ?><br><small><?= h((string)$r['empresa']) ?></small></td><td><bdi dir="ltr"><?= h($r['email']) ?></bdi></td>
            <td dir="ltr"><?= h((string)$r['telefono']) ?></td><td><?= h($r['idioma']) ?></td><td><?= nl2br(h($r['mensaje'])) ?></td></tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
