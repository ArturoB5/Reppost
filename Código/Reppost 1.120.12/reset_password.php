<?php
include('Config/dbcon.php');

$token = $_GET['token'] ?? '';
if (empty($token)) {
    echo "Token inválido.";
    exit;
}
// Verificar si el token existe
$query = "SELECT member_id FROM members WHERE reset_token = :token LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':token', $token);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Token no válido o expirado.";
    exit;
}
if ($_POST) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = "UPDATE members 
                   SET password = :pass, reset_token=NULL
                   WHERE member_id=:id";
        $upStmt = $conn->prepare($update);
        $upStmt->bindParam(':pass', $hashed);
        $upStmt->bindParam(':id', $user['member_id']);
        $upStmt->execute();

        echo "<script>
            alert('¡Contraseña actualizada con éxito!');
            window.location='index.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reppost</title>
    <link rel="shortcut icon" href="View/Images/app_images/logo.ico">
    <link rel="stylesheet" type="text/css" href="View/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="View/css/style_index.css" />
    <link rel="stylesheet" type="text/css" href="View/css/animate-custom.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="clr">
            <div class="title"></div>
            <a href="index.php">
                <img class="logo" src="View/Images/app_images/logo.png" alt="Logo" width="150">
            </a>
        </div>
        <section>
            <!-- Podrías usar el mismo contenedor -->
            <div id="container_demo">
                <div id="wrapper">
                    <div style="position: relative;">
                        <div id="login" class="animate form" style="padding: 16px 6% 8px 6%">
                            <form method="post">
                                <h4>REESTABLECER CONTRASEÑA</h4>
                                <hr>
                                <p style="position: relative;">
                                    <label for="new_password">Nueva contraseña</label>
                                    <input type="password" id="new_password" name="new_password" required
                                        style="width: 100%; padding-left: 30px; box-sizing: border-box;" />
                                    <i class="fa fa-key"
                                        style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)">
                                    </i>
                                </p>
                                <p style="position: relative;">
                                    <label for="confirm_password">Confirmar contraseña</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                        style="width: 100%; padding-left: 30px; box-sizing: border-box;" />
                                    <i class="fa fa-key"
                                        style="position: absolute; left: 10px; top: 35px; color: rgb(97 160 159)">
                                    </i>
                                </p>
                                <p class="login button" style="margin-top: 20px;">
                                    <button type="submit" style="font-size: 24px; width: 30%; cursor: pointer; background: rgb(61, 157, 179); color: #fff; border: 2px solid rgb(28, 108, 122);text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5); -webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;-webkit-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset, 0px 0px 0px 3px rgb(254, 254, 254), 0px 5px 3px 3px rgb(210, 210, 210);-moz-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset, 0px 0px 0px 3px rgb(254, 254, 254), 0px 5px 3px 3px rgb(210, 210, 210);box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset, 0px 0px 0px 3px rgb(254, 254, 254), 0px 5px 3px 3px rgb(210, 210, 210);-webkit-transition: all 0.2s linear;-moz-transition: all 0.2s linear;-o-transition: all 0.2s linear;transition: all 0.2s linear;">
                                        Guardar
                                    </button>
                                </p>
                            </form>
                            <!-- Opcional: Mostrar mensaje de error si las contraseñas no coinciden -->
                            <?php if (isset($error_message)): ?>
                                <div class="error-message">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </section>
    </div>

</body>

</html>