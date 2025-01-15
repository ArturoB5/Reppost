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
	<link href="View/css/textarea.css" rel="stylesheet">
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
							<span id="notification-badge" class="badge badge-danger" style="position: absolute; width: 16px; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 10px; display: none;">0</span>
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
	<!-- seccion info principal del perfil -->
	<div id="masthead">
		<div class="container">
			<div class="col-md-12 text-center"
				style="background-image: url(View/Images/app_images/background.jpeg);
                    background-size: cover; 
                    background-position: center; 
                    background-repeat: no-repeat;
                    border-bottom-left-radius: 20px; 
                    border-bottom-right-radius: 20px;">
				<br>
				<img src="<?php echo $image; ?>" style="border-radius: 50%; height:150px; width:150px;">
				<br><br>
			</div>
			<div class="profile-info">
				<div class="col-md-12">
					<div style="position: relative; text-align: center;">
						<h3><strong>Información General</strong></h3><br>
						<?php
						$query = $conn->query("SELECT * FROM members WHERE member_id = '$session_id'");
						$row = $query->fetch();
						$fullName = htmlspecialchars($row['firstname'] . " " . $row['lastname']);
						if ($row['role'] === 'moderador') {
							$fullName .= ' <span style="color:#11a39c; font-style: italic; padding-left: 0px;">(Moderador) <i class="fa-solid fa-star"></i></span>';
							echo '<img src="View\\Images\\app_images\\mod.png" 
									   alt="Moderador" 
									   title="MODERADOR"
									   style="position: absolute; top: -10px; right: 10px; width: 40px; cursor: pointer;" />';
						}
						$token_query = $conn->prepare("SELECT SUM(token_reward) AS total_tokens FROM post WHERE member_id = :session_id");
						$token_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
						$token_query->execute();
						$token_row = $token_query->fetch();
						$total_tokens = $token_row['total_tokens'] ? $token_row['total_tokens'] : 0;
						?>
						<p><span class="info-label"><strong>Nombre de usuario:</strong></span> <?php echo htmlspecialchars($row['username']); ?></p>
						<p><span class="info-label"><strong>Nombre completo:</strong></span> <?php echo $fullName; ?></p>
						<p><span class="info-label"><strong>Correo electrónico:</strong></span> <?php echo htmlspecialchars($row['email']); ?></p>
						<p><span class="info-label"><strong>Fecha de nacimiento:</strong></span>
							<?php
							$birthdate = $row['birthdate'];
							$formattedDate = date('d / m / Y - ', strtotime($birthdate));
							$birthDateObject = new DateTime($birthdate);
							$currentDate = new DateTime();
							$age = $birthDateObject->diff($currentDate)->y;
							echo htmlspecialchars($formattedDate) . " (" . $age . " años)";
							?>
						</p>
						<?php
						$countriesFull = [
							"AR" => "Argentina",
							"BO" => "Bolivia",
							"BR" => "Brasil",
							"CL" => "Chile",
							"CO" => "Colombia",
							"EC" => "Ecuador",
							"GY" => "Guyana",
							"PY" => "Paraguay",
							"PE" => "Perú",
							"SR" => "Surinam",
							"UY" => "Uruguay",
							"VE" => "Venezuela"
						];
						$countryCode = $row['country'];
						$countryName = isset($countriesFull[$countryCode]) ? $countriesFull[$countryCode] : "Desconocido";
						?>
						<p><span class="info-label"><strong>Número de celular:</strong></span> <?php echo htmlspecialchars($row['mobile']); ?></p>
						<p><span class="info-label"><strong>Género:</strong></span> <?php echo htmlspecialchars($row['gender']); ?></p>
						<p><span class="info-label"><strong>País:</strong></span> <?php echo htmlspecialchars($countryName); ?></p>
						<p><span class="info-label"><strong>Ciudad:</strong></span> <?php echo htmlspecialchars($row['city']); ?></p>
						<p><span class="info-label"><strong>Ocupación:</strong></span> <?php echo htmlspecialchars($row['work']); ?></p>
						<p><span class="info-label"><strong>Total de tokens:</strong></span> <?php echo htmlspecialchars(number_format($total_tokens, 8)); ?></p><br>
						<div class="text-center mt-3">
							<a href="edit_profile.php" class="btn btn-info"><i class="fa fa-edit"></i> Editar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><br>
	<!-- seccion amigos -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel" style="border-radius: 30px;">
					<div class="panel-body" style="margin-top: -40px;">
						<div class="col-md-12 text-center">
							<br>
							<?php
							// Consulta para contar el número total de amigos
							$count_query = $conn->prepare("
									SELECT COUNT(DISTINCT m.member_id) AS total_friends
									FROM members AS m
									JOIN friends AS f
									ON (m.member_id = f.my_friend_id OR m.member_id = f.my_id)
									WHERE (f.my_friend_id = :session_id OR f.my_id = :session_id)
									AND m.member_id != :session_id
									AND m.member_id NOT IN (
									SELECT blocked_id 
									FROM blocked_users 
									WHERE user_id = :session_id
									)
									AND m.member_id NOT IN (
									SELECT user_id 
									FROM blocked_users 
									WHERE blocked_id = :session_id
									)
								");
							$count_query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
							$count_query->execute();
							$friend_count = $count_query->fetchColumn(); // Total de amigos
							?>
							<h3>Amigos (<?php echo $friend_count; ?>)</h3>
							<?php
							// Consulta para obtener los amigos
							$query = $conn->query("
								SELECT m.member_id,
										m.firstname,
										m.lastname,
										m.image,
										f.friends_id
								FROM members AS m
								JOIN friends AS f
									ON (m.member_id = f.my_friend_id OR m.member_id = f.my_id)
								WHERE (f.my_friend_id = '$session_id' OR f.my_id = '$session_id')
								AND m.member_id != '$session_id'
								-- EXCLUYE los usuarios que el session_id haya bloqueado
								AND m.member_id NOT IN (
									SELECT blocked_id 
									FROM blocked_users 
									WHERE user_id = '$session_id'
								)
								-- EXCLUYE los usuarios que hayan bloqueado al session_id
								AND m.member_id NOT IN (
									SELECT user_id 
									FROM blocked_users 
									WHERE blocked_id = '$session_id'
								)
								ORDER BY f.friends_id DESC
								LIMIT 6
								");
							while ($row = $query->fetch()) {
								$friend_name  = $row['firstname'] . ' ' . $row['lastname'];
								$friend_image = $row['image'];
								$friend_id    = $row['member_id']; // ID del amigo
							?>
								<div class="col-6 col-sm-4 col-md-2 text-center">
									<a href="profile_friend.php?member_id=<?php echo $friend_id; ?>">
										<img src="<?php echo $friend_image; ?>"
											alt="<?php echo $friend_name; ?>"
											class="img-thumbnail"
											style="width:100px;height:100px;border-radius: 50%;">
									</a>
									<p class="mt-2"><?php echo $friend_name; ?></p>
									<a href="delete_friend.php?id=<?php echo $friend_id; ?>"
										class="btn btn-danger">
										<i class="fa fa-user-minus"></i> Eliminar
									</a>
								</div>
							<?php } ?>
						</div>
					</div>
					<center>
						<a href="friends.php" class="btn btn-primary btn-sm" style="border-radius: 15px;">
							Ver Todos
						</a>
					</center>
					<br>
				</div>
			</div>
		</div>
	</div>
	<!-- seccion fotos -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel" style="border-radius: 30px;">
					<div class="panel-body" style="margin-top: 95px;">
						<form id="photos" method="POST" enctype="multipart/form-data">
							<h2 class="text-center">Mi galería de fotos</h2>

							<div class="upload-container">
								<label class="upload-btn">
									<i class="fa fa-paperclip"></i>Seleccionar
									<input type="file" name="image" accept="image/*" class="form-control" required onchange="previewImage(event)">
								</label>
								<div class="button-container">
									<button type="submit" name="submit" class="btn btn-success">
										<i class="fa fa-upload"></i>Subir Foto
									</button>
								</div>
							</div>
							<div class="form-group mt-3">
								<center>
									<div style="position: relative; display: inline-block;">
										<img id="imagePreview" alt="Previsualización" style="display: none; max-width: 250px; height: auto; margin-top: 10px; border-radius: 8px;" />
										<button id="removeImageButton" onclick="removeImage()" style="display: none; position: absolute; top: 15px; right: 5px; background-color: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; z-index: 10;">&times;</button>
									</div>
								</center>
							</div>
							<hr>
							<?php
							if (isset($_POST['submit']) && isset($_FILES['image'])) {
								$imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
								$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
								if (in_array($imageFileType, $allowedTypes)) {
									$targetDir = "View/Images/gallery-uploads/";
									$image_name = uniqid() . "." . $imageFileType;
									$targetFile = $targetDir . $image_name;
									if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
										$stmt = $conn->prepare("INSERT INTO photos (location, member_id) VALUES (?, ?)");
										$stmt->execute([$targetFile, $session_id]);
										// Redirigir para limpiar el POST
										echo "<script>window.location = 'profile.php';</script>";
										exit;
									} else {
										echo "Error al subir la imagen.";
									}
								} else {
									echo "Formato de archivo no permitido.";
								}
							}
							?>
							<div class="row">
								<?php
								// Obtener las fotos del usuario actual
								$query = $conn->prepare("SELECT * FROM photos WHERE member_id = ?");
								$query->execute([$session_id]);
								while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
									$id = htmlspecialchars($row['photos_id']); // Escapar el ID
									$location = htmlspecialchars($row['location']); // Escapar la ruta
								?>
									<div class="col-md-3 col-sm-6 text-center">
										<img class="photo" src="<?php echo $location; ?>" style="width: 250px; height: 250px;"><br><br>
										<a style="margin-right: 0%;" class="btn btn-danger" href="delete_photos.php?id=<?php echo $id; ?>">
											<i class="fa fa-trash"></i> Borrar
										</a>
									</div>
								<?php
								}
								?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<footer>
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/preview_photo_gallery.js"></script>
	<script src="View/JS/notifications.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>
