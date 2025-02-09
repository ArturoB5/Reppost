<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['id'])) { // Verifica si la variable 'id' está establecida. Si no lo está, redirige al usuario a index.php
    header('location:index.php'); // Redirige a la página de inicio si el usuario no ha iniciado sesión
    exit; // Asegura de que no se ejecute el resto del código después de la redirección
}
// Obtiene el id de la sesión y lo almacena en la variable local $session_id
$session_id = $_SESSION['id'];
// Prepara la consulta de forma segura utilizando una sentencia preparada para evitar inyección SQL
$query = "SELECT * FROM members WHERE member_id = :session_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT); // Vincula el parámetro de forma segura
$stmt->execute();
// Recupera la fila de resultados de la consulta de manera segura y la almacena en $user_row
$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
// Verifica si se ha encontrado un usuario con ese ID
if ($user_row) {
    // Concatena el nombre y el apellido del usuario para formar el nombre completo, y lo almacena en la variable $username
    $username = $user_row['firstname'] . " " . $user_row['lastname'];
    // Obtiene la ruta de la imagen de perfil del usuario desde la base de datos y la almacena en la variable $image
    $image = $user_row['image'];
} else {
    // Si no se encuentra el usuario, redirige a la página de inicio
    header('location:index.php');
    exit; // Asegura que no continúen procesándose más líneas de código
}
