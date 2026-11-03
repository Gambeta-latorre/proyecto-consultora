<?php
// Conexión a la base de datos con PDO.
// En la computadora (XAMPP) se usa MySQL: alcanzan los valores por defecto (root sin contraseña).
// En la versión publicada en Vercel no hay MySQL: llega la variable DATABASE_URL y se usa inc/vercel.php.
if (getenv('DATABASE_URL')) {
    require __DIR__ . '/vercel.php';
    return;
}

$host  = getenv('DB_HOST') ?: 'localhost';
$base  = getenv('DB_NAME') ?: 'ashab';
$user  = getenv('DB_USER') ?: 'root';
$clave = getenv('DB_PASS') ?: '';

$db = new PDO("mysql:host=$host;dbname=$base;charset=utf8mb4", $user, $clave, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // si una consulta falla, se ve el error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // las filas vuelven como arrays con nombre
]);
