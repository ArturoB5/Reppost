<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');


// Obtener datos de la solicitud
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

if (isset($data['post_id'])) {
    $post_id = intval($data['post_id']); // Aseguramos que sea un entero
    $member_id = intval($_SESSION['member_id']); // Usamos la sesión para identificar al usuario
    $reaction_value = 0.00000005;

    // Verificar si ya reaccionó al post
    $checkQuery = "SELECT COUNT(*) as count FROM post_reactions WHERE post_id = ? AND member_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ii', $post_id, $member_id);
    $stmt->execute();
    $stmt->bind_result($reactionCount);
    $stmt->fetch();
    $stmt->close();

    if ($reactionCount == 0) {
        // Agregar reacción
        $insertQuery = "INSERT INTO post_reactions (post_id, member_id, reaction_value) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('iid', $post_id, $member_id, $reaction_value);

        if ($stmt->execute()) {
            $stmt->close();

            // Obtener el nuevo conteo de reacciones
            $countQuery = "SELECT COUNT(*) as count FROM post_reactions WHERE post_id = ?";
            $stmt = $conn->prepare($countQuery);
            $stmt->bind_param('i', $post_id);
            $stmt->execute();
            $stmt->bind_result($newReactionCount);
            $stmt->fetch();
            $stmt->close();

            echo json_encode([
                'success' => true,
                'new_count' => $newReactionCount
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al agregar la reacción'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ya reaccionaste a este post'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos en la solicitud'
    ]);
}
