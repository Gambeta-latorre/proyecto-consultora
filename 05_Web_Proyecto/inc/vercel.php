<?php
// SOLO para el sitio publicado en Vercel (en XAMPP este archivo no se usa).
// Vercel no tiene MySQL: se usa una base PostgreSQL (Neon) con la misma tabla "productos".
$p = parse_url(getenv('DATABASE_URL'));
parse_str($p['query'] ?? '', $q);
$dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;sslmode=%s', $p['host'], $p['port'] ?? 5432, ltrim($p['path'], '/'), $q['sslmode'] ?? 'require');
if (str_ends_with($p['host'], '.neon.tech')) { // Neon pide el nombre del proyecto
    $dsn .= ";options='endpoint=" . preg_replace('/-pooler$/', '', explode('.', $p['host'])[0]) . "'";
}
$db = new PDO($dsn, urldecode($p['user']), urldecode($p['pass'] ?? ''));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// La tabla va en un esquema propio. La primera vez se crea y se cargan las 4 yerbas (igual que database.sql).
$db->exec('CREATE SCHEMA IF NOT EXISTS ashab_web');
$db->exec('SET search_path TO ashab_web');
if (!$db->query("SELECT to_regclass('ashab_web.productos')")->fetchColumn()) {
    $db->exec("CREATE TABLE productos (
        id SERIAL PRIMARY KEY, nombre VARCHAR(80) NOT NULL, descripcion VARCHAR(200) NOT NULL,
        presentacion VARCHAR(60) NOT NULL, precio_publico DECIMAL(10,2) NOT NULL,
        precio_mayorista_kg DECIMAL(10,2) NOT NULL, activo SMALLINT NOT NULL DEFAULT 1)");
    $sql = file_get_contents(__DIR__ . '/../database.sql');
    $inicio = strpos($sql, 'INSERT INTO productos');
    $db->exec(substr($sql, $inicio, strpos($sql, ';', $inicio) - $inicio + 1));
}
if (!$db->query("SELECT to_regclass('ashab_web.usuarios')")->fetchColumn()) {
    $db->exec("CREATE TABLE usuarios (id SERIAL PRIMARY KEY, nombre VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE, contrasena VARCHAR(255) NOT NULL, fecha_registro TIMESTAMP NOT NULL)");
}
