<?php
date_default_timezone_set('America/Guayaquil');
try {
    $conn = new PDO('mysql:host=localhost;dbname=reppostdb;charset=utf8mb4', 'root', '');
    //$conn = new PDO('pgsql:host=localhost;port=5432;dbname=reppostdb', 'postgres', '100401');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8mb4'"); // Codificación MySQL
    //$conn->exec("SET NAMES 'UTF8'"); // Codififcación Postgres
    $conn->exec("SET time_zone = '-05:00'"); // Zona horaria MySQL
    //$conn->exec("SET TIME ZONE 'America/Guayaquil'"); // Zona horaria Postgres
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
