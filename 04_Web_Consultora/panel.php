<?php
// Panel del socio: solo se ve con la sesión iniciada. Resume servicios, precios y clientes.
require_once 'inc/datos.php';
if (!logueado()) {                 // sin sesión, vuelve al inicio de sesión
    header('Location: login.php');
    exit;
}
$titulo = 'Panel';
$activa = 'Panel';
require 'inc/cabecera.php';
?>
<h1>Panel del socio</h1>
<p>Sesión iniciada como <strong><?= h(ADMIN_EMAIL) ?></strong>.</p>

<h2>Servicios y precios de referencia</h2>
<table>
  <tr><th>Servicio</th><th>Precio</th></tr>
  <?php foreach (SERVICIOS as $nombre => [$detalle, $precio]): ?>
    <tr><td><?= h($nombre) ?></td><td><?= h($precio) ?></td></tr>
  <?php endforeach; ?>
</table>

<h2>Clientes</h2>
<table>
  <tr><th>Cliente</th><th>Trabajo</th><th>Sitio</th></tr>
  <tr><td>ASHAB · yerba mate</td><td>Catálogo con MySQL, cuentas de clientes y pedidos por WhatsApp</td><td><a href="<?= URL_ASHAB ?>"><?= URL_ASHAB ?></a></td></tr>
</table>
<?php require 'inc/pie.php'; ?>
