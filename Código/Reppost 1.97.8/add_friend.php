<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificamos si recibimos el ID del amigo
if (isset($_POST['my_friend_id'])) {
	$my_friend_id = intval($_POST['my_friend_id']);

	// Evitar que alguien se agregue a sí mismo
	if ($my_friend_id !== (int)$session_id) {
		// Insertar en tabla friends
		$stmt = $conn->prepare("
            INSERT INTO friends (my_id, my_friend_id)
            VALUES (:session_id, :my_friend_id)
        ");
		$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
		$stmt->bindParam(':my_friend_id', $my_friend_id, PDO::PARAM_INT);
		$stmt->execute();
	}
	// Redirigir de regreso al perfil
	header("Location: profile_friend.php?member_id=$my_friend_id");
	exit;
} else {
	// Si no hay POST, redirigir a home o a otra parte
	header("Location: home.php");
	exit;
}
