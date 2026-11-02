-- ASHAB · esquema para MySQL 5.7+ / MariaDB 10.3+ (hosting clásico o XAMPP)
CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
  perfil_completo TINYINT NOT NULL DEFAULT 0,
  cuenta VARCHAR(12) NOT NULL DEFAULT 'pendiente',
  nota_admin TEXT,
  activo TINYINT NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL,
  ultimo_login DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS log_accesos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NULL,
  identificador VARCHAR(120) NOT NULL,
  metodo VARCHAR(12) NOT NULL,
  exito TINYINT NOT NULL,
  motivo VARCHAR(120) NULL,
  ip VARCHAR(45) NOT NULL,
  pais VARCHAR(2) NULL,
  user_agent VARCHAR(250) NULL,
  fecha DATETIME NOT NULL,
  INDEX idx_log_ip (ip, fecha), INDEX idx_log_fecha (fecha),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_es VARCHAR(120) NOT NULL, nombre_en VARCHAR(120) NOT NULL, nombre_ar VARCHAR(120) NOT NULL,
  descripcion_es VARCHAR(300) NOT NULL, descripcion_en VARCHAR(300) NOT NULL, descripcion_ar VARCHAR(300) NOT NULL,
  presentacion VARCHAR(60) NOT NULL,
  precio_fob_kg DECIMAL(8,2) NOT NULL,
  activo TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(16) NOT NULL UNIQUE,
  usuario_id INT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  cantidad_kg INT UNSIGNED NOT NULL,
  destino VARCHAR(5) NOT NULL,
  incoterm VARCHAR(4) NOT NULL DEFAULT 'FOB',
  notas_cliente TEXT NULL,
  precio_kg DECIMAL(10,2) NULL,
  total_usd DECIMAL(12,2) NULL,
  sena_usd DECIMAL(12,2) NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'solicitada',
  vence_en DATETIME NULL,
  terminos_aceptados_en DATETIME NULL,
  terminos_ip VARCHAR(45) NULL,
  pago_banco VARCHAR(120) NULL,
  pago_ref VARCHAR(120) NULL,
  pago_fecha VARCHAR(12) NULL,
  pago_monto DECIMAL(12,2) NULL,
  pago_informado_en DATETIME NULL,
  sena_acreditada_en DATETIME NULL,
  creado_en DATETIME NOT NULL,
  actualizado_en DATETIME NOT NULL,
  INDEX idx_pedidos_usuario (usuario_id, estado),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS consultas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  empresa VARCHAR(120) NULL,
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(30) NULL,
  mensaje TEXT NOT NULL,
  idioma CHAR(2) NOT NULL DEFAULT 'es',
  fecha DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) PRIMARY KEY,
  data TEXT NOT NULL,
  updated_at BIGINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
