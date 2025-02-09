<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$session_id = $_SESSION['id'];
// Verificar si se envió el ID del amigo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['my_friend_id'])) {
    $my_friend_id = intval($_POST['my_friend_id']);
    // Evitar que el usuario se agregue a sí mismo
    if ($my_friend_id === (int)$session_id) {
        header("Location: profile_friend.php?member_id=$my_friend_id&error=self_add");
        exit();
    }
    // Verificar si ya existe la amistad
    $check = $conn->prepare("
        SELECT * FROM friends 
        WHERE (my_id = :session_id AND my_friend_id = :my_friend_id)
           OR (my_id = :my_friend_id AND my_friend_id = :session_id)
    ");
    $check->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $check->bindParam(':my_friend_id', $my_friend_id, PDO::PARAM_INT);
    $check->execute();
    if ($check->rowCount() === 0) {
        // Insertar la amistad en la base de datos
        $stmt = $conn->prepare("
            INSERT INTO friends (my_id, my_friend_id)
            VALUES (:session_id, :my_friend_id)
        ");
        $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $stmt->bindParam(':my_friend_id', $my_friend_id, PDO::PARAM_INT);
        $stmt->execute();
        // Redirigir de regreso al perfil con éxito
        header("Location: profile_friend.php?member_id=$my_friend_id&success=friend_added");
        exit();
    } else {
        // Ya son amigos
        header("Location: profile_friend.php?member_id=$my_friend_id&error=already_friends");
        exit();
    }
} else {
    // Si no se recibió el ID del amigo
    header("Location: home.php?error=no_data");
    exit();
}
