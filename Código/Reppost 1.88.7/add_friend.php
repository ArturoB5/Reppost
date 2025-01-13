<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

$my_friend_id = $_POST['my_friend_id'];
$response = [];

if ($my_friend_id !== $session_id) {
	$query = $conn->query("INSERT INTO friends (my_id, my_friend_id) VALUES ('$session_id', '$my_friend_id')");
	if ($query) {
		$response['status'] = 'success';
		$response['message'] = 'Amigo agregado';
	} else {
		$response['status'] = 'error';
		$response['message'] = 'Error al agregar amigo';
	}
} else {
	$response['status'] = 'error';
	$response['message'] = 'No puedes agregarte a ti mismo como amigo';
}

header('Content-Type: application/json');
echo json_encode($response);
