-- Script mínimo para preparar la base de datos de autenticación.
-- Ejecuta en MySQL/MariaDB: mysql -u root -p < database.sql

-- Crea la base de datos con UTF-8 completo
CREATE DATABASE IF NOT EXISTS evap_auth
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE evap_auth;

-- Tabla de usuarios: id, username único y hash de contraseña
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Usuario demo (admin / Admin123!) si no existe ya
INSERT INTO users (username, password_hash)
VALUES
  ('admin', '$2y$10$9X3qMP.Z36Iegx6m.kyZvOjj6alUjng7jkbwTIIlaKhSxC9HZxeLK')
ON DUPLICATE KEY UPDATE username = VALUES(username);
