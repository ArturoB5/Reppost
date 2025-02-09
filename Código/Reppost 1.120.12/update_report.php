<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if (isset($_POST['report_id']) && isset($_POST['status'])) {
    $report_id = $_POST['report_id']; // Obtener ID del reporte
    $status = $_POST['status']; // Obtener estado (en_revision o resuelto)

    if ($status == 'resuelto') {
        $status_response = "La solicitud ha sido revisada y se tomará medidas al respecto.";
    } elseif ($status == 'en_revision') {
        $status_response = "La solicitud se encuentra en revisión para tomar medidas al respecto.";
    } else {
        $status_response = isset($_POST['status_response']) ? $_POST['status_response'] : ''; // Obtener la respuesta si la hay
    }

    // ===================== REPORTES DE PUBLICACIONES =====================
    $query = $conn->prepare("SELECT * FROM post_reports WHERE report_id = :report_id");
    $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
        $update_query = $conn->prepare("UPDATE post_reports SET status = :status, status_response = :status_response WHERE report_id = :report_id");
        $update_query->bindParam(':status', $status, PDO::PARAM_STR);
        $update_query->bindParam(':status_response', $status_response, PDO::PARAM_STR);
        $update_query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $update_query->execute();
        header("Location: moderator.php?msg=Reporte de publicación actualizado.");
        exit;
    }

    // ===================== REPORTES DE COMENTARIOS =====================
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

    // ===================== REPORTES DE USUARIOS =====================
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
    // ===================== TICKETS DE AYUDA =====================
    $query = $conn->prepare("SELECT * FROM help_tickets WHERE ticket_id = :report_id");
    $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $query->execute();
    $ticket = $query->fetch(PDO::FETCH_ASSOC);
    if ($ticket) {
        // Actualizar estado y respuesta del ticket
        $update_query = $conn->prepare("UPDATE help_tickets SET status = :status, status_response = :status_response WHERE ticket_id = :report_id");
        $update_query->bindParam(':status', $status, PDO::PARAM_STR);
        $update_query->bindParam(':status_response', $status_response, PDO::PARAM_STR);
        $update_query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $update_query->execute();
        // Enviar correo al usuario si el ticket ha sido resuelto
        if ($status == 'resuelto') {
            $user_email = $ticket['email'];
            $subject = "Respuesta a tu solicitud de ayuda en Reppost";
            $message = "Hola,\n\nTu solicitud de ayuda ha sido revisada y resuelta.\n\nRespuesta del moderador:\n" . $status_response . "\n\nGracias por contactarnos.";
            $headers = "From: soporte@reppost.com";
            // Intentar enviar el correo
            if (mail($user_email, $subject, $message, $headers)) {
                header("Location: moderator.php?msg=Ticket de ayuda resuelto y correo enviado.");
            } else {
                header("Location: moderator.php?msg=Ticket resuelto, pero el correo no pudo enviarse.");
            }
        } else {
            // Confirmar la actualización del estado a "En Revisión"
            header("Location: moderator.php?msg=El ticket ha sido puesto en revisión.");
        }
        exit;
    }
    // ===================== REPORTE NO ENCONTRADO =====================
    header("Location: moderator.php?msg=Reporte o ticket no encontrado.");
} else {
    header("Location: moderator.php?msg=Datos inválidos.");
}
