<?php
// SOLO para la versión publicada en Vercel. En XAMPP este archivo no se usa.
// Vercel no tiene MySQL ni guarda archivos entre visitas, por eso:
//   1) se conecta a Postgres (Neon) con la dirección que llega en DATABASE_URL,
//   2) crea las tablas la primera vez (esquema "patitas"),
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
$db->exec('CREATE SCHEMA IF NOT EXISTS patitas');
$db->exec('SET search_path TO patitas');

// Primera visita: se crean las tablas (mismas que database.sql, escritas para Postgres) y los datos de prueba.
if (!$db->query("SELECT to_regclass('patitas.turnos')")->fetchColumn()) {
    $db->exec("
      CREATE TABLE usuarios (
        id SERIAL PRIMARY KEY, email VARCHAR(120) NOT NULL UNIQUE, clave_hash VARCHAR(255) NOT NULL,
        nombre VARCHAR(80) NOT NULL, whatsapp VARCHAR(30) NOT NULL,
        rol VARCHAR(10) NOT NULL DEFAULT 'cliente' CHECK (rol IN ('cliente','admin')),
        activo SMALLINT NOT NULL DEFAULT 1, creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
      CREATE TABLE servicios (
        id SERIAL PRIMARY KEY, nombre VARCHAR(80) NOT NULL, detalle VARCHAR(200) NOT NULL, precio DECIMAL(10,2) NOT NULL);
      CREATE TABLE turnos (
        id SERIAL PRIMARY KEY,
        usuario_id INT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
        servicio_id INT NOT NULL REFERENCES servicios(id) ON DELETE RESTRICT,
        mascota VARCHAR(60) NOT NULL,
        especie VARCHAR(10) NOT NULL CHECK (especie IN ('Perro','Gato','Ovino','Bovino')),
        fecha DATE NOT NULL, hora CHAR(5) NOT NULL,
        estado VARCHAR(10) NOT NULL DEFAULT 'reservado' CHECK (estado IN ('reservado','atendido','cancelado')),
        creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
      CREATE INDEX ON turnos (fecha, hora);
      CREATE TABLE consultas (
        id SERIAL PRIMARY KEY, nombre VARCHAR(80) NOT NULL, email VARCHAR(120) NOT NULL,
        whatsapp VARCHAR(30) NOT NULL, mensaje TEXT NOT NULL, fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
      CREATE TABLE accesos (
        id SERIAL PRIMARY KEY, usuario_id INT NULL REFERENCES usuarios(id) ON DELETE SET NULL,
        email VARCHAR(120) NOT NULL, exito SMALLINT NOT NULL, ip VARCHAR(100) NOT NULL,
        fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
      CREATE INDEX ON accesos (email, fecha);
      CREATE TABLE sesiones (id VARCHAR(128) PRIMARY KEY, datos TEXT NOT NULL, actualizado INT NOT NULL);

      INSERT INTO usuarios (email, clave_hash, nombre, whatsapp, rol) VALUES
      ('admin@patitasfelices.test', '\$2y\$10\$w4zFmO6Lkczn/gIOmERAeejpwccaFR5rBdWsetoD5H85uVxJgEOwi', 'Recepción', '+54 9 11 3348-6017', 'admin'),
      ('sofia@correo.test', '\$2y\$10\$7/Z4nWNaFcOEQDDx0M2QVOsp6qhz1g2JNAlJRyjc96ZXI96eNQDHW', 'Sofía Pérez', '+54 9 11 4000-0002', 'cliente');
      INSERT INTO servicios (nombre, detalle, precio) VALUES
      ('Consulta clínica', 'Revisión general de perros, gatos, ovejas y vacas.', 18000),
      ('Vacunación', 'Vacunas anuales y antirrábica, con libreta.', 15000),
      ('Baño y corte', 'Baño, corte de pelo y uñas.', 22000),
      ('Control de cachorro', 'Primer control, desparasitación y plan de vacunas.', 16000);
      INSERT INTO turnos (usuario_id, servicio_id, mascota, especie, fecha, hora) VALUES (2, 2, 'Toby', 'Perro', CURRENT_DATE + 2, '10:00');
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
