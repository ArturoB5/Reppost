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
					<li><a style="margin-right:0%" href="friends.php"><i class="fa fa-users"></i> Amigos</a></li>
					<li><a style="margin-right:0%" href="message.php"><i class="fa fa-comment"></i> Chat</a></li>
					<li><a style="margin-right:0%" href="paintarea.php"><i class="fa fa-pencil"></i> Pizarra</a></li>
					<li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Cerrar Sesión</a></li>
					<li><button id="darkModeToggle" class="btn btn-light" style="padding: 14px 5px;"><i class="fa fa-moon"></i></button></li>
				</ul>
				<div class="navbar-form navbar-search" role="search">
					<form method="post" action="search.php" class="search-form">
						<div class="input-group">
							<input type="text" name="search" class="form-control search-query" id="span5" placeholder="Buscar" style="margin-left: 125px;">
						</div>
					</form>
				</div>
			</nav>
		</div>
	</header>
	<div class="container">
		<div class="row">
			<!-- Columna izquierda: Contactos -->
			<div class="col-md-3" style="border-right: 1px solid #ddd; height: 100vh; overflow-y: auto;">
				<h4><b>Contactos</b></h4>
				<input type="text" id="search_contacts" class="form-control mb-3" placeholder="Buscar contacto...">
				<ul class="list-group" id="contacts_list">
					<?php
					$query = $conn->query("
                    SELECT members.member_id, 
                           members.firstname, 
                           members.lastname, 
                           members.image 
                    FROM members
                    JOIN friends 
                        ON (friends.my_friend_id = members.member_id OR friends.my_id = members.member_id)
                    WHERE (friends.my_friend_id = '$session_id' OR friends.my_id = '$session_id')
                    AND members.member_id != '$session_id'
                ");
					while ($row = $query->fetch()) {
						$friend_name = $row['firstname'] . " " . $row['lastname'];
						$id = $row['member_id'];
					?>
						<li class="list-group-item contact-item" data-name="<?php echo strtolower($friend_name); ?>">
							<form method="post" action="" class="d-flex align-items-center">
								<input type="hidden" name="friend_id" value="<?php echo $id; ?>">
								<img src="<?php echo $row['image']; ?>" style="width:30px; height:30px; border-radius:50%">
								<button type="submit" class="btn btn-link text-start p-0">
									<?php echo htmlspecialchars($friend_name); ?>
								</button>
							</form>
						</li>
					<?php } ?>
				</ul>
			</div>
			<!-- Columna derecha: Bandeja de mensajes -->
			<div class="col-md-9 d-flex flex-column">
				<div class="flex-grow-1">
					<center>
						<h4><b>Bandeja de mensajes</b></h4>
					</center>
					<center>
						<div id="message-container" style="overflow-y: auto; max-height: 750px;">
							<?php
							if (isset($_POST['friend_id'])) {
								$friend_id = intval($_POST['friend_id']);
								$query = $conn->prepare("
                    SELECT message.*, sender.firstname AS sender_firstname, sender.lastname AS sender_lastname,
                        receiver.firstname AS receiver_firstname, receiver.lastname AS receiver_lastname
                    FROM message
                    LEFT JOIN members AS sender ON message.sender_id = sender.member_id
                    LEFT JOIN members AS receiver ON message.receiver_id = receiver.member_id
                    WHERE (sender_id = :session_id AND receiver_id = :friend_id)
                    OR (sender_id = :friend_id AND receiver_id = :session_id)
                    ORDER BY date_sended ASC
                ");
								$query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
								$query->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
								$query->execute();
								$messages = $query->fetchAll();
								if ($messages) {
									foreach ($messages as $row) {
										$is_sent = $row['sender_id'] == $session_id;
							?>
										<div class="mb-3">
											<!-- Alineación del mensaje -->
											<div style="color: #fff; background-color: 
										<?php echo $is_sent ? '#008568' : '#5e7671'; ?>; text-align: 
										<?php echo $is_sent ? 'right' : 'left'; ?>; margin-
										<?php echo $is_sent ? 'right' : 'left'; ?>; padding: 15px; border-radius: 20px; max-width: 70%; margin-bottom: 25px;
										<?php echo $is_sent ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;'; ?>">
												<?php echo htmlspecialchars($row['content']); ?>
												<div class="small text-muted mt-2">
													<?php
													// Convierte la fecha 'date_sended' al formato deseado
													$date_sended = new DateTime($row['date_sended']);
													echo $date_sended->format('H:i - d/m/Y');
													?>
												</div>
											</div>
										</div>
							<?php
									}
								} else {
									echo "<p>No hay mensajes en esta conversación.</p>";
								}
							} else {
								echo "<p>Selecciona un amigo para ver tu chat.</p>";
							}
							?>
						</div>
					</center>
				</div>
			</div>
			<!-- Área de texto fija para enviar mensajes -->
			<div style="border-top: 1px solid #ddd;">
				<form method="post" id="send_message" action="send_message.php" class="d-flex">
					<input type="hidden" name="friend_id" value="<?php echo isset($_POST['friend_id']) ? intval($_POST['friend_id']) : ''; ?>">
					<center>
						<div class="input-group w-100">
							<textarea name="my_message" class="form-control me-2" rows="1" placeholder="Escribe un mensaje..." required style="width: 450px !important; border-radius: 20px; resize: none;"></textarea>
							<button class="btn btn-success" style="border-radius: 20px; margin-left: 10px; margin-top:10px" type="submit"><i class="fa fa-paper-plane"></i> Enviar</button>
						</div>
					</center>
				</form>
			</div>
		</div>
	</div>
</body>
<footer>
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/message.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>

</html>