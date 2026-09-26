CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    clave_hash CHAR(255) NOT NULL,
    rol ENUM('administrador','vendedor','consultor') NOT NULL DEFAULT 'consultor',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    bloqueado_hasta DATETIME NULL DEFAULT NULL,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_usuarios_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE intentos_acceso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(150) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    exitoso TINYINT(1) NOT NULL DEFAULT 0,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_intentos_correo_fecha (correo, creado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;