<?php
include('Config/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'libs/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $identifier = $_POST['identifier'] ?? '';
  if (empty($identifier)) {
    echo "<script>
            alert('Por favor ingresa tu correo o usuario.');
            window.history.back();
        </script>";
    exit;
  }
  // Buscar al usuario por email o username
  $query = "
        SELECT * FROM members 
        WHERE email = :identifier OR username = :identifier
        LIMIT 1
    ";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':identifier', $identifier);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    // Usuario no encontrado
    echo "<script>
            alert('No existe una cuenta con ese correo o usuario.');
            window.history.back();
        </script>";
    exit;
  }
  // Generar token de reseteo
  $reset_token = bin2hex(random_bytes(16));
  // Actualizar la BD con el reset_token
  $updateQuery = "UPDATE members SET reset_token = :token WHERE member_id = :member_id";
  $updateStmt = $conn->prepare($updateQuery);
  $updateStmt->bindParam(':token', $reset_token);
  $updateStmt->bindParam(':member_id', $user['member_id']);
  $updateStmt->execute();
  // Enviar correo con PHPMailer
  $mail = new PHPMailer(true);
  try {
    // Configura tu SMTP
    $mail->isSMTP();                               // Usa SMTP
    $mail->Host       = 'smtp.gmail.com';          // Servidor SMTP de Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'arturobadillo18@gmail.com'; // Tu usuario de Gmail
    $mail->Password   = 'oveighioveooixvi';          // Contraseña real o de aplicación
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // TLS implícito
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port       = 465;                         // Puerto seguro
    $mail->Port       = 587;
    $mail->setFrom('reppost.network@gmail.com', 'Reppost');
    $mail->addAddress($user['email'], $user['firstname'] . ' ' . $user['lastname']);
    $mail->AddEmbeddedImage('View/Images/app_images/logo.png', 'reppostLogo', 'logo.png');
    $mail->isHTML(true);
    $mail->Subject = 'Recupera tu clave de Reppost';
    $mail->Body = '
        <div style="font-family: sans-serif; padding: 20px; background-color: #f0f0f0;">
          <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 5px; overflow: hidden;">
            <div style="padding: 10px 20px; text-align: center;">
              <img src="cid:reppostLogo" alt="Reppost Logo" style="width: 100px; display: block; margin: 0 auto;" />
            </div>
            <div style="padding: 20px;">
              <p style="margin-bottom: 15px;">Hola, <strong>' . $user['firstname'] . '</strong></p>
              <p style="margin-bottom: 15px;">
                Recibimos una solicitud para restablecer la clave de tu cuenta Reppost.
              </p>
              <p style="margin-bottom: 15px;">
                Para establecer una nueva, haz clic en el siguiente boton:
              </p>
              <div style="text-align: center; margin: 20px 0;">
                <a href="http://localhost/Reppost/reset_password.php?token=' . $reset_token . '" 
                   style="background-color: #3d9db3; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                   Restablecer clave
                </a>
              </div>
              <p style="margin-bottom: 15px;">
                Si no solicitaste este cambio, ignora este correo.
              </p>
              <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
              <p style="font-size: 12px; color: #666;">
                © Reppost 2025. Todos los derechos reservados.
              </p>
            </div>
          </div>
        </div>
        ';
    $mail->send();
    echo "<script>
            alert('Te hemos enviado un correo con instrucciones para recuperar tu contraseña.');
            window.location='index.php';
        </script>";
  } catch (Exception $e) {
    echo "<script>
            alert('Ocurrió un error al enviar el correo: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
  }
}
