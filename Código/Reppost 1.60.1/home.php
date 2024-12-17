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
  <link href="https://unpkg.com/@emoji-mart/react/dist/emoji-mart.css" rel="stylesheet" />
  <link href="View/css/style-regform.css" type="text/css" rel="stylesheet" />
  <link href="View/css/dark_mode.css" rel="stylesheet">
</head>
<?php include('Controller/Backend/session.php'); ?>

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
  <div id="masthead">
    <div class="container">
      <div class="row mt-4">
        <div class="col-md-4 text-center" style="margin-top: 15px;">
          <a href="profile.php">
            <img class="pp img-fluid" src="<?php echo $image; ?>">
          </a>
          <?php
          $query = $conn->query("SELECT * FROM members WHERE member_id = '$session_id'");
          $row = $query->fetch();
          $id = $row['member_id'];
          ?>
          <p style="text-align: center;"><?php echo $row['firstname'] . " " . $row['lastname']; ?></p>
        </div>
        <div class="col-md-2">
          <form method="post" action="post.php" enctype="multipart/form-data" onsubmit="return validatePostForm(this);">
            <div class="input-group">
              <!-- Área de texto -->
              <textarea id="content-area" name="content" placeholder="Di algo..."></textarea>
              <div class="button-row">
                <!-- Botón para subir imagenes -->
                <label class="upload-btn">
                  <i class="fa fa-paperclip"></i> Adjuntar
                  <input id="file-input" type="file" name="images[]" accept="image/*" multiple hidden onchange="previewFiles(event)">
                </label>
                <!-- Botón para enviar publicaciones -->
                <button class="post-btn" type="submit">
                  <i class="fa fa-feather-pointed"></i> Postear
                </button>
                <!-- Botón para seleccionar emojis -->
                <button type="button" class="emoji-btn" onclick="toggleEmojiPicker()">
                  <i class="fa fa-smile"></i> Emojis
                </button>
              </div>
              <!-- Contenedor de imágenes seleccionadas -->
              <div id="preview-container" class="preview-container"></div>
              <!-- Contenedor de emoticonos -->
              <div id="emoji-picker" class="emoji-picker">
                <!-- Caritas -->
                <div class="emoji-category">
                  <h4>Caritas</h4>
                  <span onclick="insertEmoji('😊')">😊</span>
                  <span onclick="insertEmoji('😂')">😂</span>
                  <span onclick="insertEmoji('🥺')">🥺</span>
                  <span onclick="insertEmoji('😨')">😨</span>
                  <span onclick="insertEmoji('😢')">😢</span>
                  <span onclick="insertEmoji('😎')">😎</span>
                  <span onclick="insertEmoji('🥳')">🥳</span>
                  <span onclick="insertEmoji('😡')">😡</span>
                  <span onclick="insertEmoji('😴')">😴</span>
                  <span onclick="insertEmoji('🤔')">🤔</span>
                  <span onclick="insertEmoji('😍')">😍</span>
                </div>
                <!-- Animales -->
                <div class="emoji-category">
                  <h4>Animales</h4>
                  <span onclick="insertEmoji('🐶')">🐶</span>
                  <span onclick="insertEmoji('🐱')">🐱</span>
                  <span onclick="insertEmoji('🐭')">🐭</span>
                  <span onclick="insertEmoji('🦁')">🦁</span>
                  <span onclick="insertEmoji('🦝')">🦝</span>
                  <span onclick="insertEmoji('🐸')">🐸</span>
                  <span onclick="insertEmoji('🐼')">🐼</span>
                  <span onclick="insertEmoji('🐧')">🐧</span>
                  <span onclick="insertEmoji('🐦')">🐦</span>
                  <span onclick="insertEmoji('🐠')">🐠</span>
                  <span onclick="insertEmoji('🦋')">🦋</span>
                </div>
                <!-- Comida -->
                <div class="emoji-category">
                  <h4>Comida</h4>
                  <span onclick="insertEmoji('🍎')">🍎</span>
                  <span onclick="insertEmoji('🍌')">🍌</span>
                  <span onclick="insertEmoji('🍇')">🍇</span>
                  <span onclick="insertEmoji('🍕')">🍕</span>
                  <span onclick="insertEmoji('🍔')">🍔</span>
                  <span onclick="insertEmoji('🍩')">🍩</span>
                  <span onclick="insertEmoji('🎂')">🎂</span>
                  <span onclick="insertEmoji('🍿')">🍿</span>
                  <span onclick="insertEmoji('🍹')">🍹</span>
                  <span onclick="insertEmoji('🍫')">🍫</span>
                </div>
                <!-- Objetos -->
                <div class="emoji-category">
                  <h4>Objetos</h4>
                  <span onclick="insertEmoji('📱')">📱</span>
                  <span onclick="insertEmoji('💻')">💻</span>
                  <span onclick="insertEmoji('🎧')">🎧</span>
                  <span onclick="insertEmoji('📷')">📷</span>
                  <span onclick="insertEmoji('💡')">💡</span>
                  <span onclick="insertEmoji('🎁')">🎁</span>
                  <span onclick="insertEmoji('🖊️')">🖊️</span>
                  <span onclick="insertEmoji('🎒')">🎒</span>
                  <span onclick="insertEmoji('📚')">📚</span>
                  <span onclick="insertEmoji('🔑')">🔑</span>
                </div>
                <!-- Símbolos -->
                <div class="emoji-category">
                  <h4>Símbolos</h4>
                  <span onclick="insertEmoji('✔️')">✔️</span>
                  <span onclick="insertEmoji('❌')">❌</span>
                  <span onclick="insertEmoji('❤️')">❤️</span>
                  <span onclick="insertEmoji('⭐')">⭐</span>
                  <span onclick="insertEmoji('🔥')">🔥</span>
                  <span onclick="insertEmoji('🌟')">🌟</span>
                  <span onclick="insertEmoji('⚡')">⚡</span>
                  <span onclick="insertEmoji('💯')">💯</span>
                  <span onclick="insertEmoji('🌀')">🌀</span>
                  <span onclick="insertEmoji('💔')">💔</span>
                  <span onclick="insertEmoji('👍')">👍</span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel">
          <div class="panel-body">
            <div class="row">
              <br>
              <?php
              $query = $conn->query("SELECT * FROM post 
                       LEFT JOIN members ON members.member_id = post.member_id 
                       LEFT JOIN post_images ON post.post_id = post_images.post_id 
                       ORDER BY post.post_id DESC");
              while ($row = $query->fetch()) {
                $posted_by = $row['firstname'] . " " . $row['lastname'];
                $profile_image = $row['image']; // Imagen de perfil
                $post_image = $row['image_path']; // Imagen asociada al post
                $id = $row['post_id'];
                $member_id = $row['member_id'];
              ?>
                <div class="col-md-1 col-sm-3">
                  <?php
                  $profile_link = ($member_id == $session_id) ? "profile.php" : "profile_friend.php";
                  ?>
                  <!-- Imagen de perfil -->
                  <a href="<?php echo $profile_link; ?>?member_id=<?php echo $member_id; ?>">
                    <img src="<?php echo $profile_image; ?>" style="width:50px;height:50px" class="img-circle">
                  </a><br><br>
                </div>
                <h4>
                  <small class="text-muted col-sm-3"><a><?php echo $posted_by; ?></a></small>
                  <div class="col-xs-9">
                    <h5><span>
                        <?php
                        $rawDate = $row['date_posted'];
                        $formattedDate = date('H:i - d/m/Y', strtotime($rawDate));
                        echo $formattedDate;
                        ?>
                      </span></h5>
                  </div>
                  <br><br>
                </h4><br>
                <div class="col-md-12 col-sm-9">
                  <div class="alert">
                    <!-- Mostrar contenido del post -->
                    <?php echo $row['content']; ?>
                    <!-- Mostrar imagen del post si existe -->
                    <?php if (!empty($post_image)) { ?>
                      <div class="post-image">
                        <center><img src="<?php echo $post_image; ?>" style="width:450px; height:450px; margin-top:10px; border-radius:10px;" alt="Post Image"></center>
                      </div>
                    <?php } ?>
                    <!-- Contenedor de botones -->
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                      <!-- Botón al lado izquierdo -->
                      <div class="reaction-container" data-post-id="123">
                        <button class="btn btn-heart">
                          <i class="fa fa-heart"></i>
                        </button>
                        <span class="reaction-count">0</span>
                      </div>
                      <!-- Enlaces al lado derecho -->
                      <div class="butt-a">
                        <a href="edit_post.php<?php echo '?id=' . $id; ?>" class="btn btn-info">
                          <i class="fa fa-edit"></i>
                        </a>
                        <a href="share_post.php<?php echo '?id=' . $id; ?>" class="btn btn-primary">
                          <i class="fa fa-share"></i>
                        </a>
                        <a href="report_post.php<?php echo '?id=' . $id; ?>" class="btn btn-warning">
                          <i class="fa fa-warning"></i>
                        </a>
                        <a href="delete_post.php<?php echo '?id=' . $id; ?>" class="btn btn-danger">
                          <i class="fa fa-trash"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <hr>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<footer>
  <script src="View/JS/reaction.js"></script>
  <script src="View/JS/dark_mode.js"></script>
  <script src="View/JS/close_navbar.js"></script>
  <script src="View/JS/validate_post.js"></script>
  <script src="View/JS/text_area.js"></script>
  <script src="https://unpkg.com/@emoji-mart/react"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script type='text/javascript'>
    $(document).ready(function() {});
  </script>
</footer>

</html>