-- ASHAB · base de datos para MySQL (XAMPP).
-- Cómo usarla: en phpMyAdmin, pestaña "Importar", elegir este archivo.
-- Crea la base "ashab", la tabla "productos" con las 4 yerbas y la tabla "usuarios" para el inicio de sesión.
CREATE DATABASE IF NOT EXISTS ashab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ashab;
SET NAMES utf8mb4; -- para que respete tildes y ñ al importar

-- Si se importa dos veces, se borra la tabla vieja para no repetir las yerbas.
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;

-- Catálogo de yerbas: precio al público (por paquete) y precio mayorista (por kilo).
CREATE TABLE productos (
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

-- Usuarios que se registran en el sitio. La contraseña se guarda encriptada con password_hash (nunca en texto).
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,   -- con este email se inicia sesión: no se puede repetir
  contrasena VARCHAR(255) NOT NULL,     -- hash de la contraseña
  fecha_registro DATETIME NOT NULL
) ENGINE=InnoDB;

-- Usuario de prueba para la demostración. Email: demo@ashab.com · Contraseña: ashab123
INSERT INTO usuarios (nombre, email, contrasena, fecha_registro) VALUES
('Almacén Demo', 'demo@ashab.com', '$2y$10$ydpVB3l8vSVleZnlfaZCsedwEgkaB.mgVY7TXVVtO0/s/V7/Rizui', NOW());
