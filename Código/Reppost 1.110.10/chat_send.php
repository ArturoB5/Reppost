<?php
include('Config/dbcon.php');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$sender_id   = $data['sender_id'] ?? 0;
$receiver_id = $data['receiver_id'] ?? 0;
$content     = trim($data['content'] ?? '');

if (!$sender_id || !$receiver_id || $content === '') {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO moderator_chat (sender_id, receiver_id, content)
        VALUES (:sender_id, :receiver_id, :content)
    ");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
