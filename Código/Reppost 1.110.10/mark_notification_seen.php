<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $notification_id = intval($input['notification_id'] ?? 0);

    if (!$notification_id) {
        echo json_encode(['success' => false, 'message' => 'ID de notificación no válido.']);
        exit;
    }
    try {
        $stmt = $conn->prepare("DELETE FROM notifications WHERE notification_id = :notification_id AND user_id = :user_id");
        $stmt->bindParam(':notification_id', $notification_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $session_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la notificación.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
