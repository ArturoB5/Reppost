<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $post_id = intval($input['post_id']);
    $user_id = $_SESSION['id'];
    if (!$post_id || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }
    try {
        $conn->beginTransaction();
        // Verificar si el usuario ya reaccionó
        $checkReaction = $conn->prepare("SELECT * FROM post_reactions WHERE post_id = :post_id AND user_id = :user_id");
        $checkReaction->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $checkReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkReaction->execute();
        $action = '';
        if ($checkReaction->rowCount() > 0) {
            // Eliminar reacción
            $deleteReaction = $conn->prepare("DELETE FROM post_reactions WHERE post_id = :post_id AND user_id = :user_id");
            $deleteReaction->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $deleteReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $deleteReaction->execute();
            $action = "removed";
            // Eliminar notificación
            $deleteNotification = $conn->prepare("
                DELETE FROM notifications 
                WHERE user_id = (SELECT member_id FROM post WHERE post_id = :post_id)
                AND message LIKE :message
            ");
            $deleteNotification->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $notificationMessage = "%reaccionó a tu publicación.%";
            $deleteNotification->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
            $deleteNotification->execute();
        } else {
            // Añadir reacción
            $addReaction = $conn->prepare("INSERT INTO post_reactions (post_id, user_id) VALUES (:post_id, :user_id)");
            $addReaction->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $addReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $addReaction->execute();
            $action = "added";
            // Obtener información de la publicación
            $postQuery = $conn->prepare("SELECT member_id FROM post WHERE post_id = :post_id");
            $postQuery->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $postQuery->execute();
            $postOwner = $postQuery->fetch(PDO::FETCH_ASSOC);
            // Obtener nombre del usuario que reaccionó
            $reactorQuery = $conn->prepare("SELECT firstname, lastname FROM members WHERE member_id = :user_id");
            $reactorQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $reactorQuery->execute();
            $reactor = $reactorQuery->fetch(PDO::FETCH_ASSOC);
            $reactorName = $reactor['firstname'] . " " . $reactor['lastname'];
            if ($postOwner && $postOwner['member_id'] != $user_id) {
                $notificationMessage = "$reactorName reaccionó a tu publicación.";
                $notificationLink = "home.php?post_id=" . $post_id;
                // Verificar si ya existe la notificación
                $checkNotification = $conn->prepare("
                    SELECT * FROM notifications 
                    WHERE user_id = :user_id AND message = :message
                ");
                $checkNotification->bindParam(':user_id', $postOwner['member_id'], PDO::PARAM_INT);
                $checkNotification->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
                $checkNotification->execute();
                if ($checkNotification->rowCount() == 0) {
                    // Crear notificación si no existe
                    $notificationQuery = $conn->prepare("
                        INSERT INTO notifications (user_id, message, link) 
                        VALUES (:user_id, :message, :link)
                    ");
                    $notificationQuery->bindParam(':user_id', $postOwner['member_id'], PDO::PARAM_INT);
                    $notificationQuery->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
                    $notificationQuery->bindParam(':link', $notificationLink, PDO::PARAM_STR);
                    $notificationQuery->execute();
                }
            }
        }
        $conn->commit();
        // Obtener conteo de reacciones
        $reactionCountQuery = $conn->prepare("SELECT COUNT(*) AS total_reactions FROM post_reactions WHERE post_id = :post_id");
        $reactionCountQuery->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $reactionCountQuery->execute();
        $reactionData = $reactionCountQuery->fetch();
        echo json_encode([
            'success' => true,
            'action' => $action,
            'reaction_count' => intval($reactionData['total_reactions']),
        ]);
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error al procesar la reacción: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
