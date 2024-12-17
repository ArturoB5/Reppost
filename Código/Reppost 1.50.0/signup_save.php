<?php
include('Config/dbcon.php');

// Variables de entrada
$username = $_POST['username'];
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password']; // Contraseña de confirmación

// Inicializamos un array para almacenar los campos duplicados y errores de validación
$duplicateFields = [];
$passwordErrors = [];

try {
	// Verificar si la conexión a la base de datos fue exitosa
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

	// Verificar si la contraseña cumple con los requisitos
	if (strlen($password) < 8) {
		$passwordErrors[] = " La contraseña debe tener al menos 8 caracteres.";
	}
	if (!preg_match('/[A-Z]/', $password)) {
		$passwordErrors[] = " La contraseña debe tener al menos una letra mayúscula.";
	}
	if (!preg_match('/[a-z]/', $password)) {
		$passwordErrors[] = " La contraseña debe tener al menos una letra minúscula.";
	}
	if (!preg_match('/[0-9]/', $password)) {
		$passwordErrors[] = " La contraseña debe tener al menos un número.";
	}
	if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
		$passwordErrors[] = " La contraseña debe tener al menos un carácter especial.";
	}

	// Verificar si las contraseñas coinciden
	if ($password !== $confirm_password) {
		$passwordErrors[] = " Las contraseñas no coinciden.";
	}

	// Si hay errores en la contraseña, los mostramos y detenemos el proceso de registro
	if (!empty($passwordErrors)) {
		// Unir los errores con guion
		$errors = implode(" - ", $passwordErrors);
		echo "<script>
                alert('Error en la contraseña:$errors');
                window.history.back(); // Regresar al formulario
              </script>";
		exit;
	}
	// Verificar si el usuario tiene al menos 18 años
	$birthdateTimestamp = strtotime($birthdate);
	$age = (int)((time() - $birthdateTimestamp) / (60 * 60 * 24 * 365.25)); // Cálculo aproximado de años

	if ($age < 18) {
		echo "<script>
            alert('Debes tener al menos 18 años para registrarte.');
            window.history.back(); // Regresar al formulario
          </script>";
		exit;
	}
	// Si se encontraron duplicados, mostramos un mensaje específico
	if (!empty($duplicateFields)) {
		$fields = implode(", ", $duplicateFields);
		echo "<script>
                alert('Hey! los siguientes campos ya han sido registrados: $fields.');
                window.history.back(); // Regresar al formulario
              </script>";
	} else {
		// Si no hay duplicados y la contraseña es válida, proceder a insertar el nuevo usuario
		$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Crear el hash de la contraseña

		$sql = "INSERT INTO members (username, email, password, firstname, lastname, gender, birthdate, mobile, image)
                VALUES (:username, :email, :password, :firstname, :lastname, :gender, :birthdate, :mobile, 'View/Images/all_images/No_Photo_Available.jpg')";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $hashed_password);
		$stmt->bindParam(':firstname', $firstname);
		$stmt->bindParam(':lastname', $lastname);
		$stmt->bindParam(':gender', $gender);
		$stmt->bindParam(':birthdate', $birthdate);
		$stmt->bindParam(':mobile', $mobile);

		try {
			$stmt->execute();
			echo "<script>
                    alert('Te has registrado exitosamente');
                    window.location = 'index.php'; // Redirigir al inicio
                  </script>";
		} catch (PDOException $e) {
			echo "<script>
                    alert('Hubo un error al registrarte: " . $e->getMessage() . "');
                    window.history.back(); // Regresar al formulario
                  </script>";
		}
	}
} catch (Exception $e) {
	// Manejo de errores generales (como problemas de conexión)
	echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.history.back(); // Regresar al formulario
          </script>";
} catch (PDOException $e) {
	// Manejo de errores específicos de PDO
	echo "<script>
            alert('Error en la base de datos: " . $e->getMessage() . "');
            window.history.back(); // Regresar al formulario
          </script>";
}
