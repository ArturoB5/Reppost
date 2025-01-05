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
                    <li><a style="margin-right:0%" href="config_preferences.php"><i class="fa fa-gear"></i> Configuraciones</a></li>
                    <li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
                    <!-- Botón de Notificaciones -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">
                            <i class="fa fa-bell"></i>
                            <span id="notification-badge" class="badge badge-danger" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 10px; display: none;">0</span>
                            <!-- Botón para eliminar todas las notificaciones -->
                            <button id="delete-notifications-button" class="btn btn-danger" style="background-color: red; position: absolute; top: 0px; right: 18px; padding: 1.5px 5px;font-size: 8px; border-radius: 100px;">
                                <i class="fa fa-xmark" style="margin-right: 1px"></i>
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
    </div><br>
    <?php
    // Configurar clave API en el backend
    $apiKey = 'OlZhbyy0xDfzMGvSNeGS3SCNXd8QRSihXmXXWtVOtWqwpfbhjpwflJXl';
    ?>
    <div class="controls">
        <label>Busca imágenes con</label>
        <a href="https://www.pexels.com">
            <img src="https://images.pexels.com/lib/api/pexels.png" style="width: 100px; height:30px" />
        </a>
        <form id="search-form">
            <input type="text" id="query-input" placeholder="Escribe tu búsqueda" required>
            <button type="submit">Buscar</button>
        </form>
        <div id="photo-container"></div>
    </div><br>
</body>
<footer>
    <script src="View/JS/dark_mode.js"></script>
    <script src="View/JS/close_navbar.js"></script>
    <script src="View/JS/paint.js"></script>
    <script src="View/JS/notifications.js"></script>
    <script>
        // Configurar clave API desde PHP
        const API_KEY = '<?php echo $apiKey; ?>';
        // Manejar el evento de búsqueda
        document.getElementById('search-form').addEventListener('submit', function(event) {
            event.preventDefault();
            // Obtener la consulta del usuario
            const query = document.getElementById('query-input').value;
            const photoContainer = document.getElementById('photo-container');
            photoContainer.innerHTML = ''; // Limpiar resultados previos
            // Realizar la solicitud directamente con fetch
            fetch(`https://api.pexels.com/v1/search?query=${query}&per_page=9`, {
                    headers: {
                        Authorization: API_KEY,
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la búsqueda: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.photos && data.photos.length > 0) {
                        // Mostrar las imágenes obtenidas
                        data.photos.forEach(photo => {
                            const imgContainer = document.createElement('div');
                            imgContainer.style.margin = '10px';
                            imgContainer.style.display = 'inline-block';
                            const img = document.createElement('img');
                            img.src = photo.src.medium;
                            img.alt = photo.photographer;
                            img.style.maxWidth = '200px';
                            imgContainer.appendChild(img);
                            // Boton para ver en pantalla grande
                            const downloadBtn = document.createElement('a');
                            downloadBtn.href = photo.src.original;
                            downloadBtn.download = photo.photographer + '-image.jpg';
                            downloadBtn.textContent = 'Visualizar';
                            downloadBtn.classList.add('btn', 'btn-primary');
                            downloadBtn.style.display = 'block';
                            downloadBtn.style.textAlign = 'center';
                            imgContainer.appendChild(downloadBtn);
                            // Agregar la imagen y el botón al contenedor
                            photoContainer.appendChild(imgContainer);
                        });
                    } else {
                        photoContainer.innerText = 'No se encontraron resultados.';
                    }
                })
                .catch(error => {
                    console.error('Error al buscar imágenes:', error);
                    photoContainer.innerText = 'Hubo un error al buscar imágenes.';
                });
        });
    </script>
    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {});
    </script>
</footer>