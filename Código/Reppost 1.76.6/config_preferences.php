<?php include('Config/dbcon.php'); ?>
<?php include('Controller/Backend/session.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/dark_mode.css" rel="stylesheet">
</head>

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
    <div class="container mt-5">
        <h2>Preferencias</h2>
        <!-- Formulario de Preferencias -->
        <form method="POST">
            <!-- Campo para cambiar el tema -->
            <div class="form-group">
                <label for="theme">Tema</label>
                <button id="darkModeToggle" class="btn btn-light" style="padding: 14px 5px;">
                    <i class="fa fa-moon"></i> Modo Oscuro
                </button>
            </div>
        </form>
        <hr>
        <?php
        // Consulta para obtener todos los usuarios que YO he bloqueado
        $query = $conn->prepare("
            SELECT 
                b.block_id, 
                m.member_id, 
                m.firstname, 
                m.lastname, 
                m.image
            FROM blocked_users b
            JOIN members m ON b.blocked_id = m.member_id
            WHERE b.user_id = :session_id
            ");
        $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $query->execute();
        $countBlocked = $query->rowCount();
        ?>
        <h2>Bloqueos</h2>
        <?php
        if (isset($_SESSION['status'])) {
            echo "<div class='alert alert-success' role='alert'>
            {$_SESSION['status']}
          </div>";
            // Eliminar el mensaje de la sesión para que no se repita
            unset($_SESSION['status']);
        }
        ?>
        <?php
        if ($countBlocked > 0) {
            while ($row = $query->fetch()) {
                $block_id  = $row['block_id'];  // id del registro en blocked_users
                $blockedId = $row['member_id']; // usuario bloqueado
                $fullname  = $row['firstname'] . " " . $row['lastname'];
                $image     = $row['image'];
        ?>
                <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px;">
                    <img src="<?php echo htmlspecialchars($image); ?>"
                        style="width:50px;height:50px;border-radius:50%;"
                        alt="Foto de <?php echo htmlspecialchars($fullname); ?>" />
                    <strong><?php echo htmlspecialchars($fullname); ?></strong>
                    <!-- Botón para desbloquear -->
                    <form action="unblock_user.php" method="POST" style="display: inline;">
                        <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                        <!-- O si prefieres usar blockedId en lugar de block_id, se ajusta en el siguiente paso -->
                        <button type="submit" class="btn btn-success">
                            Desbloquear
                        </button>
                    </form>
                </div>
        <?php
            }
        } else {
            echo "<p>No has bloqueado a ningún usuario.</p>";
        }
        ?>
        <hr>
        <h2>Cambio de contraseña</h2>
        <form action="#" method="POST" class="form-inline">
            <!-- Por ahora sin lógica, solo el diseño básico -->
            <div class="form-group">
                <label for="new_password">Nueva contraseña:</label>
                <input type="password" id="new_password" name="new_password"
                    class="form-control" placeholder="Nueva contraseña">
            </div>
            <div class="form-group" style="margin-left: 10px;">
                <label for="confirm_password">Confirmar contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="form-control" placeholder="Repite la nueva contraseña">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-left: 10px;">
                Guardar
            </button>
        </form>
        <hr>
        <h2>Eliminar Perfil</h2>
        <form id="delete-profile-form" action="delete_profile.php" method="POST">
            <button type="submit" name="delete_profile" class="btn btn-danger" id="delete-btn">Eliminar Perfil</button>
        </form>
        <script>
            // Obtener el botón de eliminación y el formulario
            const deleteBtn = document.getElementById('delete-btn');
            const deleteForm = document.getElementById('delete-profile-form');
            // Agregar un evento de clic al botón de eliminación
            deleteBtn.addEventListener('click', function(event) {
                // Mostrar advertencia de confirmación
                const userConfirmed = confirm('¿Estás seguro de que quieres eliminar tu perfil? Esta acción es irreversible.');
                // Si el usuario confirma la eliminación, enviar el formulario
                if (!userConfirmed) {
                    event.preventDefault(); // Evitar que se envíe el formulario si el usuario cancela
                }
            });
        </script>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/notifications.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>

</html>