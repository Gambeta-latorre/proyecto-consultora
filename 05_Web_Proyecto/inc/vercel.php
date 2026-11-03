<?php
// SOLO para la versión publicada en Vercel. En XAMPP este archivo no se usa.
// Vercel no tiene MySQL ni guarda archivos entre visitas, por eso:
//   1) se conecta a Postgres (Neon) con la dirección que llega en DATABASE_URL,
//   2) crea las tablas la primera vez (esquema "ashab"),
//   3) guarda las sesiones en la base en lugar de archivos.

$url = getenv('DATABASE_URL_UNPOOLED') ?: getenv('DATABASE_URL');
$p = parse_url($url);
parse_str($p['query'] ?? '', $q);
$dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;sslmode=%s', $p['host'], $p['port'] ?? 5432, ltrim($p['path'], '/'), $q['sslmode'] ?? 'prefer');
if (str_ends_with($p['host'], '.neon.tech')) { // Neon necesita saber el nombre del proyecto
    $dsn .= ";options='endpoint=" . preg_replace('/-pooler$/', '', explode('.', $p['host'])[0]) . "'";
}
$db = new PDO($dsn, urldecode($p['user']), urldecode($p['pass'] ?? ''), [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Las tablas de este sitio van en su propio esquema, separadas de cualquier otra cosa que haya en la base.
$db->exec('CREATE SCHEMA IF NOT EXISTS ashab');
$db->exec('SET search_path TO ashab');

// Primera visita: se crean las tablas (mismas que database.sql, escritas para Postgres) y los datos iniciales.
if (!$db->query("SELECT to_regclass('ashab.pedidos')")->fetchColumn()) {
    $db->exec("
      CREATE TABLE usuarios (
        id SERIAL PRIMARY KEY, email VARCHAR(120) NOT NULL UNIQUE, google_sub VARCHAR(64), nombre VARCHAR(100) NOT NULL DEFAULT '',
        empresa VARCHAR(120), cuit VARCHAR(13), ciudad VARCHAR(80), whatsapp VARCHAR(25),
        rol VARCHAR(10) NOT NULL DEFAULT 'cliente' CHECK (rol IN ('cliente','admin')),
        cuenta VARCHAR(10) NOT NULL DEFAULT 'pendiente' CHECK (cuenta IN ('pendiente','aprobada','rechazada')),
        perfil_completo SMALLINT NOT NULL DEFAULT 0, activo SMALLINT NOT NULL DEFAULT 1,
        creado_en TIMESTAMP NOT NULL, ultimo_ingreso TIMESTAMP);
      CREATE TABLE productos (
        id SERIAL PRIMARY KEY, nombre VARCHAR(80) NOT NULL, descripcion VARCHAR(200) NOT NULL, presentacion VARCHAR(60) NOT NULL,
        precio_publico DECIMAL(10,2) NOT NULL, precio_mayorista_kg DECIMAL(10,2) NOT NULL, activo SMALLINT NOT NULL DEFAULT 1);
      CREATE TABLE pedidos (
        id SERIAL PRIMARY KEY, codigo VARCHAR(10) NOT NULL UNIQUE,
        usuario_id INT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
        producto_id INT NOT NULL REFERENCES productos(id),
        cantidad_kg INT NOT NULL, destino VARCHAR(80) NOT NULL,
        precio_kg DECIMAL(10,2) NOT NULL, total DECIMAL(12,2) NOT NULL,
        estado VARCHAR(20) NOT NULL DEFAULT 'solicitada' CHECK (estado IN ('solicitada','en_produccion','embarcada','cerrada','cancelada')),
        embarcacion VARCHAR(100), creado_en TIMESTAMP NOT NULL);
      CREATE TABLE consultas (
        id SERIAL PRIMARY KEY, nombre VARCHAR(80) NOT NULL, email VARCHAR(120) NOT NULL,
        whatsapp VARCHAR(30) NOT NULL, mensaje TEXT NOT NULL, fecha TIMESTAMP NOT NULL);
      CREATE TABLE accesos (
        id SERIAL PRIMARY KEY, usuario_id INT REFERENCES usuarios(id) ON DELETE SET NULL,
        email VARCHAR(120) NOT NULL, exito SMALLINT NOT NULL, motivo VARCHAR(80) NOT NULL DEFAULT '',
        ip VARCHAR(100) NOT NULL, fecha TIMESTAMP NOT NULL);
      CREATE INDEX ON accesos (ip, fecha);
      CREATE TABLE sesiones (id VARCHAR(128) PRIMARY KEY, datos TEXT NOT NULL, actualizado INT NOT NULL);

      INSERT INTO usuarios (email, nombre, rol, cuenta, perfil_completo, activo, creado_en)
        VALUES ('juancruzceresani@gmail.com', 'Juan Cruz Ceresani', 'admin', 'aprobada', 1, 1, CURRENT_TIMESTAMP);
      INSERT INTO productos (nombre, descripcion, presentacion, precio_publico, precio_mayorista_kg) VALUES
      ('ASHAB Tradicional', 'Yerba mate con palo, sabor intenso y clásico, la de todos los días.', 'Paquete 1 kg · caja de 10 kg', 7500, 5200),
      ('ASHAB Suave', 'Poco palo y molienda fina, un mate más suave y parejo.', 'Paquete 500 g · caja de 10 kg', 8200, 5600),
      ('ASHAB Serrana', 'Yerba compuesta con menta y boldo, fresca y aromática.', 'Paquete 500 g · caja de 10 kg', 8600, 5900),
      ('ASHAB Premium', 'Hoja seleccionada con 24 meses de estacionamiento.', 'Paquete 1 kg · caja de 12 kg', 9800, 6800);
    ");
}

// Sesiones guardadas en la tabla "sesiones" (en Vercel cada visita puede caer en un servidor distinto).
class SesionesEnBase implements SessionHandlerInterface {
    public function __construct(private PDO $db) {}
    public function open(string $ruta, string $nombre): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false {
        $st = $this->db->prepare('SELECT datos FROM sesiones WHERE id = ?');
        $st->execute([$id]);
        return (string)$st->fetchColumn();
    }
    public function write(string $id, string $datos): bool {
        $this->db->prepare('INSERT INTO sesiones (id, datos, actualizado) VALUES (?, ?, ?)
                            ON CONFLICT (id) DO UPDATE SET datos = EXCLUDED.datos, actualizado = EXCLUDED.actualizado')
                 ->execute([$id, $datos, time()]);
        return true;
    }
    public function destroy(string $id): bool {
        $this->db->prepare('DELETE FROM sesiones WHERE id = ?')->execute([$id]);
        return true;
    }
    public function gc(int $segundos): int|false {
        $st = $this->db->prepare('DELETE FROM sesiones WHERE actualizado < ?');
        $st->execute([time() - $segundos]);
        return $st->rowCount();
    }
}
session_set_save_handler(new SesionesEnBase($db), true);
session_set_cookie_params(['httponly' => true, 'secure' => ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https', 'samesite' => 'Lax']);
