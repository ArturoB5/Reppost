<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
$stmt = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id");
$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userRole = $user['role'] ?? 'usuario';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/config_preferences.css" type="text/css" rel="stylesheet">
    <link href="View/css/dark_mode.css" rel="stylesheet">
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
                    <li><a href="#preferences"><i class="fa fa-heart"></i> Preferencias</a></li>
                    <li><a href="#security"><i class="fa fa-shield"></i> Seguridad</a></li>
                    <li><a href="#account"><i class="fa fa-id-card"></i> Cuenta</a></li>
                    <li><a href="#reports"><i class="fa fa-flag"></i> Reportes</a></li>
                    <li><a href="#help"><i class="fa fa-circle-info"></i> Ayuda</a></li>
                    <?php if ($user['role'] === 'moderador' || $user['role'] === 'admin') { ?>
                        <li><a href="#moderation"><i class="fa fa-id-badge"></i> Moderación</a></li>
                    <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">
                            <i class="fa fa-bell"></i> Notificaciones
                            <span id="notification-badge" class="badge badge-danger" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 10px; display: none;">0</span>
                        </a>
                        <ul class="dropdown-menu" id="notification-list" style="max-height: 300px; overflow-y: auto; width: 300px;">
                            <li class="dropdown-header"> Notificaciones</li>
                            <li class="divider"></li>
                            <!-- Las notificaciones se cargan aquí -->
                        </ul>
                    </li>
                </ul>
                <li style=" list-style: none;">
                    <div class="navbar-form navbar-search" role="search">
                        <form method="post" action="search.php" class="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control search-query" placeholder="Buscar" style="width: 180px; margin-left: 7px;">
                            </div>
                        </form>
                    </div>
                </li>
            </nav>
        </div>
    </header>
    <div class="container mt-5">
        <!-- Seccion Preferencias -->
        <div id="preferences">
            <h2><i class="fa fa-heart" style="color: rgb(97 160 159)"></i> Preferencias</h2>
            <?php
            $stmt = $conn->prepare("SELECT privacy FROM members WHERE member_id = :session_id");
            $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $privacy = $user['privacy'] ?? 'public';
            ?>
            <div class="config-container">
                <h3>Privacidad de contenido <i class="fa fa-lock" style="color: rgb(97 160 159)"></i></h3>
                <form action="update_privacy.php" method="POST">
                    <div class="privacy-container">
                        <!-- Opción Público -->
                        <label class="privacy-option">
                            <input type="radio" name="privacy" value="public" <?php echo ($privacy == 'public') ? 'checked' : ''; ?> hidden>
                            <div>
                                <i class="fa fa-globe"></i>
                                <span>Público</span> <br>
                                <small>(Todos pueden ver tus publicaciones)</small>
                            </div>
                        </label>
                        <!-- Opción Privado -->
                        <label class="privacy-option">
                            <input type="radio" name="privacy" value="private" <?php echo ($privacy == 'private') ? 'checked' : ''; ?> hidden>
                            <div>
                                <i class="fa fa-user-lock"></i>
                                <span>Privado</span> <br>
                                <small>(Solo amigos verán tus publicaciones)</small>
                            </div>
                        </label>
                    </div>
                    <button type="submit" class="save-btn"><i class="fa fa-wrench"></i> Definir</button>
            </div><br>
            </form>
            <div class="config-container">
                <form method="POST">
                    <div class="form-group">
                        <h3>Tema <i class="fa fa-brush" style="color: rgb(97 160 159)"></i></h3>
                        <div class="alerta">
                            Cambia el modo de la plataforma entre oscuro o claro según tus preferencias. Este cambio afectará la apariencia general de la aplicación.
                        </div>
                        <div class="theme-toggle-container">
                            <button id="lightModeBtn" class="theme-btn">
                                <i class="fa fa-sun"></i> Modo Claro
                            </button>
                            <button id="darkModeBtn" class="theme-btn">
                                <i class="fa fa-moon"></i> Modo Oscuro
                            </button>
                        </div>
                    </div>
                </form>
            </div><br>
            <?php
            // Consulta para obtener los usuarios bloqueados
            $query = $conn->prepare("SELECT b.block_id, m.member_id, m.firstname, m.lastname, m.image FROM blocked_users b JOIN members m ON b.blocked_id = m.member_id WHERE b.user_id = :session_id");
            $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $query->execute();
            $countBlocked = $query->rowCount();
            ?>
            <div class="config-container">
                <h3>Notificaciones <i class="fa fa-bell" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Elimina todas las notificaciones que tengas en tu bandeja de notificaciones
                </div><br>
                <form method="POST" action="delete_notifications_config.php">
                    <button type="submit" class="delete-btn">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
            <hr>
        </div>
        <!-- Seccion Seguridad -->
        <div id="security">
            <h2><i class="fa fa-shield" style="color: rgb(97 160 159)"></i> Seguridad</h2>
            <div class="config-container">
                <h3>Usuarios bloqueados <i class="fa fa-user-lock" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Aquí puedes gestionar a los usuarios que has bloqueado. Los usuarios bloqueados no podrán interactuar contigo en la plataforma. Si decides desbloquear a alguien, podrán volver a enviarte mensajes y solicitudes.
                </div>

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
            </div><br>
            <div class="password-container">
                <h3>Cambio de contraseña <i class="fa fa-key"></i></h3>
                <div class="alerta">
                    Cambia tu contraseña actual si lo necesitas. Asegúrate de que tu contraseña actual sea la que usaste para registrarte y que la nueva cumpla con los requisitos de seguridad.<br>
                    La nueva contraseña debe contener al menos:
                    <li>8 caracteres</li>
                    <li>1 mayúscula</li>
                    <li>1 minúscula</li>
                    <li>1 número</li>
                    <li>1 carácter especial</li>
                </div><br>
                <form action="change_password.php" method="POST">
                    <!-- Contraseña actual -->
                    <div class="password-row">
                        <div class="password-input half-width">
                            <p for="current_password">Contraseña actual</p>
                            <input id="current_password" name="current_password" type="password" placeholder="Contraseña actual" required>
                            <i id="toggle-password-current" class="fa fa-eye" style="top: 54px;"></i>
                        </div>
                    </div>
                    <!-- Nueva contraseña & Confirmación -->
                    <div class="password-row">
                        <div class="password-input half-width">
                            <p for="new_password">Nueva contraseña</p>
                            <input id="new_password" name="new_password" type="password" placeholder="Nueva contraseña" required>
                            <i id="toggle-password-new" class="fa fa-eye" style="top: 55px;"></i>
                            <div id="password-strength">
                                <div id="strength-bar"></div>
                            </div>
                            <span id="strength-text">Dificultad:</span>
                        </div>
                        <div class="password-input half-width">
                            <p for="confirm_password">Confirmar contraseña</p>
                            <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirmar contraseña" required>
                            <i id="toggle-password-confirm" class="fa fa-eye" style="top: 55px;"></i>
                        </div>
                    </div>
                    <button type="submit" class="save-pass-btn"><i class="fa fa-save"></i> Guardar</button>
                </form>
            </div>
            <hr>
        </div>
        <!-- Seccion Gestión de cuenta -->
        <div id="account">
            <h2><i class="fa fa-id-card" style="color: rgb(97 160 159)"></i> Gestión de la cuenta</h2>
            <div class="config-container">
                <h3>Informe del Usuario <i class="fa-solid fa-file" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Presiona el botón para revisar un informe detallado de tu cuenta, incluyendo todos tus datos y adicionalmente la cantidad de
                    publicaciones, comentarios, reacciones y tokens que hayasganado hasta la fecha.</div><br>
                <form action="generate_report.php" method="POST" target="_blank">
                    <button type="submit" class="report-btn">
                        <i class="fa fa-file-pdf"></i> Ver Informe
                    </button>
                </form>
            </div><br>
            <div class="config-container">
                <h3>Registro de actividades <i class="fa-solid fa-user-clock" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    En esta sección puedes ver todo el historial de actividades que has realizado en tu cuenta, incluyendo publicaciones, comentarios y cambios de perfil.
                </div><br>
                <a href="view_history.php">
                    <button class="report-btn">
                        <i class="fa-solid fa-clock"></i> Ver Registro
                    </button>
                </a>
            </div><br>
            <div class="config-container">
                <h3>Eliminar Perfil <i class="fa fa-user-xmark" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    <strong style="color: red;">¡Advertencia!</strong> Esta acción eliminará de forma permanente tu perfil y todos los datos asociados, incluyendo:
                    <li>Tu información personal (nombre, email, etc.).</li>
                    <li>Todas tus publicaciones, comentarios, reacciones, imágenes, mensajes y amigos.</li>
                    Una vez completada, esta acción no se puede deshacer. Por favor, asegúrate de estar completamente seguro antes de proceder.
                </div>
                <form id="delete-profile-form" action="delete_profile.php" method="POST"><br>
                    <button type="submit" name="delete_profile" class="delete-btn" id="delete-btn">
                        <i class="fa fa-eraser"></i> Eliminar
                    </button>
                </form>
            </div><br>
            <hr>
        </div>
        <!-- Sección de reportes -->
        <div id="reports">
            <h2><i class="fa fa-flag" style="color: rgb(97 160 159)"></i> Reportes</h2>
            <div class="config-container">
                <h3>Usuarios reportados <i class="fa fa-user-tag" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Visualiza los reportes que has hecho a otros usuarios.
                </div><br>
                <div class="report-container">
                    <?php
                    $reported_users_query = $conn->prepare("SELECT m.member_id, m.firstname, m.lastname, m.image, r.status, r.status_response 
                    FROM members AS m JOIN report_users AS r ON m.member_id = r.reported_id WHERE r.reporter_id = :session_id");
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
            </div><br>
            <div class="config-container">
                <h3>Publicaciones reportadas <i class="fa fa-message" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Visualiza los reportes que has hecho a publicaciones de otro usuarios.
                </div><br>
                <div class="report-container">
                    <?php
                    $reported_posts_query = $conn->prepare("SELECT p.post_id, p.content, m.firstname, m.lastname, m.image, pr.status, pr.status_response
                    FROM post AS p JOIN post_reports AS pr ON p.post_id = pr.post_id JOIN members AS m ON p.member_id = m.member_id WHERE pr.user_id = :session_id");
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
            </div><br>
            <div class="config-container">
                <h3>Comentarios reportados <i class="fa fa-comments" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Visualiza los reportes que has hecho a comentarios de otro usuarios.
                </div><br>
                <div class="report-container">
                    <?php
                    $reported_comments_query = $conn->prepare("SELECT c.comment_id, c.comment_text, m.firstname, m.lastname, m.image, rc.status, rc.status_response
                    FROM post_comments AS c JOIN reports_comment AS rc ON c.comment_id = rc.comment_id JOIN members AS m ON c.user_id = m.member_id WHERE rc.user_id = :session_id");
                    $reported_comments_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
                    $reported_comments_query->execute();
                    while ($comment = $reported_comments_query->fetch()) {
                        echo '<div class="report-card">';
                        echo '<img src="' . htmlspecialchars($comment['image'] ?? 'default_profile.png') . '" style="width:35px;height:35px;" class="img-circle">';
                        echo '<b>' . ' ' . htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']) . ': ' . '</b>' . htmlspecialchars($comment['comment_text']);
                        echo '<hr>';
                        echo '<b>Estado:</b> ' . htmlspecialchars($comment['status']) . '<bfr>';
                        echo '<b>Respuesta:</b> ' . htmlspecialchars($comment['status_response']) . '<br>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div><br>
            <div class="config-container">
                <h3>Te reportaron <i class="fa fa-flag-checkered" style="color: rgb(97 160 159)"></i></h3>
                <div class="alerta">
                    Visualiza los reportes que te han hecho sobre tu comportamiento
                </div><br>
                <div class="report-container">
                    <?php
                    $report_to_me_query = $conn->prepare("SELECT pr.report_type, pr.report_date, pr.status,pr.status_response,'publicación' AS report_source FROM post_reports AS pr
                    JOIN post AS p ON p.post_id = pr.post_id WHERE p.member_id = :session_id UNION SELECT rc.report_type, rc.report_date, rc.status,rc.status_response,'comentario' AS report_source
                    FROM reports_comment AS rc JOIN post_comments AS c ON c.comment_id = rc.comment_id WHERE c.user_id = :session_id UNION SELECT ru.report_type, ru.report_date, ru.status,ru.status_response,
                    'usuario' AS report_source FROM report_users AS ru WHERE ru.reported_id = :session_id");
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
            </div><br>
            <hr>
        </div>
        <!-- Sección de Ayuda -->
        <div id="help">
            <h2><i class="fa fa-circle-info" style="color: rgb(97 160 159)"></i> Ayuda</h2>
            <div class="config-container">
                <h3>¿Necesitas ayuda?</h3>
                <p>Contactate con arturobadillo18@gmail.com para mas información o ayuda adicional</p>
            </div><br><br>
        </div>
        <!-- Seccion Moderación -->
        <div id="moderation">
            <?php
            $query = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id");
            $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $query->execute();
            $user = $query->fetch();
            if ($user['role'] === 'moderador' || $user['role'] === 'admin') {
            ?>
                <?php if ($user['role'] === 'moderador' || $user['role'] === 'admin') { ?>
                    <hr>
                    <h2><i class="fa fa-id-badge" style="color: rgb(97 160 159)"></i> Moderación</h2>
                    <div class="config-container">
                        <h3>Gestión de reportes <i class="fa fa-file" style="color: rgb(97 160 159)"></i></h3>
                        <div class="alerta">
                            Estás entrando a un entorno de <strong style="color: red;">moderación</strong> para gestionar los reportes de usuarios, publicaciones y comentarios.
                        </div><br>
                        <a href="moderator.php" class="moderation-btn">
                            <i class="fa fa-cogs"></i> Ir a Moderación
                        </a>
                    </div>
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
                    <br><br>
                <?php
                }
                ?>
            <?php } ?>
        </div>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/change_pass.js"></script>
    <script src="View/JS/navigation.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/notifications.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>

</html>