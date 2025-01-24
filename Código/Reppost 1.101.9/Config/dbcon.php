<?php
date_default_timezone_set('America/Guayaquil'); // Zona horaria PHP
try {
    $conn = new PDO('mysql:host=localhost;dbname=reppostdb;charset=utf8mb4', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8mb4'");
    $conn->exec("SET time_zone = '-05:00'"); // Zona horaria MySQL
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
