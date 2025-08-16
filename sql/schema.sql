CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL,
  clave VARCHAR(100) NOT NULL
);

CREATE TABLE socios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(100),
  telefono VARCHAR(20),
  estado ENUM('activo', 'inactivo', 'moroso') DEFAULT 'activo'
);

CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  socio_id INT,
  fecha_pago DATE,
  monto DECIMAL(10, 2),
  metodo_pago VARCHAR(50),
  observaciones TEXT,
  FOREIGN KEY (socio_id) REFERENCES socios(id)
);



-- Insertar un usuario administrador (la contraseña es 'admin123')
INSERT INTO usuarios (usuario, clave)
VALUES ('admin', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8byQn3H.1ErwUVoG5qUqMGd0XewAey');

-- Insertar socios de prueba
INSERT INTO socios (nombre, email, telefono, estado) VALUES
('Carlos Pérez', 'carlos@example.com', '612345678', 'activo'),
('Ana Gómez', 'ana@example.com', '622345678', 'moroso'),
('Luis Martínez', 'luis@example.com', '632345678', 'inactivo');

-- Insertar pagos para los socios
INSERT INTO pagos (socio_id, fecha_pago, monto, metodo_pago, observaciones) VALUES
(1, '2025-05-01', 30.00, 'efectivo', 'Pago mensual mayo'),
(1, '2025-04-01', 30.00, 'tarjeta', 'Pago mensual abril'),
(2, '2025-03-15', 30.00, 'bizum', 'Pago parcial marzo');
