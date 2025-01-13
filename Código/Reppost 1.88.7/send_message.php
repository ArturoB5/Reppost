<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $friend_id = filter_var($_POST['friend_id'], FILTER_VALIDATE_INT);
        $my_message = htmlspecialchars($_POST['my_message'], ENT_QUOTES, 'UTF-8');
        // Iniciar una transacción para garantizar la consistencia
        $conn->beginTransaction();
        // Inserta el mensaje en la base de datos
        $stmt = $conn->prepare("INSERT INTO message(receiver_id, content, date_sended, sender_id) 
                                VALUES(:friend_id, :my_message, NOW(), :session_id)");
        $stmt->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
        $stmt->bindParam(':my_message', $my_message, PDO::PARAM_STR);
        $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $stmt->execute();
        // Recuperar el ID del mensaje recién enviado
        $last_message_id = $conn->lastInsertId();
        // Crear la notificación para el receptor del mensaje
        $notification_message = "Has recibido un nuevo mensaje.";
        $notification_stmt = $conn->prepare("INSERT INTO notifications(user_id, message, date_created) 
                                             VALUES(:friend_id, :message, NOW())");
        $notification_stmt->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
        $notification_stmt->bindParam(':message', $notification_message, PDO::PARAM_STR);
        $notification_stmt->execute();
        // Confirmar la transacción
        $conn->commit();
        // Devolver el mensaje como JSON
        $message_html = "";
        echo json_encode(['status' => 'success', 'message_html' => $message_html]);
    } catch (Exception $e) {
        // En caso de error, deshacer la transacción
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar el mensaje: ' . $e->getMessage()]);
    }
}
