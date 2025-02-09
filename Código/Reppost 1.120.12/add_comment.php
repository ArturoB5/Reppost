<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $post_id = $_POST['post_id'];
        $user_id = $session_id;
        $comment_text = $_POST['comment_text'];
        // Insertar comentario en la base de datos
        $insert_comment = $conn->prepare("INSERT INTO post_comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)");
        $insert_comment->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $insert_comment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_comment->bindParam(':comment_text', $comment_text, PDO::PARAM_STR);
        $insert_comment->execute();
        // Obtener el dueño de la publicación
        $post_owner_query = $conn->prepare("SELECT member_id FROM post WHERE post_id = :post_id");
        $post_owner_query->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $post_owner_query->execute();
        $post_owner = $post_owner_query->fetch(PDO::FETCH_ASSOC);
        // Obtener el nombre del usuario que comentó
        $commenter_query = $conn->prepare("SELECT firstname, lastname FROM members WHERE member_id = :user_id");
        $commenter_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $commenter_query->execute();
        $commenter = $commenter_query->fetch(PDO::FETCH_ASSOC);
        $commenter_name = $commenter['firstname'] . " " . $commenter['lastname'];
        if ($post_owner && $post_owner['member_id'] != $user_id) {
            // Crear notificación con el nombre del usuario que comentó
            $notification_message = "$commenter_name comentó en tu publicación.";
            $notification_link = "home.php?post_id=" . $post_id;
            $create_notification = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (:user_id, :message, :link)");
            $create_notification->bindParam(':user_id', $post_owner['member_id'], PDO::PARAM_INT);
            $create_notification->bindParam(':message', $notification_message, PDO::PARAM_STR);
            $create_notification->bindParam(':link', $notification_link, PDO::PARAM_STR);
            $create_notification->execute();
        }
        header("Location: home.php?id=$post_id");
    } catch (PDOException $e) {
        echo "Error al procesar el comentario: " . $e->getMessage();
    }
}
