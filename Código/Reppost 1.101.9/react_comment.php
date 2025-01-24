<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $comment_id = intval($input['comment_id']);
    $user_id = $_SESSION['id'];
    if (!$comment_id || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }
    try {
        $conn->beginTransaction();
        // Verificar si usuario ya reaccionó al comentario
        $checkReaction = $conn->prepare("SELECT * FROM comment_reactions WHERE comment_id = :comment_id AND user_id = :user_id");
        $checkReaction->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $checkReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkReaction->execute();
        $action = '';
        if ($checkReaction->rowCount() > 0) {
            // Eliminar reacción
            $deleteReaction = $conn->prepare("DELETE FROM comment_reactions WHERE comment_id = :comment_id AND user_id = :user_id");
            $deleteReaction->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $deleteReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $deleteReaction->execute();
            $action = "removed";
            // Eliminar notificación
            $deleteNotification = $conn->prepare("
                DELETE FROM notifications 
                WHERE user_id = (SELECT user_id FROM post_comments WHERE comment_id = :comment_id)
                AND message LIKE :message
            ");
            $deleteNotification->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $notificationMessage = "%reaccionó a tu comentario.%";
            $deleteNotification->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
            $deleteNotification->execute();
        } else {
            // Añadir reacción
            $addReaction = $conn->prepare("INSERT INTO comment_reactions (comment_id, user_id) VALUES (:comment_id, :user_id)");
            $addReaction->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $addReaction->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $addReaction->execute();
            $action = "added";
            // Obtener información del comentario
            $commentQuery = $conn->prepare("SELECT user_id, post_id FROM post_comments WHERE comment_id = :comment_id");
            $commentQuery->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
            $commentQuery->execute();
            $commentOwner = $commentQuery->fetch(PDO::FETCH_ASSOC);
            // Obtener nombre del usuario que reaccionó
            $reactorQuery = $conn->prepare("SELECT firstname, lastname FROM members WHERE member_id = :user_id");
            $reactorQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $reactorQuery->execute();
            $reactor = $reactorQuery->fetch(PDO::FETCH_ASSOC);
            $reactorName = $reactor['firstname'] . " " . $reactor['lastname'];
            if ($commentOwner && $commentOwner['user_id'] != $user_id) {
                $notificationMessage = "$reactorName reaccionó a tu comentario.";
                $notificationLink = "home.php?post_id=" . $commentOwner['post_id'];
                // Verificar si ya existe la notificación
                $checkNotification = $conn->prepare("
                    SELECT * FROM notifications 
                    WHERE user_id = :user_id AND message = :message
                ");
                $checkNotification->bindParam(':user_id', $commentOwner['user_id'], PDO::PARAM_INT);
                $checkNotification->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
                $checkNotification->execute();
                if ($checkNotification->rowCount() == 0) {
                    // Crear la notificación si no existe
                    $notificationQuery = $conn->prepare("
                        INSERT INTO notifications (user_id, message, link) 
                        VALUES (:user_id, :message, :link)
                    ");
                    $notificationQuery->bindParam(':user_id', $commentOwner['user_id'], PDO::PARAM_INT);
                    $notificationQuery->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
                    $notificationQuery->bindParam(':link', $notificationLink, PDO::PARAM_STR);
                    $notificationQuery->execute();
                }
            }
        }
        $conn->commit();
        // Obtener conteo de reacciones
        $reactionCountQuery = $conn->prepare("SELECT COUNT(*) AS total_reactions FROM comment_reactions WHERE comment_id = :comment_id");
        $reactionCountQuery->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
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
