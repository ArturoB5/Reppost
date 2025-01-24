<?php include('Config/dbcon.php'); ?>
<?php include('Controller/Backend/session.php'); ?>
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

<body>
  <!-- Header -->
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
                <i class="fa fa-xmark"></i>
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
  <!-- Sección para hacer publicacion -->
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
              <textarea id="content-area" name="content" placeholder="Di algo..." maxlength="10000" oninput="updateCharacterCount()" onkeyup="filterOffensiveWords()"></textarea>
              <!-- Indicador de caracteres restantes -->
              <p id="character-counter" style="text-align: right; margin: 5px 0 10px; font-size: 12px; color: gray;">
                10000 caracteres restantes
              </p>
              <!-- Mensaje de advertencia -->
              <p id="offensive-warning" style="display: none; color: red; font-size: 12px;">
                ¡Tu mensaje contiene palabras no permitidas!
              </p>
              <div class="button-row">
                <!-- Botón para subir imagenes -->
                <label class="upload-btn">
                  <i class="fa fa-paperclip"></i> Adjuntar
                  <input id="file-input" type="file" name="images[]" accept="image/*" multiple hidden onchange="previewFiles(event)">
                </label>
                <!-- Botón para seleccionar emojis -->
                <button type="button" class="emoji-btn" onclick="toggleEmojiPicker()">
                  <i class="fa fa-smile"></i> Emojis
                </button>
                <!-- Botón para enviar publicaciones -->
                <button class="post-btn" type="submit">
                  <i class="fa fa-feather-pointed"></i> Postear
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
  <!-- Sección para mostrar las publicaciones -->
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel">
          <div class="panel-body">
            <div class="row">
              <?php
              $query = $conn->prepare("
                  SELECT 
                      p.post_id AS id,
                      p.content,
                      p.date_posted,
                      m.firstname,
                      m.lastname,
                      m.image AS profile_image,
                      pi.image_path,
                      m.member_id
                  FROM post p
                  LEFT JOIN members m ON m.member_id = p.member_id
                  LEFT JOIN post_images pi ON p.post_id = pi.post_id
                  WHERE m.member_id NOT IN (
                      SELECT blocked_id FROM blocked_users WHERE user_id = :session_id
                  )
                  AND m.member_id NOT IN (
                      SELECT user_id FROM blocked_users WHERE blocked_id = :session_id
                  )
                  ORDER BY p.post_id DESC
              ");
              $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
              $query->execute();
              while ($row = $query->fetch()) {
                $id = intval($row['id'] ?? 0);
                $posted_by = htmlspecialchars($row['firstname'] ?? '') . " " . htmlspecialchars($row['lastname'] ?? '');
                $profile_image = htmlspecialchars($row['profile_image'] ?? 'default_profile.png');
                $post_image = htmlspecialchars($row['image_path'] ?? '');
                $content = htmlspecialchars($row['content'] ?? '');
                $rawDate = $row['date_posted'] ?? '1970-01-01 00:00:00';
                $formattedDate = date('H:i - d/m/Y', strtotime($rawDate));
                $member_id = intval($row['member_id'] ?? 0);
              ?>
                <div class="col-md-1" style="width: 6%; margin-left: 0.1%;">
                  <?php
                  $profile_link = ($member_id == $session_id) ? "profile.php" : "profile_friend.php";
                  ?>
                  <a href="<?php echo htmlspecialchars($profile_link); ?>?member_id=<?php echo intval($member_id); ?>">
                    <img src="<?php echo $profile_image; ?>" style="width:50px;height:50px" class="img-circle">
                  </a>
                </div>
                <h4>
                  <small class="col-xs-9"><a><?php echo $posted_by; ?></a></small>
                  <div class="col-xs-9">
                    <h5><span><?php echo $formattedDate; ?></span></h5>
                  </div>
                </h4>
                <div class="col-md-12 col-sm-9" style="margin-top: 10px;">
                  <div class="alert" id="post-<?php echo $id; ?>">
                    <!-- Contenido de la publicación-->
                    <p><?php echo $content; ?></p>
                    <?php if (!empty($post_image)): ?>
                      <div class="post-image">
                        <center>
                          <img src="<?php echo $post_image; ?>" style="width:350px; height:350px; margin-top:10px; border-radius:10px;" alt="Post Image">
                        </center>
                      </div>
                    <?php endif; ?>
                    <!-- Contenedor de botones -->
                    <div class="d-flex justify-content-between align-items-center">
                      <!-- Reacciones publicación -->
                      <div class="reaction-container">
                        <a class="btn btn-heart reaction-btn" data-id="<?php echo $id; ?>" style="padding: 6px 10px;">
                          <i class="fa fa-heart"></i>
                        </a>
                        <span class="reaction-count" id="reaction-count-<?php echo $id; ?>">
                          <?php
                          // Obtener el conteo de reacciones para la publicación actual
                          $reactionQuery = $conn->prepare("SELECT COUNT(*) AS total_reactions FROM post_reactions WHERE post_id = :post_id");
                          $reactionQuery->bindParam(':post_id', $id, PDO::PARAM_INT);
                          $reactionQuery->execute();
                          $reactionData = $reactionQuery->fetch();
                          echo intval($reactionData['total_reactions'] ?? 0);
                          ?>
                        </span>
                      </div>
                      <!-- Botones condicionales -->
                      <div class="butt-a">
                        <?php
                        // Comprobar si el post ya ha sido reportado
                        $check_reported = $conn->prepare("SELECT * FROM post_reports WHERE post_id = :post_id AND user_id = :user_id");
                        $check_reported->bindParam(':post_id', $id, PDO::PARAM_INT);
                        $check_reported->bindParam(':user_id', $session_id, PDO::PARAM_INT);
                        $check_reported->execute();
                        $report_exists = $check_reported->rowCount() > 0;
                        if ($member_id == $session_id): ?>
                          <!-- No mostrar el botón de reportar si es tu publicación -->
                          <a href="edit_post.php?id=<?php echo $id; ?>" class="btn btn-info" style="padding: 6px 10px;">
                            <i class="fa fa-edit"></i>
                          </a>
                          <a href="delete_post.php?id=<?php echo $id; ?>" class="btn btn-danger" style="padding: 6px 10px;">
                            <i class="fa fa-trash"></i>
                          </a>
                        <?php else: ?>
                          <?php if ($report_exists): ?>
                            <!-- Si ya se ha reportado, mostrar mensaje en vez de botón -->
                            <span class="btn btn-warning" style="padding: 6px 10px;" disabled>
                              <i class="fa fa-warning"></i>
                            </span>
                          <?php else: ?>
                            <!-- Mostrar el botón de reportar si no se ha reportado -->
                            <button class="btn btn-warning" style="padding: 6px 10px;" data-toggle="modal" data-target="#reportModal<?php echo $id; ?>">
                              <i class="fa fa-warning"></i>
                            </button>
                          <?php endif; ?>
                        <?php endif; ?>
                        <a href="share_post.php?id=<?php echo $id; ?>" class="btn btn-primary" style="padding: 6px 10px;">
                          <i class="fa fa-share"></i>
                        </a>
                      </div>
                      <!-- Formulario de reporte-->
                      <div class="modal fade" id="reportModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel<?php echo $id; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <center>
                                <h5 class="modal-title" style="color: black;" id="reportModalLabel<?php echo $id; ?>">Reporte de publicaciones</h5>
                              </center>
                            </div>
                            <div class="modal-body">
                              <form action="report_post.php" method="POST">
                                <div class="form-group">
                                  <label for="report_type" style="color: black;">Selecciona el tipo de reporte:</label>
                                  <select id="report_type" name="report_type" class="form-control" required>
                                    <option value="Desnudos">Desnudos</option>
                                    <option value="Ofensivo">Ofensivo</option>
                                    <option value="Amenazas">Amenazas</option>
                                    <option value="Fraude">Fraude</option>
                                    <option value="Spam">Spam</option>
                                    <option value="Violencia">Violencia</option>
                                    <option value="Información falsa">Información falsa</option>
                                    <option value="Suplatanción de identidad">Suplantación de identidad</option>
                                    <option value="Lenguaje inaproiado">Lenguaje inapropiado</option>
                                    <option value="Bullying o acoso">Bullying o Acoso</option>
                                    <option value="Suicidio o autolesión">Suicidio o autolesión</option>
                                    <option value="Terrorismo">Terrorismo</option>
                                    <option value="Ventas o promoción no autorizada">Ventas o promoción no autorizada</option>
                                    <option value="Incumplimientos de normas">Incumplimiento de normas de Reppost</option>
                                  </select>
                                </div>
                                <input type="hidden" name="post_id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $session_id; ?>" />
                                <button type="submit" class="btn btn-warning">Enviar <i class="fa fa-paper-plane"></i></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fa fa-xmark"></i></button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Sección comentarios -->
                    <div class="comments-section">
                      <hr>
                      <h4>Comentarios</h4>
                      <?php
                      // Obtener comentarios
                      $comments_query = $conn->prepare("SELECT * FROM post_comments WHERE post_id = :post_id ORDER BY comment_date DESC");
                      $comments_query->bindParam(':post_id', $id, PDO::PARAM_INT);
                      $comments_query->execute();
                      while ($comment = $comments_query->fetch()) {
                        $comment_user_id = $comment['user_id'];
                        $comment_text = htmlspecialchars($comment['comment_text']);
                        $comment_date = date('H:i - d/m/Y', strtotime($comment['comment_date']));
                        $comment_user_query = $conn->prepare("SELECT firstname, lastname, image FROM members WHERE member_id = :user_id");
                        $comment_user_query->bindParam(':user_id', $comment_user_id, PDO::PARAM_INT);
                        $comment_user_query->execute();
                        $comment_user = $comment_user_query->fetch();
                        $comment_user_name = $comment_user['firstname'] . " " . $comment_user['lastname'];
                        $comment_user_image = $comment_user['image'] ?? 'default_profile.png';
                        // Contar reacciones para el comentario
                        $reaction_query = $conn->prepare("SELECT COUNT(*) AS total_reactions FROM comment_reactions WHERE comment_id = :comment_id");
                        $reaction_query->bindParam(':comment_id', $comment['comment_id'], PDO::PARAM_INT);
                        $reaction_query->execute();
                        $reaction_count = $reaction_query->fetchColumn();
                        // Comprobar si comentario ha sido reportado
                        $report_query = $conn->prepare("SELECT * FROM reports_comment WHERE comment_id = :comment_id");
                        $report_query->bindParam(':comment_id', $comment['comment_id'], PDO::PARAM_INT);
                        $report_query->execute();
                        $report_exists = $report_query->rowCount() > 0;
                      ?>
                        <div class="alert">
                          <div class="comment">
                            <div class="comment-header" style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 10px">
                              <img src="<?php echo $comment_user_image; ?>" style="width:30px; height:30px; margin-right:5px" class="img-circle">
                              <strong> <?php echo $comment_user_name; ?></strong>
                              <span style="margin-left: auto; margin-right: 0;"><?php echo $comment_date; ?></span>
                            </div>
                            <p><?php echo $comment_text; ?></p>
                            <hr>
                            <!-- Reacciones -->
                            <div class="reactions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px">
                              <!-- Reacción al comentario -->
                              <div>
                                <a class="btn btn-heart reaction-comment-btn" style="padding: 6px 10px;" data-comment-id="<?php echo $comment['comment_id']; ?>">
                                  <i class="fa fa-heart"></i>
                                </a>
                                <span id="comment-reaction-count-<?php echo $comment['comment_id']; ?>">
                                  <?php echo $reaction_count; ?>
                                </span>
                              </div>
                              <!-- Verificación si el comentario es tuyo -->
                              <?php if ($comment['user_id'] == $session_id): ?>
                                <!-- No mostrar reportar si el comentario es tuyo -->
                              <?php else: ?>
                                <!-- Si el comentario no ha sido reportado -->
                                <?php if ($report_exists): ?>
                                  <span class="badge badge-danger"><i class="fa fa-warning" style="padding: 6px 10px;"></i></span>
                                <?php else: ?>
                                  <!-- Botón de reporte si el comentario no ha sido reportado -->
                                  <button class="btn btn-warning" style="padding: 6px 10px;" data-toggle="modal" data-target="#reportCommentModal<?php echo $comment['comment_id']; ?>" style="margin-left: auto;">
                                    <i class="fa fa-warning"></i>
                                  </button>
                                <?php endif; ?>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>
                        <!-- Modal de Reporte para comentarios -->
                        <div class="modal fade" id="reportCommentModal<?php echo $comment['comment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="reportCommentModalLabel<?php echo $comment['comment_id']; ?>" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <center>
                                  <h5 class="modal-title" style="color: black;">Reporte de comentario</h5>
                                </center>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="report_comment.php" method="POST">
                                  <div class="form-group">
                                    <label for="report_type" style="color: black;">Selecciona el tipo de reporte:</label>
                                    <select id="report_type" name="report_type" class="form-control" required>
                                      <option value="Desnudos">Desnudos</option>
                                      <option value="Ofensivo">Ofensivo</option>
                                      <option value="Amenazas">Amenazas</option>
                                      <option value="Fraude">Fraude</option>
                                      <option value="Spam">Spam</option>
                                      <option value="Violencia">Violencia</option>
                                      <option value="Información falsa">Información falsa</option>
                                      <option value="Suplatanción de identidad">Suplantación de identidad</option>
                                      <option value="Lenguaje inaproiado">Lenguaje inapropiado</option>
                                      <option value="Bullying o acoso">Bullying o Acoso</option>
                                      <option value="Suicidio o autolesión">Suicidio o autolesión</option>
                                      <option value="Terrorismo">Terrorismo</option>
                                      <option value="Ventas o promoción no autorizada">Ventas o promoción no autorizada</option>
                                      <option value="Incumplimientos de normas">Incumplimiento de normas de Reppost</option>
                                    </select>
                                  </div>
                                  <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>" />
                                  <input type="hidden" name="user_id" value="<?php echo $session_id; ?>" />
                                  <button type="submit" class="btn btn-warning">Enviar <i class="fa fa-paper-plane"></i></button>
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar <i class="fa fa-xmark"></i></button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                    <hr>
                    <!-- Área de texto de comentario -->
                    <form action="add_comment.php" method="POST" style="display: flex; align-items: center; width: 100%; margin: 0 auto;">
                      <input type="hidden" name="post_id" value="<?php echo $id; ?>" />
                      <textarea name="comment_text" class="form-control" placeholder="Escribe un comentario..." style="flex-grow: 1; margin-right: 10px; width: 100%; max-width: calc(100% - 45px);"></textarea>
                      <button type="submit" class="btn btn-primary" style="margin-top:10px; padding: 6px 10px;">
                        <i class="fa fa-paper-plane"></i>
                      </button>
                    </form>
                  </div><br>
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
  <script src="View/JS/text_area_control.js"></script>
  <script src="View/JS/found_post.js"></script>
  <script src="View/JS/dark_mode.js"></script>
  <script src="View/JS/close_navbar.js"></script>
  <script src="View/JS/validate_post.js"></script>
  <script src="View/JS/text_area.js"></script>
  <script src="View/JS/notifications.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script type='text/javascript'>
    $(document).ready(function() {});
  </script>
</footer>

</html>