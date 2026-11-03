<?php
// Un pedido: detalle y botones para enviarlo por WhatsApp o mail. El pago se acuerda por esos medios.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Pedido';
$activa = 'Mi cuenta';
$yo = exigir_perfil();
$codigo = $_GET['c'] ?? $_POST['c'] ?? '';
$p = cargar_pedido($codigo, $yo['id']);
if (!$p) { aviso('Pedido inexistente.', 'error'); ir('panel.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'cancelar' && $p['estado'] === 'solicitada') {
    $db->prepare("UPDATE pedidos SET estado = 'cancelada' WHERE id = ?")->execute([$p['id']]);
    aviso('Pedido cancelado.');
    ir('panel.php');
}
$mensaje = mensaje_pedido($p);
require 'inc/cabecera.php';
?>
<div class="pagina media">
  <p><a href="panel.php">&larr; Mis pedidos</a></p>
  <h1>Pedido <?= h($p['codigo']) ?></h1>
  <p><?= etiqueta_estado($p['estado']) ?></p>

  <table class="datos">
    <tr><th>Yerba</th><td><?= h($p['producto']) ?> (<?= h($p['presentacion']) ?>)</td></tr>
    <tr><th>Cantidad</th><td><?= (int)$p['cantidad_kg'] ?> kg</td></tr>
    <tr><th>Destino</th><td><?= h($p['destino']) ?></td></tr>
    <tr><th>Precio por kilo</th><td><?= pesos($p['precio_kg']) ?></td></tr>
    <tr><th>Total</th><td><b><?= pesos($p['total']) ?></b></td></tr>
    <?php if ($p['embarcacion']): ?><tr><th>Embarcación / transporte</th><td><?= h($p['embarcacion']) ?></td></tr><?php endif; ?>
  </table>

  <?php if ($p['estado'] === 'solicitada'): ?>
    <h2>Confirmá y acordá el pago</h2>
    <p>El pago se arregla directamente con nosotros. Enviá el detalle del pedido por el medio que prefieras; el mensaje ya está escrito:</p>
    <pre class="banco"><?= h($mensaje) ?></pre>
    <p>
      <a class="boton" href="https://wa.me/<?= WHATSAPP ?>?text=<?= rawurlencode($mensaje) ?>">Enviar por WhatsApp</a>
      <a class="boton borde" href="mailto:<?= EMAIL_PEDIDOS ?>?subject=<?= rawurlencode('Pedido ' . $p['codigo']) ?>&amp;body=<?= rawurlencode($mensaje) ?>">Enviar por mail</a>
    </p>
    <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="c" value="<?= h($p['codigo']) ?>"><input type="hidden" name="accion" value="cancelar"><button class="chico borde">Cancelar pedido</button></form>
  <?php else: ?>
    <p><a href="https://wa.me/<?= WHATSAPP ?>?text=<?= rawurlencode('Hola, consulta por el pedido ' . $p['codigo']) ?>">Consultar este pedido por WhatsApp &rarr;</a></p>
  <?php endif; ?>
</div>
<?php require 'inc/pie.php'; ?>
