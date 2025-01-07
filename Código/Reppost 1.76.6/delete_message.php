<?php
include('Config/dbcon.php');

if (isset($_POST['friend_id'])) {
    $friend_id = intval($_POST['friend_id']); // Asegurarse de que friend_id sea un número entero
    $session_id = $_SESSION['member_id'];  // Obtener el ID del usuario desde la sesión
    // Comprobar que tanto el session_id como el friend_id sean válidos
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

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir a la bandeja de mensajes después de eliminar la conversación
                header("Location: message.php");
                exit();
            } else {
                echo "Hubo un problema al eliminar la conversación.";
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de que la consulta falle
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Datos inválidos. No se pudo eliminar la conversación.";
    }
} else {
    echo "No se ha proporcionado un ID de amigo.";
}
