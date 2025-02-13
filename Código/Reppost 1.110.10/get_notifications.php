<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json');

$user_id = $session_id;
try {
    $stmt = $conn->prepare("SELECT notification_id, message, link, date_created FROM notifications WHERE user_id = :user_id ORDER BY date_created DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = ['status' => 'success', 'notifications' => $notifications];
    echo json_encode($response);
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
    echo json_encode($response);
}
