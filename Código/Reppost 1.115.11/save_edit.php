<?php
include('Config/dbcon.php');
// Recibir ID del usuario
$member_id = $_POST['member_id'];
// Consultar datos actuales
$stmtOld = $conn->prepare("
    SELECT *
    FROM members
    WHERE member_id = :member_id
");
$stmtOld->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$stmtOld->execute();
$oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
// Recuperar los datos del formulario (o usar los anteriores si están vacíos)
$mobile  = !empty($_POST['mobile'])  ? $_POST['mobile']  : $oldData['mobile'];
$gender  = !empty($_POST['gender'])  ? $_POST['gender']  : $oldData['gender'];
$country = !empty($_POST['country']) ? $_POST['country'] : $oldData['country'];
$city    = !empty($_POST['city'])    ? $_POST['city']    : $oldData['city'];
$work    = !empty($_POST['work'])    ? $_POST['work']    : $oldData['work'];

// Verificar si mobile ya está registrado en otro usuario
if ($mobile !== $oldData['mobile']) {
    $stmtCheck = $conn->prepare("
        SELECT COUNT(*) 
        FROM members 
        WHERE mobile = :mobile
          AND member_id <> :current_id
    ");
    $stmtCheck->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $stmtCheck->bindParam(':current_id', $member_id, PDO::PARAM_INT);
    $stmtCheck->execute();
    $alreadyExists = $stmtCheck->fetchColumn();
    if ($alreadyExists > 0) {
        // Si ya existe, mostrar alerta y retornar
        echo "<script>
            alert('Ese número de celular ya se encuentra registrado.');
            window.history.back(); 
        </script>";
        exit;
    }
}
// Si pasa la verificación, proceder a actualizar
$stmtUpdate = $conn->prepare("
    UPDATE members
    SET
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
exit;
