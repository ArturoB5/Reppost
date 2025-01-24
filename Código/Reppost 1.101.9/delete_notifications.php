<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json');

try {
    $user_id = $session_id;
    $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e) {
    error_log("Error al eliminar notificaciones: " . $e->getMessage());
}
