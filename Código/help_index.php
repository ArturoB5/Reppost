<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
$success = "";
$error = "";

// Procesa el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo ingresado no es válido.";
    } elseif (empty($subject) || empty($message)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        try {
            // Inserta ticket en la base de datos
            $stmt = $conn->prepare("INSERT INTO help_tickets (email, subject, message, status, created_at) VALUES (?, ?, ?, 'pendiente', NOW())");
            if ($stmt->execute([$email, $subject, $message])) {
                $success = "Tu solicitud ha sido enviada. Un moderador revisará tu caso pronto.";
            } else {
                $error = "Error al enviar el ticket. Inténtalo nuevamente.";
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Ayuda</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link rel="stylesheet" href="View/css/bootstrap.css">
    <link rel="stylesheet" href="View/css/help.css">
    <link rel="stylesheet" href="View/css/dark_mode.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Botón de Volver -->
    <a href="index.php" class="back-home">
        <i class="fa fa-home"></i> Inicio
    </a>

    <!-- Botón de Alternar Modo Oscuro -->
    <button id="toggle-dark-mode" class="dark-mode-btn">
        <i class="fa fa-sun"></i> Modo Claro
    </button>

    <div class="help-container">
        <h2><i class="fa fa-circle-info"></i> Solicitar Ayuda</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <p for="email">Correo Electrónico:</p>
            <input type="email" name="email" required placeholder="Ingresa tu correo">
            <br><br>

            <p for="subject">Asunto:</p>
            <input type="text" name="subject" required placeholder="Escribe el motivo de tu solicitud">
            <br><br>

            <p for="message">Mensaje:</p>
            <textarea name="message" required placeholder="Describe tu problema..." rows="5"></textarea>
            <br>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</body>

<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggle-dark-mode");
            const isDarkMode = localStorage.getItem("dark-mode") === "enabled";

            if (isDarkMode) {
                document.body.classList.add("dark-mode");
                toggleButton.innerHTML = `<i class="fa fa-sun"></i> Modo Claro`;
            }

            toggleButton.addEventListener("click", function() {
                document.body.classList.toggle("dark-mode");
                const modeText = document.body.classList.contains("dark-mode") ? "Modo Claro" : "Modo Oscuro";
                toggleButton.innerHTML = `<i class="fa fa-${document.body.classList.contains("dark-mode") ? "sun" : "moon"}"></i> ${modeText}`;

                if (document.body.classList.contains("dark-mode")) {
                    localStorage.setItem("dark-mode", "enabled");
                } else {
                    localStorage.removeItem("dark-mode");
                }
            });
        });
    </script>
</footer>

</html>