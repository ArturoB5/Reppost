<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporter_id = $session_id;  // Usuario que reporta (suponiendo que $session_id es el ID del usuario actual)
    $reported_id = $_POST['reported_id'];  // ID del usuario reportado
    $report_type = $_POST['report_type'];  // Tipo de reporte

    // Verificar si ya se ha reportado a este usuario
    $check_query = $conn->prepare("SELECT * FROM report_users WHERE reporter_id = :reporter_id AND reported_id = :reported_id");
    $check_query->bindParam(':reporter_id', $reporter_id, PDO::PARAM_INT);
    $check_query->bindParam(':reported_id', $reported_id, PDO::PARAM_INT);
    $check_query->execute();

    if ($check_query->rowCount() > 0) {
        // Si ya se reportó, redirigir con un mensaje
        header("Location: profile_friend.php?member_id=$reported_id&msg=Ya has reportado a este usuario.");
        exit;
    } else {
        // Si no se ha reportado, proceder a registrar el reporte
        $report_query = $conn->prepare("INSERT INTO report_users (reporter_id, reported_id, report_type) VALUES (:reporter_id, :reported_id, :report_type)");
        $report_query->bindParam(':reporter_id', $reporter_id, PDO::PARAM_INT);
        $report_query->bindParam(':reported_id', $reported_id, PDO::PARAM_INT);
        $report_query->bindParam(':report_type', $report_type, PDO::PARAM_STR);

        if ($report_query->execute()) {
            header("Location: profile_friend.php?member_id=$reported_id&msg=Usuario reportado con éxito.");
        } else {
            header("Location: profile_friend.php?member_id=$reported_id&msg=Error al reportar al usuario.");
        }
        exit;
    }
}
