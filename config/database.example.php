<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'login_register_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function db_conectar(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $opciones);
    } catch (PDOException) {
        exit('Error de conexión con la base de datos.');
    }
}
