CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    precio DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    documento VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_clientes_documento (documento),
    UNIQUE KEY uk_clientes_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_detalle_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Semilla: migra tus 15 productos de datos-prueba.js a la base real
INSERT INTO productos (nombre, categoria, precio, stock) VALUES
('Balón de Fútbol Pro', 'Fútbol', 120000, 12),
('Guantes de Arquero', 'Fútbol', 85000, 4),
('Zapatillas Running', 'Running', 250000, 8),
('Camiseta Deportiva Seco', 'Textil', 45000, 25),
('Mancuernas 5kg (Par)', 'Gym', 95000, 15),
('Mat de Yoga Antideslizante', 'Yoga', 60000, 3),
('Banda Elástica de Resistencia', 'Gym', 25000, 30),
('Raqueta de Tenis Junior', 'Tenis', 180000, 5),
('Casco de Ciclismo Ruta', 'Ciclismo', 210000, 7),
('Botilcar / Caramañola 750ml', 'Accesorios', 20000, 2),
('Cuerda para Saltar de Alta Velocidad', 'Gym', 35000, 14),
('Rodillera Ortopédica Deportiva', 'Salud', 50000, 9),
('Medias Antideslizantes Fútbol', 'Fútbol', 22000, 40),
('Proteína Whey 2lb', 'Suplementos', 160000, 6),
('Gafas de Natación Anti-empañantes', 'Natación', 70000, 10);