<?php
include('Config/dbcon.php');

// Obtener datos del formulario
$username_or_email = $_POST['username'];
$password = $_POST['password'];

// Consulta para buscar usuario por nombre de usuario o correo electrónico
$query = $conn->prepare("
    SELECT * 
    FROM members 
    WHERE username = :username_or_email OR email = :username_or_email
");
$query->bindParam(':username_or_email', $username_or_email);
$query->execute();
$row = $query->fetch();

if ($row) {
    $hashed_password = $row['password'];
    // Verificar contraseña
    if (password_verify($password, $hashed_password)) {
        if ($row['email_verified'] == 1) {
            // Email verificado, iniciar sesión
            session_start();
            $_SESSION['id'] = $row['member_id'];
            header('Location: home.php');
            exit();
        } else {
            // Email no verificado
            header('Location: index.php?error=email_not_verified');
            exit();
        }
    } else {
        // Error: Contraseña inválida
        header('Location: index.php?error=invalid_password');
        exit();
    }
} else {
    // Error: Usuario o correo no encontrado
    header('Location: index.php?error=user_not_found');
    exit();
}
