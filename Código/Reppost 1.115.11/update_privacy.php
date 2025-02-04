<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $privacy = $_POST['privacy'];

    if ($privacy === 'public' || $privacy === 'private') {
        $stmt = $conn->prepare("UPDATE members SET privacy = :privacy WHERE member_id = :session_id");
        $stmt->bindParam(':privacy', $privacy, PDO::PARAM_STR);
        $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<script>
                window.location.href = 'config_preferences.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Opción de privacidad no válida.')                window.history.back();
              </script>";
        exit;
    }
}
