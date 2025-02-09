<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $friend_id   = filter_var($_POST['friend_id'], FILTER_VALIDATE_INT);
        $my_message  = htmlspecialchars($_POST['my_message'], ENT_QUOTES, 'UTF-8');
        $conn->beginTransaction();
        // Insertar el mensaje en la tabla messages
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content, date_sent) VALUES (:session_id, :friend_id, :my_message, NOW())");
        $stmt->bindParam(':session_id',  $session_id,  PDO::PARAM_INT);
        $stmt->bindParam(':friend_id',   $friend_id,   PDO::PARAM_INT);
        $stmt->bindParam(':my_message',  $my_message,  PDO::PARAM_STR);
        $stmt->execute();
        $last_message_id = $conn->lastInsertId();
        // Obtener el nombre de quien envía
        $sender_query = $conn->prepare("SELECT firstname, lastname FROM members WHERE member_id = :session_id");
        $sender_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $sender_query->execute();
        $sender = $sender_query->fetch(PDO::FETCH_ASSOC);
        $sender_name = $sender['firstname'] . ' ' . $sender['lastname'];
        // Crear notificación
        $notification_link    = "message.php";
        $notification_message = "$sender_name te envió un mensaje.";
        $notification_stmt = $conn->prepare(" INSERT INTO notifications (user_id, message, link, date_created) VALUES (:friend_id, :message, :link, NOW())");
        $notification_stmt->bindParam(':friend_id',  $friend_id,           PDO::PARAM_INT);
        $notification_stmt->bindParam(':message',    $notification_message, PDO::PARAM_STR);
        $notification_stmt->bindParam(':link',       $notification_link,    PDO::PARAM_STR);
        $notification_stmt->execute();
        $conn->commit();
        // Respuesta JSON para tu lógica Ajax
        $message_html = "";
        echo json_encode(['status' => 'success', 'message_html' => $message_html]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error al enviar el mensaje: ' . $e->getMessage()
        ]);
    }
}
