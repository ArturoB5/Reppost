<?php include('Config/dbcon.php'); ?>

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
<?php include('Controller/Backend/session.php');
// Obtener el member_id del usuario que se quiere ver
$profile_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : $session_id;
// Realizar la consulta para obtener los detalles del perfil
$query = $conn->prepare("SELECT * FROM members WHERE member_id = :profile_id");
$query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
$query->execute();
$profile = $query->fetch();
// Verificar si el perfil existe
if ($profile) {
    $profile_name = $profile['firstname'] . ' ' . $profile['lastname'];
    $profile_image = $profile['image'];
    $profile_email = $profile['email'];
    // Obtener las fotos del usuario desde la tabla de fotos 
    $photo_query = $conn->prepare("SELECT * FROM photos WHERE member_id = :profile_id");
    $photo_query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
    $photo_query->execute();
    $photos = $photo_query->fetchAll(); // Obtener todas las fotos asociadas al perfil
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
                    <span class="sr-only">Barra de navegacion</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><img src="View/Images/app_images/logo.ico" alt="Logo" style="width: 20px; height: auto;"></a>
            </div>
            <nav class="collapse navbar-collapse" role="navigation">
                <ul class="nav navbar-nav">
                    <li><a style="margin-right:0%" href="home.php"><i class="fa fa-house"></i> Inicio</a></li>
                    <li><a style="margin-right:0%" href="profile.php"><i class="fa fa-user"></i> Perfil</a></li>
                    <li><a style="margin-right:0%" href="message.php"><i class="fa fa-comment"></i> Chat</a></li>
                    <li><a style="margin-right:0%" href="paintarea.php"><i class="fa fa-pencil"></i> Pizarra</a></li>
                    <li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
                    <li><button id="darkModeToggle" class="btn btn-light" style="padding: 14px 5px;"><i class="fa fa-moon"></i></button></li>
                </ul>
                <div class="navbar-form navbar-search" role="search">
                    <form method="post" action="search.php" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control search-query" id="span5" placeholder="Buscar" style="margin-left: 225px;">
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
                    // Obtener el member_id del perfil a mostrar desde la URL
                    if (isset($_GET['member_id'])) {
                        $profile_id = intval($_GET['member_id']);
                    } else {
                        die("No se ha especificado el perfil.");
                    }
                    // Consultar información del usuario basado en el member_id
                    $query = $conn->prepare("SELECT * FROM members WHERE member_id = :profile_id");
                    $query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                    $query->execute();
                    $row = $query->fetch();
                    if (!$row) {
                        // Manejo de error si no se encuentra el perfil
                        die("Perfil no encontrado.");
                    }
                    // Mostrar la imagen del perfil
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
                        $query = $conn->prepare("SELECT * FROM members WHERE member_id = :profile_id");
                        $query->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
                        $query->execute();
                        $row = $query->fetch();
                        ?>
                        <p><span class="info-label"><strong>Nombre completo:</strong></span> <?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></p>
                        <p><span class="info-label"><strong>Género:</strong></span> <?php echo htmlspecialchars($row['gender']); ?></p>
                        <p><span class="info-label"><strong>País:</strong></span> <?php echo htmlspecialchars($row['country']); ?></p>
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
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
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