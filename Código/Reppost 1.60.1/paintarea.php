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
    <link href="View/css/paint.css" type="text/css" rel="stylesheet">
</head>
<?php include('Controller/Backend/session.php'); ?>

<body><br>
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
                    <li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Cerrar Sesión</a></li>
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
    <canvas id="drawingCanvas" width="800" height="600"></canvas>
    <div class="controls">
        <label for="colorPicker">Color: </label>
        <input type="color" id="colorPicker" value="#000000">
        <label for="brushSize">Tamaño: </label>
        <input type="range" id="brushSize" min="1" max="100" value="5">
        <button id="setBackgroundColor"><i class="fas fa-fill"></i>Color de Fondo</button>
        <button id="eraser"><i class="fas fa-eraser"></i> Borrador</button>
        <button id="saveCanvas"><i class="fas fa-save"></i> Guardar</button>
        <button id="uploadCanvas"><i class="fas fa-upload"></i>Subir a galería</button>
        <button id="clearCanvas"><i class="fas fa-trash-alt"></i> Borrar Todo</button>
    </div>
    <div class="controls">
        <input type="text" id="promptInput" placeholder="Describe la imagen">
        <button id="generateImage">Generar</button>
    </div>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/paint.js"></script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>