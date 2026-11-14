<?php
// Conexión a la base de datos MySQL con PDO.
// En XAMPP alcanzan los valores por defecto: servidor localhost, usuario root, sin contraseña.
// (Solo en el sitio publicado en Vercel llega DATABASE_URL y se usa inc/vercel.php.)
if (getenv('DATABASE_URL')) {
    require __DIR__ . '/vercel.php';
    return;
}

$servidor = 'localhost';
$base     = 'ashab';
$usuario  = 'root';
$clave    = '';

try {
    $db = new PDO("mysql:host=$servidor;dbname=$base;charset=utf8mb4", $usuario, $clave);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       // si una consulta falla, avisa
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // filas como arrays con nombre
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo conectar con la base de datos. Revisá que MySQL esté encendido en XAMPP y que la base "ashab" esté importada.');
}
