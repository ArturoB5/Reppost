<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si el reporte existe
if (isset($_POST['report_id']) && isset($_POST['status'])) {
    $report_id = $_POST['report_id']; // Obtener el ID del reporte
    $status = $_POST['status']; // Obtener el nuevo estado (en_revision o resuelto)

    // Si el estado es "resuelto", asignamos una respuesta predeterminada
    if ($status == 'resuelto') {
        $status_response = "La solicitud de reporte ha sido analizada y se tomará medidas al respecto.";
    } elseif ($status == 'en_revision') {
        // Si el estado es "en_revision", asignamos el mensaje correspondiente
        $status_response = "La solicitud se encuentra en revisión para tomar medidas al respecto.";
    } else {
        $status_response = isset($_POST['status_response']) ? $_POST['status_response'] : ''; // Obtener la respuesta si la hay
    }

    // Verificar si el reporte es de una publicación
    $query = $conn->prepare("SELECT * FROM post_reports WHERE report_id = :report_id");
    $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $query->execute();

    // Si el reporte es de una publicación
    if ($query->rowCount() > 0) {
        $update_query = $conn->prepare("UPDATE post_reports SET status = :status, status_response = :status_response WHERE report_id = :report_id");
        $update_query->bindParam(':status', $status, PDO::PARAM_STR);
        $update_query->bindParam(':status_response', $status_response, PDO::PARAM_STR);
        $update_query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $update_query->execute();
        header("Location: moderator.php?msg=Reporte de publicación actualizado.");
        exit;
    }

    // Si el reporte es de un comentario
    $query = $conn->prepare("SELECT * FROM reports_comment WHERE report_id = :report_id");
    $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $update_query = $conn->prepare("UPDATE reports_comment SET status = :status, status_response = :status_response WHERE report_id = :report_id");
        $update_query->bindParam(':status', $status, PDO::PARAM_STR);
        $update_query->bindParam(':status_response', $status_response, PDO::PARAM_STR);
        $update_query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $update_query->execute();
        header("Location: moderator.php?msg=Reporte de comentario actualizado.");
        exit;
    }

    // Si el reporte es de un usuario
    $query = $conn->prepare("SELECT * FROM report_users WHERE report_id = :report_id");
    $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $update_query = $conn->prepare("UPDATE report_users SET status = :status, status_response = :status_response WHERE report_id = :report_id");
        $update_query->bindParam(':status', $status, PDO::PARAM_STR);
        $update_query->bindParam(':status_response', $status_response, PDO::PARAM_STR);
        $update_query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $update_query->execute();
        header("Location: moderator.php?msg=Reporte de usuario actualizado.");
        exit;
    }

    // Si no se encuentra el reporte
    header("Location: moderator.php?msg=Reporte no encontrado.");
} else {
    header("Location: moderator.php?msg=Datos inválidos.");
}
