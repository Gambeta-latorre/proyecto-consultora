-- Base de datos del sitio de Veterinaria Patitas Felices
-- Importar en phpMyAdmin (pestaña Importar) o con: mysql -u root < database.sql

SET NAMES utf8mb4;  -- para que los acentos se guarden bien

DROP DATABASE IF EXISTS patitas_felices;
CREATE DATABASE patitas_felices CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE patitas_felices;

-- Clientes de la veterinaria y el administrador
CREATE TABLE usuarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(120) NOT NULL UNIQUE,
  clave_hash VARCHAR(255) NOT NULL,             -- nunca se guarda la contraseña, solo su hash
  nombre     VARCHAR(80)  NOT NULL,
  whatsapp   VARCHAR(30)  NOT NULL,
  rol        ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  activo     TINYINT(1) NOT NULL DEFAULT 1,     -- el admin puede bloquear una cuenta
  creado_en  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Servicios de la veterinaria (se muestran con precio en el inicio)
CREATE TABLE servicios (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nombre  VARCHAR(80)   NOT NULL,
  detalle VARCHAR(200)  NOT NULL,
  precio  DECIMAL(10,2) NOT NULL               -- en pesos
) ENGINE=InnoDB;

-- Turnos (cada turno dura 30 minutos)
CREATE TABLE turnos (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT NOT NULL,
  servicio_id INT NOT NULL,
  mascota     VARCHAR(60) NOT NULL,
  especie     ENUM('Perro','Gato','Ovino','Bovino') NOT NULL,   -- tipo de animal
  fecha       DATE NOT NULL,
  hora        TIME NOT NULL,
  estado      ENUM('reservado','atendido','cancelado') NOT NULL DEFAULT 'reservado',
  creado_en   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id)  REFERENCES usuarios(id)  ON DELETE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE RESTRICT,
  INDEX (fecha, hora)
) ENGINE=InnoDB;

-- Consultas del formulario de contacto (se responden por WhatsApp)
CREATE TABLE consultas (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  nombre   VARCHAR(80)  NOT NULL,
  email    VARCHAR(120) NOT NULL,
  whatsapp VARCHAR(30)  NOT NULL,
  mensaje  TEXT NOT NULL,
  fecha    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Registro de cada intento de ingreso (auditoría y bloqueo por intentos)
CREATE TABLE accesos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NULL,
  email      VARCHAR(120) NOT NULL,
  exito      TINYINT(1) NOT NULL,
  ip         VARCHAR(45) NOT NULL,
  fecha      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  INDEX (email, fecha)
) ENGINE=InnoDB;

-- ---------- Datos de prueba ----------
-- Contraseñas: admin -> Admin2026 | cliente de prueba -> Demo2026
INSERT INTO usuarios (email, clave_hash, nombre, whatsapp, rol) VALUES
('admin@patitasfelices.test', '$2y$10$w4zFmO6Lkczn/gIOmERAeejpwccaFR5rBdWsetoD5H85uVxJgEOwi', 'Recepción', '+54 9 11 3348-6017', 'admin'),
('sofia@correo.test',         '$2y$10$7/Z4nWNaFcOEQDDx0M2QVOsp6qhz1g2JNAlJRyjc96ZXI96eNQDHW', 'Sofía Pérez', '+54 9 11 4000-0002', 'cliente');

INSERT INTO servicios (nombre, detalle, precio) VALUES
('Consulta clínica',    'Revisión general de perros, gatos, ovejas y vacas.',                18000),
('Vacunación',          'Vacunas anuales y antirrábica, con libreta.',        15000),
('Baño y corte',        'Baño, corte de pelo y uñas.',                        22000),
('Control de cachorro', 'Primer control, desparasitación y plan de vacunas.', 16000);

INSERT INTO turnos (usuario_id, servicio_id, mascota, especie, fecha, hora) VALUES
(2, 2, 'Toby', 'Perro', CURDATE() + INTERVAL 2 DAY, '10:00');
