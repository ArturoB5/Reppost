<?php include('Config/dbcon.php'); ?>
<?php include('Controller/Backend/session.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost - Moderación</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/style-regform.css" type="text/css" rel="stylesheet" />
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
                <a style="color: white;" class="navbar-brand" href="home.php">
                    <img src="View/Images/app_images/logo.ico" alt="Logo" style="width: 20px; height: auto;"> INICIO
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
        <div class="report-container">
            <?php
            $query = $conn->prepare("
            SELECT pr.report_id, pr.report_type, pr.report_date, pr.status, pr.status_response, p.content, m.firstname, m.lastname
            FROM post_reports pr
            JOIN post p ON p.post_id = pr.post_id
            JOIN members m ON p.member_id = m.member_id
            WHERE pr.status = 'pendiente' OR pr.status = 'en_revision'
        ");
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
                echo '<form action="update_report.php" method="POST">
                    <input type="hidden" name="report_id" value="' . $report['report_id'] . '">
                    <button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>
                        <i class="fa-solid fa-magnifying-glass"></i> En revisión
                    </button>
                    <button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>
                        <i class="fa-solid fa-check"></i> Resuelto
                    </button>
                </form>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Comentarios reportados -->
        <h3>Comentarios reportados <i class="fa fa-comments" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $query = $conn->prepare("
            SELECT rc.report_id, rc.report_type, rc.report_date, rc.status, rc.status_response, c.comment_text, m.firstname, m.lastname
            FROM reports_comment rc
            JOIN post_comments c ON c.comment_id = rc.comment_id
            JOIN members m ON c.user_id = m.member_id
            WHERE rc.status = 'pendiente' OR rc.status = 'en_revision'
        ");
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
                echo '<form action="update_report.php" method="POST">
                    <input type="hidden" name="report_id" value="' . $report['report_id'] . '">
                    <button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>
                        <i class="fa-solid fa-magnifying-glass"></i> En revisión
                    </button>
                    <button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>
                        <i class="fa-solid fa-check"></i> Resuelto
                    </button>
                </form>';
                echo '</div>';
            }
            ?>
        </div>
        <!-- Usuarios reportados -->
        <h3>Usuarios reportados <i class="fa fa-user" style="color: rgb(97 160 159)"></i></h3>
        <div class="report-container">
            <?php
            $query = $conn->prepare("
            SELECT ru.report_id, ru.report_type, ru.report_date, ru.status, ru.status_response, m.firstname, m.lastname
            FROM report_users ru
            JOIN members m ON m.member_id = ru.reported_id
            WHERE ru.status = 'pendiente' OR ru.status = 'en_revision'
        ");
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
                echo '<form action="update_report.php" method="POST">
                    <input type="hidden" name="report_id" value="' . $report['report_id'] . '">
                    <button type="submit" name="status" value="en_revision" class="btn btn-warning" ' . ($is_in_revision ? 'disabled' : '') . '>
                        <i class="fa-solid fa-magnifying-glass"></i> En revisión
                    </button>
                    <button type="submit" name="status" value="resuelto" class="btn btn-success" ' . ($is_resolved ? 'disabled' : '') . '>
                        <i class="fa-solid fa-check"></i> Resuelto
                    </button>
                </form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <footer>
        <script src="View/JS/dark_mode.js"></script>
        <script src="View/JS/close_navbar.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    </footer>
</body>

</html>