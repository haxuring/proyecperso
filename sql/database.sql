-- Base de datos del proyecto LoginRegister
-- Importa este archivo con: mysql -u root -p < sql/database.sql
-- O desde phpMyAdmin usando la pestaña "Importar".

CREATE DATABASE IF NOT EXISTS login_register_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE login_register_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_email (email)
) ENGINE = InnoDB;
