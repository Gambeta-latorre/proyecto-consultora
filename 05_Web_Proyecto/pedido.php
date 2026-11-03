<?php
// Un pedido: precio, seña del 50 %, datos para transferir e informe del pago.
$raiz = '';
require 'inc/funciones.php';
$titulo = 'Pedido';
$activa = 'Mi cuenta';
$yo = exigir_perfil();
vencer_proformas();
$codigo = $_GET['c'] ?? $_POST['c'] ?? '';
$p = cargar_pedido($codigo, $yo['id']);
if (!$p) { aviso('Pedido inexistente.', 'error'); ir('panel.php'); }
$error = '';
$v = ['pago_banco' => '', 'pago_ref' => '', 'pago_fecha' => date('Y-m-d'), 'pago_monto' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'cancelar' && in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida'])) {
        $db->prepare("UPDATE pedidos SET estado = 'cancelada' WHERE id = ?")->execute([$p['id']]);
        aviso('Pedido cancelado.');
        ir('panel.php');
    }
    if ($accion === 'informar_pago' && $p['estado'] === 'proforma_emitida') {
        foreach ($v as $campo => $_) $v[$campo] = trim($_POST[$campo] ?? '');
        $fecha = DateTime::createFromFormat('!Y-m-d', $v['pago_fecha']);
        $monto = (float)str_replace(',', '.', $v['pago_monto']);
        if ($v['pago_banco'] === '' || $v['pago_ref'] === '' || mb_strlen($v['pago_banco']) > 80 || mb_strlen($v['pago_ref']) > 80) $error = 'Completá el banco y el número de operación.';
        elseif (!$fecha || $fecha->format('Y-m-d') !== $v['pago_fecha'] || $v['pago_fecha'] > date('Y-m-d'))                      $error = 'La fecha del pago no es válida.';
        elseif ($monto <= 0 || $monto > $p['total'])                                                                             $error = 'El monto no es válido.';
        elseif (empty($_POST['acepto']))                                                                                         $error = 'Tenés que aceptar las condiciones.';
        else {
            $db->prepare("UPDATE pedidos SET estado = 'sena_informada', pago_banco = ?, pago_ref = ?, pago_fecha = ?, pago_monto = ?, acepto_en = ?, acepto_ip = ? WHERE id = ?")
               ->execute([$v['pago_banco'], $v['pago_ref'], $v['pago_fecha'], $monto, date('Y-m-d H:i:s'), ip_cliente(), $p['id']]);
            aviso('Pago informado. Lo verificamos y te avisamos por WhatsApp.');
            ir('pedido.php?c=' . rawurlencode($p['codigo']));
        }
    }
    $p = cargar_pedido($codigo, $yo['id']);
}
require 'inc/cabecera.php';
?>
<div class="pagina media">
  <p><a href="panel.php">&larr; Mis pedidos</a></p>
  <h1>Pedido <?= h($p['codigo']) ?></h1>
  <p><?= etiqueta_estado($p['estado']) ?></p>
  <?php if ($error): ?><div class="aviso error"><?= h($error) ?></div><?php endif; ?>

  <table class="datos">
    <tr><th>Yerba</th><td><?= h($p['producto']) ?> (<?= h($p['presentacion']) ?>)</td></tr>
    <tr><th>Cantidad</th><td><?= (int)$p['cantidad_kg'] ?> kg</td></tr>
    <tr><th>Destino</th><td><?= h($p['destino']) ?></td></tr>
    <?php if ($p['total'] !== null): ?>
      <tr><th>Precio por kilo</th><td><?= pesos($p['precio_kg']) ?></td></tr>
      <tr><th>Total</th><td><b><?= pesos($p['total']) ?></b></td></tr>
      <tr><th>Seña (50 %)</th><td><b><?= pesos($p['sena']) ?></b></td></tr>
      <tr><th>Saldo antes de la entrega</th><td><?= pesos($p['total'] - $p['sena']) ?></td></tr>
      <?php if ($p['estado'] === 'proforma_emitida'): ?><tr><th>Proforma válida hasta</th><td><?= h(substr($p['vence_en'], 0, 10)) ?></td></tr><?php endif; ?>
    <?php endif; ?>
    <?php if ($p['embarcacion']): ?><tr><th>Embarcación / transporte</th><td><?= h($p['embarcacion']) ?></td></tr><?php endif; ?>
  </table>

  <?php if ($p['estado'] === 'solicitada'): ?>
    <p class="aviso ok">Recibimos tu pedido. En breve te enviamos la proforma por WhatsApp.</p>
  <?php elseif ($p['estado'] === 'vencida'): ?>
    <p class="aviso error">La proforma venció. Podés hacer un pedido nuevo desde <a href="productos.php">Productos</a>.</p>
  <?php elseif ($p['estado'] === 'proforma_emitida'): ?>
    <h2>Cómo pagar la seña</h2>
    <ol class="pasos">
      <li>Transferí <b><?= pesos($p['sena']) ?></b> (el 50 %) a esta cuenta antes del vencimiento.</li>
      <li>Completá el formulario de abajo con los datos de la transferencia.</li>
      <li>Verificamos el pago y recién ahí producimos tu pedido.</li>
      <li>El saldo se paga antes de la entrega. Si cancelás con la producción iniciada, la seña no se devuelve.</li>
    </ol>
    <pre class="banco"><?= h(datos_bancarios()) ?></pre>
    <h3>Informar el pago</h3>
    <form method="post" class="formulario">
      <?= csrf_campo() ?><input type="hidden" name="c" value="<?= h($p['codigo']) ?>"><input type="hidden" name="accion" value="informar_pago">
      <label>Banco desde el que transferiste<input name="pago_banco" value="<?= h($v['pago_banco']) ?>" maxlength="80" required></label>
      <label>Número de operación<input name="pago_ref" value="<?= h($v['pago_ref']) ?>" maxlength="80" required></label>
      <div class="dos">
        <label>Fecha<input name="pago_fecha" value="<?= h($v['pago_fecha']) ?>" maxlength="10" placeholder="2026-12-31" required></label>
        <label>Monto transferido<input name="pago_monto" value="<?= h($v['pago_monto']) ?>" inputmode="decimal" placeholder="<?= h(number_format($p['sena'], 2, '.', '')) ?>" required></label>
      </div>
      <label class="casilla"><input type="checkbox" name="acepto" value="1"> <span>Acepto las condiciones: seña del 50 %, saldo antes de la entrega y sin devolución de la seña si la producción ya empezó.</span></label>
      <button>Informar pago</button>
    </form>
  <?php elseif ($p['estado'] === 'sena_informada'): ?>
    <p class="aviso ok">Pago informado: <?= h($p['pago_banco']) ?> · operación <?= h($p['pago_ref']) ?> · <?= h($p['pago_fecha']) ?> · <?= pesos($p['pago_monto']) ?>. Lo estamos verificando.</p>
  <?php elseif ($p['estado'] === 'sena_acreditada'): ?>
    <p class="aviso ok">Seña acreditada. Tu pedido entra en producción.</p>
  <?php endif; ?>

  <?php if (in_array($p['estado'], ['solicitada', 'proforma_emitida', 'vencida'])): ?>
    <form method="post" class="en-linea"><?= csrf_campo() ?><input type="hidden" name="c" value="<?= h($p['codigo']) ?>"><input type="hidden" name="accion" value="cancelar"><button class="chico borde">Cancelar pedido</button></form>
  <?php endif; ?>
  <p><a href="https://wa.me/<?= WHATSAPP ?>?text=<?= rawurlencode('Hola, consulta por el pedido ' . $p['codigo']) ?>">Consultar este pedido por WhatsApp &rarr;</a></p>
</div>
<?php require 'inc/pie.php'; ?>
