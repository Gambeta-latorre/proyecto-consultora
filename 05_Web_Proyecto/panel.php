<?php
// Mi cuenta: estado de la cuenta y lista de pedidos.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Mi cuenta';
$activa = 'Mi cuenta';
$yo = exigir_perfil();
vencer_proformas();
$st = $db->prepare('SELECT p.codigo, p.cantidad_kg, p.destino, p.estado, p.total, p.creado_en, pr.nombre AS producto
                    FROM pedidos p JOIN productos pr ON pr.id = p.producto_id WHERE p.usuario_id = ? ORDER BY p.id DESC');
$st->execute([$yo['id']]);
$pedidos = $st->fetchAll();
require 'inc/cabecera.php';
?>
<div class="pagina">
  <h1>Hola, <?= h($yo['nombre'] ?: $yo['email']) ?></h1>
  <p><?= h($yo['empresa']) ?> · <?= h($yo['email']) ?></p>
  <div class="cuenta <?= h($yo['cuenta']) ?>">
    <b>Estado de la cuenta:</b> <?= h(ucfirst($yo['cuenta'])) ?>
    <?php if ($yo['cuenta'] === 'pendiente'): ?><p>Estamos revisando tus datos. Cuando la aprobemos vas a poder ver precios mayoristas y pedir proforma.</p>
    <?php elseif ($yo['cuenta'] === 'rechazada'): ?><p>No pudimos aprobar tu cuenta. Escribinos por WhatsApp.</p><?php endif; ?>
    <?php if ($yo['rol'] !== 'admin'): ?><p><a href="perfil.php">Editar datos del comercio</a></p><?php endif; ?>
  </div>

  <h2>Mis pedidos</h2>
  <?php if (!$pedidos): ?>
    <p>Todavía no hiciste pedidos. Mirá los <a href="productos.php">productos</a>.</p>
  <?php else: ?>
    <div class="tabla"><table>
      <tr><th>Código</th><th>Fecha</th><th>Yerba</th><th>Kilos</th><th>Destino</th><th>Total</th><th>Estado</th></tr>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td><a href="pedido.php?c=<?= h(rawurlencode($p['codigo'])) ?>"><?= h($p['codigo']) ?></a></td>
          <td><?= h(substr($p['creado_en'], 0, 10)) ?></td><td><?= h($p['producto']) ?></td>
          <td><?= (int)$p['cantidad_kg'] ?></td><td><?= h($p['destino']) ?></td>
          <td><?= $p['total'] !== null ? pesos($p['total']) : '—' ?></td><td><?= etiqueta_estado($p['estado']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</div>
<?php require 'inc/pie.php'; ?>
