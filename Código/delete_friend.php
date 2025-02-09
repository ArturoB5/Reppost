<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

$friend_id = intval($_GET['id'] ?? 0);
$redirect  = $_GET['redirect'] ?? '';

// 1) Eliminar la relación en la tabla friends
$query = "DELETE FROM friends
          WHERE (my_id = :session_id AND my_friend_id = :friend_id)
             OR (my_id = :friend_id AND my_friend_id = :session_id)";
$stmt = $conn->prepare($query);
$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$stmt->bindParam(':friend_id',  $friend_id,  PDO::PARAM_INT);
$stmt->execute();

// 2) Redirigir según el parámetro 'redirect'
if ($redirect === 'profile') {
    // Volver a mi perfil
    header("Location: profile.php");
    exit;
} elseif ($redirect === 'friend') {
    // Vamos a profile_friend.php con el friend_id pasado
    $goto = $_GET['friend_id'] ?? 0;
    header("Location: profile_friend.php?member_id=$goto");
    exit;
} else {
    // Fallback: ir a home o friends.php
    header("Location: friends.php");
    exit;
}
