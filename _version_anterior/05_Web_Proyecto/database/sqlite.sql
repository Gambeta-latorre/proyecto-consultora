-- ASHAB · esquema para SQLite (solo pruebas locales sin instalar MySQL)
CREATE TABLE IF NOT EXISTS usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL UNIQUE,
  google_sub TEXT UNIQUE,
  nombre TEXT NOT NULL DEFAULT '',
  foto_url TEXT,
  idioma TEXT NOT NULL DEFAULT 'en',
  empresa TEXT,
  pais TEXT,
  ciudad TEXT,
  cargo TEXT,
  registro_comercial TEXT,
  whatsapp TEXT,
  sitio_web TEXT,
  perfil_completo INTEGER NOT NULL DEFAULT 0,
  cuenta TEXT NOT NULL DEFAULT 'pendiente',
  nota_admin TEXT,
  activo INTEGER NOT NULL DEFAULT 1,
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

CREATE TABLE IF NOT EXISTS productos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre_es TEXT NOT NULL, nombre_en TEXT NOT NULL, nombre_ar TEXT NOT NULL,
  descripcion_es TEXT NOT NULL, descripcion_en TEXT NOT NULL, descripcion_ar TEXT NOT NULL,
  presentacion TEXT NOT NULL,
  precio_fob_kg REAL NOT NULL,
  activo INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS pedidos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  codigo TEXT NOT NULL UNIQUE,
  usuario_id INTEGER NOT NULL,
  producto_id INTEGER NOT NULL,
  cantidad_kg INTEGER NOT NULL,
  destino TEXT NOT NULL,
  incoterm TEXT NOT NULL DEFAULT 'FOB',
  notas_cliente TEXT,
  precio_kg REAL,
  total_usd REAL,
  sena_usd REAL,
  estado TEXT NOT NULL DEFAULT 'solicitada',
  vence_en TEXT,
  terminos_aceptados_en TEXT,
  terminos_ip TEXT,
  pago_banco TEXT,
  pago_ref TEXT,
  pago_fecha TEXT,
  pago_monto REAL,
  pago_informado_en TEXT,
  sena_acreditada_en TEXT,
  creado_en TEXT NOT NULL,
  actualizado_en TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS consultas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre TEXT NOT NULL,
  empresa TEXT,
  email TEXT NOT NULL,
  telefono TEXT,
  mensaje TEXT NOT NULL,
  idioma TEXT NOT NULL DEFAULT 'es',
  fecha TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
  id TEXT PRIMARY KEY,
  data TEXT NOT NULL,
  updated_at INTEGER NOT NULL
);
