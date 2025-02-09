<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'libs/vendor/autoload.php';

// Verificar si el usuario ha solicitado la eliminación
if (isset($_POST['delete_profile'])) {
    // 1) Obtener datos del usuario (email, firstname, lastname) 
    //    antes de borrarlo.
    $stmtUser = $conn->prepare("
        SELECT email, firstname, lastname 
        FROM members 
        WHERE member_id = :member_id
        LIMIT 1
    ");
    $stmtUser->bindParam(':member_id', $session_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        // Si no hay usuario, no hay nada que eliminar
        echo "<script>alert('Error: Usuario no encontrado');</script>";
        exit;
    }

    // 2) Eliminar el perfil de la base de datos
    $delete_query = $conn->prepare("
        DELETE FROM members
        WHERE member_id = :member_id
    ");
    $delete_query->bindParam(':member_id', $session_id, PDO::PARAM_INT);

    if ($delete_query->execute()) {
        // 3) Enviar correo de confirmación de eliminación
        $firstname = $userData['firstname'];
        $lastname  = $userData['lastname'];
        $email     = $userData['email'];
        // Lógica PHPMailer
        try {
            $mail = new PHPMailer(true);
            // Ajustes del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'arturobadillo18@gmail.com';
            $mail->Password   = 'oveighioveooixvi';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->Port       = 465;                         // Puerto seguro
            $mail->Port       = 587;
            // Remitente y destinatario
            $mail->setFrom('reppost.network@gmail.com', 'Reppost');
            $mail->addAddress($email, "$firstname $lastname");
            // Incrustar logo en el cuerpo del mensaje
            $mail->AddEmbeddedImage('View/Images/app_images/logo.png', 'reppostLogo', 'logo.png');
            // Configurar HTML
            $mail->isHTML(true);
            $mail->Subject = 'Tu perfil ha sido eliminado';
            $mail->Body = "
            <div style='font-family: sans-serif; padding: 20px; background-color: #f0f0f0;'>
                <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 5px; overflow: hidden;'>
                    <div style='padding: 10px 20px; text-align: center;'>
                        <!-- Aquí incrustamos la imagen usando cid: -->
                        <img src='cid:reppostLogo' alt='Reppost Logo' style='width: 100px; display: block; margin: 0 auto;' />
                        <h2 style='color: #ff0000;'>Perfil Eliminado</h2>
                    </div>
                    <div style='padding: 20px;'>
                        <p>Hola, <strong>$firstname</strong>:</p>
                        <p>Te confirmamos que tu perfil en <strong>Reppost</strong> ha sido eliminado satisfactoriamente. 
                        Ya no tendras acceso a tu cuenta ni a los datos asociados.</p>
                        <p>¡Gracias por haber formado parte de nuestra comunidad!</p>
                        <hr style='border:none; border-top:1px solid #ddd; margin:20px 0;'>
                        <p style='font-size: 12px; color: #666;'>© Reppost 2025. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>";
            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
        }
        session_destroy();
        header('Location: index.php');
        exit();
    } else {
        echo "Error al eliminar el perfil.";
    }
}
