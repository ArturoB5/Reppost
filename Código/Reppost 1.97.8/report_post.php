<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibe los datos del reporte
    $report_type = $_POST['report_type'];
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // Verificar si ya se ha reportado la publicación
    $check_query = $conn->prepare("SELECT * FROM post_reports WHERE post_id = :post_id AND user_id = :user_id");
    $check_query->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $check_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_query->execute();

    if ($check_query->rowCount() > 0) {
        // Si ya se reportó, redirigir con un mensaje de error
        header("Location: home.php?id=$post_id&msg=Ya se ha reportado esta publicación.");
        exit;
    } else {
        // Si no se ha reportado, proceder a registrar el reporte
        $report_query = $conn->prepare("INSERT INTO post_reports (post_id, user_id, report_type, status, report_date) 
                                        VALUES (:post_id, :user_id, :report_type, 'pendiente', NOW())");
        $report_query->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $report_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $report_query->bindParam(':report_type', $report_type, PDO::PARAM_STR);

        if ($report_query->execute()) {
            // Redirigir a la página de inicio con un mensaje de éxito
            header("Location: home.php?id=$post_id&msg=Publicación reportada con éxito.");
        } else {
            // En caso de error, redirigir con un mensaje de error
            header("Location: home.php?id=$post_id&msg=Error al reportar la publicación.");
        }
        exit;
    }
}
