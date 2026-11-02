-- Rumbo Global · esquema SQLite (solo pruebas locales)
CREATE TABLE IF NOT EXISTS usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  dni TEXT UNIQUE,
  email TEXT UNIQUE,
  google_sub TEXT UNIQUE,
  nombre TEXT NOT NULL DEFAULT '',
  apellido TEXT NOT NULL DEFAULT '',
  foto_url TEXT,
  password_hash TEXT,
  activo INTEGER NOT NULL DEFAULT 1,
  intentos_fallidos INTEGER NOT NULL DEFAULT 0,
  bloqueado_hasta TEXT,
  creado_en TEXT NOT NULL,
  ultimo_login TEXT
);

CREATE TABLE IF NOT EXISTS log_accesos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario_id INTEGER,
  identificador TEXT NOT NULL,
  metodo TEXT NOT NULL,
  exito INTEGER NOT NULL,
  motivo TEXT,
  ip TEXT NOT NULL,
  pais TEXT,
  user_agent TEXT,
  fecha TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS proyectos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario_id INTEGER NOT NULL,
  nombre TEXT NOT NULL,
  estado TEXT NOT NULL DEFAULT 'En diagnóstico',
  avance INTEGER NOT NULL DEFAULT 0,
  fecha_inicio TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS consultas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre TEXT NOT NULL,
  email TEXT NOT NULL,
  telefono TEXT,
  mensaje TEXT NOT NULL,
  fecha TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
  id TEXT PRIMARY KEY,
  data TEXT NOT NULL,
  updated_at INTEGER NOT NULL
);
