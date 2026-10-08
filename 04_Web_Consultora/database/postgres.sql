-- Rumbo Global · esquema para PostgreSQL (Neon, Supabase, Vercel Postgres)
CREATE TABLE IF NOT EXISTS usuarios (
  id SERIAL PRIMARY KEY,
  dni VARCHAR(8) UNIQUE,
  email VARCHAR(160) UNIQUE,
  google_sub VARCHAR(64) UNIQUE,
  nombre VARCHAR(120) NOT NULL DEFAULT '',
  apellido VARCHAR(80) NOT NULL DEFAULT '',
  foto_url VARCHAR(300),
  password_hash VARCHAR(255),
  activo SMALLINT NOT NULL DEFAULT 1,
  intentos_fallidos SMALLINT NOT NULL DEFAULT 0,
  bloqueado_hasta TIMESTAMP,
  creado_en TIMESTAMP NOT NULL,
  ultimo_login TIMESTAMP
);

CREATE TABLE IF NOT EXISTS log_accesos (
  id BIGSERIAL PRIMARY KEY,
  usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
  identificador VARCHAR(120) NOT NULL,
  metodo VARCHAR(12) NOT NULL,
  exito SMALLINT NOT NULL,
  motivo VARCHAR(120),
  ip VARCHAR(45) NOT NULL,
  pais VARCHAR(2),
  user_agent VARCHAR(250),
  fecha TIMESTAMP NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_log_ip ON log_accesos (ip, fecha);
CREATE INDEX IF NOT EXISTS idx_log_fecha ON log_accesos (fecha);

CREATE TABLE IF NOT EXISTS proyectos (
  id SERIAL PRIMARY KEY,
  usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
  nombre VARCHAR(150) NOT NULL,
  estado VARCHAR(40) NOT NULL DEFAULT 'En diagnóstico',
  avance SMALLINT NOT NULL DEFAULT 0,
  fecha_inicio VARCHAR(10) NOT NULL
);

CREATE TABLE IF NOT EXISTS consultas (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(30),
  mensaje TEXT NOT NULL,
  fecha TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) PRIMARY KEY,
  data TEXT NOT NULL,
  updated_at BIGINT NOT NULL
);
