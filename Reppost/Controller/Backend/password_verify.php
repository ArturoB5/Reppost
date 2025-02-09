<?php
$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT password FROM members WHERE username = '$username'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];
    if (password_verify($password, $hashed_password)) {
        echo "Inicio de sesión exitoso.";
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "El usuario no existe.";
}
