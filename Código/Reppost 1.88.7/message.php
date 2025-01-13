<?php include('Config/dbcon.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Reppost</title>
	<link href="View/Images/app_images/logo.ico" type="image/x-icon" rel="icon">
	<link href="View/css/bootstrap.min.css" rel="stylesheet">
	<link href="View/css/my_style.css" type="text/css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="View/css/dark_mode.css" rel="stylesheet">
</head>
<?php include('Controller/Backend/session.php'); ?>

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
	<div class="container">
		<div class="row">
			<div class="col-md-3" style="border-right: 1px solid #ddd; height: 94vh; overflow-y: auto">
				<h4><b>Contactos</b></h4>
				<input type="text" id="search_contacts" class="form-control mb-3" placeholder="Buscar contacto...">
				<?php
				// Obtener los amigos con conversaciones activas ordenadas por la fecha del último mensaje
				$activeQuery = $conn->query("
					SELECT DISTINCT members.member_id, 
							members.firstname, 
							members.lastname, 
							members.image,
							(SELECT MAX(date_sended) 
								FROM message 
								WHERE (sender_id = members.member_id AND receiver_id = '$session_id') 
								OR (receiver_id = members.member_id AND sender_id = '$session_id')
							) AS last_message_date
						FROM members
						JOIN friends 
							ON (friends.my_friend_id = members.member_id OR friends.my_id = members.member_id)
						JOIN message 
							ON (message.sender_id = members.member_id OR message.receiver_id = members.member_id)
						WHERE (friends.my_friend_id = '$session_id' OR friends.my_id = '$session_id')
						AND members.member_id != '$session_id'
						AND (message.sender_id = '$session_id' OR message.receiver_id = '$session_id')
						-- AÑADIR EXCLUSIÓN DE BLOQUEADOS
						AND members.member_id NOT IN (
							SELECT blocked_id 
							FROM blocked_users
							WHERE user_id = '$session_id'
						)
						AND members.member_id NOT IN (
							SELECT user_id
							FROM blocked_users
							WHERE blocked_id = '$session_id'
						)
						ORDER BY last_message_date DESC
				");
				// Almacenar IDs de amigos con conversaciones activas
				$activeFriends = [];
				while ($row = $activeQuery->fetch()) {
					$activeFriends[] = $row['member_id'];
				}
				// Todos los amigos
				$allFriendsQuery = $conn->query("
					SELECT DISTINCT 
							members.member_id, 
							members.firstname, 
							members.lastname, 
							members.image 
						FROM members
						JOIN friends 
							ON (friends.my_friend_id = members.member_id OR friends.my_id = members.member_id)
						WHERE (friends.my_friend_id = '$session_id' OR friends.my_id = '$session_id')
						AND members.member_id != '$session_id'
						-- AÑADIR EXCLUSIÓN DE BLOQUEADOS
						AND members.member_id NOT IN (
							SELECT blocked_id 
							FROM blocked_users
							WHERE user_id = '$session_id'
						)
						AND members.member_id NOT IN (
							SELECT user_id
							FROM blocked_users
							WHERE blocked_id = '$session_id'
						)
				");
				?>
				<ul class="list-group" id="contacts_list">
					<?php
					// Mostrar amigos con conversaciones activas
					foreach ($activeFriends as $friend_id) {
						$activeQuery = $conn->query("
							SELECT firstname, lastname, image 
							FROM members 
							WHERE member_id = '$friend_id'
						");
						$row = $activeQuery->fetch();
						$friend_name = $row['firstname'] . " " . $row['lastname'];
					?>
						<li class="list-group-item contact-item" data-name="<?php echo strtolower($friend_name); ?>">
							<form method="post" action="" class="d-flex align-items-center justify-content-between w-100">
								<input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
								<img src="<?php echo $row['image']; ?>" style="width:30px; height:30px; border-radius:50%">
								<button type="submit" class="btn btn-link text-start p-0">
									<?php echo htmlspecialchars($friend_name); ?>
								</button>
								<!-- Botón para borrar la conversación -->
								<button type="submit" name="delete_conversation" value="<?php echo $friend_id; ?>" class="btn btn-danger" title="Borra el chat para ambos">
									<i class="fa fa-trash"></i>
								</button>
							</form>
						</li>
					<?php } ?>
					<?php
					// Mostrar amigos sin conversaciones activas
					while ($row = $allFriendsQuery->fetch()) {
						if (!in_array($row['member_id'], $activeFriends)) {
							$friend_name = $row['firstname'] . " " . $row['lastname'];
							$id = $row['member_id'];
					?>
							<li class="list-group-item contact-item hidden" data-name="<?php echo strtolower($friend_name); ?>">
								<form method="post" action="" class="d-flex align-items-center">
									<input type="hidden" name="friend_id" value="<?php echo $id; ?>">
									<img src="<?php echo $row['image']; ?>" style="width:30px; height:30px; border-radius:50%">
									<button type="submit" class="btn btn-link text-start p-0">
										<?php echo htmlspecialchars($friend_name); ?>
									</button>
								</form>
							</li>
					<?php }
					} ?>
				</ul>
			</div>
			<?php
			// Borrar conversación
			if (isset($_POST['delete_conversation'])) {
				$friend_id = $_POST['delete_conversation'];
				// Eliminar historial entre el usuario y amigo seleccionado
				$deleteQuery = $conn->prepare("
					DELETE FROM message 
					WHERE (sender_id = :session_id AND receiver_id = :friend_id) 
					OR (sender_id = :friend_id AND receiver_id = :session_id)
				");
				$deleteQuery->bindParam(':session_id', $session_id);
				$deleteQuery->bindParam(':friend_id', $friend_id);
				$deleteQuery->execute();
				// Redirigir o mostrar mensaje de éxito
				echo "<script>alert('Conversación borrada correctamente'); window.location.href='';</script>";
			}
			?>
			<!-- Columna derecha: Bandeja de mensajes -->
			<div class="col-md-9 d-flex flex-column">
				<div class="flex-grow-1">
					<center>
						<h4><b>Chat</b></h4>
					</center>
					<center>
						<center>
							<p>Los mensajes están cifrados de extremo a extremo</p>
						</center>
						<div id="message-container" style="overflow-y: auto; max-height: 800px;">
							<!-- Aqui se uestran los mensajes dinamicamente -->
						</div>
					</center>
				</div>
			</div>
			<!-- Área de texto para enviar mensajes -->
			<?php if (isset($_POST['friend_id'])) { ?>
				<div>
					<form method="post" id="send_message" class="d-flex">
						<input type="hidden" id="friend_id" name="friend_id" value="<?php echo intval($_POST['friend_id']); ?>">
						<center>
							<div class="input-group w-100" style="position: relative;">
								<div id="emoji-picker" style="display: none; position: absolute; left:0px; bottom:-5px; z-index: 999; background-color: #fff; padding: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 10px; margin-bottom: 5px; max-height: 130px; overflow-y: auto;">
									<!-- Secciones de emojis -->
									<div id="emoji-categories">
										<!-- Botones para cambiar de categoría -->
										<button class="emoji-category-btn" onclick="showCategory('faces')">Caras</button>
										<button class="emoji-category-btn" onclick="showCategory('animals')">Animales</button>
										<button class="emoji-category-btn" onclick="showCategory('things')">Cosas</button>
										<button class="emoji-category-btn" onclick="showCategory('food')">Comida</button>
										<button class="emoji-category-btn" onclick="showCategory('symbols')">Símbolos</button>
										<button class="emoji-category-btn" onclick="showCategory('nature')">Naturaleza</button>
									</div>
									<!-- Categoría de Caras -->
									<div class="emoji-category faces" style="display: block;">
										<button type="button" onclick="insertEmoji('😀')">😀</button>
										<button type="button" onclick="insertEmoji('😂')">😂</button>
										<button type="button" onclick="insertEmoji('😍')">😍</button>
										<button type="button" onclick="insertEmoji('😎')">😎</button>
										<button type="button" onclick="insertEmoji('🥺')">🥺</button>
										<button type="button" onclick="insertEmoji('😢')">😢</button>
										<button type="button" onclick="insertEmoji('😡')">😡</button>
										<button type="button" onclick="insertEmoji('😜')">😜</button>
										<button type="button" onclick="insertEmoji('😊')">😊</button>
										<button type="button" onclick="insertEmoji('😏')">😏</button>
									</div>
									<!-- Categoría de Animales -->
									<div class="emoji-category animals" style="display: none;">
										<button type="button" onclick="insertEmoji('🐶')">🐶</button>
										<button type="button" onclick="insertEmoji('🐱')">🐱</button>
										<button type="button" onclick="insertEmoji('🐯')">🐯</button>
										<button type="button" onclick="insertEmoji('🐮')">🐮</button>
										<button type="button" onclick="insertEmoji('🐷')">🐷</button>
										<button type="button" onclick="insertEmoji('🐸')">🐸</button>
										<button type="button" onclick="insertEmoji('🐵')">🐵</button>
										<button type="button" onclick="insertEmoji('🐘')">🐘</button>
										<button type="button" onclick="insertEmoji('🦁')">🦁</button>
										<button type="button" onclick="insertEmoji('🐼')">🐼</button>
									</div>
									<!-- Categoría de Cosas -->
									<div class="emoji-category things" style="display: none;">
										<button type="button" onclick="insertEmoji('🍎')">🍎</button>
										<button type="button" onclick="insertEmoji('🍔')">🍔</button>
										<button type="button" onclick="insertEmoji('🚗')">🚗</button>
										<button type="button" onclick="insertEmoji('📱')">📱</button>
										<button type="button" onclick="insertEmoji('💻')">💻</button>
										<button type="button" onclick="insertEmoji('🎧')">🎧</button>
										<button type="button" onclick="insertEmoji('📚')">📚</button>
										<button type="button" onclick="insertEmoji('🎮')">🎮</button>
										<button type="button" onclick="insertEmoji('🖊️')">🖊️</button>
										<button type="button" onclick="insertEmoji('🕶️')">🕶️</button>
									</div>
									<!-- Categoría de Comida -->
									<div class="emoji-category food" style="display: none;">
										<button type="button" onclick="insertEmoji('🍕')">🍕</button>
										<button type="button" onclick="insertEmoji('🍩')">🍩</button>
										<button type="button" onclick="insertEmoji('🍣')">🍣</button>
										<button type="button" onclick="insertEmoji('🍪')">🍪</button>
										<button type="button" onclick="insertEmoji('🍦')">🍦</button>
										<button type="button" onclick="insertEmoji('🍔')">🍔</button>
										<button type="button" onclick="insertEmoji('🍺')">🍺</button>
										<button type="button" onclick="insertEmoji('🍇')">🍇</button>
										<button type="button" onclick="insertEmoji('🍉')">🍉</button>
										<button type="button" onclick="insertEmoji('🥑')">🥑</button>
									</div>
									<!-- Categoría de Símbolos -->
									<div class="emoji-category symbols" style="display: none;">
										<button type="button" onclick="insertEmoji('❤️')">❤️</button>
										<button type="button" onclick="insertEmoji('💔')">💔</button>
										<button type="button" onclick="insertEmoji('🔥')">🔥</button>
										<button type="button" onclick="insertEmoji('✨')">✨</button>
										<button type="button" onclick="insertEmoji('👍')">👍</button>
										<button type="button" onclick="insertEmoji('👎')">👎</button>
										<button type="button" onclick="insertEmoji('✔️')">✔️</button>
										<button type="button" onclick="insertEmoji('❌')">❌</button>
										<button type="button" onclick="insertEmoji('⚡')">⚡</button>
										<button type="button" onclick="insertEmoji('💡')">💡</button>
									</div>
									<!-- Categoría de Naturaleza -->
									<div class="emoji-category nature" style="display: none;">
										<button type="button" onclick="insertEmoji('🌸')">🌸</button>
										<button type="button" onclick="insertEmoji('🌳')">🌳</button>
										<button type="button" onclick="insertEmoji('🌵')">🌵</button>
										<button type="button" onclick="insertEmoji('🌻')">🌻</button>
										<button type="button" onclick="insertEmoji('🌞')">🌞</button>
										<button type="button" onclick="insertEmoji('🌧️')">🌧️</button>
										<button type="button" onclick="insertEmoji('🌨️')">🌨️</button>
										<button type="button" onclick="insertEmoji('🌲')">🌲</button>
										<button type="button" onclick="insertEmoji('🌊')">🌊</button>
										<button type="button" onclick="insertEmoji('🌝')">🌝</button>
									</div>
								</div>
								<!-- Área de texto -->
								<textarea id="my_message" name="my_message" class="form-control me-2" rows="1" placeholder="Escribe tu mensaje..." required style="width: 450px !important; border-radius: 20px; resize: none;"></textarea>
								<!-- Botón para mostrar el picker de emojis -->
								<button type="button" id="emoji-btn" class="btn btn-light" onclick="toggleEmojiPicker()" style="border-radius: 20px; margin-left: 5px; margin-top: 15px; background-color:#ffff51">
									<i class="fa fa-smile"></i>
								</button>
								<!-- Botón para enviar el mensaje -->
								<button id="send_button" class="btn btn-success" style="border-radius: 20px; margin-left: 5px; margin-top: 15px;" type="button">
									<i class="fa fa-paper-plane"></i> Enviar
								</button>
							</div>
						</center>
					</form><br>
				</div>
			<?php } ?>
		</div>
	</div>
	<script>
		const sessionId = <?php echo json_encode($session_id); ?>;
	</script>
</body>
<footer>
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/notifications.js"></script>
	<script src="View/JS/search_friends_messages.js"></script>
	<script src="View/JS/text_area_messages.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>

</html>
<style>
	#emoji-categories {
		margin-bottom: 10px;
	}

	.emoji-category-btn {
		background-color: #f1f1f1;
		border: none;
		padding: 5px 10px;
		margin: 0 5px;
		border-radius: 5px;
		cursor: pointer;
	}

	.emoji-category-btn:hover {
		background-color: #e0e0e0;
	}

	.emoji-category {
		display: none;
		padding: 5px 0;
	}

	.emoji-category button {
		font-size: 20px;
		padding: 5px;
		margin: 5px;
		background-color: transparent;
		border: none;
		cursor: pointer;
	}

	.emoji-category button:hover {
		background-color: #f1f1f1;
	}
</style>