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
	<link href="View/css/textarea.css" rel="stylesheet">
	<link href="View/css/bootstrap.css" rel="stylesheet">
	<link href="View/css/my_style.css" type="text/css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="View/css/style_regform.css" type="text/css" rel="stylesheet" />
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
		<!-- Cambio de imágen -->
		<div class="panel text-center" style="margin-top: 20px;">
			<div class="col-md-3">
				<center><img src="<?php echo $image; ?>" style="border-radius: 50%; display: block; margin-top:25px; height:180px; width:180px"></center>
			</div>
			<div class="col-md-5" style="margin-top:20px;">
				<?php
				$query = $conn->query("SELECT * FROM members WHERE member_id = '$session_id'");
				$row = $query->fetch();
				$id = $row['member_id'];
				?>
				<form id="upload_image" class="form-horizontal mt-3" method="POST" enctype="multipart/form-data">

					<h3>Cambia tu foto</h3>
					<label class="upload-btn" style="width:auto; margin-bottom:5px">
						<i class="fa fa-paperclip"></i> Selecciona
						<input type="file" name="image" accept="image/*" class="btn btn-default" id="imageInput" onchange="previewImage(event)">
					</label>
					<!-- Botones en línea -->
					<center>
						<div class="form-group mt-3">
							<button type="submit" name="submit" class="btn btn-success">
								<i class="fa fa-upload"></i> Subir Foto
							</button>
							<button type="submit" name="delete" class="btn btn-danger">
								<i class="fa fa-trash"></i> Borrar Foto
							</button>
						</div>
					</center>
				</form>
			</div>
			<!-- Contenedor para la previsualización de la imagen -->
			<div class="form-group mt-12" style="margin-top:15px">
				<center>
					<div style="position: relative; display: inline-block;">
						<!-- Imagen circular de previsualización -->
						<img id="imagePreview" alt="Previsualización" style="display: none; max-width: 180px; height: auto; margin-top: 10px; border-radius: 50%" />
					</div>
					<div style="position: relative; display: inline-block;">
						<!-- Imagen rectangular de previsualización -->
						<img id="imagePreview2" alt="Previsualización" style="display: none; max-width: 250px; height: auto; margin-top: 10px; border-radius: 8px;" />
						<!-- Botón de eliminar -->
						<button id="removeImageButton" onclick="removeImage()"
							style="display: none; position: absolute; top: -150px; right: 15px; background-color: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; z-index: 10; text-align: center; line-height: 25px; font-size: 18px;">
							&times;
						</button>
					</div>
				</center>
			</div>
			<?php
			if (isset($_POST['submit'])) {
				if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
					$image_name = $_FILES['image']['name'];
					$temp_name = $_FILES['image']['tmp_name'];
					$destination_path = "View/Images/profile_img/" . basename($image_name);
					if (move_uploaded_file($temp_name, $destination_path)) {
						$stmt = $conn->prepare("UPDATE members SET image = :location WHERE member_id = :session_id");
						$stmt->bindValue(':location', $destination_path);
						$stmt->bindValue(':session_id', $session_id, PDO::PARAM_INT);
						$stmt->execute();
						echo "<script>window.location = 'profile.php';</script>";
					} else {
						echo "Error al mover el archivo de imagen.";
					}
				} else {
					echo "Error en la carga de la imagen.";
				}
			}
			// Lógica para eliminar la foto
			if (isset($_POST['delete'])) {
				$default_image = "View/Images/profile_img/default.png"; // Imagen predeterminada
				$stmt = $conn->prepare("UPDATE members SET image = :default_image WHERE member_id = :session_id");
				$stmt->bindValue(':default_image', $default_image);
				$stmt->bindValue(':session_id', $session_id, PDO::PARAM_INT);
				$stmt->execute();
				echo "<script>window.location = 'profile.php';</script>";
			}
			?>
		</div>
		<!-- Información -->
		<div class="panel text-center">
			<div class="form-container">
				<?php
				$query = $conn->query("SELECT * FROM members WHERE member_id = '$session_id'");
				$row = $query->fetch();
				$id = $row['member_id'];
				?>
				<form method="post" action="save_edit.php">
					<h3><strong>Datos generales</strong></h3><br>
					<input type="hidden" name="member_id" value="<?php echo $id; ?>">
					<div class="form-row">
						<p>Nombre de usuario:</p>
						<span><?php echo $row['username']; ?></span>
					</div><br>
					<div class="form-row">
						<p>Nombre/s:</p>
						<span><?php echo $row['firstname']; ?></span>
					</div><br>
					<div class="form-row">
						<p>Apellido/s:</p>
						<span><?php echo $row['lastname']; ?></span>
					</div><br>
					<div class="form-row">
						<p>Correo electrónico:</p>
						<span><?php echo $row['email']; ?></span>
					</div><br>
					<div class="form-row">
						<p>Fecha de Nacimiento:</p>
						<span>
							<?php
							$birthdate = $row['birthdate'];
							$formattedDate = date('d / m / Y - ', strtotime($birthdate));
							// Calcular la edad
							$birthDateObject = new DateTime($birthdate);
							$currentDate = new DateTime();
							$age = $birthDateObject->diff($currentDate)->y;
							echo htmlspecialchars($formattedDate) . " (" . $age . " años)";
							?>
						</span>
					</div><br>
					<div class="form-row">
						<p>Número de celular:</p>
						<input type="text" name="mobile" value="<?php echo $row['mobile']; ?>" />
					</div><br>
					<div class="form-row">
						<p>Género:</p>
						<select name="gender">
							<option><?php echo $row['gender']; ?></option>
							<option>Hombre</option>
							<option>Mujer</option>
							<option>Otro</option>
						</select>
					</div><br>
					<div class="form-row">
						<p>País:</p>
						<select id="country" name="country" onchange="fetchCities(this.value)">
							<option value="">Selecciona un país</option>
							<option value="AR">Argentina</option>
							<option value="BO">Bolivia</option>
							<option value="BR">Brasil</option>
							<option value="CL">Chile</option>
							<option value="CO">Colombia</option>
							<option value="EC">Ecuador</option>
							<option value="GY">Guyana</option>
							<option value="PY">Paraguay</option>
							<option value="PE">Perú</option>
							<option value="SR">Surinam</option>
							<option value="UY">Uruguay</option>
							<option value="VE">Venezuela</option>
						</select>
					</div><br>
					<div class="form-row">
						<p>Ciudad:</p>
						<select id="city" name="city">
							<option value="">Primero selecciona un país</option>
						</select>
					</div><br>
					<div class="form-row">
						<p>Ocupación:</p>
						<input type="text" name="work" value="<?php echo $row['work']; ?>">
					</div>
					<div class="btn-container">
						<button type="submit" name="save" class="btn btn-success">
							<i class="fa fa-save" style="margin-right: 5px;"></i> Guardar
						</button>
						<a href="profile.php" class="btn btn-danger">
							<i class="fa fa-undo" style="margin-right: 5px;"></i> Regresar
						</a>
						<br><br>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<footer>
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/location.js"></script>
	<script src="View/JS/preview_photo_profile.js"></script>
	<script src="View/JS/notifications.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>

</html>