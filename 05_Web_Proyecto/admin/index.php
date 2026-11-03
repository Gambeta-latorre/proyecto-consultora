<?php
// Panel de administración (solo para los Gmail de ADMIN_EMAILS): resumen, cuentas, pedidos con seña, ingresos y consultas.
$raiz = '../';
require '../inc/funciones.php';
$titulo = 'Administración';
$activa = 'Administración';
$yo = exigir_admin();
vencer_proformas();
$pestanas = ['resumen' => 'Resumen', 'cuentas' => 'Cuentas', 'pedidos' => 'Pedidos y seña', 'accesos' => 'Ingresos', 'consultas' => 'Consultas'];
$tab = isset($pestanas[$_GET['tab'] ?? '']) ? $_GET['tab'] : 'resumen';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($accion === 'cuenta' && in_array($_POST['cuenta'] ?? '', ['aprobada', 'pendiente', 'rechazada'])) {
        $db->prepare("UPDATE usuarios SET cuenta = ? WHERE id = ? AND rol = 'cliente'")->execute([$_POST['cuenta'], $id]);
        aviso('Cuenta actualizada.');
    } elseif ($accion === 'activo') {
        $db->prepare("UPDATE usuarios SET activo = ? WHERE id = ? AND rol = 'cliente'")->execute([empty($_POST['activo']) ? 0 : 1, $id]);
        aviso('Usuario actualizado.');
    } else {
        $p = cargar_pedido($_POST['c'] ?? '');
        if ($p && $accion === 'proforma') {                      // el admin fija el precio y se calcula la seña
            $precio = (float)str_replace(',', '.', $_POST['precio'] ?? '0');
            if (in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida']) && $precio > 0) {
                [$total, $sena] = calcular_totales($p['cantidad_kg'], $precio);
                $db->prepare("UPDATE pedidos SET precio_kg = ?, total = ?, sena = ?, estado = 'proforma_emitida', vence_en = ? WHERE id = ?")
                   ->execute([$precio, $total, $sena, date('Y-m-d H:i:s', time() + PROFORMA_DIAS * 86400), $p['id']]);
                aviso('Proforma emitida. Avisale al cliente por WhatsApp.');
            } else aviso('No se pudo emitir la proforma.', 'error');
        } elseif ($p && $accion === 'estado') {                  // avanzar el pedido
            $nuevo = $_POST['nuevo'] ?? '';
            $permitido = ($nuevo === 'sena_acreditada' && in_array($p['estado'], ['proforma_emitida', 'sena_informada']) && $p['total'] !== null)
                      || ($nuevo === 'cancelada' && !in_array($p['estado'], ['embarcada', 'cerrada', 'cancelada']))
                      || (SIGUIENTE[$p['estado']] ?? '') === $nuevo;
            if ($permitido) {
                $embarcacion = $nuevo === 'embarcada' ? mb_substr(trim($_POST['embarcacion'] ?? ''), 0, 100) : $p['embarcacion'];
                $db->prepare('UPDATE pedidos SET estado = ?, embarcacion = ? WHERE id = ?')->execute([$nuevo, $embarcacion, $p['id']]);
                aviso('Pedido ' . $p['codigo'] . ': ' . ESTADOS[$nuevo] . '.');
            } else aviso('Ese cambio de estado no está permitido.', 'error');
        }
    }
    ir('admin/index.php?tab=' . $tab . (isset($_POST['c']) ? '&c=' . rawurlencode($_POST['c']) : ''));
}
require '../inc/cabecera.php';
?>
<div class="pagina">
  <h1>Administración</h1>
  <p class="pestanas">
    <?php foreach ($pestanas as $clave => $nombre): ?><a href="?tab=<?= $clave ?>" class="<?= $tab === $clave ? 'activa' : '' ?>"><?= $nombre ?></a><?php endforeach; ?>
  </p>

