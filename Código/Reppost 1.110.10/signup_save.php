<?php
include('Config/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'libs/vendor/autoload.php';

$mail = new PHPMailer(true);
$username = $_POST['username'];
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$duplicateFields = [];
$passwordErrors = [];

if ($conn === null) {
  throw new Exception("Error de conexión a la base de datos.");
}
// Verificar si el username ya existe
$query = "SELECT * FROM members WHERE username = :username";
$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();
if ($stmt->rowCount() > 0) {
  $duplicateFields[] = "nombre de usuario";
}
// Verificar si el email ya existe
$query = "SELECT * FROM members WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
if ($stmt->rowCount() > 0) {
  $duplicateFields[] = "correo electrónico";
}
// Verificar la contraseña cumple con los requisitos
if (strlen($password) < 8) {
  $passwordErrors[] = "La contraseña debe tener al menos 8 caracteres.";
}
if (!preg_match('/[A-Z]/', $password)) {
  $passwordErrors[] = "La contraseña debe tener al menos una letra mayúscula.";
}
if (!preg_match('/[a-z]/', $password)) {
  $passwordErrors[] = "La contraseña debe tener al menos una letra minúscula.";
}
if (!preg_match('/[0-9]/', $password)) {
  $passwordErrors[] = "La contraseña debe tener al menos un número.";
}
if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
  $passwordErrors[] = "La contraseña debe tener al menos un carácter especial.";
}
if ($password !== $confirm_password) {
  $passwordErrors[] = "Las contraseñas no coinciden.";
}
if (!empty($passwordErrors)) {
  $errors = implode(" - ", $passwordErrors);
  echo "<script>
            alert('Error en la contraseña: $errors');
            window.history.back();
        </script>";
  exit;
}
// Verificar si el usuario tiene al menos 18 años
$birthdateTimestamp = strtotime($birthdate);
$age = (int)((time() - $birthdateTimestamp) / (60 * 60 * 24 * 365.25)); // Cálculo aproximado de años
if ($age < 18) {
  echo "<script>
            alert('Debes tener al menos 18 años para registrarte.');
            window.history.back();
        </script>";
  exit;
}
// Si se encontraron duplicados, mostramos un mensaje específico
if (!empty($duplicateFields)) {
  echo "<script>
            alert('Hey! Los siguientes campos ya han sido registrados: " . implode(', ', $duplicateFields) . "');
            window.history.back();
        </script>";
  exit;
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$verification_token = bin2hex(random_bytes(16));
$sql = "INSERT INTO members (username, email, password, firstname, lastname, gender, birthdate, image, verification_token)
            VALUES (:username, :email, :password, :firstname, :lastname, :gender, :birthdate, 'View/Images/app_images/default-user.png', :verification_token)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':birthdate', $birthdate);
$stmt->bindParam(':verification_token', $verification_token);
$stmt->execute();
try {
  // Ajustes del servidor SMTP
  $mail->isSMTP();                               // Usa SMTP
  $mail->Host       = 'smtp.gmail.com';          // Servidor SMTP de Gmail
  $mail->SMTPAuth   = true;
  $mail->Username   = 'arturobadillo18@gmail.com'; // Tu usuario de Gmail
  $mail->Password   = 'oveighioveooixvi';          // Contraseña real o de aplicación
  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // TLS implícito
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->Port       = 465;                         // Puerto seguro
  $mail->Port       = 587;
  // Remitente y destinatario
  $mail->setFrom('reppost.network@gmail.com', 'Reppost');
  $mail->addAddress($email, "$firstname $lastname");
  // Incrustar logo en el cuerpo del mensaje
  $mail->AddEmbeddedImage('View/Images/app_images/logo.png', 'reppostLogo', 'logo.png');
  // Contenido del correo
  $mail->isHTML(true);
  $mail->Subject = 'Verifica tu cuenta en Reppost';
  $mail->Body = '
    <div style="font-family: sans-serif; padding: 20px; background-color: #f0f0f0;">
      <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 5px; overflow: hidden;">
        <div style="padding: 10px 20px; text-align: center;">
          <img src="cid:reppostLogo" alt="Reppost Logo" style="width: 100px; display: block; margin: 0 auto;" />
        </div>
        <div style="padding: 20px;">
          <p style="margin-bottom: 15px;">Hola, <strong>' . $firstname . '</strong> :)</p>
          <p style="margin-bottom: 15px;">
            Gracias por registrarte en Reppost. 
            Para completar tu registro, por favor verifica tu cuenta haciendo clic en el siguiente enlace:
          </p>
          <div style="text-align: center; margin: 20px 0;">
            <a href="http://localhost/Reppost/verify_email.php?token=' . $verification_token . '" 
               style="background-color: #3d9db3; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
               Verificar mi correo
            </a>
          </div>
          <p style="margin-bottom: 15px;">
            Si no solicitaste verificar tu cuenta, puedes ignorar este correo.
          </p>
          <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
          <p style="font-size: 12px; color: #666;">
            © Reppost 2025. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </div>';
  $mail->send();
  // Redirigir con mensaje de éxito
  echo "<script>
        alert('Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.');
        window.location = 'index.php';
    </script>";
} catch (Exception $e) {
  echo "<script>
        alert('Error al enviar correo: {$e->getMessage()}');
        window.history.back();
    </script>";
}
