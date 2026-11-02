<?php
// Turnos del cliente: paso 1 elige el día, paso 2 elige un horario libre. Abajo ve y cancela sus turnos.
$raiz = '';
require 'inc/funciones.php';
$yo = exigir_login();
$titulo = 'Turnos';
$error = '';
$aviso = '';

// ¿Cuántos turnos reservados (a futuro) tiene este cliente?
$st = $db->prepare("SELECT COUNT(*) FROM turnos WHERE usuario_id = ? AND estado = 'reservado' AND fecha >= ?");
$st->execute([$yo['id'], date('Y-m-d')]);
$reservados = $st->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'reservar') {
    $fecha   = $_POST['fecha'] ?? '';
    $hora    = $_POST['hora'] ?? '';
    $mascota = trim($_POST['mascota'] ?? '');
    $especie = $_POST['especie'] ?? '';
    $st = $db->prepare('SELECT id FROM servicios WHERE id = ?');
    $st->execute([$_POST['servicio_id'] ?? 0]);

    if (!$st->fetch())                                $error = 'Elegí un servicio.';
    elseif ($mascota === '')                          $error = 'Escribí el nombre de tu animal.';
    elseif (!in_array($especie, ESPECIES))            $error = 'Elegí qué animal es.';
    elseif (!fecha_valida($fecha))                    $error = 'Elegí un día de lunes a sábado, desde mañana.';
    elseif (!in_array($hora, horarios_libres($fecha))) $error = 'Ese horario ya no está libre. Elegí otro.';
    elseif ($reservados >= MAX_TURNOS)                $error = 'Ya tenés ' . MAX_TURNOS . ' turnos reservados.';
    else {
        $db->prepare('INSERT INTO turnos (usuario_id, servicio_id, mascota, especie, fecha, hora) VALUES (?, ?, ?, ?, ?, ?)')
           ->execute([$yo['id'], $_POST['servicio_id'], $mascota, $especie, $fecha, $hora]);
        $aviso = 'Turno reservado para el ' . date('d/m/Y', strtotime($fecha)) . " a las $hora.";
        $reservados++;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cancelar') {
    // Solo puede cancelar SUS turnos reservados, y hasta el día anterior.
    $st = $db->prepare("UPDATE turnos SET estado = 'cancelado'
                        WHERE id = ? AND usuario_id = ? AND estado = 'reservado' AND fecha > ?");
    $st->execute([$_POST['id'], $yo['id'], date('Y-m-d')]);
    $aviso = $st->rowCount() ? 'Turno cancelado.' : 'Ese turno ya no se puede cancelar.';
    if ($st->rowCount()) $reservados--;
}

// Paso 1: el día llega por GET (?fecha=...). Si es válido se buscan los horarios libres.
$fecha = $_GET['fecha'] ?? '';
$libres = fecha_valida($fecha) ? horarios_libres($fecha) : [];
$servicios = $db->query('SELECT * FROM servicios ORDER BY id')->fetchAll();

$st = $db->prepare('SELECT t.*, s.nombre AS servicio FROM turnos t JOIN servicios s ON s.id = t.servicio_id
                    WHERE t.usuario_id = ? ORDER BY t.fecha DESC, t.hora DESC');
$st->execute([$yo['id']]);
$misTurnos = $st->fetchAll();

require 'inc/cabecera.php';
?>
<h1>Hola, <?= h($yo['nombre']) ?></h1>
<?php if ($aviso): ?><p class="ok"><?= h($aviso) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

<?php if ($reservados >= MAX_TURNOS): ?>
  <p class="error">Ya tenés <?= MAX_TURNOS ?> turnos reservados. Para sacar otro, cancelá uno o esperá a que pase.</p>
<?php else: ?>
<section class="formulario">
  <h2>Sacar turno</h2>
  <!-- Paso 1: elegir el día (GET, así la página muestra los horarios de ese día) -->
  <form method="get">
    <label>Día <input type="date" name="fecha" required value="<?= h($fecha) ?>"
           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" max="<?= date('Y-m-d', strtotime('+30 days')) ?>"></label>
    <button class="boton claro">Ver horarios libres</button>
  </form>

  <?php if ($fecha !== '' && !fecha_valida($fecha)): ?>
    <p class="error">Ese día no atendemos o está fuera de rango. Elegí de lunes a sábado, desde mañana y hasta 30 días.</p>
  <?php elseif ($fecha !== '' && !$libres): ?>
    <p class="error">No quedan horarios libres ese día. Probá con otro.</p>
  <?php elseif ($libres): ?>
  <!-- Paso 2: elegir horario, servicio y mascota (POST, porque guarda en la base) -->
  <form method="post">
    <?= csrf_campo() ?>
    <input type="hidden" name="accion" value="reservar">
    <input type="hidden" name="fecha" value="<?= h($fecha) ?>">
    <label>Horario
      <select name="hora"><?php foreach ($libres as $h): ?><option><?= $h ?></option><?php endforeach; ?></select>
    </label>
    <label>Servicio
      <select name="servicio_id">
        <?php foreach ($servicios as $s): ?><option value="<?= $s['id'] ?>"><?= h($s['nombre']) ?> (<?= pesos($s['precio']) ?>)</option><?php endforeach; ?>
      </select>
    </label>
    <label>Tipo de animal
      <select name="especie"><?php foreach (ESPECIES as $e): ?><option><?= $e ?></option><?php endforeach; ?></select>
    </label>
    <label>Nombre del animal <input name="mascota" required maxlength="60"></label>
    <button class="boton">Reservar turno</button>
  </form>
  <?php endif; ?>
</section>
<?php endif; ?>

<h2>Mis turnos</h2>
<table>
  <tr><th>Día</th><th>Hora</th><th>Animal</th><th>Servicio</th><th>Estado</th><th></th></tr>
  <?php foreach ($misTurnos as $t): ?>
  <tr>
    <td><?= date('d/m/Y', strtotime($t['fecha'])) ?></td>
    <td><?= substr($t['hora'], 0, 5) ?></td>
    <td><?= h($t['mascota']) ?> <span class="especie <?= strtolower($t['especie']) ?>"><?= $t['especie'] ?></span></td>
    <td><?= h($t['servicio']) ?></td>
    <td><span class="estado <?= $t['estado'] ?>"><?= $t['estado'] ?></span></td>
    <td>
      <?php if ($t['estado'] === 'reservado' && $t['fecha'] > date('Y-m-d')): ?>
        <form method="post" class="enlinea">
          <?= csrf_campo() ?>
          <input type="hidden" name="accion" value="cancelar">
          <input type="hidden" name="id" value="<?= $t['id'] ?>">
          <button class="boton chico">Cancelar</button>
        </form>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (!$misTurnos): ?><tr><td colspan="6">Todavía no sacaste turnos.</td></tr><?php endif; ?>
</table>
<?php require 'inc/pie.php'; ?>
