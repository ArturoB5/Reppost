<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Reppost - Acceso Denegado</title>
    <link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
    <link href="View/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="View/css/my_style.css" type="text/css" rel="stylesheet">
    <link href="View/css/sorry.css" type="text/css" rel="stylesheet">
</head>

<body>
    <center><img class="logo" src="View/Images/app_images/logo.png" alt="Logo"></center>
    <div class="container">
        <h2><i class="fa fa-exclamation-triangle" style="color: rgb(97 160 159)"></i> Lo sentimos, no tienes acceso a esta página :c</h2>
        <br>
        <p style="font-size: 18px; color: #555;">
            No tienes permisos suficientes para acceder a esta sección. Si necesitas acceso,
            o crees que es un error, contacta con un moderador.
        </p>
        <br>
        <a href="home.php" class="btn btn-primary">Regresar al inicio</a>
        <hr>
        <!-- Contenedor del juego -->
        <div class="game-container" style="width: 480px; margin: 0 auto; text-align: center;">
            <!-- Indicaciones para jugar -->
            <h4>
                Presiona las flechas
                <i style="size: 12px;" class="fa-solid fa-arrow-left"></i>
                <i class="fa-solid fa-arrow-right"></i> para jugar
            </h4>
            <!-- HUD del juego -->
            <div id="hud" style="margin-bottom: 10px;">
                <span id="scoreText" style="margin-right: 20px; color:#000000; font-weight: bold;">
                    Puntos: 0
                </span>
                <span id="livesText" style="color:#000000; font-weight: bold;">
                    Vidas: 3
                </span>
            </div>
            <!-- Canvas del juego -->
            <canvas id="gameCanvas" width="480" height="320" style="border: 3px solid #000000;"></canvas>
        </div>
    </div>
    <script src="View/JS/game.js"></script>
</body>

</html>