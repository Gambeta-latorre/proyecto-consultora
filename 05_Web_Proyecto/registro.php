<?php
// Registro: crea la cuenta de un cliente y la guarda en la tabla usuarios de MySQL.
require 'inc/config.php';
require 'inc/conexion.php';
$titulo = 'Crear cuenta';
$activa = 'Ingresar';
$error = '';
$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {          // el formulario se envió
    $nombre     = trim($_POST['nombre'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar  = $_POST['confirmar'] ?? '';

    // Validaciones: si algo falla, se vuelve a mostrar el formulario con el motivo
    if ($nombre === '' || $email === '' || $contrasena === '') $error = 'Completá todos los campos.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))         $error = 'El email no es válido.';
    elseif (strlen($contrasena) < 6)                           $error = 'La contraseña debe tener al menos 6 caracteres.';
    elseif ($contrasena !== $confirmar)                        $error = 'Las contraseñas no coinciden.';
    else {
        try {
            // Consulta preparada: los datos van aparte (con ?) y no se pueden mezclar con el SQL
            $consulta = $db->prepare('INSERT INTO usuarios (nombre, email, contrasena, fecha_registro) VALUES (?, ?, ?, NOW())');
            $consulta->execute([$nombre, $email, password_hash($contrasena, PASSWORD_DEFAULT)]);
            header('Location: login.php?registrado=1');  // cuenta creada: va al inicio de sesión
            exit;
        } catch (PDOException $e) {
            // Código 23000 = se repitió un valor UNIQUE: ese email ya está registrado
            if ($e->getCode() == 23000 || $e->getCode() == 23505) $error = 'Ya existe una cuenta con ese email.';
            else throw $e;
        }
    }
}
require 'inc/cabecera.php';
?>
<div class="pagina angosta">
  <h1>Crear cuenta</h1>
  <p>Registrate para ver el precio por mayor de cada yerba.</p>
  <?php if ($error): ?><p class="aviso error"><?= h($error) ?></p><?php endif; ?>
  <form method="post" class="formulario">
    <label>Nombre o comercio <input name="nombre" value="<?= h($nombre) ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= h($email) ?>" required></label>
    <label>Contraseña (mínimo 6) <input type="password" name="contrasena" required></label>
    <label>Repetir contraseña <input type="password" name="confirmar" required></label>
    <button class="boton">Crear cuenta</button>
  </form>
  <p>¿Ya tenés cuenta? <a href="login.php">Ingresá</a></p>
</div>
<?php require 'inc/pie.php'; ?>