<?php if ($tab === 'resumen'): ?>
  <?php
    $cuentas = $db->query("SELECT cuenta, COUNT(*) AS n FROM usuarios WHERE rol = 'cliente' GROUP BY cuenta")->fetchAll();
    $pedidos = $db->query('SELECT estado, COUNT(*) AS n FROM pedidos GROUP BY estado')->fetchAll();
    $st = $db->prepare('SELECT exito, COUNT(*) AS n FROM accesos WHERE fecha > ? GROUP BY exito');
    $st->execute([date('Y-m-d H:i:s', time() - 86400)]);
    $ingresos = $st->fetchAll();
  ?>
  <div class="grilla">
    <article class="tarjeta"><h3>Cuentas</h3><?php foreach ($cuentas as $r): ?><p><?= h(ucfirst($r['cuenta'])) ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="?tab=cuentas">Revisar cuentas &rarr;</a></p></article>
    <article class="tarjeta"><h3>Pedidos</h3><?php foreach ($pedidos as $r): ?><p><?= h(ESTADOS[$r['estado']]) ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="?tab=pedidos">Ver pedidos &rarr;</a></p></article>
    <article class="tarjeta"><h3>Ingresos de las últimas 24 h</h3><?php foreach ($ingresos as $r): ?><p><?= $r['exito'] ? 'Correctos' : 'Fallidos' ?>: <b><?= (int)$r['n'] ?></b></p><?php endforeach; ?><p><a href="?tab=accesos">Ver registro &rarr;</a></p></article>
  </div>
  <h2>Descargar para Excel (CSV)</h2>
  <p>
    <?php foreach (['usuarios' => 'Clientes', 'pedidos' => 'Pedidos', 'accesos' => 'Ingresos', 'consultas' => 'Consultas'] as $q => $n): ?>
      <a class="boton chico" href="exportar.php?que=<?= $q ?>"><?= $n ?></a>
    <?php endforeach; ?>
  </p>

<?php elseif ($tab === 'cuentas'): ?>
  <?php $filas = $db->query("SELECT * FROM usuarios WHERE rol = 'cliente' ORDER BY id DESC")->fetchAll(); ?>
  <div class="tabla"><table class="chica">
    <tr><th>Alta</th><th>Cliente</th><th>Comercio</th><th>CUIT</th><th>Ciudad</th><th>WhatsApp</th><th>Estado</th><th>Acción</th></tr>
    <?php foreach ($filas as $r): ?>
      <tr>
        <td><?= h(substr($r['creado_en'], 0, 10)) ?></td><td><?= h($r['email']) ?><br><?= h($r['nombre']) ?></td>
        <td><?= h($r['empresa']) ?><?= $r['perfil_completo'] ? '' : '<br><small>perfil incompleto</small>' ?></td>
        <td><?= h($r['cuit']) ?></td><td><?= h($r['ciudad']) ?></td><td><?= h($r['whatsapp']) ?></td>
        <td><span class="estado <?= h($r['cuenta']) ?>"><?= h(ucfirst($r['cuenta'])) ?></span><?= $r['activo'] ? '' : '<br><small>DESACTIVADO</small>' ?></td>
        <td>
          <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="cuenta"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <select name="cuenta"><?php foreach (['aprobada', 'pendiente', 'rechazada'] as $c): ?><option <?= $r['cuenta'] === $c ? 'selected' : '' ?>><?= $c ?></option><?php endforeach; ?></select>
            <button class="chico">OK</button></form>
          <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="activo"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <input type="hidden" name="activo" value="<?= $r['activo'] ? '' : '1' ?>"><button class="chico borde"><?= $r['activo'] ? 'Desactivar' : 'Reactivar' ?></button></form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>

