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
	<link href="View/css/messages.css" rel="stylesheet">
	<link href="View/css/emojis_chat.css" type="text/css" rel="stylesheet">
	<link href="View/css/bootstrap.css" rel="stylesheet">
	<link href="View/css/my_style.css" type="text/css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="View/css/dark_mode.css" rel="stylesheet">
</head>

<body>
	<header class="navbar navbar-bright navbar-fixed-top" role="banner">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
					<i class="fa-solid fa-bars"></i>
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
					<li><a style="margin-right:0%" href="config_preferences.php"><i class="fa fa-gear"></i> Configuración</a></li>
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
					<li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
					<li style=" list-style: none;">
						<div class="navbar-form navbar-search" role="search">
							<form method="post" action="search.php" class="search-form">
								<div class="input-group">
									<input type="text" name="search" class="form-control search-query" placeholder="Buscar" style="width:290px;" required>
								</div>
							</form>
						</div>
					</li>
				</ul>
			</nav>
		</div>
	</header>
	<div class="container">
		<div class="row">
			<!-- Columna izquierda: Lista de contactos -->
			<div class="col-md-3" style="overflow-y: auto">
				<h4><b>Contactos</b></h4>
				<input type="text" id="search_contacts" class="form-control mb-3" placeholder="Buscar contacto...">
				<?php
				// Obtener los amigos con conversaciones activas ordenadas por la fecha del último mensaje
				$activeQuery = $conn->query("SELECT DISTINCT m.member_id, m.firstname, m.lastname, m.image,
            	(SELECT MAX(msg.date_sent) FROM messages AS msg
               	WHERE ((msg.sender_id = m.member_id AND msg.receiver_id = '$session_id' AND msg.deleted_for_receiver=0)OR(msg.receiver_id = m.member_id AND msg.sender_id = '$session_id' AND msg.deleted_for_sender=0))) 
				AS last_message_date FROM members AS m JOIN friends AS f ON (f.my_friend_id = m.member_id OR f.my_id = m.member_id)
        		JOIN messages AS msg2 ON (msg2.sender_id = m.member_id OR msg2.receiver_id = m.member_id) WHERE (f.my_friend_id = '$session_id' OR f.my_id = '$session_id')
          		AND m.member_id != '$session_id' AND m.member_id NOT IN (SELECT blocked_id FROM blocked_users WHERE user_id = '$session_id') AND m.member_id NOT IN (SELECT user_id FROM blocked_users WHERE blocked_id = '$session_id')
        		ORDER BY last_message_date DESC");
				// Amigos con convesarciones activas
				$activeFriends = [];
				while ($row = $activeQuery->fetch()) {
					if ($row['last_message_date'] !== null) {
						$activeFriends[] = $row['member_id'];
					}
				}
				// Todos los amigos
				$allFriendsQuery = $conn->query(" SELECT DISTINCT m.member_id, m.firstname, m.lastname, m.image
				FROM members AS m JOIN friends AS f ON (f.my_friend_id = m.member_id OR f.my_id = m.member_id)
				WHERE (f.my_friend_id = '$session_id' OR f.my_id = '$session_id')
				AND m.member_id != '$session_id' AND m.member_id NOT IN (SELECT blocked_id FROM blocked_users WHERE user_id = '$session_id')
				AND m.member_id NOT IN (SELECT user_id FROM blocked_users WHERE blocked_id = '$session_id')");
				?>
				<ul class="list-group" id="contacts_list">
					<?php
					// Conversaciones activas
					foreach ($activeFriends as $friend_id) {
						$activeQ = $conn->prepare("SELECT firstname, lastname, image FROM members WHERE member_id = :friend_id");
						$activeQ->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
						$activeQ->execute();
						$row = $activeQ->fetch();
						$friend_name = $row['firstname'] . " " . $row['lastname'];
					?>
						<li class="list-group-item contact-item" data-name="<?php echo strtolower($friend_name); ?>">
							<form method="post" action="" class="d-flex align-items-center justify-content-between w-100">
								<input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
								<!-- Botón abrir chat -->
								<button type="submit" class="btn btn-link text-start p-0">
									<img src="<?php echo $row['image']; ?>" style="width:30px; height:30px; border-radius:50%">
									<span class="friend-name"><?php echo htmlspecialchars($friend_name); ?></span>
								</button>
								<!-- Botón borrar chat -->
								<?php
								// Verificar si se ha solicitado la confirmación de eliminación
								if (isset($_POST['confirm_delete']) && isset($_POST['friend_id'])) {
									$friend_id = $_POST['friend_id'];
									echo "
									<div class='delete-confirmation'>
										<p>¿Estás seguro de que deseas eliminar esta conversación?</p>
										<form method='post'>
											<input type='hidden' name='friend_id' value='$friend_id'>
											<button type='submit' name='delete_conversation' class='btn btn-danger'>Sí, eliminar</button>
											<a href='' class='btn btn-secondary'>Cancelar</a>
										</form>
									</div>
									";
								}
								// Si el usuario confirma la eliminación, ejecutar el proceso
								if (isset($_POST['delete_conversation']) && isset($_POST['friend_id'])) {
									$friend_id = $_POST['friend_id'];
									$conn->beginTransaction();
									try {
										// Para mensajes que se enviaron
										$stmt1 = $conn->prepare("UPDATE messages SET deleted_for_sender = 1 WHERE sender_id = :session_id AND receiver_id = :friend_id AND deleted_for_sender = 0");
										$stmt1->bindParam(':session_id', $session_id, PDO::PARAM_INT);
										$stmt1->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
										$stmt1->execute();
										// Para mensajes que se recibieron
										$stmt2 = $conn->prepare("UPDATE messages SET deleted_for_receiver = 1 WHERE sender_id = :friend_id AND receiver_id = :session_id AND deleted_for_receiver = 0");
										$stmt2->bindParam(':session_id', $session_id, PDO::PARAM_INT);
										$stmt2->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
										$stmt2->execute();
										$conn->commit();
										echo "<script>window.location.href='';
										</script>";
									} catch (Exception $e) {
										$conn->rollBack();
										echo "<script>
										alert('Error al borrar conversación: " . addslashes($e->getMessage()) . "');
										window.location.href='';
										</script>";
									}
								}
								?>
								<!-- Botón borrar chat -->
								<form method="post" class="d-inline">
									<input type="hidden" name="friend_id" value="<?php echo $friend_id; ?>">
									<button type="submit" name="confirm_delete" class="btn btn-danger delete-btn" title="Borra el chat para ambos">
										<i class="fa fa-trash"></i>
									</button>
								</form>
							</form>
						</li>
						<?php } // Conversaciones inactivas
					while ($row = $allFriendsQuery->fetch()) {
						if (!in_array($row['member_id'], $activeFriends)) {
							$friend_name = $row['firstname'] . " " . $row['lastname'];
							$id = $row['member_id'];
						?>
							<li class="list-group-item contact-item hidden" data-name="<?php echo strtolower($friend_name); ?>">
								<form method="post" action="" class="d-flex align-items-center justify-content-between w-100">
									<input type="hidden" name="friend_id" value="<?php echo $id; ?>">
									<img src="<?php echo $row['image']; ?>" style="width:30px; height:30px; border-radius:50%">
									<button type="submit" class="btn btn-link text-start p-0">
										<?php echo htmlspecialchars($friend_name); ?>
									</button>
								</form>
							</li>
					<?php }
					}
					?>
				</ul>
			</div>
			<!-- Columna derecha: Bandeja de mensajes -->
			<div class="col-md-9 d-flex flex-column">
				<div class="chat-header text-center">
					<h4><b>Chat</b></h4>
				</div>
				<center>
					<div id="message-container" style="overflow-y: auto; max-height: 790px;">
						<!-- Chat dinámico -->
					</div>
				</center>
				<!-- Área para enviar mensajes -->
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
									<textarea id="my_message" name="my_message" class="form-control me-2 chat-input" rows="1" placeholder="Escribe tu mensaje..." required style="width: 450px !important; border-radius: 20px; resize: none; border:none;"></textarea>
									<!-- Botón para mostrar el picker de emojis -->
									<button type="button" id="emoji-btn" class="btn btn-light" onclick="toggleEmojiPicker()" style="border-radius: 20px; margin-left: 5px; margin-top: 0px; background-color:#ffff51">
										<i class="fa fa-smile"></i>
									</button>
									<!-- Botón para enviar el mensaje -->
									<button id="send_button" class="btn btn-success" style="border-radius: 20px; margin-left: 5px; margin-top: 0px;" type="button">
										<i class="fa fa-paper-plane"></i> Enviar
									</button>
								</div>
							</center>
						</form>
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