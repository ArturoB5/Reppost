<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=reppostdb;charset=utf8mb4', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
