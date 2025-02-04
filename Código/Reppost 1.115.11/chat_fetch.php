<?php
include('Config/dbcon.php');
header('Content-Type: application/json');
// Carga de mensajes 
$sender_id   = intval($_GET['sender_id'] ?? 0);
$receiver_id = intval($_GET['receiver_id'] ?? 0);

if (!$sender_id || !$receiver_id) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT * FROM moderator_chat
        WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
           OR (sender_id = :receiver_id AND receiver_id = :sender_id)
        ORDER BY date_sent ASC
    ");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'messages' => $messages
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
