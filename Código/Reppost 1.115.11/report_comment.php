<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si el formulario fue enviado correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $comment_id = $_POST['comment_id'];
    $user_id = $session_id; // ID del usuario que reporta
    $report_type = $_POST['report_type']; // Tipo de reporte (ofensivo, spam, etc.)

    // Verificar si ya se ha reportado este comentario
    $check_report = $conn->prepare("SELECT * FROM reports_comment WHERE comment_id = :comment_id AND user_id = :user_id");
    $check_report->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $check_report->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_report->execute();

    if ($check_report->rowCount() > 0) {
        // Si ya se reportó, redirigir con un mensaje de error
        header("Location: home.php?id=$post_id&msg=Ya se ha reportado este comentario.");
        exit;
    } else {
        // Si no se ha reportado, proceder a registrar el reporte
        $report_query = $conn->prepare("INSERT INTO reports_comment (comment_id, user_id, report_type) VALUES (:comment_id, :user_id, :report_type)");
        $report_query->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $report_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $report_query->bindParam(':report_type', $report_type, PDO::PARAM_STR);
        $report_query->execute();

        // Redirigir con un mensaje de éxito
        header("Location: home.php?id=$post_id&msg=Comentario reportado con éxito.");
        exit;
    }
}
