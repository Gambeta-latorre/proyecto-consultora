<?php
// Panel de administración. Acceso: emails en la variable ADMIN_EMAILS.
requerir_admin();
$titulo = 'Administración';
$tab = in_array($_GET['tab'] ?? '', ['resumen', 'usuarios', 'accesos', 'proyectos', 'consultas'], true) ? $_GET['tab'] : 'resumen';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $msg = 'La sesión expiró. Volvé a intentar.';
    } else {
        $a = (string)($_POST['accion'] ?? '');
        if ($a === 'user_activo') {
            db()->prepare('UPDATE usuarios SET activo = ?, intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?')->execute([!empty($_POST['activo']) ? 1 : 0, (int)$_POST['id']]);
            $msg = 'Usuario actualizado.';
        } elseif ($a === 'proyecto_nuevo') {
            $nombre = trim((string)($_POST['nombre'] ?? ''));
            $avance = max(0, min(100, (int)($_POST['avance'] ?? 0)));
            if ($nombre !== '' && mb_strlen($nombre) <= 150) {
                db()->prepare('INSERT INTO proyectos (usuario_id, nombre, estado, avance, fecha_inicio) VALUES (?,?,?,?,?)')
                    ->execute([(int)$_POST['usuario_id'], $nombre, mb_substr(trim((string)($_POST['estado'] ?? 'En diagnóstico')), 0, 40), $avance, date('Y-m-d')]);
                $msg = 'Proyecto creado.';
            }
        } elseif ($a === 'proyecto_avance') {
            db()->prepare('UPDATE proyectos SET estado = ?, avance = ? WHERE id = ?')
                ->execute([mb_substr(trim((string)($_POST['estado'] ?? '')), 0, 40), max(0, min(100, (int)($_POST['avance'] ?? 0))), (int)$_POST['id']]);
            $msg = 'Proyecto actualizado.';
        }
    }
}
$data = [];
if ($tab === 'resumen') {
    $data['usuarios'] = (int)db()->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    $st = db()->prepare('SELECT exito, COUNT(*) AS n FROM log_accesos WHERE fecha > ? GROUP BY exito');
    $st->execute([date('Y-m-d H:i:s', time() - 86400)]);
    $data['accesos24'] = $st->fetchAll();
    $data['consultas'] = (int)db()->query('SELECT COUNT(*) FROM consultas')->fetchColumn();
} elseif ($tab === 'usuarios') {
    $data['rows'] = db()->query('SELECT * FROM usuarios ORDER BY creado_en DESC LIMIT 500')->fetchAll();
} elseif ($tab === 'accesos') {
    $data['rows'] = db()->query('SELECT * FROM log_accesos ORDER BY fecha DESC LIMIT 300')->fetchAll();
} elseif ($tab === 'proyectos') {
    $data['rows'] = db()->query('SELECT p.*, u.dni, u.email FROM proyectos p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id DESC LIMIT 300')->fetchAll();
    $data['users'] = db()->query('SELECT id, dni, email, nombre, apellido FROM usuarios ORDER BY nombre')->fetchAll();
} else {
    $data['rows'] = db()->query('SELECT * FROM consultas ORDER BY fecha DESC LIMIT 300')->fetchAll();
}
require __DIR__ . '/../layout/header.php';
?>
<section class="wrap page admin">
  <h1>Administración</h1>
  <p class="tabs">
    <?php foreach (['resumen' => 'Resumen', 'usuarios' => 'Usuarios', 'accesos' => 'Inicios de sesión', 'proyectos' => 'Proyectos', 'consultas' => 'Consultas'] as $k => $n): ?>
      <a href="/admin?tab=<?= $k ?>" class="<?= $tab === $k ? 'on' : '' ?>"><?= $n ?></a>
    <?php endforeach; ?>
  </p>
  <?php if ($msg): ?><div class="alert ok"><?= h($msg) ?></div><?php endif; ?>

  <?php if ($tab === 'resumen'): ?>
    <div class="grid3">
      <article class="card"><h3>Usuarios</h3><p><b><?= $data['usuarios'] ?></b> registrados</p><p><a href="/admin?tab=usuarios">Ver todos &rarr;</a></p></article>
      <article class="card"><h3>Ingresos últimas 24 h</h3><?php foreach ($data['accesos24'] as $r): ?><p><?= (int)$r['exito'] ? 'Correctos' : 'Fallidos' ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="/admin?tab=accesos">Ver registro &rarr;</a></p></article>
      <article class="card"><h3>Consultas</h3><p><b><?= $data['consultas'] ?></b> recibidas</p><p><a href="/admin?tab=consultas">Leerlas &rarr;</a></p></article>
    </div>
    <h2>Exportar todo (CSV para Excel)</h2>
    <p><a class="btn small" href="/admin_export?tipo=usuarios">Usuarios</a> <a class="btn small" href="/admin_export?tipo=accesos">Inicios de sesión</a>
       <a class="btn small" href="/admin_export?tipo=proyectos">Proyectos</a> <a class="btn small" href="/admin_export?tipo=consultas">Consultas</a></p>
    <p class="note">Las contraseñas se guardan cifradas (hash irreversible): ni el administrador ni nadie puede verlas. Si alguien la olvida, se le restablece el acceso.</p>

  <?php elseif ($tab === 'usuarios'): ?>
    <div class="tablewrap"><table class="small">
      <tr><th>Alta</th><th>DNI</th><th>Nombre</th><th>Email</th><th>Método</th><th>Último ingreso</th><th>Estado</th><th></th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td dir="ltr"><?= h(substr($r['creado_en'], 0, 10)) ?></td><td dir="ltr"><?= h((string)$r['dni']) ?></td><td><?= h($r['nombre'] . ' ' . $r['apellido']) ?></td>
            <td><bdi dir="ltr"><?= h((string)$r['email']) ?></bdi></td><td><?= $r['password_hash'] ? 'DNI' : '' ?><?= $r['google_sub'] ? ' Google' : '' ?></td>
            <td dir="ltr"><?= h((string)$r['ultimo_login']) ?></td>
            <td><?= (int)$r['activo'] ? 'activo' : '<b>DESACTIVADO</b>' ?><?= $r['bloqueado_hasta'] && $r['bloqueado_hasta'] > now() ? '<br><b>bloqueado hasta ' . h($r['bloqueado_hasta']) . '</b>' : '' ?></td>
            <td><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="user_activo"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
              <input type="hidden" name="activo" value="<?= (int)$r['activo'] ? '' : '1' ?>"><button class="btn small ghost-dark"><?= (int)$r['activo'] ? 'Desactivar' : 'Reactivar / desbloquear' ?></button></form></td></tr>
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

  <?php elseif ($tab === 'proyectos'): ?>
    <form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="proyecto_nuevo">
      <select name="usuario_id"><?php foreach ($data['users'] as $x): ?><option value="<?= (int)$x['id'] ?>"><?= h(trim($x['nombre'] . ' ' . $x['apellido']) . ' · ' . ($x['dni'] ?: $x['email'])) ?></option><?php endforeach; ?></select>
      <input name="nombre" placeholder="Nombre del proyecto" required maxlength="150"> <input name="estado" value="En diagnóstico" maxlength="40"> <input name="avance" type="number" min="0" max="100" value="0" style="width:70px">
      <button class="btn small">Crear proyecto</button></form>
    <div class="tablewrap"><table class="small">
      <tr><th>Cliente</th><th>Proyecto</th><th>Estado</th><th>Avance %</th><th>Inicio</th><th></th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td><?= h($r['dni'] ?: $r['email']) ?></td><td><?= h($r['nombre']) ?></td>
          <td colspan="3"><form method="post" class="inline-form"><?= csrf_field() ?><input type="hidden" name="accion" value="proyecto_avance"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <input name="estado" value="<?= h($r['estado']) ?>" maxlength="40"> <input name="avance" type="number" min="0" max="100" value="<?= (int)$r['avance'] ?>" style="width:70px"> <?= h($r['fecha_inicio']) ?> <button class="btn small">Guardar</button></form></td><td></td></tr>
      <?php endforeach; ?>
    </table></div>

  <?php else: ?>
    <div class="tablewrap"><table class="small">
      <tr><th>Fecha</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Mensaje</th></tr>
      <?php foreach ($data['rows'] as $r): ?>
        <tr><td dir="ltr"><?= h($r['fecha']) ?></td><td><?= h($r['nombre']) ?></td><td><bdi dir="ltr"><?= h($r['email']) ?></bdi></td><td dir="ltr"><?= h((string)$r['telefono']) ?></td><td><?= nl2br(h($r['mensaje'])) ?></td></tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
