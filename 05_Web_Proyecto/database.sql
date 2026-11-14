-- ASHAB · base de datos para MySQL (XAMPP).
-- Cómo usarla: en phpMyAdmin, pestaña "Importar", elegir este archivo.
-- Crea la base "ashab", la tabla "productos" y carga las 4 yerbas.
CREATE DATABASE IF NOT EXISTS ashab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ashab;
SET NAMES utf8mb4; -- para que respete tildes y ñ al importar

-- Catálogo de yerbas: precio al público (por paquete) y precio mayorista (por kilo).
CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  descripcion VARCHAR(200) NOT NULL,
  presentacion VARCHAR(60) NOT NULL,
  precio_publico DECIMAL(10,2) NOT NULL,
  precio_mayorista_kg DECIMAL(10,2) NOT NULL,
  activo TINYINT NOT NULL DEFAULT 1   -- 1 = se muestra en la página, 0 = oculto
) ENGINE=InnoDB;

INSERT INTO productos (nombre, descripcion, presentacion, precio_publico, precio_mayorista_kg) VALUES
('ASHAB Tradicional', 'Yerba mate con palo, sabor intenso y clásico, la de todos los días.', 'Paquete 1 kg · caja de 10 kg', 7500, 5200),
('ASHAB Suave', 'Poco palo y molienda fina, un mate más suave y parejo.', 'Paquete 500 g · caja de 10 kg', 8200, 5600),
('ASHAB Serrana', 'Yerba compuesta con menta y boldo, fresca y aromática.', 'Paquete 500 g · caja de 10 kg', 8600, 5900),
('ASHAB Premium', 'Hoja seleccionada con 24 meses de estacionamiento.', 'Paquete 1 kg · caja de 12 kg', 9800, 6800);
