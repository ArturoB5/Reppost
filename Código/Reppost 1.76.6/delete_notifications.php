<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json');
$user_id = $session_id; // ID del usuario actual
try {
    // Eliminar las notificaciones del usuario actual
    $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // No se envía ninguna respuesta al cliente
} catch (PDOException $e) {
    // Solo registrar errores en el log del servidor
    error_log("Error al eliminar notificaciones: " . $e->getMessage());
}
