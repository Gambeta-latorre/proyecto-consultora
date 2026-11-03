-- ASHAB · base de datos para MySQL / MariaDB (XAMPP).
-- Cómo usarla: en phpMyAdmin, pestaña "Importar", elegir este archivo. Crea la base "ashab" y todo lo necesario.
CREATE DATABASE IF NOT EXISTS ashab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ashab;
SET NAMES utf8mb4; -- para que respete tildes y ñ al importar

-- Quiénes entran al sitio. El ingreso es con Google; el administrador aprueba cada cuenta.
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(120) NOT NULL UNIQUE,
  google_sub VARCHAR(64) NULL,
  nombre VARCHAR(100) NOT NULL DEFAULT '',
  empresa VARCHAR(120) NULL,
  cuit VARCHAR(13) NULL,
  ciudad VARCHAR(80) NULL,
  whatsapp VARCHAR(25) NULL,
  rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  cuenta ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  perfil_completo TINYINT NOT NULL DEFAULT 0,
  activo TINYINT NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL,
  ultimo_ingreso DATETIME NULL
) ENGINE=InnoDB;

-- Catálogo de yerbas: precio al público y precio mayorista por kilo.
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  descripcion VARCHAR(200) NOT NULL,
  presentacion VARCHAR(60) NOT NULL,
  precio_publico DECIMAL(10,2) NOT NULL,
  precio_mayorista_kg DECIMAL(10,2) NOT NULL,
  activo TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- Pedidos mayoristas. Se paga una seña del 50 % y recién ahí se produce y se embarca.
CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(10) NOT NULL UNIQUE,
  usuario_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad_kg INT NOT NULL,
  destino VARCHAR(80) NOT NULL,
  precio_kg DECIMAL(10,2) NULL,
  total DECIMAL(12,2) NULL,
  sena DECIMAL(12,2) NULL,
  estado ENUM('solicitada','proforma_emitida','sena_informada','sena_acreditada','en_produccion','embarcada','cerrada','cancelada','vencida') NOT NULL DEFAULT 'solicitada',
  vence_en DATETIME NULL,
  pago_banco VARCHAR(80) NULL,
  pago_ref VARCHAR(80) NULL,
  pago_fecha DATE NULL,
  pago_monto DECIMAL(12,2) NULL,
  acepto_en DATETIME NULL,
  acepto_ip VARCHAR(100) NULL,
  embarcacion VARCHAR(100) NULL,
  creado_en DATETIME NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

CREATE TABLE consultas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  whatsapp VARCHAR(30) NOT NULL,
  mensaje TEXT NOT NULL,
  fecha DATETIME NOT NULL
) ENGINE=InnoDB;

-- Registro de ingresos (quién, cuándo, desde qué IP y si salió bien).
CREATE TABLE accesos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NULL,
  email VARCHAR(120) NOT NULL,
  exito TINYINT NOT NULL,
  motivo VARCHAR(80) NOT NULL DEFAULT '',
  ip VARCHAR(100) NOT NULL,
  fecha DATETIME NOT NULL,
  INDEX (ip, fecha),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Datos iniciales: el administrador (se aprueba solo) y las 4 yerbas.
INSERT INTO usuarios (email, nombre, rol, cuenta, perfil_completo, activo, creado_en)
VALUES ('juancruzceresani@gmail.com', 'Juan Cruz Ceresani', 'admin', 'aprobada', 1, 1, NOW());

INSERT INTO productos (nombre, descripcion, presentacion, precio_publico, precio_mayorista_kg) VALUES
('ASHAB Tradicional', 'Yerba mate con palo, sabor intenso y clásico, la de todos los días.', 'Paquete 1 kg · caja de 10 kg', 7500, 5200),
('ASHAB Suave', 'Poco palo y molienda fina, un mate más suave y parejo.', 'Paquete 500 g · caja de 10 kg', 8200, 5600),
('ASHAB Serrana', 'Yerba compuesta con menta y boldo, fresca y aromática.', 'Paquete 500 g · caja de 10 kg', 8600, 5900),
('ASHAB Premium', 'Hoja seleccionada con 24 meses de estacionamiento.', 'Paquete 1 kg · caja de 12 kg', 9800, 6800);
