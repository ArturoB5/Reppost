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
  <link href="View/css/style_regform.css" type="text/css" rel="stylesheet" />
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
          <li><a href="home.php"><i class="fa fa-house"></i> Inicio</a></li>
          <li><a href="profile.php"><i class="fa fa-user"></i> Perfil</a></li>
          <li><a href="message.php"><i class="fa fa-comment"></i> Chat</a></li>
          <li><a href="paintarea.php"><i class="fa fa-pencil"></i> Pizarra</a></li>
          <li><a href="config_preferences.php"><i class="fa fa-gear"></i> Configuración</a></li>
          <!-- Botón de Notificaciones -->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">
              <i class="fa fa-bell"></i> Notificaciones
              <span id="notification-badge" class="badge badge-danger" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 10px; display: none;">0</span>
            </a>
            <ul class="dropdown-menu" id="notification-list" style="max-height: 300px; overflow-y: auto; width: 100%;">
              <li class="dropdown-header">Notificaciones</li>
              <li class="divider"></li>
              <!-- Las notificaciones se cargan aquí -->
            </ul>
          </li>
          <li><a href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
          <li style=" list-style: none;">
            <div class="navbar-form navbar-search" role="search">
              <form method="post" action="search.php" class="search-form">
                <div class="input-group">
                  <input type="text" name="search" class="form-control search-query" placeholder="Buscar" style="width:290px;">
                </div>
              </form>
            </div>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  <!-- Sección para hacer publicacion -->
  <div id="masthead">
    <div class="container">
      <div class="post-container">
        <center><a href="profile.php"><img class="pp" src="<?php echo $image; ?>"></a></center>
        <form method="post" action="post.php" enctype="multipart/form-data" onsubmit="return validatePostForm(this);">
          <!-- Área de texto -->
          <textarea id="content-area" name="content" placeholder="Di algo..." maxlength="10000" oninput="updateCharacterCount()" onkeyup="filterOffensiveWords()"></textarea>
          <!-- Indicador de caracteres restantes -->
          <p id="character-counter" style="text-align: right; margin: 5px 0 10px; font-size: 12px; color: gray;">
            10000 caracteres restantes
          </p>
          <!-- Mensaje de advertencia -->
          <p id="offensive-warning" style="display: none; color: red; margin: -25px 0 10px; font-size: 12px;">
            ¡Tu mensaje contiene palabras no permitidas!
          </p>
          <div class="button-row">
            <!-- Botón para subir imágenes -->
            <label class="upload-btn">
              <i class="fa-regular fa-image"></i> Imágen
              <input id="image-input" type="file" name="images[]" accept="image/*" multiple hidden onchange="previewFiles(event)">
            </label>
            <!-- Botón para subir videos -->
            <label class="upload-btn">
              <i class="fa-solid fa-video"></i> Video
              <input id="video-input" type="file" name="videos[]" accept="video/*" multiple hidden onchange="previewFiles(event)">
            </label>
            <!-- Botón para seleccionar emojis -->
            <button type="button" class="emoji-btn" onclick="toggleEmojiPicker()">
              <i class="fa-regular fa-face-smile"></i> Emojis
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
              <h4>Emojis</h4>
              <span onclick="insertEmoji('😀')">😀</span>
              <span onclick="insertEmoji('😃')">😃</span>
              <span onclick="insertEmoji('😄')">😄</span>
              <span onclick="insertEmoji('😁')">😁</span>
              <span onclick="insertEmoji('😆')">😆</span>
              <span onclick="insertEmoji('😅')">😅</span>
              <span onclick="insertEmoji('🤣')">🤣</span>
              <span onclick="insertEmoji('😂')">😂</span>
              <span onclick="insertEmoji('🙂')">🙂</span>
              <span onclick="insertEmoji('🙃')">🙃</span>
              <span onclick="insertEmoji('🫠')">🫠</span>
              <span onclick="insertEmoji('😉')">😉</span>
              <span onclick="insertEmoji('😊')">😊</span>
              <span onclick="insertEmoji('😇')">😇</span>
              <span onclick="insertEmoji('🥰')">🥰</span>
              <span onclick="insertEmoji('😍')">😍</span>
              <span onclick="insertEmoji('🤩')">🤩</span>
              <span onclick="insertEmoji('😘')">😘</span>
              <span onclick="insertEmoji('😗')">😗</span>
              <span onclick="insertEmoji('😚')">😚</span>
              <span onclick="insertEmoji('😙')">😙</span>
              <span onclick="insertEmoji('🥲')">🥲</span>
              <span onclick="insertEmoji('😋')">😋</span>
              <span onclick="insertEmoji('😛')">😛</span>
              <span onclick="insertEmoji('😜')">😜</span>
              <span onclick="insertEmoji('🤪')">🤪</span>
              <span onclick="insertEmoji('😝')">😝</span>
              <span onclick="insertEmoji('🤑')">🤑</span>
              <span onclick="insertEmoji('🤗')">🤗</span>
              <span onclick="insertEmoji('🤭')">🤭</span>
              <span onclick="insertEmoji('🫢')">🫢</span>
              <span onclick="insertEmoji('🫣')">🫣</span>
              <span onclick="insertEmoji('🤫')">🤫</span>
              <span onclick="insertEmoji('🤔')">🤔</span>
              <span onclick="insertEmoji('🫡')">🫡</span>
              <span onclick="insertEmoji('🤐')">🤐</span>
              <span onclick="insertEmoji('🤨')">🤨</span>
              <span onclick="insertEmoji('😐')">😐</span>
              <span onclick="insertEmoji('😑')">😑</span>
              <span onclick="insertEmoji('😶')">😶</span>
              <span onclick="insertEmoji('🫥')">🫥</span>
              <span onclick="insertEmoji('😶‍🌫️')">😶‍🌫️</span>
              <span onclick="insertEmoji('😏')">😏</span>
              <span onclick="insertEmoji('😒')">😒</span>
              <span onclick="insertEmoji('🙄')">🙄</span>
              <span onclick="insertEmoji('😬')">😬</span>
              <span onclick="insertEmoji('😮‍💨')">😮‍💨</span>
              <span onclick="insertEmoji('🤥')">🤥</span>
              <span onclick="insertEmoji('🫨')">🫨</span>
              <span onclick="insertEmoji('🙂‍↔️')">🙂‍↔️</span>
              <span onclick="insertEmoji('🙂‍↕️')">🙂‍↕️</span>
              <span onclick="insertEmoji('😌')">😌</span>
              <span onclick="insertEmoji('😔')">😔</span>
              <span onclick="insertEmoji('😪')">😪</span>
              <span onclick="insertEmoji('🤤')">🤤</span>
              <span onclick="insertEmoji('😴')">😴</span>
              <span onclick="insertEmoji('😷')">😷</span>
              <span onclick="insertEmoji('🤒')">🤒</span>
              <span onclick="insertEmoji('🤕')">🤕</span>
              <span onclick="insertEmoji('🤢')">🤢</span>
              <span onclick="insertEmoji('🤮')">🤮</span>
              <span onclick="insertEmoji('🤧')">🤧</span>
              <span onclick="insertEmoji('🥵')">🥵</span>
              <span onclick="insertEmoji('🥶')">🥶</span>
              <span onclick="insertEmoji('🥴')">🥴</span>
              <span onclick="insertEmoji('😵')">😵</span>
              <span onclick="insertEmoji('😵‍💫')">😵‍💫</span>
              <span onclick="insertEmoji('🤯')">🤯</span>
              <span onclick="insertEmoji('🤠')">🤠</span>
              <span onclick="insertEmoji('🥳')">🥳</span>
              <span onclick="insertEmoji('🥸')">🥸</span>
              <span onclick="insertEmoji('😎')">😎</span>
              <span onclick="insertEmoji('🤓')">🤓</span>
              <span onclick="insertEmoji('🧐')">🧐</span>
              <span onclick="insertEmoji('😕')">😕</span>
              <span onclick="insertEmoji('🫤')">🫤</span>
              <span onclick="insertEmoji('😟')">😟</span>
              <span onclick="insertEmoji('🙁')">🙁</span>
              <span onclick="insertEmoji('☹️')">☹️</span>
              <span onclick="insertEmoji('😮')">😮</span>
              <span onclick="insertEmoji('😯')">😯</span>
              <span onclick="insertEmoji('😲')">😲</span>
              <span onclick="insertEmoji('😳')">😳</span>
              <span onclick="insertEmoji('🥺')">🥺</span>
              <span onclick="insertEmoji('🥹')">🥹</span>
              <span onclick="insertEmoji('😦')">😦</span>
              <span onclick="insertEmoji('😧')">😧</span>
              <span onclick="insertEmoji('😨')">😨</span>
              <span onclick="insertEmoji('😰')">😰</span>
              <span onclick="insertEmoji('😥')">😥</span>
              <span onclick="insertEmoji('😢')">😢</span>
              <span onclick="insertEmoji('😭')">😭</span>
              <span onclick="insertEmoji('😱')">😱</span>
              <span onclick="insertEmoji('😖')">😖</span>
              <span onclick="insertEmoji('😣')">😣</span>
              <span onclick="insertEmoji('😞')">😞</span>
              <span onclick="insertEmoji('😓')">😓</span>
              <span onclick="insertEmoji('😩')">😩</span>
              <span onclick="insertEmoji('😫')">😫</span>
              <span onclick="insertEmoji('🥱')">🥱</span>
              <span onclick="insertEmoji('😤')">😤</span>
              <span onclick="insertEmoji('😡')">😡</span>
              <span onclick="insertEmoji('😠')">😠</span>
              <span onclick="insertEmoji('🤬')">🤬</span>
              <span onclick="insertEmoji('😈')">😈</span>
              <span onclick="insertEmoji('👿')">👿</span>
              <span onclick="insertEmoji('💀')">💀</span>
              <span onclick="insertEmoji('☠️')">☠️</span>
              <span onclick="insertEmoji('💩')">💩</span>
              <span onclick="insertEmoji('🤡')">🤡</span>
              <span onclick="insertEmoji('👹')">👹</span>
              <span onclick="insertEmoji('👺')">👺</span>
              <span onclick="insertEmoji('👻')">👻</span>
              <span onclick="insertEmoji('👽')">👽</span>
              <span onclick="insertEmoji('👾')">👾</span>
              <span onclick="insertEmoji('🤖')">🤖</span>
              <span onclick="insertEmoji('😺')">😺</span>
              <span onclick="insertEmoji('😸')">😸</span>
              <span onclick="insertEmoji('😹')">😹</span>
              <span onclick="insertEmoji('😻')">😻</span>
              <span onclick="insertEmoji('😼')">😼</span>
              <span onclick="insertEmoji('😽')">😽</span>
              <span onclick="insertEmoji('🙀')">🙀</span>
              <span onclick="insertEmoji('😿')">😿</span>
              <span onclick="insertEmoji('😾')">😾</span>
              <span onclick="insertEmoji('🙈')">🙈</span>
              <span onclick="insertEmoji('🙉')">🙉</span>
              <span onclick="insertEmoji('🙊')">🙊</span>
              <span onclick="insertEmoji('💋')">💋</span>
              <span onclick="insertEmoji('💯')">💯</span>
              <span onclick="insertEmoji('💢')">💢</span>
              <span onclick="insertEmoji('💥')">💥</span>
              <span onclick="insertEmoji('💫')">💫</span>
              <span onclick="insertEmoji('💦')">💦</span>
              <span onclick="insertEmoji('💨')">💨</span>
              <span onclick="insertEmoji('🕳️')">🕳️</span>
              <span onclick="insertEmoji('💤')">💤</span>
              <span onclick="insertEmoji('👋')">👋</span>
              <span onclick="insertEmoji('🤚')">🤚</span>
              <span onclick="insertEmoji('🖐️')">🖐️</span>
              <span onclick="insertEmoji('✋')">✋</span>
              <span onclick="insertEmoji('🖖')">🖖</span>
              <span onclick="insertEmoji('🫱')">🫱</span>
              <span onclick="insertEmoji('🫲')">🫲</span>
              <span onclick="insertEmoji('🫳')">🫳</span>
              <span onclick="insertEmoji('🫴')">🫴</span>
              <span onclick="insertEmoji('🫷')">🫷</span>
              <span onclick="insertEmoji('🫸')">🫸</span>
              <span onclick="insertEmoji('👌')">👌</span>
              <span onclick="insertEmoji('🤌')">🤌</span>
              <span onclick="insertEmoji('🤏')">🤏</span>
              <span onclick="insertEmoji('✌️')">✌️</span>
              <span onclick="insertEmoji('🤞')">🤞</span>
              <span onclick="insertEmoji('🫰')">🫰</span>
              <span onclick="insertEmoji('🤟')">🤟</span>
              <span onclick="insertEmoji('🤘')">🤘</span>
              <span onclick="insertEmoji('🤙')">🤙</span>
              <span onclick="insertEmoji('👈')">👈</span>
              <span onclick="insertEmoji('👉')">👉</span>
              <span onclick="insertEmoji('👆')">👆</span>
              <span onclick="insertEmoji('🖕')">🖕</span>
              <span onclick="insertEmoji('👇')">👇</span>
              <span onclick="insertEmoji('☝️')">☝️</span>
              <span onclick="insertEmoji('🫵')">🫵</span>
              <span onclick="insertEmoji('👍')">👍</span>
              <span onclick="insertEmoji('👎')">👎</span>
              <span onclick="insertEmoji('✊')">✊</span>
              <span onclick="insertEmoji('👊')">👊</span>
              <span onclick="insertEmoji('🤛')">🤛</span>
              <span onclick="insertEmoji('🤜')">🤜</span>
              <span onclick="insertEmoji('👏')">👏</span>
              <span onclick="insertEmoji('🙌')">🙌</span>
              <span onclick="insertEmoji('🫶')">🫶</span>
              <span onclick="insertEmoji('👐')">👐</span>
              <span onclick="insertEmoji('🤲')">🤲</span>
              <span onclick="insertEmoji('🤝')">🤝</span>
              <span onclick="insertEmoji('🙏')">🙏</span>
              <span onclick="insertEmoji('✍️')">✍️</span>
              <span onclick="insertEmoji('💅')">💅</span>
              <span onclick="insertEmoji('🤳')">🤳</span>
              <span onclick="insertEmoji('💪')">💪</span>
              <span onclick="insertEmoji('🦾')">🦾</span>
              <span onclick="insertEmoji('🦿')">🦿</span>
              <span onclick="insertEmoji('🦵')">🦵</span>
              <span onclick="insertEmoji('🦶')">🦶</span>
              <span onclick="insertEmoji('👂')">👂</span>
              <span onclick="insertEmoji('🦻')">🦻</span>
              <span onclick="insertEmoji('👃')">👃</span>
              <span onclick="insertEmoji('🧠')">🧠</span>
              <span onclick="insertEmoji('🫀')">🫀</span>
              <span onclick="insertEmoji('🫁')">🫁</span>
              <span onclick="insertEmoji('🦷')">🦷</span>
              <span onclick="insertEmoji('🦴')">🦴</span>
              <span onclick="insertEmoji('👀')">👀</span>
              <span onclick="insertEmoji('👁️')">👁️</span>
              <span onclick="insertEmoji('👅')">👅</span>
              <span onclick="insertEmoji('👄')">👄</span>
              <span onclick="insertEmoji('🫦')">🫦</span>
              <span onclick="insertEmoji('👶')">👶</span>
              <span onclick="insertEmoji('🧒')">🧒</span>
              <span onclick="insertEmoji('👦')">👦</span>
              <span onclick="insertEmoji('👧')">👧</span>
              <span onclick="insertEmoji('🧑')">🧑</span>
              <span onclick="insertEmoji('👱')">👱</span>
              <span onclick="insertEmoji('👨')">👨</span>
              <span onclick="insertEmoji('🧔')">🧔</span>
              <span onclick="insertEmoji('🧔‍♂️')">🧔‍♂️</span>
              <span onclick="insertEmoji('🧔‍♀️')">🧔‍♀️</span>
              <span onclick="insertEmoji('👨‍🦰')">👨‍🦰</span>
              <span onclick="insertEmoji('👨‍🦱')">👨‍🦱</span>
              <span onclick="insertEmoji('👨‍🦳')">👨‍🦳</span>
              <span onclick="insertEmoji('👨‍🦲')">👨‍🦲</span>
              <span onclick="insertEmoji('👩')">👩</span>
              <span onclick="insertEmoji('👩‍🦰')">👩‍🦰</span>
              <span onclick="insertEmoji('🧑‍🦰')">🧑‍🦰</span>
              <span onclick="insertEmoji('👩‍🦱')">👩‍🦱</span>
              <span onclick="insertEmoji('🧑‍🦱')">🧑‍🦱</span>
              <span onclick="insertEmoji('👩‍🦳')">👩‍🦳</span>
              <span onclick="insertEmoji('🧑‍🦳')">🧑‍🦳</span>
              <span onclick="insertEmoji('👩‍🦲')">👩‍🦲</span>
              <span onclick="insertEmoji('🧑‍🦲')">🧑‍🦲</span>
              <span onclick="insertEmoji('👱‍♀️')">👱‍♀️</span>
              <span onclick="insertEmoji('👱‍♂️')">👱‍♂️</span>
              <span onclick="insertEmoji('🧓')">🧓</span>
              <span onclick="insertEmoji('👴')">👴</span>
              <span onclick="insertEmoji('👵')">👵</span>
              <span onclick="insertEmoji('🙍')">🙍</span>
              <span onclick="insertEmoji('🙎')">🙎</span>
              <span onclick="insertEmoji('🙅')">🙅</span>
              <span onclick="insertEmoji('🙆')">🙆</span>
              <span onclick="insertEmoji('💁')">💁</span>
              <span onclick="insertEmoji('🙋')">🙋</span>
              <span onclick="insertEmoji('🧏')">🧏</span>
              <span onclick="insertEmoji('🙇')">🙇</span>
              <span onclick="insertEmoji('🤦')">🤦</span>
              <span onclick="insertEmoji('🤷')">🤷</span>
              <span onclick="insertEmoji('🫅')">🫅</span>
              <span onclick="insertEmoji('🤴')">🤴</span>
              <span onclick="insertEmoji('👸')">👸</span>
              <span onclick="insertEmoji('👳')">👳</span>
              <span onclick="insertEmoji('👲')">👲</span>
              <span onclick="insertEmoji('🧕')">🧕</span>
              <span onclick="insertEmoji('🤵')">🤵</span>
              <span onclick="insertEmoji('👰')">👰</span>
              <span onclick="insertEmoji('🤰')">🤰</span>
              <span onclick="insertEmoji('🤱')">🤱</span>
              <span onclick="insertEmoji('💃')">💃</span>
              <span onclick="insertEmoji('🕺')">🕺</span>
              <span onclick="insertEmoji('👨‍👩‍👦')">👨‍👩‍👦</span>
              <span onclick="insertEmoji('👨‍👩‍👧')">👨‍👩‍👧</span>
              <span onclick="insertEmoji('👨‍👩‍👧‍👦')">👨‍👩‍👧‍👦</span>
              <span onclick="insertEmoji('👨‍👩‍👦‍👦')">👨‍👩‍👦‍👦</span>
              <span onclick="insertEmoji('👨‍👩‍👧‍👧')">👨‍👩‍👧‍👧</span>
              <span onclick="insertEmoji('👨‍👨‍👦')">👨‍👨‍👦</span>
              <span onclick="insertEmoji('👨‍👨‍👧')">👨‍👨‍👧</span>
              <span onclick="insertEmoji('👨‍👨‍👧‍👦')">👨‍👨‍👧‍👦</span>
              <span onclick="insertEmoji('👨‍👨‍👦‍👦')">👨‍👨‍👦‍👦</span>
              <span onclick="insertEmoji('👨‍👨‍👧‍👧')">👨‍👨‍👧‍👧</span>
              <span onclick="insertEmoji('👩‍👩‍👦')">👩‍👩‍👦</span>
              <span onclick="insertEmoji('👩‍👩‍👧')">👩‍👩‍👧</span>
              <span onclick="insertEmoji('👩‍👩‍👧‍👦')">👩‍👩‍👧‍👦</span>
              <span onclick="insertEmoji('👩‍👩‍👦‍👦')">👩‍👩‍👦‍👦</span>
              <span onclick="insertEmoji('👩‍👩‍👧‍👧')">👩‍👩‍👧‍👧</span>
              <span onclick="insertEmoji('👨‍👦')">👨‍👦</span>
              <span onclick="insertEmoji('👨‍👦‍👦')">👨‍👦‍👦</span>
              <span onclick="insertEmoji('👨‍👧')">👨‍👧</span>
              <span onclick="insertEmoji('👨‍👧‍👦')">👨‍👧‍👦</span>
              <span onclick="insertEmoji('👨‍👧‍👧')">👨‍👧‍👧</span>
              <span onclick="insertEmoji('👩‍👦')">👩‍👦</span>
              <span onclick="insertEmoji('👩‍👦‍👦')">👩‍👦‍👦</span>
              <span onclick="insertEmoji('👩‍👧')">👩‍👧</span>
              <span onclick="insertEmoji('👩‍👧‍👦')">👩‍👧‍👦</span>
              <span onclick="insertEmoji('👩‍👧‍👧')">👩‍👧‍👧</span>
              <span onclick="insertEmoji('👪')">👪</span>
              <span onclick="insertEmoji('🧑‍🧑‍🧒')">🧑‍🧑‍🧒</span>
              <span onclick="insertEmoji('🧑‍🧑‍🧒‍🧒')">🧑‍🧑‍🧒‍🧒</span>
              <span onclick="insertEmoji('🧑‍🧒')">🧑‍🧒</span>
              <span onclick="insertEmoji('🧑‍🧒‍🧒')">🧑‍🧒‍🧒</span>
            </div>
            <!-- Naturaleza -->
            <div class="emoji-category">
              <h4>Naturaleza</h4>
              <span onclick="insertEmoji('🌍')">🌍</span>
              <span onclick="insertEmoji('🌎')">🌎</span>
              <span onclick="insertEmoji('🌏')">🌏</span>
              <span onclick="insertEmoji('🏔️')">🏔️</span>
              <span onclick="insertEmoji('⛰️')">⛰️</span>
              <span onclick="insertEmoji('🌋')">🌋</span>
              <span onclick="insertEmoji('🗻')">🗻</span>
              <span onclick="insertEmoji('🏕️')">🏕️</span>
              <span onclick="insertEmoji('🏖️')">🏖️</span>
              <span onclick="insertEmoji('🏜️')">🏜️</span>
              <span onclick="insertEmoji('🏝️')">🏝️</span>
              <span onclick="insertEmoji('🏞️')">🏞️</span>
              <span onclick="insertEmoji('🔥')">🔥</span>
              <span onclick="insertEmoji('💧')">💧</span>
              <span onclick="insertEmoji('🌊')">🌊</span>
              <span onclick="insertEmoji('🐵')">🐵</span>
              <span onclick="insertEmoji('🐒')">🐒</span>
              <span onclick="insertEmoji('🦍')">🦍</span>
              <span onclick="insertEmoji('🦧')">🦧</span>
              <span onclick="insertEmoji('🐶')">🐶</span>
              <span onclick="insertEmoji('🐕')">🐕</span>
              <span onclick="insertEmoji('🦮')">🦮</span>
              <span onclick="insertEmoji('🐕‍🦺')">🐕‍🦺</span>
              <span onclick="insertEmoji('🐩')">🐩</span>
              <span onclick="insertEmoji('🐺')">🐺</span>
              <span onclick="insertEmoji('🦊')">🦊</span>
              <span onclick="insertEmoji('🦝')">🦝</span>
              <span onclick="insertEmoji('🐱')">🐱</span>
              <span onclick="insertEmoji('🐈')">🐈</span>
              <span onclick="insertEmoji('🐈‍⬛')">🐈‍⬛</span>
              <span onclick="insertEmoji('🦁')">🦁</span>
              <span onclick="insertEmoji('🐯')">🐯</span>
              <span onclick="insertEmoji('🐅')">🐅</span>
              <span onclick="insertEmoji('🐆')">🐆</span>
              <span onclick="insertEmoji('🐴')">🐴</span>
              <span onclick="insertEmoji('🫎')">🫎</span>
              <span onclick="insertEmoji('🫏')">🫏</span>
              <span onclick="insertEmoji('🐎')">🐎</span>
              <span onclick="insertEmoji('🦄')">🦄</span>
              <span onclick="insertEmoji('🦓')">🦓</span>
              <span onclick="insertEmoji('🦌')">🦌</span>
              <span onclick="insertEmoji('🦬')">🦬</span>
              <span onclick="insertEmoji('🐮')">🐮</span>
              <span onclick="insertEmoji('🐂')">🐂</span>
              <span onclick="insertEmoji('🐃')">🐃</span>
              <span onclick="insertEmoji('🐄')">🐄</span>
              <span onclick="insertEmoji('🐷')">🐷</span>
              <span onclick="insertEmoji('🐖')">🐖</span>
              <span onclick="insertEmoji('🐗')">🐗</span>
              <span onclick="insertEmoji('🐽')">🐽</span>
              <span onclick="insertEmoji('🐏')">🐏</span>
              <span onclick="insertEmoji('🐑')">🐑</span>
              <span onclick="insertEmoji('🐐')">🐐</span>
              <span onclick="insertEmoji('🐪')">🐪</span>
              <span onclick="insertEmoji('🐫')">🐫</span>
              <span onclick="insertEmoji('🦙')">🦙</span>
              <span onclick="insertEmoji('🦒')">🦒</span>
              <span onclick="insertEmoji('🐘')">🐘</span>
              <span onclick="insertEmoji('🦣')">🦣</span>
              <span onclick="insertEmoji('🦏')">🦏</span>
              <span onclick="insertEmoji('🦛')">🦛</span>
              <span onclick="insertEmoji('🐭')">🐭</span>
              <span onclick="insertEmoji('🐁')">🐁</span>
              <span onclick="insertEmoji('🐀')">🐀</span>
              <span onclick="insertEmoji('🐹')">🐹</span>
              <span onclick="insertEmoji('🐰')">🐰</span>
              <span onclick="insertEmoji('🐇')">🐇</span>
              <span onclick="insertEmoji('🐿️')">🐿️</span>
              <span onclick="insertEmoji('🦫')">🦫</span>
              <span onclick="insertEmoji('🦔')">🦔</span>
              <span onclick="insertEmoji('🦇')">🦇</span>
              <span onclick="insertEmoji('🐻')">🐻</span>
              <span onclick="insertEmoji('🐻‍❄️')">🐻‍❄️</span>
              <span onclick="insertEmoji('🐨')">🐨</span>
              <span onclick="insertEmoji('🐼')">🐼</span>
              <span onclick="insertEmoji('🦥')">🦥</span>
              <span onclick="insertEmoji('🦦')">🦦</span>
              <span onclick="insertEmoji('🦨')">🦨</span>
              <span onclick="insertEmoji('🦘')">🦘</span>
              <span onclick="insertEmoji('🦡')">🦡</span>
              <span onclick="insertEmoji('🐾')">🐾</span>
              <span onclick="insertEmoji('🦃')">🦃</span>
              <span onclick="insertEmoji('🐔')">🐔</span>
              <span onclick="insertEmoji('🐓')">🐓</span>
              <span onclick="insertEmoji('🐣')">🐣</span>
              <span onclick="insertEmoji('🐤')">🐤</span>
              <span onclick="insertEmoji('🐥')">🐥</span>
              <span onclick="insertEmoji('🐦')">🐦</span>
              <span onclick="insertEmoji('🐧')">🐧</span>
              <span onclick="insertEmoji('🕊️')">🕊️</span>
              <span onclick="insertEmoji('🦅')">🦅</span>
              <span onclick="insertEmoji('🦆')">🦆</span>
              <span onclick="insertEmoji('🦢')">🦢</span>
              <span onclick="insertEmoji('🦉')">🦉</span>
              <span onclick="insertEmoji('🦤')">🦤</span>
              <span onclick="insertEmoji('🪶')">🪶</span>
              <span onclick="insertEmoji('🦩')">🦩</span>
              <span onclick="insertEmoji('🦚')">🦚</span>
              <span onclick="insertEmoji('🦜')">🦜</span>
              <span onclick="insertEmoji('🪽')">🪽</span>
              <span onclick="insertEmoji('🐸')">🐸</span>
              <span onclick="insertEmoji('🐊')">🐊</span>
              <span onclick="insertEmoji('🐢')">🐢</span>
              <span onclick="insertEmoji('🦎')">🦎</span>
              <span onclick="insertEmoji('🐍')">🐍</span>
              <span onclick="insertEmoji('🐲')">🐲</span>
              <span onclick="insertEmoji('🐉')">🐉</span>
              <span onclick="insertEmoji('🦕')">🦕</span>
              <span onclick="insertEmoji('🦖')">🦖</span>
              <span onclick="insertEmoji('🐳')">🐳</span>
              <span onclick="insertEmoji('🐋')">🐋</span>
              <span onclick="insertEmoji('🐬')">🐬</span>
              <span onclick="insertEmoji('🦭')">🦭</span>
              <span onclick="insertEmoji('🐟')">🐟</span>
              <span onclick="insertEmoji('🐠')">🐠</span>
              <span onclick="insertEmoji('🐡')">🐡</span>
              <span onclick="insertEmoji('🦈')">🦈</span>
              <span onclick="insertEmoji('🐙')">🐙</span>
              <span onclick="insertEmoji('🐚')">🐚</span>
              <span onclick="insertEmoji('🪸')">🪸</span>
              <span onclick="insertEmoji('🪼')">🪼</span>
              <span onclick="insertEmoji('🍄')">🍄</span>
            </div>
            <!-- Comida -->
            <div class="emoji-category">
              <h4>Comida</h4>
              <span onclick="insertEmoji('🍇')">🍇</span>
              <span onclick="insertEmoji('🍈')">🍈</span>
              <span onclick="insertEmoji('🍉')">🍉</span>
              <span onclick="insertEmoji('🍊')">🍊</span>
              <span onclick="insertEmoji('🍋')">🍋</span>
              <span onclick="insertEmoji('🍋‍🟩')">🍋‍🟩</span>
              <span onclick="insertEmoji('🍌')">🍌</span>
              <span onclick="insertEmoji('🍍')">🍍</span>
              <span onclick="insertEmoji('🥭')">🥭</span>
              <span onclick="insertEmoji('🍎')">🍎</span>
              <span onclick="insertEmoji('🍏')">🍏</span>
              <span onclick="insertEmoji('🍐')">🍐</span>
              <span onclick="insertEmoji('🍑')">🍑</span>
              <span onclick="insertEmoji('🍒')">🍒</span>
              <span onclick="insertEmoji('🍓')">🍓</span>
              <span onclick="insertEmoji('🫐')">🫐</span>
              <span onclick="insertEmoji('🥝')">🥝</span>
              <span onclick="insertEmoji('🍅')">🍅</span>
              <span onclick="insertEmoji('🫒')">🫒</span>
              <span onclick="insertEmoji('🥥')">🥥</span>
              <span onclick="insertEmoji('🥑')">🥑</span>
              <span onclick="insertEmoji('🍆')">🍆</span>
              <span onclick="insertEmoji('🥔')">🥔</span>
              <span onclick="insertEmoji('🥕')">🥕</span>
              <span onclick="insertEmoji('🌽')">🌽</span>
              <span onclick="insertEmoji('🌶️')">🌶️</span>
              <span onclick="insertEmoji('🫑')">🫑</span>
              <span onclick="insertEmoji('🥒')">🥒</span>
              <span onclick="insertEmoji('🥬')">🥬</span>
              <span onclick="insertEmoji('🥦')">🥦</span>
              <span onclick="insertEmoji('🧄')">🧄</span>
              <span onclick="insertEmoji('🧅')">🧅</span>
              <span onclick="insertEmoji('🥜')">🥜</span>
              <span onclick="insertEmoji('🫘')">🫘</span>
              <span onclick="insertEmoji('🌰')">🌰</span>
              <span onclick="insertEmoji('🫚')">🫚</span>
              <span onclick="insertEmoji('🫛')">🫛</span>
              <span onclick="insertEmoji('🍄‍🟫')">🍄‍🟫</span>
              <span onclick="insertEmoji('🍞')">🍞</span>
              <span onclick="insertEmoji('🥐')">🥐</span>
              <span onclick="insertEmoji('🥖')">🥖</span>
              <span onclick="insertEmoji('🫓')">🫓</span>
              <span onclick="insertEmoji('🥨')">🥨</span>
              <span onclick="insertEmoji('🥯')">🥯</span>
              <span onclick="insertEmoji('🥞')">🥞</span>
              <span onclick="insertEmoji('🧇')">🧇</span>
              <span onclick="insertEmoji('🧀')">🧀</span>
              <span onclick="insertEmoji('🍖')">🍖</span>
              <span onclick="insertEmoji('🍗')">🍗</span>
              <span onclick="insertEmoji('🥩')">🥩</span>
              <span onclick="insertEmoji('🥓')">🥓</span>
              <span onclick="insertEmoji('🍔')">🍔</span>
              <span onclick="insertEmoji('🍟')">🍟</span>
              <span onclick="insertEmoji('🍕')">🍕</span>
              <span onclick="insertEmoji('🌭')">🌭</span>
              <span onclick="insertEmoji('🥪')">🥪</span>
              <span onclick="insertEmoji('🌮')">🌮</span>
              <span onclick="insertEmoji('🌯')">🌯</span>
              <span onclick="insertEmoji('🫔')">🫔</span>
              <span onclick="insertEmoji('🥙')">🥙</span>
              <span onclick="insertEmoji('🧆')">🧆</span>
              <span onclick="insertEmoji('🥚')">🥚</span>
              <span onclick="insertEmoji('🍳')">🍳</span>
              <span onclick="insertEmoji('🥘')">🥘</span>
              <span onclick="insertEmoji('🍲')">🍲</span>
              <span onclick="insertEmoji('🫕')">🫕</span>
              <span onclick="insertEmoji('🥣')">🥣</span>
              <span onclick="insertEmoji('🥗')">🥗</span>
              <span onclick="insertEmoji('🍿')">🍿</span>
              <span onclick="insertEmoji('🧈')">🧈</span>
              <span onclick="insertEmoji('🧂')">🧂</span>
              <span onclick="insertEmoji('🥫')">🥫</span>
              <span onclick="insertEmoji('🍱')">🍱</span>
              <span onclick="insertEmoji('🍘')">🍘</span>
              <span onclick="insertEmoji('🍙')">🍙</span>
              <span onclick="insertEmoji('🍚')">🍚</span>
              <span onclick="insertEmoji('🍛')">🍛</span>
              <span onclick="insertEmoji('🍜')">🍜</span>
              <span onclick="insertEmoji('🍝')">🍝</span>
              <span onclick="insertEmoji('🍠')">🍠</span>
              <span onclick="insertEmoji('🍢')">🍢</span>
              <span onclick="insertEmoji('🍣')">🍣</span>
              <span onclick="insertEmoji('🍤')">🍤</span>
              <span onclick="insertEmoji('🍥')">🍥</span>
              <span onclick="insertEmoji('🥮')">🥮</span>
              <span onclick="insertEmoji('🍡')">🍡</span>
              <span onclick="insertEmoji('🥟')">🥟</span>
              <span onclick="insertEmoji('🥠')">🥠</span>
              <span onclick="insertEmoji('🥡')">🥡</span>
              <span onclick="insertEmoji('🦀')">🦀</span>
              <span onclick="insertEmoji('🦞')">🦞</span>
              <span onclick="insertEmoji('🦐')">🦐</span>
              <span onclick="insertEmoji('🦑')">🦑</span>
              <span onclick="insertEmoji('🦪')">🦪</span>
              <span onclick="insertEmoji('🍦')">🍦</span>
              <span onclick="insertEmoji('🍧')">🍧</span>
              <span onclick="insertEmoji('🍨')">🍨</span>
              <span onclick="insertEmoji('🍩')">🍩</span>
              <span onclick="insertEmoji('🍪')">🍪</span>
              <span onclick="insertEmoji('🎂')">🎂</span>
              <span onclick="insertEmoji('🍰')">🍰</span>
              <span onclick="insertEmoji('🧁')">🧁</span>
              <span onclick="insertEmoji('🥧')">🥧</span>
              <span onclick="insertEmoji('🍫')">🍫</span>
              <span onclick="insertEmoji('🍬')">🍬</span>
              <span onclick="insertEmoji('🍭')">🍭</span>
              <span onclick="insertEmoji('🍮')">🍮</span>
              <span onclick="insertEmoji('🍯')">🍯</span>
              <span onclick="insertEmoji('🍼')">🍼</span>
              <span onclick="insertEmoji('🥛')">🥛</span>
              <span onclick="insertEmoji('☕')">☕</span>
              <span onclick="insertEmoji('🫖')">🫖</span>
              <span onclick="insertEmoji('🍵')">🍵</span>
              <span onclick="insertEmoji('🍶')">🍶</span>
              <span onclick="insertEmoji('🍾')">🍾</span>
              <span onclick="insertEmoji('🍷')">🍷</span>
              <span onclick="insertEmoji('🍸')">🍸</span>
              <span onclick="insertEmoji('🍹')">🍹</span>
              <span onclick="insertEmoji('🍺')">🍺</span>
              <span onclick="insertEmoji('🍻')">🍻</span>
              <span onclick="insertEmoji('🥂')">🥂</span>
              <span onclick="insertEmoji('🥃')">🥃</span>
              <span onclick="insertEmoji('🫗')">🫗</span>
              <span onclick="insertEmoji('🥤')">🥤</span>
              <span onclick="insertEmoji('🍽️')">🍽️</span>
              <span onclick="insertEmoji('🔪')">🔪</span>
              <span onclick="insertEmoji('🏺')">🏺</span>
            </div>
            <!-- Actividades -->
            <div class="emoji-category">
              <h4>Actividades</h4>
              <span onclick="insertEmoji('🎃')">🎃</span>
              <span onclick="insertEmoji('🎄')">🎄</span>
              <span onclick="insertEmoji('🎆')">🎆</span>
              <span onclick="insertEmoji('🎇')">🎇</span>
              <span onclick="insertEmoji('🧨')">🧨</span>
              <span onclick="insertEmoji('✨')">✨</span>
              <span onclick="insertEmoji('🎈')">🎈</span>
              <span onclick="insertEmoji('🎉')">🎉</span>
              <span onclick="insertEmoji('🎊')">🎊</span>
              <span onclick="insertEmoji('🎋')">🎋</span>
              <span onclick="insertEmoji('🎍')">🎍</span>
              <span onclick="insertEmoji('🎎')">🎎</span>
              <span onclick="insertEmoji('🎏')">🎏</span>
              <span onclick="insertEmoji('🎐')">🎐</span>
              <span onclick="insertEmoji('🎑')">🎑</span>
              <span onclick="insertEmoji('🧧')">🧧</span>
              <span onclick="insertEmoji('🎀')">🎀</span>
              <span onclick="insertEmoji('🎁')">🎁</span>
              <span onclick="insertEmoji('🎗️')">🎗️</span>
              <span onclick="insertEmoji('🎟️')">🎟️</span>
              <span onclick="insertEmoji('🎫')">🎫</span>
              <span onclick="insertEmoji('🎖️')">🎖️</span>
              <span onclick="insertEmoji('🏆')">🏆</span>
              <span onclick="insertEmoji('🏅')">🏅</span>
              <span onclick="insertEmoji('🥇')">🥇</span>
              <span onclick="insertEmoji('🥈')">🥈</span>
              <span onclick="insertEmoji('🥉')">🥉</span>
              <span onclick="insertEmoji('⚽')">⚽</span>
              <span onclick="insertEmoji('⚾')">⚾</span>
              <span onclick="insertEmoji('🥎')">🥎</span>
              <span onclick="insertEmoji('🏀')">🏀</span>
              <span onclick="insertEmoji('🏐')">🏐</span>
              <span onclick="insertEmoji('🏈')">🏈</span>
              <span onclick="insertEmoji('🏉')">🏉</span>
              <span onclick="insertEmoji('🎾')">🎾</span>
              <span onclick="insertEmoji('🥏')">🥏</span>
              <span onclick="insertEmoji('🎳')">🎳</span>
              <span onclick="insertEmoji('🏏')">🏏</span>
              <span onclick="insertEmoji('🏑')">🏑</span>
              <span onclick="insertEmoji('🏒')">🏒</span>
              <span onclick="insertEmoji('🥍')">🥍</span>
              <span onclick="insertEmoji('🏓')">🏓</span>
              <span onclick="insertEmoji('🏸')">🏸</span>
              <span onclick="insertEmoji('🥊')">🥊</span>
              <span onclick="insertEmoji('🥋')">🥋</span>
              <span onclick="insertEmoji('🥅')">🥅</span>
              <span onclick="insertEmoji('⛳')">⛳</span>
              <span onclick="insertEmoji('⛸️')">⛸️</span>
              <span onclick="insertEmoji('🎣')">🎣</span>
              <span onclick="insertEmoji('🤿')">🤿</span>
              <span onclick="insertEmoji('🎽')">🎽</span>
              <span onclick="insertEmoji('🎿')">🎿</span>
              <span onclick="insertEmoji('🛷')">🛷</span>
              <span onclick="insertEmoji('🥌')">🥌</span>
              <span onclick="insertEmoji('🎯')">🎯</span>
              <span onclick="insertEmoji('🪀')">🪀</span>
              <span onclick="insertEmoji('🪁')">🪁</span>
              <span onclick="insertEmoji('🔫')">🔫</span>
              <span onclick="insertEmoji('🎱')">🎱</span>
              <span onclick="insertEmoji('🔮')">🔮</span>
              <span onclick="insertEmoji('🪄')">🪄</span>
              <span onclick="insertEmoji('🎮')">🎮</span>
              <span onclick="insertEmoji('🕹️')">🕹️</span>
              <span onclick="insertEmoji('🎰')">🎰</span>
              <span onclick="insertEmoji('🎲')">🎲</span>
              <span onclick="insertEmoji('🧩')">🧩</span>
              <span onclick="insertEmoji('🧸')">🧸</span>
              <span onclick="insertEmoji('🪅')">🪅</span>
              <span onclick="insertEmoji('🪩')">🪩</span>
              <span onclick="insertEmoji('🪆')">🪆</span>
              <span onclick="insertEmoji('♟️')">♟️</span>
              <span onclick="insertEmoji('🃏')">🃏</span>
              <span onclick="insertEmoji('🀄')">🀄</span>
              <span onclick="insertEmoji('🎴')">🎴</span>
              <span onclick="insertEmoji('🎭')">🎭</span>
              <span onclick="insertEmoji('🖼️')">🖼️</span>
              <span onclick="insertEmoji('🎨')">🎨</span>
              <span onclick="insertEmoji('🧵')">🧵</span>
              <span onclick="insertEmoji('🪡')">🪡</span>
              <span onclick="insertEmoji('🧶')">🧶</span>
              <span onclick="insertEmoji('🪢')">🪢</span>
              <span onclick="insertEmoji('👮')">👮</span>
              <span onclick="insertEmoji('👮‍♂️')">👮‍♂️</span>
              <span onclick="insertEmoji('👮‍♀️')">👮‍♀️</span>
              <span onclick="insertEmoji('💂')">💂</span>
              <span onclick="insertEmoji('💂‍♂️')">💂‍♂️</span>
              <span onclick="insertEmoji('💂‍♀️')">💂‍♀️</span>
              <span onclick="insertEmoji('🥷')">🥷</span>
              <span onclick="insertEmoji('👷')">👷</span>
              <span onclick="insertEmoji('👷‍♂️')">👷‍♂️</span>
              <span onclick="insertEmoji('👷‍♀️')">👷‍♀️</span>
              <span onclick="insertEmoji('🕵️')">🕵️</span>
              <span onclick="insertEmoji('🕵️‍♂️')">🕵️‍♂️</span>
              <span onclick="insertEmoji('🕵️‍♀️')">🕵️‍♀️</span>
              <span onclick="insertEmoji('🤵')">🤵</span>
              <span onclick="insertEmoji('🤵‍♂️')">🤵‍♂️</span>
              <span onclick="insertEmoji('🤵‍♀️')">🤵‍♀️</span>
              <span onclick="insertEmoji('👰')">👰</span>
              <span onclick="insertEmoji('👰‍♂️')">👰‍♂️</span>
              <span onclick="insertEmoji('👰‍♀️')">👰‍♀️</span>
              <span onclick="insertEmoji('🕴️')">🕴️</span>
              <span onclick="insertEmoji('👯')">👯</span>
              <span onclick="insertEmoji('👯‍♂️')">👯‍♂️</span>
              <span onclick="insertEmoji('👯‍♀️')">👯‍♀️</span>
              <span onclick="insertEmoji('🧖')">🧖</span>
              <span onclick="insertEmoji('🧖‍♂️')">🧖‍♂️</span>
              <span onclick="insertEmoji('🧖‍♀️')">🧖‍♀️</span>
              <span onclick="insertEmoji('🏄')">🏄</span>
              <span onclick="insertEmoji('🏄‍♂️')">🏄‍♂️</span>
              <span onclick="insertEmoji('🏄‍♀️')">🏄‍♀️</span>
              <span onclick="insertEmoji('🤸')">🤸</span>
              <span onclick="insertEmoji('🤸‍♂️')">🤸‍♂️</span>
              <span onclick="insertEmoji('🤸‍♀️')">🤸‍♀️</span>
            </div>
            <!-- Lugares -->
            <div class="emoji-category">
              <h4>Lugares</h4>
              <span onclick="insertEmoji('🌐')">🌐</span>
              <span onclick="insertEmoji('🗺️')">🗺️</span>
              <span onclick="insertEmoji('🗾')">🗾</span>
              <span onclick="insertEmoji('🧭')">🧭</span>
              <span onclick="insertEmoji('🏟️')">🏟️</span>
              <span onclick="insertEmoji('🏛️')">🏛️</span>
              <span onclick="insertEmoji('🏗️')">🏗️</span>
              <span onclick="insertEmoji('🧱')">🧱</span>
              <span onclick="insertEmoji('🪨')">🪨</span>
              <span onclick="insertEmoji('🪵')">🪵</span>
              <span onclick="insertEmoji('🛖')">🛖</span>
              <span onclick="insertEmoji('🏘️')">🏘️</span>
              <span onclick="insertEmoji('🏚️')">🏚️</span>
              <span onclick="insertEmoji('🏠')">🏠</span>
              <span onclick="insertEmoji('🏡')">🏡</span>
              <span onclick="insertEmoji('🏢')">🏢</span>
              <span onclick="insertEmoji('🏣')">🏣</span>
              <span onclick="insertEmoji('🏤')">🏤</span>
              <span onclick="insertEmoji('🏥')">🏥</span>
              <span onclick="insertEmoji('🏦')">🏦</span>
              <span onclick="insertEmoji('🏨')">🏨</span>
              <span onclick="insertEmoji('🏩')">🏩</span>
              <span onclick="insertEmoji('🏪')">🏪</span>
              <span onclick="insertEmoji('🏫')">🏫</span>
              <span onclick="insertEmoji('🏬')">🏬</span>
              <span onclick="insertEmoji('🏭')">🏭</span>
              <span onclick="insertEmoji('🏯')">🏯</span>
              <span onclick="insertEmoji('🏰')">🏰</span>
              <span onclick="insertEmoji('💒')">💒</span>
              <span onclick="insertEmoji('🗼')">🗼</span>
              <span onclick="insertEmoji('🗽')">🗽</span>
              <span onclick="insertEmoji('⛪')">⛪</span>
              <span onclick="insertEmoji('🕌')">🕌</span>
              <span onclick="insertEmoji('🛕')">🛕</span>
              <span onclick="insertEmoji('🕍')">🕍</span>
              <span onclick="insertEmoji('⛩️')">⛩️</span>
              <span onclick="insertEmoji('🕋')">🕋</span>
              <span onclick="insertEmoji('⛲')">⛲</span>
              <span onclick="insertEmoji('⛺')">⛺</span>
              <span onclick="insertEmoji('🌁')">🌁</span>
              <span onclick="insertEmoji('🌃')">🌃</span>
              <span onclick="insertEmoji('🏙️')">🏙️</span>
              <span onclick="insertEmoji('🌄')">🌄</span>
              <span onclick="insertEmoji('🌅')">🌅</span>
              <span onclick="insertEmoji('🌆')">🌆</span>
              <span onclick="insertEmoji('🌇')">🌇</span>
              <span onclick="insertEmoji('🌉')">🌉</span>
              <span onclick="insertEmoji('♨️')">♨️</span>
              <span onclick="insertEmoji('🎠')">🎠</span>
              <span onclick="insertEmoji('🛝')">🛝</span>
              <span onclick="insertEmoji('🎡')">🎡</span>
              <span onclick="insertEmoji('🎢')">🎢</span>
              <span onclick="insertEmoji('💈')">💈</span>
              <span onclick="insertEmoji('🎪')">🎪</span>
              <span onclick="insertEmoji('🚂')">🚂</span>
              <span onclick="insertEmoji('🚃')">🚃</span>
              <span onclick="insertEmoji('🚄')">🚄</span>
              <span onclick="insertEmoji('🚅')">🚅</span>
              <span onclick="insertEmoji('🚆')">🚆</span>
              <span onclick="insertEmoji('🚇')">🚇</span>
              <span onclick="insertEmoji('🚈')">🚈</span>
              <span onclick="insertEmoji('🚉')">🚉</span>
              <span onclick="insertEmoji('🚊')">🚊</span>
              <span onclick="insertEmoji('🚝')">🚝</span>
              <span onclick="insertEmoji('🚞')">🚞</span>
              <span onclick="insertEmoji('🚋')">🚋</span>
              <span onclick="insertEmoji('🚌')">🚌</span>
              <span onclick="insertEmoji('🚍')">🚍</span>
              <span onclick="insertEmoji('🚎')">🚎</span>
              <span onclick="insertEmoji('🚐')">🚐</span>
              <span onclick="insertEmoji('🚑')">🚑</span>
              <span onclick="insertEmoji('🚒')">🚒</span>
              <span onclick="insertEmoji('🚓')">🚓</span>
              <span onclick="insertEmoji('🚔')">🚔</span>
              <span onclick="insertEmoji('🚕')">🚕</span>
              <span onclick="insertEmoji('🚖')">🚖</span>
              <span onclick="insertEmoji('🚗')">🚗</span>
              <span onclick="insertEmoji('🚘')">🚘</span>
              <span onclick="insertEmoji('🚙')">🚙</span>
              <span onclick="insertEmoji('🛻')">🛻</span>
              <span onclick="insertEmoji('🚚')">🚚</span>
              <span onclick="insertEmoji('🚛')">🚛</span>
              <span onclick="insertEmoji('🚜')">🚜</span>
              <span onclick="insertEmoji('🏎️')">🏎️</span>
              <span onclick="insertEmoji('🏍️')">🏍️</span>
              <span onclick="insertEmoji('🛵')">🛵</span>
              <span onclick="insertEmoji('🦽')">🦽</span>
              <span onclick="insertEmoji('🦼')">🦼</span>
              <span onclick="insertEmoji('🛺')">🛺</span>
              <span onclick="insertEmoji('🚲')">🚲</span>
              <span onclick="insertEmoji('🛴')">🛴</span>
              <span onclick="insertEmoji('🛹')">🛹</span>
              <span onclick="insertEmoji('🛼')">🛼</span>
              <span onclick="insertEmoji('🚏')">🚏</span>
              <span onclick="insertEmoji('🛣️')">🛣️</span>
              <span onclick="insertEmoji('🛤️')">🛤️</span>
              <span onclick="insertEmoji('🛢️')">🛢️</span>
              <span onclick="insertEmoji('⛽')">⛽</span>
              <span onclick="insertEmoji('🛞')">🛞</span>
              <span onclick="insertEmoji('🚨')">🚨</span>
              <span onclick="insertEmoji('🚥')">🚥</span>
              <span onclick="insertEmoji('🚦')">🚦</span>
              <span onclick="insertEmoji('🛑')">🛑</span>
              <span onclick="insertEmoji('🚧')">🚧</span>
              <span onclick="insertEmoji('⚓')">⚓</span>
            </div>
            <!-- Objetos -->
            <div class="emoji-category">
              <h4>Objetos</h4>
              <span onclick="insertEmoji('👓')">👓</span>
              <span onclick="insertEmoji('🕶️')">🕶️</span>
              <span onclick="insertEmoji('🥽')">🥽</span>
              <span onclick="insertEmoji('🥼')">🥼</span>
              <span onclick="insertEmoji('🦺')">🦺</span>
              <span onclick="insertEmoji('👔')">👔</span>
              <span onclick="insertEmoji('👕')">👕</span>
              <span onclick="insertEmoji('👖')">👖</span>
              <span onclick="insertEmoji('🧣')">🧣</span>
              <span onclick="insertEmoji('🧤')">🧤</span>
              <span onclick="insertEmoji('🧥')">🧥</span>
              <span onclick="insertEmoji('🧦')">🧦</span>
              <span onclick="insertEmoji('👗')">👗</span>
              <span onclick="insertEmoji('👘')">👘</span>
              <span onclick="insertEmoji('🥻')">🥻</span>
              <span onclick="insertEmoji('🩱')">🩱</span>
              <span onclick="insertEmoji('🩲')">🩲</span>
              <span onclick="insertEmoji('🩳')">🩳</span>
              <span onclick="insertEmoji('👙')">👙</span>
              <span onclick="insertEmoji('👚')">👚</span>
              <span onclick="insertEmoji('🪭')">🪭</span>
              <span onclick="insertEmoji('👛')">👛</span>
              <span onclick="insertEmoji('👜')">👜</span>
              <span onclick="insertEmoji('👝')">👝</span>
              <span onclick="insertEmoji('🛍️')">🛍️</span>
              <span onclick="insertEmoji('🎒')">🎒</span>
              <span onclick="insertEmoji('🩴')">🩴</span>
              <span onclick="insertEmoji('👞')">👞</span>
              <span onclick="insertEmoji('👟')">👟</span>
              <span onclick="insertEmoji('🥾')">🥾</span>
              <span onclick="insertEmoji('🥿')">🥿</span>
              <span onclick="insertEmoji('👠')">👠</span>
              <span onclick="insertEmoji('👡')">👡</span>
              <span onclick="insertEmoji('🩰')">🩰</span>
              <span onclick="insertEmoji('👢')">👢</span>
              <span onclick="insertEmoji('🪮')">🪮</span>
              <span onclick="insertEmoji('👑')">👑</span>
              <span onclick="insertEmoji('👒')">👒</span>
              <span onclick="insertEmoji('🎩')">🎩</span>
              <span onclick="insertEmoji('🎓')">🎓</span>
              <span onclick="insertEmoji('🧢')">🧢</span>
              <span onclick="insertEmoji('🪖')">🪖</span>
              <span onclick="insertEmoji('⛑️')">⛑️</span>
              <span onclick="insertEmoji('📿')">📿</span>
              <span onclick="insertEmoji('💄')">💄</span>
              <span onclick="insertEmoji('💍')">💍</span>
              <span onclick="insertEmoji('💎')">💎</span>
              <span onclick="insertEmoji('🔇')">🔇</span>
              <span onclick="insertEmoji('🔈')">🔈</span>
              <span onclick="insertEmoji('🔉')">🔉</span>
              <span onclick="insertEmoji('🔊')">🔊</span>
              <span onclick="insertEmoji('📢')">📢</span>
              <span onclick="insertEmoji('📣')">📣</span>
              <span onclick="insertEmoji('📯')">📯</span>
              <span onclick="insertEmoji('🔔')">🔔</span>
              <span onclick="insertEmoji('🔕')">🔕</span>
              <span onclick="insertEmoji('🎼')">🎼</span>
              <span onclick="insertEmoji('🎵')">🎵</span>
              <span onclick="insertEmoji('🎶')">🎶</span>
              <span onclick="insertEmoji('🎙️')">🎙️</span>
              <span onclick="insertEmoji('🎚️')">🎚️</span>
              <span onclick="insertEmoji('🎛️')">🎛️</span>
              <span onclick="insertEmoji('🎤')">🎤</span>
              <span onclick="insertEmoji('🎧')">🎧</span>
              <span onclick="insertEmoji('📻')">📻</span>
              <span onclick="insertEmoji('🎷')">🎷</span>
              <span onclick="insertEmoji('🪗')">🪗</span>
              <span onclick="insertEmoji('🎸')">🎸</span>
              <span onclick="insertEmoji('🎹')">🎹</span>
              <span onclick="insertEmoji('🎺')">🎺</span>
              <span onclick="insertEmoji('🎻')">🎻</span>
              <span onclick="insertEmoji('🪕')">🪕</span>
              <span onclick="insertEmoji('🥁')">🥁</span>
              <span onclick="insertEmoji('🪘')">🪘</span>
              <span onclick="insertEmoji('🪇')">🪇</span>
              <span onclick="insertEmoji('🪈')">🪈</span>
              <span onclick="insertEmoji('📱')">📱</span>
              <span onclick="insertEmoji('📲')">📲</span>
              <span onclick="insertEmoji('☎️')">☎️</span>
              <span onclick="insertEmoji('📞')">📞</span>
              <span onclick="insertEmoji('📟')">📟</span>
              <span onclick="insertEmoji('📠')">📠</span>
              <span onclick="insertEmoji('🔋')">🔋</span>
              <span onclick="insertEmoji('🪫')">🪫</span>
              <span onclick="insertEmoji('🔌')">🔌</span>
              <span onclick="insertEmoji('💻')">💻</span>
              <span onclick="insertEmoji('🖥️')">🖥️</span>
              <span onclick="insertEmoji('🖨️')">🖨️</span>
              <span onclick="insertEmoji('⌨️')">⌨️</span>
              <span onclick="insertEmoji('🖱️')">🖱️</span>
              <span onclick="insertEmoji('🖲️')">🖲️</span>
              <span onclick="insertEmoji('💽')">💽</span>
              <span onclick="insertEmoji('💾')">💾</span>
              <span onclick="insertEmoji('💿')">💿</span>
              <span onclick="insertEmoji('📀')">📀</span>
              <span onclick="insertEmoji('🧮')">🧮</span>
              <span onclick="insertEmoji('🎥')">🎥</span>
              <span onclick="insertEmoji('🎞️')">🎞️</span>
              <span onclick="insertEmoji('📽️')">📽️</span>
              <span onclick="insertEmoji('🎬')">🎬</span>
              <span onclick="insertEmoji('📺')">📺</span>
              <span onclick="insertEmoji('📷')">📷</span>
              <span onclick="insertEmoji('📸')">📸</span>
              <span onclick="insertEmoji('📹')">📹</span>
              <span onclick="insertEmoji('📼')">📼</span>
              <span onclick="insertEmoji('🔍')">🔍</span>
              <span onclick="insertEmoji('🔎')">🔎</span>
              <span onclick="insertEmoji('🕯️')">🕯️</span>
              <span onclick="insertEmoji('💡')">💡</span>
              <span onclick="insertEmoji('🔦')">🔦</span>
              <span onclick="insertEmoji('🏮')">🏮</span>
              <span onclick="insertEmoji('🪔')">🪔</span>
            </div>
            <!-- Símbolos -->
            <div class="emoji-category">
              <h4>Símbolos</h4>
              <span onclick="insertEmoji('💌')">💌</span>
              <span onclick="insertEmoji('💘')">💘</span>
              <span onclick="insertEmoji('💝')">💝</span>
              <span onclick="insertEmoji('💖')">💖</span>
              <span onclick="insertEmoji('💗')">💗</span>
              <span onclick="insertEmoji('💓')">💓</span>
              <span onclick="insertEmoji('💞')">💞</span>
              <span onclick="insertEmoji('💕')">💕</span>
              <span onclick="insertEmoji('💟')">💟</span>
              <span onclick="insertEmoji('❣️')">❣️</span>
              <span onclick="insertEmoji('💔')">💔</span>
              <span onclick="insertEmoji('❤️‍🔥')">❤️‍🔥</span>
              <span onclick="insertEmoji('❤️‍🩹')">❤️‍🩹</span>
              <span onclick="insertEmoji('❤️')">❤️</span>
              <span onclick="insertEmoji('🩷')">🩷</span>
              <span onclick="insertEmoji('🧡')">🧡</span>
              <span onclick="insertEmoji('💛')">💛</span>
              <span onclick="insertEmoji('💚')">💚</span>
              <span onclick="insertEmoji('💙')">💙</span>
              <span onclick="insertEmoji('🩵')">🩵</span>
              <span onclick="insertEmoji('💜')">💜</span>
              <span onclick="insertEmoji('🤎')">🤎</span>
              <span onclick="insertEmoji('🖤')">🖤</span>
              <span onclick="insertEmoji('🩶')">🩶</span>
              <span onclick="insertEmoji('🤍')">🤍</span>
              <span onclick="insertEmoji('🏧')">🏧</span>
              <span onclick="insertEmoji('🚮')">🚮</span>
              <span onclick="insertEmoji('🚰')">🚰</span>
              <span onclick="insertEmoji('♿')">♿</span>
              <span onclick="insertEmoji('🚹')">🚹</span>
              <span onclick="insertEmoji('🚺')">🚺</span>
              <span onclick="insertEmoji('🚻')">🚻</span>
              <span onclick="insertEmoji('🚼')">🚼</span>
              <span onclick="insertEmoji('🚾')">🚾</span>
              <span onclick="insertEmoji('🛂')">🛂</span>
              <span onclick="insertEmoji('🛃')">🛃</span>
              <span onclick="insertEmoji('🛄')">🛄</span>
              <span onclick="insertEmoji('🛅')">🛅</span>
              <span onclick="insertEmoji('🗣️')">🗣️</span>
              <span onclick="insertEmoji('👤')">👤</span>
              <span onclick="insertEmoji('👥')">👥</span>
              <span onclick="insertEmoji('🫂')">🫂</span>
              <span onclick="insertEmoji('👣')">👣</span>
              <span onclick="insertEmoji('⚠️')">⚠️</span>
              <span onclick="insertEmoji('🔴')">🔴</span>
              <span onclick="insertEmoji('🟠')">🟠</span>
              <span onclick="insertEmoji('🟡')">🟡</span>
              <span onclick="insertEmoji('🟢')">🟢</span>
              <span onclick="insertEmoji('🔵')">🔵</span>
              <span onclick="insertEmoji('🟣')">🟣</span>
              <span onclick="insertEmoji('🟤')">🟤</span>
              <span onclick="insertEmoji('⚫')">⚫</span>
              <span onclick="insertEmoji('⚪')">⚪</span>
              <span onclick="insertEmoji('🟥')">🟥</span>
              <span onclick="insertEmoji('🟧')">🟧</span>
              <span onclick="insertEmoji('🟨')">🟨</span>
              <span onclick="insertEmoji('🟩')">🟩</span>
              <span onclick="insertEmoji('🟦')">🟦</span>
              <span onclick="insertEmoji('🟪')">🟪</span>
              <span onclick="insertEmoji('🟫')">🟫</span>
              <span onclick="insertEmoji('⬛')">⬛</span>
              <span onclick="insertEmoji('⬜')">⬜</span>
              <span onclick="insertEmoji('◼️')">◼️</span>
              <span onclick="insertEmoji('◻️')">◻️</span>
              <span onclick="insertEmoji('◾')">◾</span>
              <span onclick="insertEmoji('◽')">◽</span>
              <span onclick="insertEmoji('🔶')">🔶</span>
              <span onclick="insertEmoji('🔷')">🔷</span>
              <span onclick="insertEmoji('🔸')">🔸</span>
              <span onclick="insertEmoji('🔹')">🔹</span>
              <span onclick="insertEmoji('🔺')">🔺</span>
              <span onclick="insertEmoji('🔻')">🔻</span>
              <span onclick="insertEmoji('💠')">💠</span>
              <span onclick="insertEmoji('🔘')">🔘</span>
              <span onclick="insertEmoji('🔳')">🔳</span>
              <span onclick="insertEmoji('🔲')">🔲</span>
              <span onclick="insertEmoji('💬')">💬</span>
              <span onclick="insertEmoji('👁️‍🗨️')">👁️‍🗨️</span>
              <span onclick="insertEmoji('🗨️')">🗨️</span>
              <span onclick="insertEmoji('🗯️')">🗯️</span>
              <span onclick="insertEmoji('💭')">💭</span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Sección para mostrar las publicaciones -->
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel-body">
          <div class="row">
            <?php
            $query = $conn->prepare("SELECT p.post_id AS id, p.content, p.date_posted, m.firstname, m.lastname, m.image AS profile_image,
            pi.image_path, pv.video_path, m.member_id, m.privacy
            FROM post p 
            LEFT JOIN members m ON m.member_id = p.member_id 
            LEFT JOIN post_images pi ON p.post_id = pi.post_id
            LEFT JOIN post_videos pv ON p.post_id = pv.post_id
            WHERE m.member_id NOT IN (SELECT blocked_id FROM blocked_users WHERE user_id = :session_id)
            AND m.member_id NOT IN (SELECT user_id FROM blocked_users WHERE blocked_id = :session_id)
            AND (m.privacy = 'public' OR m.member_id = :session_id 
                OR (m.privacy = 'private' AND EXISTS 
                    (SELECT 1 FROM friends 
                     WHERE (friends.my_id = :session_id AND friends.my_friend_id = m.member_id)
                     OR (friends.my_friend_id = :session_id AND friends.my_id = m.member_id))))
            ORDER BY p.post_id DESC");
            $query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
            $query->execute();
            while ($row = $query->fetch()) {
              $id = intval($row['id'] ?? 0);
              $posted_by = htmlspecialchars($row['firstname'] ?? '') . " " . htmlspecialchars($row['lastname'] ?? '');
              $profile_image = htmlspecialchars($row['profile_image'] ?? 'default_profile.png');
              $post_image = htmlspecialchars($row['image_path'] ?? '');
              $post_video = htmlspecialchars($row['video_path'] ?? '');
              $content = htmlspecialchars($row['content'] ?? '');
              $rawDate = $row['date_posted'] ?? '1970-01-01 00:00:00';
              $formattedDate = date('H:i - d/m/Y', strtotime($rawDate));
              $member_id = intval($row['member_id'] ?? 0);
            ?>
              <div class="panel">
                <!-- Autor de la publicación -->
                <div class="col-md-1" style="display: flex; align-items: center; width:100%; margin-top:10px">
                  <?php
                  $profile_link = ($member_id == $session_id) ? "profile.php" : "profile_friend.php";
                  ?>
                  <a href="<?php echo htmlspecialchars($profile_link); ?>?member_id=<?php echo intval($member_id); ?>" style="margin-right: 10px;">
                    <img src="<?php echo $profile_image; ?>" alt="User Profile" style="width:40px; height:40px; border-radius: 50%; object-fit: cover;">
                  </a>
                  <div style="margin-left: 1px;">
                    <h5 style="margin: 0">
                      <a href="<?php echo htmlspecialchars($profile_link); ?>?member_id=<?php echo intval($member_id); ?>" style="text-decoration: none; color: inherit;">
                        <?php echo $posted_by; ?>
                      </a>
                    </h5>
                    <p style="margin: 0; font-size: 12px; color: gray;"><?php echo $formattedDate; ?></p>
                  </div>
                </div>
                <!-- Contenido de la publicación -->
                <div class="col-md-12 col-sm-9" style="width:100%;margin-top: 10px">
                  <div class="alert" id="post-<?php echo $id; ?>">
                    <p><?php echo $content; ?></p>
                    <?php if (!empty($post_image)): ?>
                      <div class="post-image">
                        <center>
                          <img src="<?php echo $post_image; ?>" style="width:100%; height:auto; margin-top:10px; border-radius:10px;" alt="Post Image">
                        </center>
                      </div>
                    <?php elseif (!empty($post_video)): ?>
                      <div class="post-video">
                        <center>
                          <video src="<?php echo $post_video; ?>" controls style="max-width:100%; height:auto; margin-top:10px; border-radius:10px;"></video>
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
                        <!-- Edicion de la publicacion -->
                        <?php
                        $member_id = intval($row['member_id'] ?? 0); // ID dueño post
                        $id        = intval($row['id'] ?? 0);        // ID post
                        $rawDate   = $row['date_posted'] ?? '1970-01-01 00:00:00';
                        $postTimestamp = strtotime($rawDate);
                        $timeSincePost = time() - $postTimestamp;
                        $isEditable    = ($timeSincePost < 300);
                        ?>
                        <?php
                        // Comprobar si el post ya ha sido reportado
                        $check_reported = $conn->prepare("SELECT * FROM post_reports WHERE post_id = :post_id AND user_id = :user_id");
                        $check_reported->bindParam(':post_id', $id, PDO::PARAM_INT);
                        $check_reported->bindParam(':user_id', $session_id, PDO::PARAM_INT);
                        $check_reported->execute();
                        $report_exists = ($check_reported->rowCount() > 0);
                        if ($member_id == $session_id):
                          if ($isEditable) {
                        ?>
                            <a href="javascript:void(0);" class="btn btn-info"
                              style="padding: 6px 10px;"
                              onclick="openEditModal(<?php echo $id; ?>)">
                              <i class="fa fa-edit"></i>
                            </a>
                          <?php
                          } else {
                          ?>
                            <!-- Botón editar -->
                            <button class="btn btn-info" style="padding: 6px 10px;" disabled title="Tiempo para editar expirado">
                              <i class="fa fa-edit"></i>
                            </button>
                          <?php
                          }
                          ?>
                          <!-- Botón eliminar -->
                          <a href="delete_post.php?id=<?php echo $id; ?>" class="btn btn-danger" style="padding: 6px 10px;">
                            <i class="fa fa-trash"></i>
                          </a>
                          <?php
                        else:
                          // No es tu publicación, revisamos el reporte
                          if ($report_exists):
                          ?>
                            <!-- Botón reportar -->
                            <span class="btn btn-warning" style="padding: 6px 10px;" disabled>
                              <i class="fa fa-warning"></i>
                            </span>
                          <?php else: ?>
                            <button class="btn btn-warning" style="padding: 6px 10px;" data-toggle="modal" data-target="#reportModal<?php echo $id; ?>">
                              <i class="fa fa-warning"></i>
                            </button>
                        <?php endif;
                        endif; ?>
                        <!-- Botón compartir -->
                        <a href="javascript:void(0);" class="btn btn-primary" style="padding: 6px 10px;" onclick="openShareModal(<?php echo $id; ?>)">
                          <i class="fa fa-share"></i>
                        </a>
                      </div>
                      <!-- Formulario de reporte-->
                      <div class="modal fade" id="reportModal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel<?php echo $id; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <center>
                                <h5 class="modal-title" id="reportModalLabel<?php echo $id; ?>">Selecciona el tipo de reporte</h5>
                              </center>
                            </div>
                            <!-- Modal de reportes -->
                            <div class="modal-body">
                              <form action="report_post.php" method="POST">
                                <div class="form-group">
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
                      // Obtener todos los comentarios de la publicación ordenados de forma descendente
                      $comments_query = $conn->prepare("SELECT * FROM post_comments WHERE post_id = :post_id ORDER BY comment_date DESC");
                      $comments_query->bindParam(':post_id', $id, PDO::PARAM_INT);
                      $comments_query->execute();
                      $comments = $comments_query->fetchAll(PDO::FETCH_ASSOC);
                      $totalComments = count($comments);
                      $initialCount = 1; // Número de comentarios a mostrar inicialmente

                      if ($totalComments > 0) {
                        // Mostrar los primeros comentarios (visibles)
                        $visibleComments = array_slice($comments, 0, $initialCount);
                        foreach ($visibleComments as $comment) {
                          // Aquí se coloca el código actual para mostrar cada comentario
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

                          // Comprobar si el comentario ha sido reportado
                          $report_query = $conn->prepare("SELECT * FROM reports_comment WHERE comment_id = :comment_id");
                          $report_query->bindParam(':comment_id', $comment['comment_id'], PDO::PARAM_INT);
                          $report_query->execute();
                          $report_exists = ($report_query->rowCount() > 0);
                      ?>
                          <div class="alert comment-item">
                            <div class="comment">
                              <div class="comment-header" style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 10px">
                                <img src="<?php echo $comment_user_image; ?>" style="width:30px; height:30px; margin-right:5px" class="img-circle">
                                <strong><?php echo $comment_user_name; ?></strong>
                                <span style="margin-left: auto;"><?php echo $comment_date; ?></span>
                              </div>
                              <p><?php echo $comment_text; ?></p>
                              <hr>
                              <!-- Reacciones -->
                              <div class="reactions" style="display: flex; justify-content: space-between; align-items: center;">
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
                                    <h5 class="modal-title" style="color: black;">Selecciona el tipo de reporte</h5>
                                  </center>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <form action="report_comment.php" method="POST">
                                    <div class="form-group">
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
                          <?php }
                        // Si hay más comentarios, mostrar botón "Ver más"
                        if ($totalComments > $initialCount) {
                          echo '<button class="btn btn-link show-more-comments" style="margin-bottom:5px;">Ver más comentarios</button>';
                          echo '<div class="hidden-comments" style="display: none;">';
                          $hiddenComments = array_slice($comments, $initialCount);
                          foreach ($hiddenComments as $comment) {
                            $comment_user_id = $comment['user_id'];
                            $comment_text = htmlspecialchars($comment['comment_text']);
                            $comment_date = date('H:i - d/m/Y', strtotime($comment['comment_date']));
                            $comment_user_query = $conn->prepare("SELECT firstname, lastname, image FROM members WHERE member_id = :user_id");
                            $comment_user_query->bindParam(':user_id', $comment_user_id, PDO::PARAM_INT);
                            $comment_user_query->execute();
                            $comment_user = $comment_user_query->fetch();
                            $comment_user_name = $comment_user['firstname'] . " " . $comment_user['lastname'];
                            $comment_user_image = $comment_user['image'] ?? 'default_profile.png';
                            $reaction_query = $conn->prepare("SELECT COUNT(*) AS total_reactions FROM comment_reactions WHERE comment_id = :comment_id");
                            $reaction_query->bindParam(':comment_id', $comment['comment_id'], PDO::PARAM_INT);
                            $reaction_query->execute();
                            $reaction_count = $reaction_query->fetchColumn();
                            $report_query = $conn->prepare("SELECT * FROM reports_comment WHERE comment_id = :comment_id");
                            $report_query->bindParam(':comment_id', $comment['comment_id'], PDO::PARAM_INT);
                            $report_query->execute();
                            $report_exists = ($report_query->rowCount() > 0);
                          ?>
                            <div class="alert comment-item">
                              <div class="comment">
                                <div class="comment-header" style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 10px">
                                  <img src="<?php echo $comment_user_image; ?>" style="width:30px; height:30px; margin-right:5px" class="img-circle">
                                  <strong><?php echo $comment_user_name; ?></strong>
                                  <span style="margin-left: auto;"><?php echo $comment_date; ?></span>
                                </div>
                                <p><?php echo $comment_text; ?></p>
                                <hr>
                                <div class="reactions" style="display: flex; justify-content: space-between; align-items: center;">
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
                                      <h5 class="modal-title" style="color: black;">Selecciona el tipo de reporte</h5>
                                    </center>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="report_comment.php" method="POST">
                                      <div class="form-group">
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
                      <?php }
                          echo '<button class="btn btn-link show-less-comments" style="display: block; margin-top: 5px;">Ver menos comentarios</button>';
                          echo '</div>';
                        }
                      } else {
                        echo '<p style="text-align: center;">Aún no hay comentarios.</p>';
                      }
                      ?>
                    </div>
                    <!-- Área de texto de comentario -->
                    <form action="add_comment.php" method="POST" style="display: flex; align-items: center; width: 100%; margin: 0 auto;">
                      <input type="hidden" name="post_id" value="<?php echo $id; ?>" />
                      <textarea name="comment_text" class="form-control" placeholder="Escribe un comentario..." style="flex-grow: 1; margin-right: 10px; width: 100%; max-width: calc(100% - 45px);"></textarea>
                      <button type="submit" class="btn btn-primary" style="margin-top:10px; padding: 6px 10px;">
                        <i class="fa fa-paper-plane"></i>
                      </button>
                    </form>
                  </div>
                  <br>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal para editar post -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 10vh;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar contenido de la publicación</h5>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 1px;">
            <label for="editContent" style="font-weight: 600;">Contenido</label>
            <textarea id="editContent" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger"
            data-dismiss="modal"
            onclick="$('#editModal').modal('hide');">
            Cancelar <i class="fa fa-xmark"></i>
          </button>
          <button type="button" class="btn btn-success" id="saveEditBtn">
            Guardar cambios <i class="fa fa-save"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal para compartir la publicación -->
  <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 10vh;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shareModalLabel">Compartir Publicación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="share-links">
            <a id="fbShareLink" class="btn btn-primary mb-2" target="_blank" style="display: inline-block;">
              <i class="fa-brands fa-facebook"></i>
            </a>
            <a id="messengerLink" class="btn" target="_blank" style="background-color: #0084ff; color: #fff;">
              <i class="fa-brands fa-facebook-messenger"></i>
            </a>
            <a id="waShareLink" class="btn btn-success mb-2" target="_blank" style="display: inline-block;">
              <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a id="xShareLink" class="btn" target="_blank" style="background-color: #000; color: #fff;">
              <i class="fa-solid fa-x"></i>
            </a>
            <a id="rdShareLink" class="btn btn-danger" target="_blank" style="background-color: #ff4500; color: #fff;">
              <i class="fa-brands fa-reddit"></i>
            </a>
            <a id="tgShareLink" class="btn btn-info" target="_blank" style="background-color: #0088cc; color: #fff;">
              <i class="fa-brands fa-telegram"></i>
            </a>
            <a id="mailShareLink" class="btn btn-secondary" target="_blank" style="background-color:rgb(62, 220, 241); color: #fff;">
              <i class="fa-solid fa-envelope"></i>
            </a>
            <a id="gmailShareLink" class="btn" target="_blank" style="background-color: #db4437; color: #fff;">
              <i class="fa-solid fa-envelope"></i>
            </a>
            <button class="btn btn-dark" id="copyLinkBtn" style="background-color: #444;">
              <i class="fa-solid fa-copy"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<footer>
  <script src="View/JS/reaction.js"></script>
  <script src="View/JS/found_post.js"></script>
  <script src="View/JS/dark_mode.js"></script>
  <script src="View/JS/close_navbar.js"></script>
  <script src="View/JS/validate_post.js"></script>
  <script src="View/JS/text_area.js"></script>
  <script src="View/JS/preview_post_media.js"></script>
  <script src="View/JS/compress_comments.js"></script>
  <script src="View/JS/notifications.js"></script>
  <script src="View/JS/share_post.js"></script>
  <script src="View/JS/edit_post.js"></script>
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