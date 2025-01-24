<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id       = filter_var($_POST['friend_id'], FILTER_VALIDATE_INT);
    $last_message_id = filter_var($_POST['last_message_id'], FILTER_VALIDATE_INT);
    // Verificamos que los datos sean válidos
    if (!$friend_id || $last_message_id < 0) {
        echo json_encode(['error' => 'Datos inválidos']);
        exit;
    }
    // Consulta la tabla messages para cargar mensajes
    $query = $conn->prepare(" SELECT msg.*, sender.firstname AS sender_firstname, sender.lastname AS sender_lastname, receiver.firstname AS receiver_firstname, receiver.lastname AS receiver_lastname
        FROM messages AS msg LEFT JOIN members AS sender ON msg.sender_id   = sender.member_id LEFT JOIN members AS receiver ON msg.receiver_id = receiver.member_id
        WHERE ((msg.sender_id = :session_id   AND msg.receiver_id = :friend_id   AND msg.deleted_for_sender   = 0) OR (msg.sender_id = :friend_id    AND msg.receiver_id = :session_id  AND msg.deleted_for_receiver = 0))
        AND msg.message_id > :last_message_id ORDER BY msg.date_sent ASC");
    $query->bindParam(':session_id', $session_id,        PDO::PARAM_INT);
    $query->bindParam(':friend_id',  $friend_id,         PDO::PARAM_INT);
    $query->bindParam(':last_message_id', $last_message_id, PDO::PARAM_INT);
    $query->execute();
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
