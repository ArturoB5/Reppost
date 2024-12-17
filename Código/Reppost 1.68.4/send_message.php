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
        $notification_stmt = $conn->prepare("INSERT INTO notifications(user_id, message, date_created, read_status) 
                                             VALUES(:friend_id, :message, NOW(), 0)");
        $notification_stmt->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
        $notification_stmt->bindParam(':message', $notification_message, PDO::PARAM_STR);
        $notification_stmt->execute();
        // Recuperar el mensaje recién enviado para generar el HTML
        $query = $conn->prepare("
            SELECT message.*, sender.firstname AS sender_firstname, sender.lastname AS sender_lastname,
                   receiver.firstname AS receiver_firstname, receiver.lastname AS receiver_lastname
            FROM message
            LEFT JOIN members AS sender ON message.sender_id = sender.member_id
            LEFT JOIN members AS receiver ON message.receiver_id = receiver.member_id
            WHERE message.message_id = :last_message_id
        ");
        $query->bindParam(':last_message_id', $last_message_id, PDO::PARAM_INT);
        $query->execute();
        $new_message = $query->fetch();
        $is_sent = $new_message['sender_id'] == $session_id;
        // Crear el HTML para el mensaje
        $message_html = '<div class="mb-3">
                            <div style="color: #fff; background-color: ' . ($is_sent ? '#008568' : '#5e7671') . ';
                                 text-align: ' . ($is_sent ? 'right' : 'left') . '; margin-' . ($is_sent ? 'right' : 'left') . ';
                                 padding: 15px; border-radius: 20px; max-width: 70%; margin-bottom: 25px;
                                 ' . ($is_sent ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;') . '">
                                ' . htmlspecialchars($new_message['content']) . '
                                <div class="small text-muted mt-2">' .
            (new DateTime($new_message['date_sended']))->format('H:i - d/m/Y') . '</div> </div> </div>';
        // Confirmar la transacción
        $conn->commit();
        // Devolver el mensaje como JSON
        echo json_encode(['status' => 'success', 'message_html' => $message_html]);
    } catch (Exception $e) {
        // En caso de error, deshacer la transacción
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar el mensaje: ' . $e->getMessage()]);
    }
}
