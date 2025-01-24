<?php
include('Config/dbcon.php');
$member_id = $_POST['member_id'];

// Obtener los datos actuales del usuario en la BD
$stmtOld = $conn->prepare("SELECT * FROM members WHERE member_id = :member_id");
$stmtOld->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$stmtOld->execute();
$oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
// Recupera los datos y evita sobrescribir valores vacíos
$mobile  = !empty($_POST['mobile'])  ? $_POST['mobile']  : $oldData['mobile'];
$gender  = !empty($_POST['gender'])  ? $_POST['gender']  : $oldData['gender'];
$country = !empty($_POST['country']) ? $_POST['country'] : $oldData['country'];
$city    = !empty($_POST['city'])    ? $_POST['city']    : $oldData['city'];
$work    = !empty($_POST['work'])    ? $_POST['work']    : $oldData['work'];
// Actualizar
$stmtUpdate = $conn->prepare("
    UPDATE members SET 
        mobile  = :mobile,
        gender  = :gender,
        country = :country,
        city    = :city,
        work    = :work
    WHERE member_id = :member_id
");
$stmtUpdate->bindParam(':mobile',     $mobile);
$stmtUpdate->bindParam(':gender',     $gender);
$stmtUpdate->bindParam(':country',    $country);
$stmtUpdate->bindParam(':city',       $city);
$stmtUpdate->bindParam(':work',       $work);
$stmtUpdate->bindParam(':member_id',  $member_id, PDO::PARAM_INT);
$stmtUpdate->execute();
echo "<script>window.location = 'profile.php?id={$member_id}';</script>";
