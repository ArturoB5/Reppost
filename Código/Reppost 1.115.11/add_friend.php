<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON
if (!isset($_SESSION['id'])) {
	echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada.']);
	exit;
}
$session_id = $_SESSION['id'];
// Verificar si `my_friend_id` fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['my_friend_id'])) {
	$my_friend_id = intval($_POST['my_friend_id']);

	// Evitar que el usuario se agregue a sí mismo
	if ($my_friend_id === (int)$session_id) {
		echo json_encode(['status' => 'error', 'message' => 'No puedes agregarte a ti mismo.']);
		exit;
	}
	// Verificar si ya son amigos
	$check = $conn->prepare("
        SELECT * FROM friends 
        WHERE (my_id = :session_id AND my_friend_id = :my_friend_id)
           OR (my_id = :my_friend_id AND my_friend_id = :session_id)
    ");
	$check->bindParam(':session_id', $session_id, PDO::PARAM_INT);
	$check->bindParam(':my_friend_id', $my_friend_id, PDO::PARAM_INT);
	$check->execute();
	if ($check->rowCount() === 0) {
		// Insertar en tabla `friends`
		$stmt = $conn->prepare("
            INSERT INTO friends (my_id, my_friend_id)
            VALUES (:session_id, :my_friend_id)
        ");
		$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
		$stmt->bindParam(':my_friend_id', $my_friend_id, PDO::PARAM_INT);
		$stmt->execute();
		echo json_encode([
			'status' => 'success',
			'message' => 'Amigo agregado con éxito.',
			'friend_id' => $my_friend_id
		]);
		exit;
	} else {
		echo json_encode(['status' => 'error', 'message' => 'Ya son amigos.']);
		exit;
	}
} else {
	echo json_encode(['status' => 'error', 'message' => 'Error: No se recibieron datos.']);
	exit;
}
