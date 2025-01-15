<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

// Verifica si el usuario en sesión es moderador
$query = $conn->prepare("SELECT role FROM members WHERE member_id = :session_id");
$query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch();
if ($user['role'] != 'moderador') {
    header("Location: sorry.php");
    exit;
}

// Checar que recibimos 'report_id' y 'type' por GET
if (!isset($_GET['report_id']) || !isset($_GET['type'])) {
    echo "No se proporcionó un ID de reporte o un tipo de reporte válido.";
    exit;
}

$report_id = intval($_GET['report_id']);
$type = $_GET['type'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost - Panel de Moderación</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/bootstrap.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/style-regform.css" type="text/css" rel="stylesheet" />
    <link href="View/css/dark_mode.css" rel="stylesheet">
    <link href="View/css/style_panel.css" rel="stylesheet">
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
                    <li><a style="margin-right:0%" href="moderator.php"><i class="fa fa-arrow-left"></i> Regresar</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
    switch ($type) {
        case 'post':
            // ======================= POST REPORT =======================
            $query = $conn->prepare("
                SELECT 
                    pr.report_id,
                    pr.report_type,
                    pr.report_date,
                    pr.status,
                    pr.status_response,
                    p.post_id,
                    p.content,
                    p.date_posted,
                    p.token_reward,
                    m.firstname,
                    m.lastname,
                    m.image AS profile_image,
                    pi.image_path
                FROM post_reports pr
                JOIN post p ON p.post_id = pr.post_id
                JOIN members m ON p.member_id = m.member_id
                LEFT JOIN post_images pi ON p.post_id = pi.post_id
                WHERE pr.report_id = :report_id
                LIMIT 1
            ");
            $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $query->execute();
            if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $post_id        = (int)$row['post_id'];
                $content        = htmlspecialchars($row['content'] ?? '');
                $date_posted    = $row['date_posted'] ?? '1970-01-01 00:00:00';
                $formatted_date = date('H:i - d/m/Y', strtotime($date_posted));
                $firstname      = htmlspecialchars($row['firstname'] ?? '');
                $lastname       = htmlspecialchars($row['lastname'] ?? '');
                $profile_image  = !empty($row['profile_image']) ? $row['profile_image'] : 'default_profile.png';
                $posted_by      = trim("$firstname $lastname");
                $post_image     = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : '';
    ?>
                <div class="post-container">
                    <div class="post-header">
                        <img src="<?php echo $profile_image; ?>" alt="Foto de perfil" class="profile-img">
                        <div>
                            <strong style="color: black;"><?php echo $posted_by; ?></strong><br>
                            <span class="post-date"><?php echo $formatted_date; ?></span>
                        </div>
                    </div>
                    <div class="post-content">
                        <p style="color: black;"><?php echo nl2br($content); ?></p>
                    </div>
                    <?php if ($post_image) : ?>
                        <div class="post-image">
                            <img src="<?php echo $post_image; ?>" alt="Imagen de la publicación">
                        </div>
                    <?php endif; ?>
                </div>
            <?php
            } else {
                echo "<div class='container'><p>No se encontró la publicación reportada.</p></div>";
            }
            break;
        case 'comment':
            // ======================= COMMENT REPORT =======================
            $query = $conn->prepare("
                SELECT
                    rc.report_id,
                    rc.report_type,
                    rc.report_date,
                    rc.status,
                    rc.status_response,

                    c.comment_id,
                    c.comment_text,
                    c.comment_date,
                    m.firstname,
                    m.lastname,
                    m.image AS user_image
                FROM reports_comment rc
                JOIN post_comments c ON c.comment_id = rc.comment_id
                JOIN members m ON m.member_id = c.user_id
                WHERE rc.report_id = :report_id
                LIMIT 1
            ");
            $query->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $query->execute();
            if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $comment_text   = htmlspecialchars($row['comment_text'] ?? '');
                $raw_date       = $row['comment_date'] ?? '1970-01-01 00:00:00';
                $comment_date   = date('H:i - d/m/Y', strtotime($raw_date));
                $firstname      = htmlspecialchars($row['firstname'] ?? '');
                $lastname       = htmlspecialchars($row['lastname'] ?? '');
                $user_fullname  = trim("$firstname $lastname");
                $user_image     = (!empty($row['user_image'])) ? $row['user_image'] : 'default_profile.png';
            ?>
                <div class="comment-container">
                    <div class="comment-header">
                        <img src="<?php echo $user_image; ?>" alt="Foto Usuario">
                        <div>
                            <div class="comment-author" style="color: black;"><?php echo $user_fullname; ?></div>
                            <div class="comment-date"><?php echo $comment_date; ?></div>
                        </div>
                    </div>
                    <div class="comment-text" style="color: black;">
                        <?php echo nl2br($comment_text); ?>
                    </div>
                </div>
            <?php
            } else {
                echo "<div class='container'><p>No se encontró el comentario reportado.</p></div>";
            }
            break;

        case 'user':
            // ======================= USER REPORT =======================
            $stmt = $conn->prepare("
            SELECT 
                ru.report_id,
                ru.report_type,
                ru.report_date,
                ru.status,
                ru.status_response,
                ru.reporter_id,
                ru.reported_id,
                m.firstname,
                m.lastname,
                m.city,
                m.country,
                m.email,
                m.gender,
                m.username,
                m.image AS profile_image,
                m.birthdate,
                m.mobile,
                m.work,
                m.member_id
            FROM report_users ru
            JOIN members m ON m.member_id = ru.reported_id
            WHERE ru.report_id = :report_id
            LIMIT 1
        ");
            $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reported_id      = (int)$row['reported_id'];
                $firstname        = htmlspecialchars($row['firstname'] ?? '');
                $lastname         = htmlspecialchars($row['lastname'] ?? '');
                $username         = htmlspecialchars($row['username'] ?? '');
                $profile_image    = !empty($row['profile_image']) ? $row['profile_image'] : 'View/Images/app_images/default_profile.png';
                $email            = htmlspecialchars($row['email'] ?? '');
                $birthdate        = $row['birthdate'] ?? '1970-01-01';
                $mobile           = htmlspecialchars($row['mobile'] ?? '');
                $gender           = htmlspecialchars($row['gender'] ?? '');
                $city             = htmlspecialchars($row['city'] ?? '');
                $country          = htmlspecialchars($row['country'] ?? '');
                $work             = htmlspecialchars($row['work'] ?? '');
                $full_name        = trim("$firstname $lastname");
                $birthDateObj  = new DateTime($birthdate);
                $currentDate   = new DateTime();
                $age           = $birthDateObj->diff($currentDate)->y;
                $formattedDate = $birthDateObj->format('d / m / Y');
                $token_query = $conn->prepare("
                SELECT SUM(token_reward) AS total_tokens
                FROM post
                WHERE member_id = :reported_id
            ");
                $token_query->execute(['reported_id' => $reported_id]);
                $token_row = $token_query->fetch();
                $total_tokens = $token_row['total_tokens'] ? $token_row['total_tokens'] : 0;
                $countriesFull = [
                    "AR" => "Argentina",
                    "BO" => "Bolivia",
                    "BR" => "Brasil",
                    "CL" => "Chile",
                    "CO" => "Colombia",
                    "EC" => "Ecuador",
                    "GY" => "Guyana",
                    "PY" => "Paraguay",
                    "PE" => "Perú",
                    "SR" => "Surinam",
                    "UY" => "Uruguay",
                    "VE" => "Venezuela"
                ];
                $countryName = isset($countriesFull[$country]) ? $countriesFull[$country] : "Desconocido";
            ?>
                <div id="masthead">
                    <div class="container">
                        <div class="col-md-12 text-center">
                            <br>
                            <img src="<?php echo $profile_image; ?>"
                                style="border-radius: 50%; height:150px; width:150px; object-fit: cover;">
                            <br><br>
                        </div>
                        <div class="profile-info col-md-12">
                            <center>
                                <h3><strong>Información General</strong></h3><br>
                            </center>
                            <p>
                                <span class="info-label"><strong>Nombre de usuario:</strong></span>
                                <?php echo $username; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Nombre completo:</strong></span>
                                <?php echo $full_name; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Correo electrónico:</strong></span>
                                <?php echo $email; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Fecha de nacimiento:</strong></span>
                                <?php echo $formattedDate . " (" . $age . " años)"; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Número de celular:</strong></span>
                                <?php echo $mobile; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Género:</strong></span>
                                <?php echo $gender; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>País:</strong></span>
                                <?php echo $countryName; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Ciudad:</strong></span>
                                <?php echo $city; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Ocupación:</strong></span>
                                <?php echo $work; ?>
                            </p>
                            <p>
                                <span class="info-label"><strong>Total de tokens:</strong></span>
                                <?php echo number_format($total_tokens, 8); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Sección fotos (galería) -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel" style="border-radius: 30px;">
                                <div class="panel-body">
                                    <h2 class="text-center">Fotos del usuario</h2>
                                    <hr>
                                    <div class="row">
                                        <?php
                                        // Obtener las fotos del usuario
                                        $photos_query = $conn->prepare("
                                        SELECT photos_id, location
                                        FROM photos
                                        WHERE member_id = :reported_id
                                    ");
                                        $photos_query->bindParam(':reported_id', $reported_id, PDO::PARAM_INT);
                                        $photos_query->execute();

                                        $has_photos = false;
                                        while ($photo_row = $photos_query->fetch(PDO::FETCH_ASSOC)) {
                                            $has_photos = true;
                                            $location  = htmlspecialchars($photo_row['location']);
                                        ?>
                                            <div class="col-md-3 col-sm-6 text-center" style="margin-bottom:20px;">
                                                <img class="photo img-responsive"
                                                    src="<?php echo $location; ?>"
                                                    style="width: 250px; height: 250px; object-fit:cover; border-radius:8px;"><br><br>
                                            </div>
                                        <?php
                                        }
                                        if (!$has_photos) {
                                            echo '<div class="col-md-12 text-center"><p>Este usuario no tiene fotos en su galería.</p></div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
            } else {
                echo "<div class='container'><p>No se encontró el usuario reportado.</p></div>";
            }
            break;

        default:
            echo "<div class='container'><p>Tipo de reporte no válido.</p></div>";
            break;
    }
    ?>
    <footer>
        <script src="View/JS/dark_mode.js"></script>
        <script src="View/JS/close_navbar.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    </footer>
</body>

</html>