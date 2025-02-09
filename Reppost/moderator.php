<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verifica si es moderador
$query = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id");
$query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch();
if ($user['role'] !== 'moderador' && $user['role'] !== 'admin') {
    header("Location: sorry.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost - Moderación</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/bootstrap.css" rel="stylesheet">
    <link href="View/css/filter.css" type="text/css" rel="stylesheet">
    <link href="View/css/chat_mods.css" type="text/css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/style_regform.css" type="text/css" rel="stylesheet" />
    <link href="View/css/dark_mode.css" rel="stylesheet">
</head>

<body>
    <header class="navbar navbar-bright navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a style="color: white;" class="navbar-brand" href="home.php">
                    <img src="View/Images/app_images/logo.ico" alt="Logo" style="width: 20px; height: auto;">
                </a>
            </div>
            <nav class="collapse navbar-collapse" role="navigation">
                <ul class="nav navbar-nav">
                    <li><a style="margin-right:0%" href="config_preferences.php"><i class="fa fa-arrow-left"></i> Regresar</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2><i class="fa fa-flag" style="color: rgb(97 160 159)"></i> Moderación</h2>
        <div class="alert alert-warning" style="color: #000000; font-size: 14px; background-color: #b2efff;">
            Como moderador, tu responsabilidad es asegurarte de que todos los reportes sean tratados con imparcialidad y justicia.
            Se debe actuar de manera ética, revisando con cuidado cada reporte antes de tomar decisiones. Recuerda que tu objetivo es crear
            un ambiente seguro y respetuoso para todos los usuarios, manejando los reportes de publicaciones, comentarios y usuarios
            con transparencia y equidad.
        </div>
        <!-- Publicaciones reportadas -->
        <h3>Publicaciones reportadas <i class="fa fa-message" style="color: rgb(97 160 159)"></i></h3>
        <!-- Filtro por estado de los reportes -->
        <form method="GET" action="">
            <select name="estado" class="form-select">
                <option value="pendiente" <?= isset($_GET['estado']) && $_GET['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_revision" <?= isset($_GET['estado']) && $_GET['estado'] == 'en_revision' ? 'selected' : ''; ?>>En revisión</option>
                <option value="resuelto" <?= isset($_GET['estado']) && $_GET['estado'] == 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                <option value="todos" <?= !isset($_GET['estado']) || $_GET['estado'] == 'todos' ? 'selected' : ''; ?>>Todos</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <div class="report-container">
            <?php
            // Obtenemos el estado seleccionado por el usuario, si no hay, mostramos todos
            $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

            // Creamos la consulta según el estado
            if ($estado != 'todos') {
                $query = $conn->prepare("SELECT pr.report_id, pr.report_type, pr.report_date, pr.status, pr.status_response, p.content, m.firstname, m.lastname
            FROM post_reports pr JOIN post p ON p.post_id = pr.post_id JOIN members m ON p.member_id = m.member_id WHERE pr.status = :estado");
                $query->bindParam(':estado', $estado);
            } else {
                $query = $conn->prepare("SELECT pr.report_id, pr.report_type, pr.report_date, pr.status, pr.status_response, p.content, m.firstname, m.lastname
            FROM post_reports pr JOIN post p ON p.post_id = pr.post_id JOIN members m ON p.member_id = m.member_id");
            }

            $query->execute();

            while ($report = $query->fetch()) {
                $formatted_date = date('d/m/Y H:i', strtotime($report['report_date']));
                $is_in_revision = $report['status'] == 'en_revision';
                $is_resolved = $report['status'] == 'resuelto';

                echo '<div class="report-card">';
                echo '<b>Tipo de reporte:</b> ' . htmlspecialchars($report['report_type']) . '</br>';
                echo '<b>Fecha de reporte:</b> ' . $formatted_date . '</br>';
                echo '<b>Contenido de la publicación:</b> ' . htmlspecialchars($report['content']) . '</br>';
                echo '<b>Reportado por:</b> ' . htmlspecialchars($report['firstname'] . ' ' . $report['lastname']) . '</br>';
                echo '<b>Estado:</b> ' . htmlspecialchars($report['status']) . '</br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($report['status_response']) . '</br>';
                echo '<br>';
                echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
                echo '<div>';
                echo '    <a href="moderator_view.php?type=post&report_id=' . $report['report_id'] . '" class="btn btn-info"><i class="fa-solid fa-eye"></i> Ver publicación</a>';
                echo '</div>';
                echo '<div>';
                echo '    <form action="update_report.php" method="POST" style="display: inline;">';
                echo '        <input type="hidden" name="report_id" value="' . $report['report_id'] . '">';
                echo '        <button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>';
                echo '            <i class="fa-solid fa-magnifying-glass"></i> En revisión';
                echo '        </button>';
                echo '        <button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>';
                echo '            <i class="fa-solid fa-check"></i> Resuelto';
                echo '        </button>';
                echo '    </form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Comentarios reportados -->
        <h3>Comentarios reportados <i class="fa fa-comments" style="color: rgb(97 160 159)"></i></h3>
        <!-- Filtro por estado de los comentarios reportados -->
        <form method="GET" action="">
            <select name="estado_comentarios" class="form-select">
                <option value="pendiente" <?= isset($_GET['estado_comentarios']) && $_GET['estado_comentarios'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_revision" <?= isset($_GET['estado_comentarios']) && $_GET['estado_comentarios'] == 'en_revision' ? 'selected' : ''; ?>>En revisión</option>
                <option value="resuelto" <?= isset($_GET['estado_comentarios']) && $_GET['estado_comentarios'] == 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                <option value="todos" <?= !isset($_GET['estado_comentarios']) || $_GET['estado_comentarios'] == 'todos' ? 'selected' : ''; ?>>Todos</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <div class="report-container">
            <?php
            // Obtenemos el estado seleccionado por el usuario, si no hay, mostramos todos
            $estado_comentarios = isset($_GET['estado_comentarios']) ? $_GET['estado_comentarios'] : 'todos';

            // Creamos la consulta según el estado
            if ($estado_comentarios != 'todos') {
                $query = $conn->prepare("SELECT rc.report_id, rc.report_type, rc.report_date, rc.status, rc.status_response, c.comment_text, m.firstname, m.lastname
            FROM reports_comment rc 
            JOIN post_comments c ON c.comment_id = rc.comment_id 
            JOIN members m ON c.user_id = m.member_id 
            WHERE rc.status = :estado_comentarios");
                $query->bindParam(':estado_comentarios', $estado_comentarios);
            } else {
                $query = $conn->prepare("SELECT rc.report_id, rc.report_type, rc.report_date, rc.status, rc.status_response, c.comment_text, m.firstname, m.lastname
            FROM reports_comment rc 
            JOIN post_comments c ON c.comment_id = rc.comment_id 
            JOIN members m ON c.user_id = m.member_id");
            }

            $query->execute();

            while ($report = $query->fetch()) {
                $formatted_date = date('d/m/Y H:i', strtotime($report['report_date']));
                $is_in_revision = $report['status'] == 'en_revision';
                $is_resolved = $report['status'] == 'resuelto';

                echo '<div class="report-card">';
                echo '<b>Tipo de reporte:</b> ' . htmlspecialchars($report['report_type']) . '</br>';
                echo '<b>Fecha de reporte:</b> ' . $formatted_date . '</br>';
                echo '<b>Contenido del comentario:</b> ' . htmlspecialchars($report['comment_text']) . '</br>';
                echo '<b>Reportado por:</b> ' . htmlspecialchars($report['firstname'] . ' ' . $report['lastname']) . '</br>';
                echo '<b>Estado:</b> ' . htmlspecialchars($report['status']) . '</br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($report['status_response']) . '</br>';
                echo '</br>';
                echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
                echo '<div>';
                echo '<a href="moderator_view.php?type=comment&report_id=' . $report['report_id'] . '" class="btn btn-info">';
                echo '    <i class="fa-solid fa-eye"></i> Ver comentario';
                echo '</a>';
                echo '</div>';
                echo '<div>';
                echo '<form action="update_report.php" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="report_id" value="' . $report['report_id'] . '">';
                echo '<button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-magnifying-glass"></i> En revisión';
                echo '</button>';
                echo '<button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-check"></i> Resuelto';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Usuarios reportados -->
        <h3>Usuarios reportados <i class="fa fa-user" style="color: rgb(97 160 159)"></i></h3>
        <!-- Filtro por estado de los usuarios reportados -->
        <form method="GET" action="">
            <select name="estado_usuarios" class="form-select">
                <option value="pendiente" <?= isset($_GET['estado_usuarios']) && $_GET['estado_usuarios'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_revision" <?= isset($_GET['estado_usuarios']) && $_GET['estado_usuarios'] == 'en_revision' ? 'selected' : ''; ?>>En revisión</option>
                <option value="resuelto" <?= isset($_GET['estado_usuarios']) && $_GET['estado_usuarios'] == 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                <option value="todos" <?= !isset($_GET['estado_usuarios']) || $_GET['estado_usuarios'] == 'todos' ? 'selected' : ''; ?>>Todos</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <div class="report-container">
            <?php
            // Obtenemos el estado seleccionado por el usuario para los reportes de usuarios
            $estado_usuarios = isset($_GET['estado_usuarios']) ? $_GET['estado_usuarios'] : 'todos';

            // Creamos la consulta según el estado seleccionado
            if ($estado_usuarios != 'todos') {
                $query = $conn->prepare("SELECT ru.report_id, ru.report_type, ru.report_date, ru.status, ru.status_response, m.firstname, m.lastname
            FROM report_users ru 
            JOIN members m ON m.member_id = ru.reported_id 
            WHERE ru.status = :estado_usuarios");
                $query->bindParam(':estado_usuarios', $estado_usuarios);
            } else {
                $query = $conn->prepare("SELECT ru.report_id, ru.report_type, ru.report_date, ru.status, ru.status_response, m.firstname, m.lastname
            FROM report_users ru 
            JOIN members m ON m.member_id = ru.reported_id");
            }

            $query->execute();

            while ($report = $query->fetch()) {
                $formatted_date = date('d/m/Y H:i', strtotime($report['report_date']));
                $is_in_revision = $report['status'] == 'en_revision';
                $is_resolved = $report['status'] == 'resuelto';

                echo '<div class="report-card">';
                echo '<b>Tipo de reporte:</b> ' . htmlspecialchars($report['report_type']) . '</br>';
                echo '<b>Fecha de reporte:</b> ' . $formatted_date . '</br>';
                echo '<b>Reportado usuario:</b> ' . htmlspecialchars($report['firstname'] . ' ' . $report['lastname']) . '</br>';
                echo '<b>Estado:</b> ' . htmlspecialchars($report['status']) . '</br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($report['status_response']) . '</br>';
                echo '</br>';
                echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
                echo '<div>';
                echo '<a href="moderator_view.php?type=user&report_id=' . $report['report_id'] . '" class="btn btn-info">';
                echo '    <i class="fa-solid fa-eye"></i> Ver usuario';
                echo '</a>';
                echo '</div>';
                echo '<div>';
                echo '<form action="update_report.php" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="report_id" value="' . $report['report_id'] . '">';
                echo '<button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-magnifying-glass"></i> En revisión';
                echo '</button>';
                echo '<button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-check"></i> Resuelto';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Tickets de Ayuda -->
        <h3>Tickets de Ayuda <i class="fa-solid fa-circle-info" style="color: rgb(97 160 159)"></i></h3>
        <!-- Filtro por estado de los tickets de ayuda -->
        <form method="GET" action="">
            <select name="estado_tickets" class="form-select">
                <option value="pendiente" <?= isset($_GET['estado_tickets']) && $_GET['estado_tickets'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_revision" <?= isset($_GET['estado_tickets']) && $_GET['estado_tickets'] == 'en_revision' ? 'selected' : ''; ?>>En revisión</option>
                <option value="resuelto" <?= isset($_GET['estado_tickets']) && $_GET['estado_tickets'] == 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                <option value="todos" <?= !isset($_GET['estado_tickets']) || $_GET['estado_tickets'] == 'todos' ? 'selected' : ''; ?>>Todos</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <div class="report-container">
            <?php
            // Obtenemos el estado seleccionado por el usuario para los tickets de ayuda
            $estado_tickets = isset($_GET['estado_tickets']) ? $_GET['estado_tickets'] : 'todos';

            // Creamos la consulta según el estado seleccionado
            if ($estado_tickets != 'todos') {
                $query = $conn->prepare("SELECT ticket_id, email, subject, message, status, status_response, created_at 
            FROM help_tickets WHERE status = :estado_tickets");
                $query->bindParam(':estado_tickets', $estado_tickets);
            } else {
                $query = $conn->prepare("SELECT ticket_id, email, subject, message, status, status_response, created_at 
            FROM help_tickets");
            }

            $query->execute();

            while ($ticket = $query->fetch()) {
                $formatted_date = date('d/m/Y H:i', strtotime($ticket['created_at']));
                $is_in_revision = $ticket['status'] == 'en_revision';
                $is_resolved = $ticket['status'] == 'resuelto';

                echo '<div class="report-card">';
                echo '<b>Correo del solicitante:</b> ' . htmlspecialchars($ticket['email']) . '<br>';
                echo '<b>Asunto:</b> ' . htmlspecialchars($ticket['subject']) . '<br>';
                echo '<b>Fecha de solicitud:</b> ' . $formatted_date . '<br>';
                echo '<b>Mensaje:</b> <div class="ticket-message">' . nl2br(htmlspecialchars($ticket['message'])) . '</div><br>';
                echo '<b>Estado:</b> ' . htmlspecialchars($ticket['status']) . '<br>';
                echo '<b>Respuesta:</b> ' . htmlspecialchars($ticket['status_response']) . '<br>';
                echo '<br>';
                echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
                echo '<div>';
                echo '</div>';
                echo '<div>';
                echo '<form action="update_report.php" method="POST" style="display:inline;">';
                echo '<input type="hidden" name="report_id" value="' . $ticket['ticket_id'] . '">';
                echo '<button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-magnifying-glass"></i> En Revisión';
                echo '</button>';
                echo '<button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>';
                echo '    <i class="fa-solid fa-check"></i> Resuelto';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <!-- CHAT EMERGENTE -->
    <div class="chat-widget" id="chatWidget">
        <div class="chat-header" id="chatHeader">
            <span>Chat de moderación</span>
            <button type="button" class="close-btn" id="chatToggleBtn">
                <i class="fa fa-chevron-down"></i>
            </button>
        </div>
        <div class="chat-body" id="chatBody" style="display: none; flex-direction: row;">
            <?php if ($user['role'] === 'admin'): ?>
                <div class="mod-list" style="width: 30%; border-right: 1px solid #ddd; padding: 5px;">
                    <h5 style="margin-top: 0; color:black">Mods:</h5>
                    <ul id="moderatorList" style="list-style: none; padding-left: 0; margin: 0;">
                        <?php
                        // Obtiene los mods
                        $modStmt = $conn->prepare("SELECT member_id, firstname, lastname FROM members WHERE role='moderador'");
                        $modStmt->execute();
                        $mods = $modStmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($mods as $mod):
                            $modId = $mod['member_id'];
                            $modName = htmlspecialchars($mod['firstname'] . ' ' . $mod['lastname']);
                            echo '<li style="margin-bottom: 8px;">';
                            echo '  <button class="chooseModBtn" data-id="' . $modId . '" style="width:100%; text-align:left; background-color: rgb(13, 183, 132); border:1px solid #ccc; padding:5px; border-radius:5px;">';
                            echo      $modName;
                            echo '  </button>';
                            echo '</li>';
                        endforeach;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- Columna Derecha (Mensajes) -->
            <div class="messages" id="chatMessages" style="flex: 1; overflow-y: auto; padding: 5px;"></div>
        </div>

        <div class="chat-footer" id="chatFooter" style="display: none;">
            <input type="text" id="chatInput" placeholder="Escribe un mensaje..." />
            <button type="button" id="sendBtn">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</body>
<footer>
    <script src="View/JS/chat_mods.js"></script>
    <script>
        const sessionId = <?php echo $session_id; ?>;
        const userRole = '<?php echo $user['role']; ?>';
        let preOtherId = 0;
        <?php if ($user['role'] === 'moderador'): ?>
            <?php
            $admQ = $conn->prepare("SELECT member_id FROM members WHERE role='admin' LIMIT 1");
            $admQ->execute();
            $admRes = $admQ->fetch();
            if ($admRes) {
                echo "preOtherId = " . intval($admRes['member_id']) . ";";
            }
            ?>
        <?php endif; ?>
        initChat({
            sessionId: sessionId,
            userRole: userRole,
            preOtherId: preOtherId
        });
    </script>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</footer>

</html>