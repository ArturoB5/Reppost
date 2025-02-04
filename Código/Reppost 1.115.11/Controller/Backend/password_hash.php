<?php
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$conn->query("INSERT INTO members (username, email, password, firstname, lastname, gender, image) VALUES ('$username', '$email', '$hashed_password', '$firstname', '$lastname', '$gender', 'View/Images/all_images/No_Photo_Available.jpg')");
