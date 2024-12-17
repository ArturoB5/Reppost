<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['notification_id'])) {
    try {
        $notificationId = $data['notification_id'];

        // Marcar la notificación como leída
        $stmt = $conn->prepare("UPDATE notifications SET read_status = 1 WHERE id = :notification_id AND user_id = :user_id");
        $stmt->bindParam(':notification_id', $notificationId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $session_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
