<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

header('Content-Type: application/json');

// Verificar acción
$action = $_GET['action'] ?? '';
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'ID de post inválido.']);
    exit;
}

// Obtener info del post
$stmt = $conn->prepare("SELECT * FROM post WHERE post_id = :post_id LIMIT 1");
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo json_encode(['success' => false, 'message' => 'El post no existe.']);
    exit;
}

// Verificar si la sesión actual es dueño del post o es admin
$member_id = $post['member_id'];
if ($member_id != $session_id) {
    // Revisar si es admin
    $roleStmt = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id LIMIT 1");
    $roleStmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $roleStmt->execute();
    $roleRow = $roleStmt->fetch();

    if (!$roleRow || !in_array($roleRow['role'], ['admin'])) {
        echo json_encode(['success' => false, 'message' => 'No tienes permisos para editar este post.']);
        exit;
    }
}

// Comprobar el límite de 5 min
$date_posted = strtotime($post['date_posted']);
$timePassed  = time() - $date_posted;
if ($timePassed > 300) {
    echo json_encode(['success' => false, 'message' => 'Tiempo para editar expirado.']);
    exit;
}

// Manejo de acciones
if ($action === 'fetch') {
    // Devolver contenido
    echo json_encode([
        'success' => true,
        'content' => $post['content']
    ]);
    exit;
} elseif ($action === 'update') {
    // Actualizar contenido
    // Obtener data enviada por POST (JSON)
    $input = json_decode(file_get_contents('php://input'), true);
    $newContent = trim($input['content'] ?? '');

    if ($newContent === '') {
        echo json_encode(['success' => false, 'message' => 'El contenido no puede estar vacío.']);
        exit;
    }

    // Actualizar BD
    $updateStmt = $conn->prepare("
        UPDATE post
        SET content = :content
        WHERE post_id = :post_id
    ");
    $updateStmt->bindParam(':content', $newContent, PDO::PARAM_STR);
    $updateStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $updateStmt->execute();

    echo json_encode(['success' => true, 'message' => 'Post actualizado.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
