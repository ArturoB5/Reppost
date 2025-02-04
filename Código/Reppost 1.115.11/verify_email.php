<?php include('Config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reppost</title>
    <link rel="shortcut icon" href="View/Images/app_images/logo.ico">
    <link rel="stylesheet" type="text/css" href="View/css/sorry.css">
</head>

<body style="margin: 220px">
    <div style="text-align: center; margin-top: 20px;">
        <img src="View/Images/app_images/logo.png" alt="Reppost" style="width: 180px;">
    </div>

    <?php
    $token = $_GET['token'] ?? '';
    if (empty($token) || !preg_match('/^[a-f0-9]{32}$/i', $token)) {
        echo "<h3 style='color: red; text-align: center;'>Token no válido.</h3>";
        echo "<p style='text-align: center;'><a href='index.php'>Volver al inicio</a></p>";
        exit;
    }
    try {
        $query = "SELECT member_id FROM members WHERE verification_token = :token AND email_verified = 0";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $update = "UPDATE members SET email_verified = 1, verification_token = NULL WHERE verification_token = :token";
            $updateStmt = $conn->prepare($update);
            $updateStmt->bindParam(':token', $token);
            $updateStmt->execute();
            echo "<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>";
            echo "<h3 style='color: green;'>¡Cuenta verificada con éxito!</h3>";
            echo "<p>Ahora puedes iniciar sesión :)</p><br>";
            echo "<a href='index.php' style='text-decoration: none; padding: 10px 20px; background-color: #28a745; color: white; border-radius: 5px;'>Ir al inicio</a>";
            echo "</div>";
            header("refresh:5;url=index.php");
        } else {
            echo "<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>";
            echo "<h3 style='color: red;'>El link no es válido o la cuenta ya ha sido verificada :(</h3><br>";
            echo "<a href='index.php' style='text-decoration: none; padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px;'>Volver al inicio</a>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>";
        echo "<h3 style='color: red;'>Error al verificar el correo.</h3>";
        echo "<p>Por favor, inténtalo más tarde.</p>";
        echo "<p>Error técnico: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='index.php' style='text-decoration: none; padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px;'>Volver al inicio</a>";
        echo "</div>";
    }
    ?>
</body>

</html>