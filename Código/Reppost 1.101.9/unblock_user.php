<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if (!isset($_SESSION['id'])) {
    die("Debes iniciar sesión para desbloquear usuarios.");
}

$session_id = $_SESSION['id'];

if (isset($_POST['block_id'])) {
    $block_id = intval($_POST['block_id']);

    // Eliminar el registro en blocked_users
    $delete = $conn->prepare("
        DELETE FROM blocked_users 
        WHERE block_id = :block_id
          AND user_id = :session_id
    ");
    $delete->bindParam(':block_id', $block_id, PDO::PARAM_INT);
    $delete->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $delete->execute();

    // Guardar el mensaje en la sesión y redirigir a config_preferences.php
    $_SESSION['status'] = "Usuario desbloqueado correctamente.";
    header("Location: config_preferences.php");
    exit;
} else {
    die("No se ha especificado el usuario a desbloquear.");
}
