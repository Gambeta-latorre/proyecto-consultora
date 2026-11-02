<?php
// Conexión a MySQL con PDO. En XAMPP alcanzan los valores por defecto (root sin contraseña).
// En un hosting se cambian acá o con variables de entorno.
$host  = getenv('DB_HOST') ?: 'localhost';
$base  = getenv('DB_NAME') ?: 'patitas_felices';
$user  = getenv('DB_USER') ?: 'root';
$clave = getenv('DB_PASS') ?: '';

$db = new PDO("mysql:host=$host;dbname=$base;charset=utf8mb4", $user, $clave, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // si una consulta falla, se ve el error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // las filas vuelven como arrays con nombre
]);
