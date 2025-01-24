<?php
include('Config/dbcon.php');
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$username = $_POST['username'];
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$duplicateFields = [];
$passwordErrors = [];

try {
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
	// Verificar si el móvil ya existe
	$query = "SELECT * FROM members WHERE mobile = :mobile";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':mobile', $mobile);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		$duplicateFields[] = "número de móvil";
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

	$sql = "INSERT INTO members (username, email, password, firstname, lastname, gender, birthdate, mobile, image, verification_token)
            VALUES (:username, :email, :password, :firstname, :lastname, :gender, :birthdate, :mobile, 'View/Images/app_images/default-user.png', :verification_token)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':password', $hashed_password);
	$stmt->bindParam(':firstname', $firstname);
	$stmt->bindParam(':lastname', $lastname);
	$stmt->bindParam(':gender', $gender);
	$stmt->bindParam(':birthdate', $birthdate);
	$stmt->bindParam(':mobile', $mobile);
	$stmt->bindParam(':verification_token', $verification_token);
	$stmt->execute();

	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'reppost_info@gmail.com';
	$mail->Password = 'nlcx vsac mpex niwk';
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;

	$mail->setFrom('reppost_info@gmail.com', 'Reppost');
	$mail->addAddress($email, "$firstname $lastname");

	$mail->isHTML(true);
	$mail->Subject = 'Verifica tu cuenta en Reppost';
	$mail->Body = "
        <p>Hola, $firstname:</p>
        <p>Gracias por registrarte en Reppost. Por favor, verifica tu cuenta haciendo clic en el siguiente enlace:</p>
        <a href='http://localhost/Reppost/verify_email.php?token=$verification_token'>Verificar mi correo</a>
        <p>Si no solicitaste esta verificación, puedes ignorar este correo.</p>
    ";

	$mail->send();
	echo "<script>
        alert('Registro exitoso. Por favor, revisa tu correo para verificar tu cuenta.');
        window.location = 'index.php';
    </script>";
} catch (Exception $e) {
	echo "<script>
        alert('Error: {$e->getMessage()}');
        window.history.back();
    </script>";
}
