<?php
session_start();
include('Config/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'libs/vendor/autoload.php';

// Si no existe la variable de sesión de intentos, inicializar a 0
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Obtener datos del formulario
$username_or_email = $_POST['username'] ?? '';
$password         = $_POST['password'] ?? '';

// Consulta para buscar usuario por nombre de usuario o correo electrónico
$query = $conn->prepare("SELECT * FROM members WHERE username = :username_or_email OR email = :username_or_email");
$query->bindParam(':username_or_email', $username_or_email, PDO::PARAM_STR);
$query->execute();
$row = $query->fetch();

if ($row) {
    $hashed_password = $row['password'];
    // Verificar contraseña
    if (password_verify($password, $hashed_password)) {
        // Reiniciar los intentos si login exitoso
        $_SESSION['login_attempts'] = 0;

        if ($row['email_verified'] == 1) {
            // Email verificado, iniciar sesión
            $_SESSION['id'] = $row['member_id'];
            header('Location: home.php');
            exit();
        } else {
            // Email no verificado
            header('Location: index.php?error=email_not_verified');
            exit();
        }
    } else {
        // Contraseña inválida, incrementar intentos
        $_SESSION['login_attempts']++;
        checkLoginAttempts($row['email'], $row['firstname'], $row['lastname']); // Verificar si excede
        header('Location: index.php?error=invalid_password');
        exit();
    }
} else {
    // Usuario/correo no encontrado incrementar intentos
    $_SESSION['login_attempts']++;
    checkLoginAttempts('', '', '');
    header('Location: index.php?error=user_not_found');
    exit();
}
// Función que verifica si se excede el límite de intentos y envía correo de alerta.
function checkLoginAttempts($email, $firstname, $lastname)
{
    if ($_SESSION['login_attempts'] === 3) {
        if (!empty($email)) {
            sendLoginAlertMail($email, $firstname, $lastname);
        }
        $_SESSION['show_modal_login'] = true;
    }
}
// Correo alertando de intentos fallidos
function sendLoginAlertMail($email, $firstname, $lastname)
{

    try {
        $mail = new PHPMailer(true);
        // Config SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        // Ajusta con tus credenciales
        $mail->Username   = 'arturobadillo18@gmail.com';
        $mail->Password   = 'oveighioveooixvi';
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // TLS implícito
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // $mail->Port       = 465;                         // Puerto seguro
        $mail->Port       = 587;
        // Remitente y destinatario
        $mail->setFrom('reppost.network@gmail.com', 'Reppost');
        $mail->addAddress($email, "$firstname $lastname");
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Intentos fallidos de inicio - Reppost';
        $mail->Body    = "
            <h3>Alerta de Seguridad</h3>
            <p>Hola, <strong>$firstname</strong>:</p>
            <p>Hemos detectado 3 intentos fallidos de inicio de sesión en tu cuenta. 
               Si fuiste tú ingoralo.
               Si no, considera restablecer tu contraseña para proteger tu cuenta.</p>
            <p>Atentamente,<br>El equipo de Reppost.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Error al enviar correo, 
        // Podrías loguear o ignorar
    }
}
