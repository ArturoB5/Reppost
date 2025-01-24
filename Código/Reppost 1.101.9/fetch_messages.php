<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friend_id = filter_var($_POST['friend_id'], FILTER_VALIDATE_INT);
    $last_message_id = filter_var($_POST['last_message_id'], FILTER_VALIDATE_INT);
    $query = $conn->prepare("
        SELECT message.*, sender.firstname AS sender_firstname, sender.lastname AS sender_lastname,
               receiver.firstname AS receiver_firstname, receiver.lastname AS receiver_lastname
        FROM message
        LEFT JOIN members AS sender ON message.sender_id = sender.member_id
        LEFT JOIN members AS receiver ON message.receiver_id = receiver.member_id
        WHERE ((sender_id = :session_id AND receiver_id = :friend_id)
               OR (sender_id = :friend_id AND receiver_id = :session_id))
          AND message.message_id > :last_message_id
        ORDER BY date_sended ASC
    ");
    $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $query->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
    $query->bindParam(':last_message_id', $last_message_id, PDO::PARAM_INT);
    $query->execute();
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
}
