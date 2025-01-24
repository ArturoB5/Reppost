<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/textarea.css" rel="stylesheet">
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/style-regform.css" type="text/css" rel="stylesheet" />
    <link href="View/css/dark_mode.css" rel="stylesheet">
</head>
<?php
// Obtener el id del usuario que se quiere ver y obtener los detalles del perfil
$profile_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : $session_id;
$query = $conn->prepare("SELECT * FROM members WHERE member_id = :profile_id");
$query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
$query->execute();
$profile = $query->fetch();
if ($profile) {
    $profile_name = $profile['firstname'] . ' ' . $profile['lastname'];
    $profile_image = $profile['image'];
    $profile_email = $profile['email'];
    $photo_query = $conn->prepare("SELECT * FROM photos WHERE member_id = :profile_id");
    $photo_query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
    $photo_query->execute();
    $photos = $photo_query->fetchAll();
} else {
    echo "Perfil no encontrado.";
    exit;
}
?>

<body>
    <header class="navbar navbar-bright navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Barra de navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">
                    <img src="View/Images/app_images/logo.ico" alt="Logo" style="width: 20px; height: auto;">
                </a>
            </div>
            <nav class="collapse navbar-collapse" role="navigation">
                <ul class="nav navbar-nav">
                    <li><a style="margin-right:0%" href="home.php"><i class="fa fa-house"></i> Inicio</a></li>
                    <li><a style="margin-right:0%" href="profile.php"><i class="fa fa-user"></i> Perfil</a></li>
                    <li><a style="margin-right:0%" href="message.php"><i class="fa fa-comment"></i> Chat</a></li>
                    <li><a style="margin-right:0%" href="paintarea.php"><i class="fa fa-pencil"></i> Pizarra</a></li>
                    <li><a style="margin-right:0%" href="config_preferences.php"><i class="fa fa-gear"></i> Configuraciones</a></li>
                    <li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
                    <!-- Botón de Notificaciones -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">
                            <i class="fa fa-bell"></i>
                            <span id="notification-badge" class="badge badge-danger" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 10px; display: none;">0</span>
                            <!-- Botón para eliminar todas las notificaciones -->
                            <button id="delete-notifications-button" class="btn btn-danger" style="background-color: red; position: absolute; top: 0px; right: 18px; padding: 1.5px 5px;font-size: 8px; border-radius: 100px;">
                                <i class="fa fa-xmark"></i>
                            </button>
                        </a>
                        <ul class="dropdown-menu" id="notification-list" style="max-height: 300px; overflow-y: auto; width: 300px;">
                            <li class="dropdown-header">Notificaciones</li>
                            <li class="divider"></li>
                            <!-- Las notificaciones se cargan aquí -->
                        </ul>
                    </li>
                </ul>
                <div class="navbar-form navbar-search" role="search">
                    <form method="post" action="search.php" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control search-query" id="span5" placeholder="Buscar" style="margin-left: 110px;">
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <div id="masthead">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="background-image: url(View/Images/app_images/background.jpeg); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <br>
                    <?php
                    // Obtener el id del perfil
                    if (isset($_GET['member_id'])) {
                        $profile_id = intval($_GET['member_id']);
                    } else {
                        die("No se ha especificado el perfil.");
                    }
                    // Consultar información del usuario basado en su id
                    $query = $conn->prepare("SELECT * FROM members WHERE member_id = :profile_id");
                    $query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                    $query->execute();
                    $row = $query->fetch();
                    if (!$row) {
                        die("Perfil no encontrado.");
                    }
                    $image = $row['image'];
                    ?>
                    <div class="text-center">
                        <center><img src="<?php echo htmlspecialchars($image); ?>" style="border-radius: 50%; width:150px; height:150px"></center>
                    </div>
                    <br>
                </div>
                <div class="col-md-12 mx-auto"><br>
                    <div class="profile-info text-center" style="border-radius: 30px;">
                        <h3><strong>Información de usuario</strong></h3>
                        <?php
                        $query = $conn->prepare("
                            SELECT *
                            FROM members
                            WHERE member_id = :profile_id
                        ");
                        $query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                        $query->execute();
                        $row = $query->fetch();
                        $firstname     = htmlspecialchars($row['firstname'] ?? '');
                        $lastname      = htmlspecialchars($row['lastname'] ?? '');
                        $nombreCompleto = trim("$firstname $lastname");
                        $role          = $row['role'] ?? '';
                        if ($role === 'moderador') {
                            echo "<p style='text-align: center; font-weight: bold; font-size: 16px; color:#11a39c; font-style: italic;'>$nombreCompleto - Moderador <i class='fa-solid fa-star'></i></p>";
                            echo '<img src="View\\Images\\app_images\\mod.png" 
									   alt="Moderador" 
                                       title="MODERADOR"
									   style="position: absolute; top: 60px; right: 30px; width: 40px; cursor: pointer;" />';
                        } else {
                        ?>
                            <p><span class="info-label"><strong>Nombre completo:</strong></span> <?php echo $nombreCompleto; ?></p>
                            <p><span class="info-label"><strong>Género:</strong></span> <?php echo htmlspecialchars($row['gender']); ?></p>
                            <p><span class="info-label"><strong>País:</strong></span> <?php echo htmlspecialchars($row['country']); ?></p>
                        <?php
                        }
                        ?>
                        <?php
                        // Consulta si existe la relación en la tabla 'friends'
                        $checkFriend = $conn->prepare("
                            SELECT *
                            FROM friends
                            WHERE (my_id = :session_id AND my_friend_id = :profile_id)
                            OR (my_id = :profile_id AND my_friend_id = :session_id)
                        ");
                        $checkFriend->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                        $checkFriend->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                        $checkFriend->execute();
                        $isFriend = ($checkFriend->rowCount() > 0);
                        ?>
                        <!-- Botones de control -->
                        <?php
                        $stmt = $conn->prepare("SELECT role FROM members WHERE member_id = :profile_id");
                        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $userRow = $stmt->fetch();
                        $userRole = $userRow['role'] ?? 'usuario';
                        if ($userRole !== 'moderador') {
                        ?>
                            <!-- Botones de control -->
                            <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                                <?php
                                // BOTÓN AGREGAR AMIGO
                                if (!$isFriend && $profile_id != $session_id) {
                                ?>
                                    <form method="POST" action="add_friend.php" style="margin: 0;">
                                        <input type="hidden" name="my_friend_id" value="<?php echo $profile_id; ?>">
                                        <button type="submit" class="btn btn-info" style="font-size: 14px;">
                                            <i class="fa fa-user-plus"></i> Agregar
                                        </button>
                                    </form>
                                <?php
                                }
                                // BOTÓN ELIMINAR AMIGO 
                                if ($isFriend) {
                                ?>
                                    <a href="delete_friend.php?id=<?php echo $profile_id; ?>"
                                        class="btn btn-danger"
                                        style="font-size: 14px;">
                                        <i class="fa fa-user-minus"></i> Eliminar
                                    </a>
                                <?php
                                }
                                ?>
                                <!-- BOTÓN REPORTAR -->
                                <?php
                                // Verificar si ya se ha reportado
                                $check_reported = $conn->prepare("
                                    SELECT *
                                    FROM report_users
                                    WHERE reporter_id = :reporter_id
                                    AND reported_id = :reported_id
                                ");
                                $check_reported->bindParam(':reporter_id', $session_id, PDO::PARAM_INT);
                                $check_reported->bindParam(':reported_id', $profile_id, PDO::PARAM_INT);
                                $check_reported->execute();
                                $report_exists = $check_reported->rowCount() > 0;
                                $disabled = $report_exists ? 'disabled' : '';
                                ?>
                                <button class="btn btn-warning <?php echo $disabled; ?>"
                                    data-toggle="modal"
                                    data-target="#reportModal<?php echo $profile_id; ?>">
                                    <i class="fa fa-warning"></i> Reportar
                                </button>
                                <!-- BOTÓN BLOQUEAR -->
                                <form action="block_user.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="blocked_id" value="<?php echo $profile_id; ?>">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-lock"></i> Bloquear
                                    </button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- Modal de Reporte -->
                        <div class="modal fade" id="reportModal<?php echo $profile_id; ?>" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel<?php echo $profile_id; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reportModalLabel<?php echo $profile_id; ?>">Reporte de usuario</h5>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Formulario de Reporte -->
                                        <form action="report_user.php" method="POST" style="text-align: center;">
                                            <input type="hidden" name="reported_id" value="<?php echo $profile_id; ?>">
                                            <div class="form-group">
                                                <label for="report_type">Selecciona el tipo de reporte:</label>
                                                <select name="report_type" class="form-control" required>
                                                    <option value="Comportamiento inapropiado">Comportamiento inapropiado</option>
                                                    <option value="Contenido ofensivo">Contenido ofensivo</option>
                                                    <option value="Desnudos">Desnudos</option>
                                                    <option value="Amenazas">Amenazas</option>
                                                    <option value="Fraude">Fraude</option>
                                                    <option value="Spam">Spam</option>
                                                    <option value="Violencia">Violencia</option>
                                                    <option value="Información falsa">Información falsa</option>
                                                    <option value="Suplatanción de identidad">Suplantación de identidad</option>
                                                    <option value="Lenguaje inaproiado">Lenguaje inapropiado</option>
                                                    <option value="Bullying o acoso">Bullying o Acoso</option>
                                                    <option value="Suicidio o autolesión">Suicidio o autolesión</option>
                                                    <option value="Terrorismo">Terrorismo</option>
                                                    <option value="Ventas o promoción no autorizada">Ventas o promoción no autorizada</option>
                                                    <option value="Incumplimientos de normas">Incumplimiento de normas de Reppost</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fa fa-warning"></i> Reportar
                                            </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                <i class="fa fa-times"></i> Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel" style="border-radius: 30px;">
                                <div class="panel-body">
                                    <br><br>
                                    <form id="photos">
                                        <h2 class="text-center">Galería de fotos de <?php echo htmlspecialchars($profile_name); ?></h2>
                                        <hr>
                                        <div class="row">
                                            <?php if (count($photos) > 0): ?>
                                                <?php foreach ($photos as $photo): ?>
                                                    <div class="col-md-3 col-sm-6 text-center">
                                                        <img class="photo" src="<?php echo htmlspecialchars($photo['location']); ?>" style="width: 250px; height: 250px;">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p style="padding-left:20px">No hay fotos disponibles.</p>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/notifications.js"></script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>

</html>
<style>
    .profile-info {
        margin-top: 20px;
        background-color: white;
        padding: 10px;
        border-radius: 2%;
    }

    .info-label {
        display: inline-block;
        padding-left: 15px;
        font-weight: bold;
        white-space: nowrap;
        margin-left: 10px;
        width: 250px;
    }

    .panel-body {
        margin-top: 50px;
    }

    .photo {
        margin-bottom: 20px;
        border: 2px solid #ddd;
        border-radius: 5px;
        box-shadow: 7px 7px 7px rgba(0, 0, 0, 0.2);
    }
</style>