<?php elseif ($tab === 'pedidos'): ?>
  <?php
    $d = isset($_GET['c']) ? cargar_pedido($_GET['c']) : null;
    $filas = $db->query('SELECT p.codigo, p.cantidad_kg, p.destino, p.estado, p.total, p.sena, p.creado_en, u.empresa FROM pedidos p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id DESC LIMIT 200')->fetchAll();
  ?>
  <?php if ($d): ?>
    <div class="tarjeta">
      <h3>Pedido <?= h($d['codigo']) ?> · <?= etiqueta_estado($d['estado']) ?></h3>
      <p><b><?= h($d['empresa']) ?></b> · <?= h($d['email']) ?> · WhatsApp <?= h($d['whatsapp']) ?></p>
      <p><?= h($d['producto']) ?> · <?= (int)$d['cantidad_kg'] ?> kg · destino <?= h($d['destino']) ?></p>
      <?php if ($d['total'] !== null): ?><p>Total <b><?= pesos($d['total']) ?></b> · seña 50 % <b><?= pesos($d['sena']) ?></b> · vence <?= h(substr((string)$d['vence_en'], 0, 10)) ?></p><?php endif; ?>
      <?php if ($d['pago_ref']): ?><p><b>Pago informado:</b> <?= h($d['pago_banco']) ?> · operación <?= h($d['pago_ref']) ?> · <?= h($d['pago_fecha']) ?> · <?= pesos($d['pago_monto']) ?> · condiciones aceptadas el <?= h($d['acepto_en']) ?> desde la IP <?= h($d['acepto_ip']) ?></p><?php endif; ?>
      <?php if ($d['embarcacion']): ?><p><b>Embarcación / transporte:</b> <?= h($d['embarcacion']) ?></p><?php endif; ?>

      <?php if (in_array($d['estado'], ['solicitada', 'proforma_emitida', 'vencida'])): ?>
        <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="proforma"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>">
          Precio por kilo $ <input name="precio" size="7" inputmode="decimal" required>
          <button class="chico"><?= $d['estado'] === 'solicitada' ? 'Emitir proforma' : 'Reemitir proforma' ?></button></form>
      <?php endif; ?>
      <?php if (in_array($d['estado'], ['proforma_emitida', 'sena_informada']) && $d['total'] !== null): ?>
        <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="sena_acreditada">
          <button class="chico">Confirmar: seña acreditada en el banco</button></form>
      <?php endif; ?>
      <?php if (($sig = SIGUIENTE[$d['estado']] ?? null)): ?>
        <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="<?= $sig ?>">
          <?php if ($sig === 'embarcada'): ?><input name="embarcacion" size="28" maxlength="100" placeholder="Embarcación o transporte" required><?php endif; ?>
          <button class="chico">Pasar a: <?= h(ESTADOS[$sig]) ?></button></form>
      <?php endif; ?>
      <?php if (!in_array($d['estado'], ['embarcada', 'cerrada', 'cancelada'])): ?>
        <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="accion" value="estado"><input type="hidden" name="c" value="<?= h($d['codigo']) ?>"><input type="hidden" name="nuevo" value="cancelada">
          <button class="chico borde">Cancelar pedido</button></form>
      <?php endif; ?>
      <p><a href="https://wa.me/<?= h(preg_replace('/\D/', '', (string)$d['whatsapp'])) ?>">Escribirle por WhatsApp</a></p>
    </div>
  <?php endif; ?>
  <div class="tabla"><table class="chica">
    <tr><th>Código</th><th>Fecha</th><th>Comercio</th><th>Kilos</th><th>Destino</th><th>Total</th><th>Seña</th><th>Estado</th></tr>
    <?php foreach ($filas as $r): ?>
      <tr><td><a href="?tab=pedidos&amp;c=<?= h(rawurlencode($r['codigo'])) ?>"><?= h($r['codigo']) ?></a></td><td><?= h(substr($r['creado_en'], 0, 10)) ?></td><td><?= h($r['empresa']) ?></td>
          <td><?= (int)$r['cantidad_kg'] ?></td><td><?= h($r['destino']) ?></td><td><?= $r['total'] !== null ? pesos($r['total']) : '—' ?></td>
          <td><?= $r['sena'] !== null ? pesos($r['sena']) : '—' ?></td><td><?= etiqueta_estado($r['estado']) ?></td></tr>
    <?php endforeach; ?>
  </table></div>

<?php elseif ($tab === 'accesos'): ?>
  <?php $filas = $db->query('SELECT * FROM accesos ORDER BY id DESC LIMIT 200')->fetchAll(); ?>
  <div class="tabla"><table class="chica">
    <tr><th>Fecha</th><th>Quién</th><th>Resultado</th><th>Motivo</th><th>IP</th></tr>
    <?php foreach ($filas as $r): ?>
      <tr><td><?= h($r['fecha']) ?></td><td><?= h($r['email']) ?></td><td><?= $r['exito'] ? 'OK' : '<b>FALLÓ</b>' ?></td><td><?= h($r['motivo']) ?></td><td><?= h($r['ip']) ?></td></tr>
    <?php endforeach; ?>
  </table></div>

<?php else: ?>
  <?php $filas = $db->query('SELECT * FROM consultas ORDER BY id DESC LIMIT 200')->fetchAll(); ?>
  <div class="tabla"><table class="chica">
    <tr><th>Fecha</th><th>Nombre</th><th>Email</th><th>WhatsApp</th><th>Mensaje</th></tr>
    <?php foreach ($filas as $r): ?>
      <tr><td><?= h($r['fecha']) ?></td><td><?= h($r['nombre']) ?></td><td><?= h($r['email']) ?></td><td><?= h($r['whatsapp']) ?></td><td><?= nl2br(h($r['mensaje'])) ?></td></tr>
    <?php endforeach; ?>
  </table></div>
<?php endif; ?>
</div>
<?php require '../inc/pie.php'; ?>
