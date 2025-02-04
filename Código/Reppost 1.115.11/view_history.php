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
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="View/css/logs.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/dark_mode.css" rel="stylesheet">
</head>
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
                <li><a href="config_preferences.php"><i class="fa fa-arrow-left"></i> Volver</a></li>
                </li>
            </ul>
        </nav>
    </div>
</header>

<body>
    <?php

    $query = $conn->prepare("
    (SELECT 'Publicación' AS tipo, content AS descripcion, date_posted AS fecha
    FROM post 
    WHERE member_id = :session_id)

    UNION ALL

    (SELECT 'Comentario' AS tipo, comment_text AS descripcion, comment_date AS fecha
    FROM post_comments 
    WHERE user_id = :session_id)

    UNION ALL

    (SELECT 'Imagen subida' AS tipo, image_path AS descripcion, 
    (SELECT date_posted FROM post WHERE post_id = post_images.post_id LIMIT 1) AS fecha
    FROM post_images 
    WHERE post_id IN (SELECT post_id FROM post WHERE member_id = :session_id))

    UNION ALL

    (SELECT 'Video subido' AS tipo, video_path AS descripcion, 
    (SELECT date_posted FROM post WHERE post_id = post_videos.post_id LIMIT 1) AS fecha
    FROM post_videos 
    WHERE post_id IN (SELECT post_id FROM post WHERE member_id = :session_id))

    UNION ALL

    (SELECT 'Reacción a publicación' AS tipo, CONCAT('Reaccionó a la publicación de ', m.firstname, ' ', m.lastname) AS descripcion, pr.reaction_date AS fecha
    FROM post_reactions pr
    JOIN post p ON pr.post_id = p.post_id
    JOIN members m ON p.member_id = m.member_id
    WHERE pr.user_id = :session_id)

    UNION ALL

    (SELECT 'Reacción a comentario' AS tipo, CONCAT('Reaccionó al comentario de ', m.firstname, ' ', m.lastname) AS descripcion, cr.reaction_date AS fecha
    FROM comment_reactions cr
    JOIN post_comments pc ON cr.comment_id = pc.comment_id
    JOIN members m ON pc.user_id = m.member_id
    WHERE cr.user_id = :session_id)

    UNION ALL

    (SELECT 'Amigo agregado' AS tipo, CONCAT('Se hizo amigo de ', m.firstname, ' ', m.lastname) AS descripcion, f.friendship_date AS fecha
    FROM friends f
    JOIN members m ON f.my_friend_id = m.member_id
    WHERE f.my_id = :session_id)

    UNION ALL

    (SELECT 'Mensaje enviado' AS tipo, content AS descripcion, date_sent AS fecha
    FROM messages 
    WHERE sender_id = :session_id)

    UNION ALL

   (SELECT 'Bloqueo de usuario' AS tipo, CONCAT('Bloqueó a ', m.firstname, ' ', m.lastname) AS descripcion, b.date_blocked AS fecha
    FROM blocked_users b
    JOIN members m ON b.blocked_id = m.member_id
    WHERE b.user_id = :session_id)

    UNION ALL

    (SELECT 'Reporte de publicación' AS tipo, CONCAT('Reportó la publicación de ', m.firstname, ' ', m.lastname) AS descripcion, r.report_date AS fecha
    FROM post_reports r
    JOIN post p ON r.post_id = p.post_id
    JOIN members m ON p.member_id = m.member_id
    WHERE r.user_id = :session_id)

    UNION ALL

    (SELECT 'Reporte de comentario' AS tipo, CONCAT('Reportó el comentario de ', m.firstname, ' ', m.lastname) AS descripcion, rc.report_date AS fecha
    FROM reports_comment rc
    JOIN post_comments pc ON rc.comment_id = pc.comment_id
    JOIN members m ON pc.user_id = m.member_id
    WHERE rc.user_id = :session_id)

    UNION ALL

    (SELECT 'Reporte de usuario' AS tipo, CONCAT('Reportó a ', m.firstname, ' ', m.lastname) AS descripcion, r.report_date AS fecha
    FROM report_users r
    JOIN members m ON r.reported_id = m.member_id
    WHERE r.reporter_id = :session_id)

    ORDER BY fecha DESC;
    ");
    $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $query->execute();
    $history = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="history-container">
        <h2>Historial de Actividades</h2>
        <table>
            <thead>
                <tr>
                    <th>Actividad</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['tipo']); ?></td>
                        <td>
                            <?php
                            if (str_contains($entry['descripcion'], '.jpg') || str_contains($entry['descripcion'], '.png')) {
                                echo '<img src="' . $entry['descripcion'] . '" alt="Imagen subida" style="width: 150px; height: auto; border-radius: 5px;">';
                            } elseif (str_contains($entry['descripcion'], '.mp4') || str_contains($entry['descripcion'], '.webm')) {
                                echo '<video src="' . $entry['descripcion'] . '" controls style="width: 285px; height: auto; border-radius: 5px;"></video>';
                            } else {
                                echo htmlspecialchars($entry['descripcion']);
                            }
                            ?>
                        </td>
                        <td><?php echo date('H:i - d/m/Y', strtotime($entry['fecha'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="config_preferences.php">
            <button class="back-btn">Volver</button>
        </a>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>

</html>