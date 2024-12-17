<?php
// update-reaction.php

// Obtener datos de la solicitud POST
$data = json_decode(file_get_contents("php://input"), true);

$postId = $data["post_id"];
$reacted = $data["reacted"]; // true: sumar, false: restar

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "password", "database_name");

// Validar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

// Actualizar el contador de reacciones
if ($reacted) {
    $query = "UPDATE post SET reaction_count = reaction_count + 1 WHERE post_id = ?";
} else {
    $query = "UPDATE post SET reaction_count = reaction_count - 1 WHERE post_id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $postId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar la reacción"]);
}

$stmt->close();
$conn->close();
