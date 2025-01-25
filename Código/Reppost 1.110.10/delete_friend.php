<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Obtenemos el ID del amigo a eliminar
if (isset($_GET['id'])) {
    $member_id = intval($_GET['id']);

    // Borramos la relación en ambos sentidos
    $stmt = $conn->prepare("
        DELETE FROM friends 
        WHERE (my_id = :session_id AND my_friend_id = :friend_id)
           OR (my_id = :friend_id AND my_friend_id = :session_id)
    ");
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $stmt->bindParam(':friend_id', $member_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirigir al mismo perfil
    header("Location: profile_friend.php?member_id=$member_id");
    exit;
} else {
    // Si no hay GET, redirigir
    header("Location: home.php");
    exit;
}