<style>
	.profile-info {
		margin-top: 20px;
		background-color: white;
		padding: 20px;
		border-radius: 30px;
	}

	span {
		display: inline-block;
		padding-left: 25px;
		font-weight: bold;
		white-space: nowrap;
		margin-left: 10px;
		width: 250px;
		text-align: left;
	}

	.upload-container {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 15px;
	}

	.upload-btn,
	.button-container button {
		width: 150px;
		max-width: 200px;
		height: 50px;
		border-radius: 8px;
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
	}

	.upload-btn {
		background-color: #007bff;
		color: #fff;
		cursor: pointer;
	}

	.button-container button {
		background-color: #28a745;
		border: 1px solid #28a745;
		color: white;
		cursor: pointer;
	}

	.upload-btn:hover {
		background-color: #0061c9;
		color: white;
	}

	.button-container button:hover {
		background-color: #218838;
		color: white;
	}

	#removeImageButton {
		display: none;
		position: absolute;
		top: 5px;
		right: 5px;
		background-color: red;
		color: white;
		border: none;
		border-radius: 50%;
		width: 25px;
		height: 25px;
		cursor: pointer;
		z-index: 10;
	}

	#imagePreview {
		display: none;
		max-width: 250px;
		height: auto;
		margin-top: 5px;
		border-radius: 8px;
	}
</style>

</html>