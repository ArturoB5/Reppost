<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si la reacción ya fue registrada
$comment_id = $_POST['comment_id'];
$user_id = $session_id; // ID del usuario que reacciona
$reaction_type = $_POST['reaction_type']; // Tipo de reacción (like, love, etc.)

// Verificar si ya se ha reaccionado a este comentario
$query = $conn->prepare("SELECT * FROM reactions_comment WHERE comment_id = :comment_id AND user_id = :user_id");
$query->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();

if ($query->rowCount() > 0) {
    // Si ya se ha reaccionado, podemos actualizar la reacción
    $update_query = $conn->prepare("UPDATE reactions_comment SET reaction_type = :reaction_type WHERE comment_id = :comment_id AND user_id = :user_id");
    $update_query->bindParam(':reaction_type', $reaction_type, PDO::PARAM_STR);
    $update_query->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $update_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $update_query->execute();
} else {
    // Si no se ha reaccionado, insertamos la reacción
    $insert_query = $conn->prepare("INSERT INTO reactions_comment (comment_id, user_id, reaction_type) VALUES (:comment_id, :user_id, :reaction_type)");
    $insert_query->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $insert_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $insert_query->bindParam(':reaction_type', $reaction_type, PDO::PARAM_STR);
    $insert_query->execute();
}
