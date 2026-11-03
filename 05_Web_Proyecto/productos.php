<?php
// Catálogo. Cualquiera ve los precios al público; los clientes aprobados ven el precio mayorista y piden presupuesto.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Productos';
$activa = 'Productos';
$yo = usuario_actual();
$aprobado = $yo && $yo['cuenta'] === 'aprobada' && ($yo['rol'] === 'admin' || $yo['perfil_completo']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yo = exigir_perfil();
    $kg = (int)($_POST['kg'] ?? 0);
    $destino = trim($_POST['destino'] ?? '');
    $st = $db->prepare('SELECT COUNT(*) FROM pedidos WHERE usuario_id = ? AND estado IN (\'solicitada\',\'proforma_emitida\',\'sena_informada\',\'sena_acreditada\',\'en_produccion\')');
    $st->execute([$yo['id']]);
    $producto = $db->prepare('SELECT id FROM productos WHERE id = ? AND activo = 1');
    $producto->execute([(int)($_POST['producto_id'] ?? 0)]);

    if ($yo['cuenta'] !== 'aprobada')                $error = 'Tu cuenta todavía no fue aprobada.';
    elseif ($kg < MIN_KG || $kg > MAX_KG)            $error = 'La cantidad debe estar entre ' . MIN_KG . ' y ' . MAX_KG . ' kg.';
    elseif ($destino === '' || mb_strlen($destino) > 80) $error = 'Indicá la ciudad o país de destino.';
    elseif ($st->fetchColumn() >= MAX_ABIERTOS)      $error = 'Ya tenés ' . MAX_ABIERTOS . ' pedidos en curso. Esperá a que se cierren.';
    elseif (!($pid = $producto->fetchColumn()))      $error = 'Producto inexistente.';
    else {
        $codigo = 'ASH-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $db->prepare('INSERT INTO pedidos (codigo, usuario_id, producto_id, cantidad_kg, destino, estado, creado_en) VALUES (?, ?, ?, ?, ?, ?, ?)')
           ->execute([$codigo, $yo['id'], $pid, $kg, $destino, 'solicitada', date('Y-m-d H:i:s')]);
        aviso('Pedido ' . $codigo . ' enviado. Te mandamos la proforma por WhatsApp.');
        ir('panel.php');
    }
}
$productos = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id')->fetchAll();
require 'inc/cabecera.php';
?>
<h1 style="margin-top:1em">Productos</h1>
<p>Precios por paquete al público. Para comprar por mayor, ingresá con tu cuenta y pedí tu proforma.</p>
<?php if ($error): ?><div class="aviso error"><?= h($error) ?></div><?php endif; ?>
<div class="grilla">
<?php foreach ($productos as $p): ?>
  <article class="tarjeta producto">
    <h3><?= h($p['nombre']) ?></h3>
    <p><?= h($p['descripcion']) ?></p>
    <p><small><?= h($p['presentacion']) ?></small></p>
    <p class="precio">Al público: <?= pesos($p['precio_publico']) ?></p>
    <?php if ($aprobado): ?>
      <p class="precio">Mayorista: <?= pesos($p['precio_mayorista_kg']) ?> / kg</p>
      <form method="post" class="formulario">
        <?= csrf_campo() ?>
        <input type="hidden" name="producto_id" value="<?= (int)$p['id'] ?>">
        <label>Kilos (<?= MIN_KG ?> a <?= MAX_KG ?>)<input type="number" name="kg" min="<?= MIN_KG ?>" max="<?= MAX_KG ?>" step="10" value="<?= MIN_KG ?>" required></label>
        <label>Destino<input name="destino" maxlength="80" placeholder="Ciudad o país" required></label>
        <button class="chico">Pedir proforma</button>
      </form>
    <?php elseif ($yo): ?>
      <p class="bloqueado"><a href="panel.php">Tu cuenta está pendiente de aprobación</a></p>
    <?php else: ?>
      <p class="bloqueado"><a href="login.php">Ingresá para ver el precio mayorista</a></p>
    <?php endif; ?>
  </article>
<?php endforeach; ?>
</div>
<?php require 'inc/pie.php'; ?>
