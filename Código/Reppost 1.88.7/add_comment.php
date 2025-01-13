<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $session_id;
    $comment_text = $_POST['comment_text'];
    // Insertar comentario en la base de datos
    $insert_comment = $conn->prepare("INSERT INTO post_comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)");
    $insert_comment->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $insert_comment->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $insert_comment->bindParam(':comment_text', $comment_text, PDO::PARAM_STR);
    $insert_comment->execute();
    header("Location: home.php?id=$post_id");
}
