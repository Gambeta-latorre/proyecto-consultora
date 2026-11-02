<?php
// Panel de administración de la veterinaria: turnos, clientes, servicios, consultas y accesos.
$raiz = '../';
require '../inc/funciones.php';
exigir_admin();
$titulo = 'Administración';
$pestanas = ['turnos' => 'Turnos', 'clientes' => 'Clientes', 'servicios' => 'Servicios', 'consultas' => 'Consultas', 'accesos' => 'Accesos'];
$ver = isset($pestanas[$_GET['ver'] ?? '']) ? $_GET['ver'] : 'turnos';
$dia = $_GET['dia'] ?? date('Y-m-d'); // en "Turnos" se ve un día por vez

// ---------- Acciones (llegan por POST con su código CSRF) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    switch ($_POST['accion'] ?? '') {
        case 'atendido':
        case 'cancelar':
            $estado = $_POST['accion'] === 'atendido' ? 'atendido' : 'cancelado';
            $db->prepare("UPDATE turnos SET estado = ? WHERE id = ? AND estado = 'reservado'")->execute([$estado, $id]);
            break;
        case 'bloquear':
        case 'activar':
            $db->prepare("UPDATE usuarios SET activo = ? WHERE id = ? AND rol = 'cliente'")
               ->execute([$_POST['accion'] === 'activar' ? 1 : 0, $id]);
            break;
        case 'precio':
            $db->prepare('UPDATE servicios SET precio = ? WHERE id = ?')->execute([max(0, (float)$_POST['precio']), $id]);
            break;
    }
    // Después de un POST se vuelve a la misma pestaña con GET (así F5 no repite la acción)
    ir('admin/index.php?' . http_build_query(['ver' => $ver, 'dia' => $dia]));
}

// Dibuja un botón que manda una acción, para no repetir el mismo <form> en cada fila.
function boton($accion, $id, $texto) {
    return '<form method="post" class="enlinea">' . csrf_campo()
         . '<input type="hidden" name="accion" value="' . $accion . '">'
         . '<input type="hidden" name="id" value="' . $id . '">'
         . '<button class="boton chico">' . $texto . '</button></form>';
}

// ---------- Datos de la pestaña elegida ----------
if ($ver === 'turnos') {
    $st = $db->prepare('SELECT t.*, s.nombre AS servicio, u.nombre AS cliente, u.whatsapp FROM turnos t
                        JOIN servicios s ON s.id = t.servicio_id JOIN usuarios u ON u.id = t.usuario_id
                        WHERE t.fecha = ? ORDER BY t.hora');
    $st->execute([$dia]);
    $filas = $st->fetchAll();
} else {
    $sql = [
        'clientes'  => "SELECT u.*, (SELECT COUNT(*) FROM turnos WHERE usuario_id = u.id) AS turnos
                        FROM usuarios u WHERE rol = 'cliente' ORDER BY id DESC",
        'servicios' => 'SELECT * FROM servicios ORDER BY id',
        'consultas' => 'SELECT * FROM consultas ORDER BY id DESC',
        'accesos'   => 'SELECT * FROM accesos ORDER BY id DESC LIMIT 100',
    ];
    $filas = $db->query($sql[$ver])->fetchAll();
}

require '../inc/cabecera.php';
?>
<h1>Administración</h1>
<nav class="pestanas">
  <?php foreach ($pestanas as $clave => $nombre): ?>
    <a href="?ver=<?= $clave ?>" class="<?= $clave === $ver ? 'activa' : '' ?>"><?= $nombre ?></a>
  <?php endforeach; ?>
  <a href="exportar.php?que=<?= $ver ?>">Descargar CSV</a>
</nav>

<?php if ($ver === 'turnos'): ?>
  <form method="get" class="enlinea">
    <input type="hidden" name="ver" value="turnos">
    <input type="date" name="dia" value="<?= h($dia) ?>">
    <button class="boton chico">Ver día</button>
  </form>
  <table>
    <tr><th>Hora</th><th>Cliente</th><th>Mascota</th><th>Servicio</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($filas as $t): ?>
    <tr>
      <td><?= substr($t['hora'], 0, 5) ?></td>
      <td><?= h($t['cliente']) ?><br>
          <a href="https://wa.me/<?= preg_replace('/\D/', '', $t['whatsapp']) ?>"><small><?= h($t['whatsapp']) ?></small></a></td>
      <td><?= h($t['mascota']) ?></td>
      <td><?= h($t['servicio']) ?></td>
      <td><span class="estado <?= $t['estado'] ?>"><?= $t['estado'] ?></span></td>
      <td><?php if ($t['estado'] === 'reservado') echo boton('atendido', $t['id'], 'Atendido') . boton('cancelar', $t['id'], 'Cancelar'); ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$filas): ?><tr><td colspan="6">No hay turnos ese día.</td></tr><?php endif; ?>
  </table>

<?php elseif ($ver === 'clientes'): ?>
  <table>
    <tr><th>Nombre</th><th>Email</th><th>WhatsApp</th><th>Turnos</th><th>Alta</th><th>Estado</th></tr>
    <?php foreach ($filas as $u): ?>
    <tr>
      <td><?= h($u['nombre']) ?></td>
      <td><?= h($u['email']) ?></td>
      <td><a href="https://wa.me/<?= preg_replace('/\D/', '', $u['whatsapp']) ?>"><?= h($u['whatsapp']) ?></a></td>
      <td><?= $u['turnos'] ?></td>
      <td><?= date('d/m/Y', strtotime($u['creado_en'])) ?></td>
      <td><?= $u['activo'] ? boton('bloquear', $u['id'], 'Bloquear') : boton('activar', $u['id'], 'Activar') ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

<?php elseif ($ver === 'servicios'): ?>
  <table>
    <tr><th>Servicio</th><th>Detalle</th><th>Precio</th></tr>
    <?php foreach ($filas as $s): ?>
    <tr>
      <td><?= h($s['nombre']) ?></td>
      <td><?= h($s['detalle']) ?></td>
      <td>
        <form method="post" class="enlinea">
          <?= csrf_campo() ?>
          <input type="hidden" name="accion" value="precio"><input type="hidden" name="id" value="<?= $s['id'] ?>">
          $ <input type="number" name="precio" min="0" step="100" value="<?= (int)$s['precio'] ?>">
          <button class="boton chico">Guardar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

<?php elseif ($ver === 'consultas'): ?>
  <table>
    <tr><th>Fecha</th><th>De</th><th>Mensaje</th><th>Responder</th></tr>
    <?php foreach ($filas as $c): ?>
    <tr>
      <td><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
      <td><?= h($c['nombre']) ?><br><small><?= h($c['email']) ?></small></td>
      <td><?= nl2br(h($c['mensaje'])) ?></td>
      <td><a class="boton chico" href="https://wa.me/<?= preg_replace('/\D/', '', $c['whatsapp']) ?>">WhatsApp</a></td>
    </tr>
    <?php endforeach; ?>
  </table>

<?php else: ?>
  <table>
    <tr><th>Fecha</th><th>Email</th><th>Resultado</th><th>IP</th></tr>
    <?php foreach ($filas as $a): ?>
    <tr>
      <td><?= date('d/m/Y H:i:s', strtotime($a['fecha'])) ?></td>
      <td><?= h($a['email']) ?></td>
      <td><span class="estado <?= $a['exito'] ? 'atendido' : 'cancelado' ?>"><?= $a['exito'] ? 'correcto' : 'fallido' ?></span></td>
      <td><?= h($a['ip']) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
<?php require '../inc/pie.php'; ?>
