<?php
include('Config/dbcon.php');
session_start();

$get_id = $_GET['id'];  // El ID del comentario a eliminar
$session_id = $_SESSION['id'];  // ID del usuario actual en sesión

// Paso 1: Verificamos que el comentario existe y obtenemos el ID del dueño
$query = $conn->prepare("SELECT member_id, post_id FROM post WHERE post_id = :post_id");
$query->bindParam(':post_id', $get_id);
$query->execute();
$row = $query->fetch();

if ($row) {
    $comment_owner_id = $row['member_id'];  // Dueño del comentario

    // Paso 2: Verificamos que el usuario en sesión es el dueño del comentario
    if ($comment_owner_id == $session_id) {

        // Definimos el valor de los tokens que vamos a restar al eliminar el comentario
        $comment_tokens = 0.00000010;

        // Paso 3: Obtenemos los tokens actuales del usuario
        $user_query = $conn->prepare("SELECT tokens FROM members WHERE member_id = :member_id");
        $user_query->bindParam(':member_id', $session_id);
        $user_query->execute();
        $user_row = $user_query->fetch();

        if ($user_row) {
            $current_tokens = $user_row['tokens'];

            // Paso 4: Calculamos el nuevo balance de tokens del usuario
            $new_token_balance = $current_tokens - $comment_tokens;

            // Paso 5: Actualizamos el balance de tokens del usuario en la tabla `members`
            $update_query = $conn->prepare("UPDATE members SET tokens = :tokens WHERE member_id = :member_id");
            $update_query->bindParam(':tokens', $new_token_balance);
            $update_query->bindParam(':member_id', $session_id);
            $update_query->execute();
        }

        // Paso 6: Eliminamos las imágenes asociadas
        $image_query = $conn->prepare("SELECT image_path FROM post_images WHERE post_id = :post_id");
        $image_query->bindParam(':post_id', $get_id);
        $image_query->execute();
        $images = $image_query->fetchAll();

        foreach ($images as $image) {
            $image_path = $image['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path); // Elimina la imagen del servidor
            }
        }

        // Eliminamos las imágenes de la base de datos
        $delete_images_query = $conn->prepare("DELETE FROM post_images WHERE post_id = :post_id");
        $delete_images_query->bindParam(':post_id', $get_id);
        $delete_images_query->execute();

        // Eliminamos el comentario
        $delete_query = $conn->prepare("DELETE FROM post WHERE post_id = :post_id");
        $delete_query->bindParam(':post_id', $get_id);
        $delete_query->execute();

        // Redirigimos a la página después de eliminar el comentario y actualizar los tokens
        header('Location: home.php');
    } else {
        // Si el usuario no es el propietario, redirige o muestra un error
        header('Location: home.php?error=not_authorized');
    }
} else {
    // Si el comentario no existe, redirige o muestra un mensaje de error
    header('Location: home.php?error=comment_not_found');
}
exit();
