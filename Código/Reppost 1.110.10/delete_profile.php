<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si el usuario ha solicitado la eliminación
if (isset($_POST['delete_profile'])) {
    // Eliminar el perfil de la base de datos
    $delete_query = $conn->prepare("DELETE FROM members WHERE member_id = :member_id");
    $delete_query->bindParam(':member_id', $session_id, PDO::PARAM_INT);
    if ($delete_query->execute()) {
        // Cerrar sesión después de la eliminación
        session_destroy();
        // Redirigir al usuario a la página de inicio o a otra página
        header('Location: index.php');
        exit();
    } else {
        // En caso de error, mostrar un mensaje
        echo "Error al eliminar el perfil.";
    }
}
