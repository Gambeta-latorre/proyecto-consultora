<?php
// Inicio de sesión: busca el email en la tabla usuarios y compara la contraseña con su hash.
require 'inc/config.php';
require 'inc/conexion.php';
$titulo = 'Ingresar';
$activa = 'Ingresar';
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $consulta = $db->prepare('SELECT id, nombre, email, contrasena FROM usuarios WHERE email = ?');
    $consulta->execute([$email]);
    $usuario = $consulta->fetch();                  // la fila del usuario, o false si no existe

    // Mismo mensaje si falla el email o la contraseña: no se revela cuál de los dos está mal
    if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
        $error = 'Email o contraseña incorrectos.';
    } else {
        session_regenerate_id(true);                // nuevo id de sesión: evita que alguien use uno robado
        $_SESSION['usuario'] = ['id' => $usuario['id'], 'nombre' => $usuario['nombre'], 'email' => $usuario['email']];
        header('Location: productos.php');
        exit;
    }
}
require 'inc/cabecera.php';
?>
<div class="pagina angosta">
  <h1>Ingresar</h1>
  <?php if (isset($_GET['registrado'])): ?><p class="aviso ok">Cuenta creada. Ya podés ingresar.</p><?php endif; ?>
  <?php if ($error): ?><p class="aviso error"><?= h($error) ?></p><?php endif; ?>
  <form method="post" class="formulario">
    <label>Email <input type="email" name="email" value="<?= h($email) ?>" required></label>
    <label>Contraseña <input type="password" name="contrasena" required></label>
    <button class="boton">Ingresar</button>
  </form>
  <p>¿No tenés cuenta? <a href="registro.php">Creá una</a></p>
</div>
<?php require 'inc/pie.php'; ?>
