-- ASHAB · esquema para PostgreSQL (Neon, Supabase, Vercel Postgres)
CREATE TABLE IF NOT EXISTS usuarios (
  id SERIAL PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  google_sub VARCHAR(64) UNIQUE,
  nombre VARCHAR(120) NOT NULL DEFAULT '',
  foto_url VARCHAR(300),
  idioma CHAR(2) NOT NULL DEFAULT 'en',
  empresa VARCHAR(120),
  pais VARCHAR(5),
  ciudad VARCHAR(80),
  cargo VARCHAR(80),
  registro_comercial VARCHAR(60),
  whatsapp VARCHAR(25),
  sitio_web VARCHAR(160),
  perfil_completo SMALLINT NOT NULL DEFAULT 0,
  cuenta VARCHAR(12) NOT NULL DEFAULT 'pendiente',
  nota_admin TEXT,
  activo SMALLINT NOT NULL DEFAULT 1,
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

CREATE TABLE IF NOT EXISTS productos (
  id SERIAL PRIMARY KEY,
  nombre_es VARCHAR(120) NOT NULL, nombre_en VARCHAR(120) NOT NULL, nombre_ar VARCHAR(120) NOT NULL,
  descripcion_es VARCHAR(300) NOT NULL, descripcion_en VARCHAR(300) NOT NULL, descripcion_ar VARCHAR(300) NOT NULL,
  presentacion VARCHAR(60) NOT NULL,
  precio_fob_kg NUMERIC(8,2) NOT NULL,
  activo SMALLINT NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS pedidos (
  id SERIAL PRIMARY KEY,
  codigo VARCHAR(16) NOT NULL UNIQUE,
  usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
  producto_id INTEGER NOT NULL REFERENCES productos(id),
  cantidad_kg INTEGER NOT NULL,
  destino VARCHAR(5) NOT NULL,
  incoterm VARCHAR(4) NOT NULL DEFAULT 'FOB',
  notas_cliente TEXT,
  precio_kg NUMERIC(10,2),
  total_usd NUMERIC(12,2),
  sena_usd NUMERIC(12,2),
  estado VARCHAR(20) NOT NULL DEFAULT 'solicitada',
  vence_en TIMESTAMP,
  terminos_aceptados_en TIMESTAMP,
  terminos_ip VARCHAR(45),
  pago_banco VARCHAR(120),
  pago_ref VARCHAR(120),
  pago_fecha VARCHAR(12),
  pago_monto NUMERIC(12,2),
  pago_informado_en TIMESTAMP,
  sena_acreditada_en TIMESTAMP,
  creado_en TIMESTAMP NOT NULL,
  actualizado_en TIMESTAMP NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_pedidos_usuario ON pedidos (usuario_id, estado);

CREATE TABLE IF NOT EXISTS consultas (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  empresa VARCHAR(120),
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(30),
  mensaje TEXT NOT NULL,
  idioma CHAR(2) NOT NULL DEFAULT 'es',
  fecha TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) PRIMARY KEY,
  data TEXT NOT NULL,
  updated_at BIGINT NOT NULL
);
