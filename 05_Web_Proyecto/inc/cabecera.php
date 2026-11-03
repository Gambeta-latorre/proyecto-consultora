<?php
// Cabecera común. Antes de incluirla cada página define $titulo, $activa y $raiz ('' o '../').
$yo = usuario_actual();
$menu = ['index.php' => 'Inicio', 'productos.php' => 'Productos', 'contacto.php' => 'Contacto'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($titulo) ?> · ASHAB</title>
  <meta name="description" content="ASHAB: yerba mate argentina. Venta mayorista con seña del 50 %.">
  <link rel="stylesheet" href="<?= $raiz ?>css/estilos.css">
  <link rel="icon" href="<?= $raiz ?>img/logo.svg">
</head>
<body>
<header>
  <div class="barra">
    <a class="marca" href="<?= $raiz ?>index.php"><img src="<?= $raiz ?>img/logo.svg" alt=""><span><b>ASHAB</b><small>Yerba mate</small></span></a>
    <!-- Menú para celular sin JavaScript: el checkbox abre y cierra la lista -->
    <input type="checkbox" id="abrir-menu">
    <label for="abrir-menu" class="hamburguesa" aria-label="Abrir menú">☰</label>
    <nav>
      <?php foreach ($menu as $archivo => $nombre): ?>
        <a href="<?= $raiz . $archivo ?>" class="<?= $nombre === $activa ? 'activa' : '' ?>"><?= $nombre ?></a>
      <?php endforeach; ?>
      <?php if ($yo): ?>
        <?php if ($yo['rol'] === 'admin'): ?><a href="<?= $raiz ?>admin/index.php" class="<?= $activa === 'Administración' ? 'activa' : '' ?>">Administración</a><?php endif; ?>
        <a href="<?= $raiz ?>panel.php" class="boton chico">Mi cuenta</a>
        <a href="<?= $raiz ?>salir.php">Salir</a>
      <?php else: ?>
        <a href="<?= $raiz ?>login.php" class="boton chico">Ingresar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
<?php if ($a = aviso()): ?><div class="aviso <?= h($a[1]) ?>"><?= h($a[0]) ?></div><?php endif; ?>
