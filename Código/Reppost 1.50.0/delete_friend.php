<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

$member_id = intval($_GET['id']); // Obtenemos el member_id del amigo a eliminar

// Eliminamos la relación desde el punto de vista de 'my_id' y 'my_friend_id'
$query = "DELETE FROM friends WHERE 
          (my_id = '$session_id' AND my_friend_id = '$member_id') 
          OR 
          (my_id = '$member_id' AND my_friend_id = '$session_id')";
$conn->query($query);

// Redirigimos al listado de amigos (friends.php)
header('Location: friends.php');
exit;
