<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Recoger el contenido del post
$content = $_POST['content'];

// Definir recompensa por interacción en tokens
$token_reward = 0.00000010;

// Obtener el hash del último post (bloque anterior) para crear el enlace
$last_post_query = $conn->query("SELECT current_hash FROM post ORDER BY post_id DESC LIMIT 1");
$last_post = $last_post_query->fetch(PDO::FETCH_ASSOC);
$previous_hash = $last_post ? $last_post['current_hash'] : '0'; // Si no hay post anterior, usar '0'

// Crear la fecha actual
$date_posted = date('Y-m-d H:i:s');

// Crear el hash actual usando los datos del post y el hash anterior
$current_hash = hash('sha256', $session_id . $content . $date_posted . $previous_hash);

// Insertar el nuevo post con los campos de blockchain
$stmt = $conn->prepare("INSERT INTO post (content, date_posted, member_id, token_reward, previous_hash, current_hash) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$content, $date_posted, $session_id, $token_reward, $previous_hash, $current_hash]);

// Obtener el ID del post recién creado
$post_id = $conn->lastInsertId();

// Manejar las imágenes adjuntas
if (isset($_FILES['images'])) {
    $allowed_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
    $upload_dir = 'View/Images/post_images/'; // Asegúrate de que esta carpeta exista y sea escribible.

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_type = $_FILES['images']['type'][$key];
        if (in_array($file_type, $allowed_types)) {
            $file_name = uniqid() . "_" . basename($_FILES['images']['name'][$key]);
            $file_path = $upload_dir . $file_name;

            // Mover la imagen al directorio de subida
            if (move_uploaded_file($tmp_name, $file_path)) {
                // Guardar la ruta en la base de datos
                $image_stmt = $conn->prepare("INSERT INTO post_images (post_id, image_path) VALUES (?, ?)");
                $image_stmt->execute([$post_id, $file_path]);
            }
        }
    }
}

// Actualizar el saldo de tokens del usuario
$user_query = $conn->prepare("SELECT tokens FROM members WHERE member_id = :member_id");
$user_query->bindParam(':member_id', $session_id);
$user_query->execute();
$user_row = $user_query->fetch();

if ($user_row) {
    $current_tokens = $user_row['tokens'];
    $new_token_balance = $current_tokens + $token_reward;

    $update_query = $conn->prepare("UPDATE members SET tokens = :tokens WHERE member_id = :member_id");
    $update_query->bindParam(':tokens', $new_token_balance);
    $update_query->bindParam(':member_id', $session_id);
    $update_query->execute();
}

// Redirigir al usuario
header('Location:home.php');
