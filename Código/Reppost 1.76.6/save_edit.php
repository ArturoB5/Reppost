<?php
include('Config/dbcon.php');

$member_id = $_POST['member_id'];
$gender = $_POST['gender'];
$country = $_POST['country'];
$city = $_POST['city'];
$work = $_POST['work'];

// Sentencia SQL para actualizar los datos del miembro
$stmt = $conn->prepare("UPDATE members SET 
    gender = :gender,
    country = :country,
    city = :city,
    work = :work
    WHERE member_id = :member_id
");
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':country', $country);
$stmt->bindParam(':city', $city);
$stmt->bindParam(':work', $work);
$stmt->bindParam(':member_id', $member_id);
$stmt->execute();

// Redirigir de vuelta a la página de perfil después de guardar
echo '<script>window.location = "profile.php?id=' . $member_id . '";</script>';
