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
        <h2><i class="fa fa-gear" style="color: rgb(97 160 159)"></i> Preferencias</h2>
        <form method="POST">
            <div class="form-group">
                <h3>Tema <i class="fa fa-brush" style="color: rgb(97 160 159)"></i></h3>
                <div class="alert alert-warning" style="font-size: 16px;">
                    Cambia el tema visual de la plataforma entre claro y oscuro según tus preferencias. Este cambio afectará la apariencia general de la aplicación.
                </div><br>
                <button id="darkModeToggle" class="btn btn-light">
                    <i class="fa fa-circle-half-stroke"></i> Modo Oscuro
                </button>
            </div>
        </form>
        <?php
        // Consulta para obtener los usuarios bloqueados
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
        <h3>Notificaciones <i class="fa fa-bell" style="color: rgb(97 160 159)"></i></h3>
        <div class="alert alert-warning" style="font-size: 16px;">
            Elimina todas las notificaciones que tengas en tu bandeja de notificaciones
        </div><br>
        <form method="POST" action="delete_notifications_config.php">
            <button type="submit" class="btn btn-danger" style="margin-bottom: 10px;">
                <i class="fa fa-trash"></i> Eliminar
            </button>
        </form>
        <hr>
        <h2><i class="fa fa-shield" style="color: rgb(97 160 159)"></i> Seguridad</h2>
        <h3>Usuarios bloqueados <i class="fa fa-user-lock" style="color: rgb(97 160 159)"></i></h3>
        <div class="alert alert-warning" style="font-size: 16px;">
            Aquí puedes gestionar a los usuarios que has bloqueado. Los usuarios bloqueados no podrán interactuar contigo en la plataforma. Si decides desbloquear a alguien, podrán volver a enviarte mensajes y solicitudes.
        </div><br>
        <?php
        if (isset($_SESSION['status'])) {
            echo "<div class='alert alert-success' role='alert'>
            {$_SESSION['status']}
          </div>";
            unset($_SESSION['status']);
        }
        ?>
        <?php
        if ($countBlocked > 0) {
            while ($row = $query->fetch()) {
                $block_id  = $row['block_id'];
                $blockedId = $row['member_id'];
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
                        <div class="pull-right" style="margin-top:6px">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-unlock"></i> Desbloquear
                            </button>
                        </div>
                    </form>
                </div>
        <?php
            }
        } else {
            echo "";
        }
        ?>
        <h3>Cambio de contraseña <i class="fa fa-key" style="color: rgb(97 160 159)"></i> </h3>
        <div class="alert alert-warning" style="font-size: 16px;">
            Cambia tu contraseña para mantener tu cuenta segura. Asegúrate de que la nueva contraseña cumpla con los requisitos de seguridad y coincida antes de guardarla.
        </div><br>
        <form action="change_password.php" method="POST" class="form-inline">
            <!-- Campo contraseña actual -->
            <div style="position: relative; margin-bottom: 10px; width: 450px;">
                <label for="current_password" style="position: relative; display: inline-block;">Contraseña actual</label>
                <input id="current_password" name="current_password" class="form-control" required="required" type="password" placeholder="Contraseña actual" style="width: 100%; box-sizing: border-box;">
                <i id="toggle-password-current" class="fa fa-eye" style="position: absolute; right: 10px; top: 35px; cursor: pointer; color: rgb(97 160 159);"></i>
            </div><br>
            <!-- Campo de nueva contraseña -->
            <div style="position: relative; margin-bottom: 10px; width: 450px;">
                <label for="new_password" style="position: relative; display: inline-block;">Nueva contraseña
                    <i class="fa fa-info-circle" style="margin-left: 5px; color: #61A09F; cursor: pointer;"
                        title="La contraseña debe tener al menos:
