<?php
include('Config/dbcon.php');
session_start();

if (!isset($_SESSION['id'])) {
    die("Debes iniciar sesión para bloquear usuarios.");
}

if (isset($_POST['blocked_id'])) {
    $blocked_id = intval($_POST['blocked_id']);
    $user_id = intval($_SESSION['id']);

    // Verificamos si ya existe un registro de bloqueo
    $checkQuery = $conn->prepare("
        SELECT * FROM blocked_users
        WHERE user_id = :user_id
          AND blocked_id = :blocked_id
    ");
    $checkQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkQuery->bindParam(':blocked_id', $blocked_id, PDO::PARAM_INT);
    $checkQuery->execute();

    if ($checkQuery->rowCount() > 0) {
        // Ya existe el bloqueo
        // Guardar mensaje en sesión y redirigir
        $_SESSION['status'] = "Ya has bloqueado a este usuario.";
        header("Location: profile.php");
        exit;
    } else {
        // Insertar nuevo registro de bloqueo
        $insertQuery = $conn->prepare("
            INSERT INTO blocked_users (user_id, blocked_id) 
            VALUES (:user_id, :blocked_id)
        ");
        $insertQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertQuery->bindParam(':blocked_id', $blocked_id, PDO::PARAM_INT);
        $insertQuery->execute();

        // Guardar mensaje en sesión y redirigir
        $_SESSION['status'] = "Has bloqueado un usuario.";
        header("Location: profile.php");
        exit;
    }
} else {
    die("No se ha especificado el usuario a bloquear.");
}
