<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Usuario logueado
        $user_id = $session_id;
        // Eliminar notificaciones del usuario
        $deleteQuery = $conn->prepare("DELETE FROM notifications WHERE user_id = :user_id");
        $deleteQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteQuery->execute();
        header("Location: config_preferences.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al eliminar las notificaciones: " . $e->getMessage();
    }
}