- 8 caracteres.
- Una letra mayúscula.
- Una letra minúscula.
- Un número.
- Un carácter especial (@, #, $, etc.).">
                    </i>
                </label>
                <input id="new_password" name="new_password" class="form-control" required="required" type="password" placeholder="Nueva contraseña" style="width: 100%; box-sizing: border-box;">
                <i id="toggle-password-new" class="fa fa-eye" style="position: absolute; right: 10px; top: 35px; cursor: pointer; color: rgb(97 160 159);"></i>
                <div id="password-strength" style="margin-top: 10px; height: 10px; width: 100%; background-color: #ccc; border-radius: 5px;">
                    <div id="strength-bar" style="height: 100%; width: 0%; border-radius: 5px;"></div>
                </div>
                <span id="strength-text" style="font-size: 14px; color: #555; margin-top: 5px; display: inline-block;">Dificultad:</span>
            </div>
            <!-- Campo de confirmación de contraseña -->
            <div style="position: relative; margin-bottom: 10px; width: 450px;">
                <label for="confirm_password" style="position: relative; display: inline-block;">Confirmar contraseña<i class="fa fa-info-circle" style="margin-left: 5px; color: #61A09F; cursor: pointer;" title="La contraseña debe coincidir con la nueva contraseña."></i></label>
                <input id="confirm_password" name="confirm_password" class="form-control" required="required" type="password" placeholder="Confirmar contraseña" style="width: 100%; box-sizing: border-box;">
                <i id="toggle-password-confirm" class="fa fa-eye" style="position: absolute; right: 10px; top: 35px; cursor: pointer; color: rgb(97 160 159);"></i>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
        <hr>
        <!-- Sección de reportes -->
        <h2><i class="fa fa-flag" style="color: rgb(97 160 159)"></i> Reportes</h2>
        <div class="alert alert-warning" style="font-size: 16px;">
            Visualiza los reportes que has hecho a comentarios y publicaciones de otro usuarios, como a cuentas de otros usuarios de Reppost.
            Además mira los reportes que tienes
        </div><br>
        <h3>Usuarios reportados <i class="fa fa-user-tag" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $reported_users_query = $conn->prepare("
                SELECT m.member_id, m.firstname, m.lastname, m.image, r.status, r.status_response
                FROM members AS m
                JOIN report_users AS r ON m.member_id = r.reported_id
                WHERE r.reporter_id = :session_id
            ");
            $reported_users_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $reported_users_query->execute();
            while ($user = $reported_users_query->fetch()) {
                echo '<div class="report-card">';
                echo '<img src="' . htmlspecialchars($user['image'] ?? 'default_profile.png') . '" style="width:35px;height:35px;" class="img-circle">';
                echo '<b>' . ' ' . htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) . '</b>';
                echo '<hr>';
                echo '<b>Estado:</b> ' . htmlspecialchars($user['status']) . '<br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($user['status_response']) . '<br>';
                echo '</div>';
            }
            ?>
        </div>
        <h3>Publicaciones reportadas <i class="fa fa-message" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $reported_posts_query = $conn->prepare("
                SELECT p.post_id, p.content, m.firstname, m.lastname, m.image, pr.status, pr.status_response
                FROM post AS p
                JOIN post_reports AS pr ON p.post_id = pr.post_id
                JOIN members AS m ON p.member_id = m.member_id
                WHERE pr.user_id = :session_id
            ");
            $reported_posts_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $reported_posts_query->execute();
            while ($post = $reported_posts_query->fetch()) {
                echo '<div class="report-card">';
                echo '<img src="' . htmlspecialchars($post['image'] ?? 'default_profile.png') . '" style="width:35px;height:35px;" class="img-circle">';
                echo '<b>' . ' ' . htmlspecialchars($post['firstname'] . ' ' . $post['lastname']) . ': ' . '</b>' . htmlspecialchars($post['content']) . '</b>';
                echo '<hr>';
                echo '<b>Estado:</b> ' . htmlspecialchars($post['status']) . '<br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($post['status_response']) . '<br>';
                echo '</div>';
            }
            ?>
        </div>
        <h3>Comentarios reportados <i class="fa fa-comments" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $reported_comments_query = $conn->prepare("
                SELECT c.comment_id, c.comment_text, m.firstname, m.lastname, m.image, rc.status, rc.status_response
                FROM post_comments AS c
                JOIN reports_comment AS rc ON c.comment_id = rc.comment_id
                JOIN members AS m ON c.user_id = m.member_id
                WHERE rc.user_id = :session_id
            ");
            $reported_comments_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $reported_comments_query->execute();
            while ($comment = $reported_comments_query->fetch()) {
                echo '<div class="report-card">';
                echo '<img src="' . htmlspecialchars($comment['image'] ?? 'default_profile.png') . '" style="width:35px;height:35px;" class="img-circle">';
                echo '<b>' . ' ' . htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']) . ': ' . '</b>' . htmlspecialchars($comment['comment_text']);
                echo '<hr>';
                echo '<b>Estado:</b> ' . htmlspecialchars($comment['status']) . '<br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($comment['status_response']) . '<br>';
                echo '</div>';
            }
            ?>
        </div>
        <h3>Te reportaron <i class="fa fa-flag-checkered" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $report_to_me_query = $conn->prepare("
                SELECT 
                    pr.report_type, 
                    pr.report_date, 
                    pr.status,
                    pr.status_response, 
                    'publicación' AS report_source
                FROM post_reports AS pr
                JOIN post AS p ON p.post_id = pr.post_id
                WHERE p.member_id = :session_id
                UNION
                SELECT 
                    rc.report_type, 
                    rc.report_date, 
                    rc.status,
                    rc.status_response, 
                    'comentario' AS report_source
                FROM reports_comment AS rc
                JOIN post_comments AS c ON c.comment_id = rc.comment_id
                WHERE c.user_id = :session_id
                UNION
                SELECT 
                    ru.report_type, 
                    ru.report_date, 
                    ru.status,
                    ru.status_response, 
                    'usuario' AS report_source
                FROM report_users AS ru
                WHERE ru.reported_id = :session_id
            ");
            $report_to_me_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $report_to_me_query->execute();
            while ($report = $report_to_me_query->fetch()) {
                $formatted_date = date('d/m/Y H:i', strtotime($report['report_date']));
                echo '<div class="report-card">';
                echo '<b>Tipo de reporte:</b> ' . htmlspecialchars($report['report_type']) . ' de ' . ucfirst($report['report_source']) . '</br>';
                echo '<b>Fecha de reporte:</b> ' . $formatted_date . '</br>';
                echo '<b>Estado:</b> ' . htmlspecialchars($report['status']) . '</br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($report['status_response']);
                echo '</div>';
            }
            ?>
        </div>
        <hr>
        <h2><i class="fa fa-id-card" style="color: rgb(97 160 159)"></i> Gestión de la cuenta</h2>
        <h3>Eliminar Perfil <i class="fa fa-user-xmark" style="color: rgb(97 160 159)"></i></h3>
        <div class="alert alert-warning" style="font-size: 16px;">
            <strong style="color: red;">¡Advertencia!</strong> Esta acción eliminará de forma permanente tu perfil y todos los datos asociados, incluyendo:
            <ul>
                <li>Tu información personal (nombre, email, etc.).</li>
                <li>Todas tus publicaciones.</li>
                <li>Imágenes, comentarios y mensajes.</li>
                <li>Amigos y conexiones.</li>
            </ul>
            Una vez completada, esta acción no se puede deshacer. Por favor, asegúrate de estar completamente seguro antes de proceder.
        </div><br>
        <form id="delete-profile-form" action="delete_profile.php" method="POST">
            <button type="submit" name="delete_profile" class="btn btn-danger" id="delete-btn"><i class="fa fa-eraser"></i> Eliminar Perfil</button>
        </form><br>
        <?php
        $query = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id");
        $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch();
        if ($user['role'] == 'moderador') {
        ?>
            <hr>
            <h2><i class="fa fa-id-badge" style="color: rgb(97 160 159)"></i> Moderación</h2>
            <div class="alert alert-warning" style="font-size: 16px;">
                Estás entrando a un entorno de <strong style="color: red;">moderación</strong> para gestionar los reportes de usuarios, publicaciones y comentarios.
            </div><br>
            <a href="moderator.php" class="btn btn-warning"><i class="fa fa-cogs"></i> Ir a Moderación</a>
            <br><br>
        <?php
        }
        ?>

        <script>
            const deleteBtn = document.getElementById('delete-btn');
            const deleteForm = document.getElementById('delete-profile-form');
            deleteBtn.addEventListener('click', function(event) {
                const userConfirmed = confirm('¿Estás seguro de que quieres eliminar tu perfil? Esta acción es irreversible.');
                if (!userConfirmed) {
                    event.preventDefault();
                }
            });
        </script>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/change_pass.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/config_notis.js"></script>
    <script src="View/JS/notifications.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>

</html>