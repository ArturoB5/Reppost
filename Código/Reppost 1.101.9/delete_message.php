<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if (isset($_POST['friend_id'])) {
    $friend_id = intval($_POST['friend_id']);
    $session_id = $_SESSION['member_id'];
    if ($session_id && $friend_id) {
        try {
            // Eliminar los mensajes entre el usuario y su amigo
            $stmt = $conn->prepare("
                DELETE FROM message 
                WHERE (sender_id = :session_id AND receiver_id = :friend_id) 
                OR (sender_id = :friend_id AND receiver_id = :session_id)
            ");
            $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $stmt->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                header("Location: message.php");
                exit();
            } else {
                echo "Hubo un problema al eliminar la conversación.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Datos inválidos. No se pudo eliminar la conversación.";
    }
} else {
    echo "No se ha proporcionado un ID de amigo.";
}
