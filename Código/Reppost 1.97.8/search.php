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
</head>
<?php include('Controller/Backend/session.php'); ?>
<?php $search = $_POST['search']; ?>

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
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body">
						<div class="row">
							<?php
							// Búsqueda por nombre completo o por partes del nombre
							$queryMembers = $conn->query("
								SELECT *
								FROM members
								WHERE role != 'moderador'  -- <--- EXCLUIR moderadores
								AND (
									CONCAT(firstname, ' ', lastname) LIKE '%$search%'
									OR firstname LIKE '%$search%'
									OR lastname LIKE '%$search%'
								)
								AND member_id NOT IN (
									SELECT blocked_id 
									FROM blocked_users 
									WHERE user_id = '$session_id'
								)
								AND member_id NOT IN (
									SELECT user_id
									FROM blocked_users
									WHERE blocked_id = '$session_id'
								)
							");
							$queryPosts = $conn->query("
								SELECT post.member_id, 
									post.content, 
									post.date_posted, 
									members.firstname, 
									members.lastname, 
									members.image
								FROM post 
								JOIN members ON post.member_id = members.member_id
								WHERE post.content LIKE '%$search%'
								-- Excluir posts de los bloqueados
								AND post.member_id NOT IN (
									SELECT blocked_id
									FROM blocked_users
									WHERE user_id = '$session_id'
								)
								-- Excluir usuarios que bloquean al que busca
								AND post.member_id NOT IN (
									SELECT user_id
									FROM blocked_users
									WHERE blocked_id = '$session_id'
								)
							");
							$countMembers = $queryMembers->rowCount();
							$countPosts = $queryPosts->rowCount();
							// Mostrar resultados de miembros
							if ($countMembers > 0) {
								echo "<h4 style='margin-left: 15px;'>Usuarios</h4><br>";
								while ($row = $queryMembers->fetch()) {
									$posted_by = $row['firstname'] . " " . $row['lastname'];
									$posted_image = $row['image'];
									$friend_id = $row['member_id'];
							?>
									<div class="col-md-12" style="margin-bottom: 5px;">
										<div class="alert">
											<div class="row" style="align-items: center">
												<!-- Imagen del usuario -->
												<div class=" col-md-2 col-sm-2 text-center">
													<a href="<?php echo ($friend_id == $session_id) ? 'profile.php' : 'profile_friend.php?member_id=' . $friend_id; ?>">
														<img src="<?php echo $posted_image; ?>" style="width:70px; height:70px;" class="img-circle">
													</a>
												</div>
												<!-- Información del usuario -->
												<div class="col-md-7" style="display: flex; align-items: center;">
													<span style="font-size: 16px; font-weight: bold;"><?php echo $posted_by; ?></span>
												</div>
												<!-- Botón agregar amigo -->
												<div class="col-md-3 text-end">
													<form method="post" action="add_friend.php" id="addFriendForm">
														<input type="hidden" name="my_friend_id" value="<?php echo $friend_id; ?>">
														<?php
														$query1 = $conn->query("SELECT * FROM friends WHERE (my_friend_id = '$friend_id' AND my_id = '$session_id') OR (my_friend_id = '$session_id' AND my_id = '$friend_id')");
														$count1 = $query1->rowCount();
														if ($friend_id == $session_id) {
															echo '<span class="pull-right">Tu usuario</span>';
														} elseif ($count1 > 0) {
															echo '<span class="pull-right">Tu amigo</span>';
														} else {
														?>
															<div class="pull-right">
																<button type="button" class="btn btn-info addFriendButton" data-id="<?php echo $friend_id; ?>" style="font-size: 14px;">
																	<i class="fa fa-user-plus"></i> Agregar amigo
																</button>
															</div>
														<?php } ?>
													</form>
												</div>
											</div>
										</div>
									</div>
								<?php
								}
							}
							// Mostrar resultados de publicaciones
							if ($countPosts > 0) {
								echo "<br><h4 style='margin-left: 15px;'>Publicaciones</h4>";
								$queryPosts = $conn->prepare("
									SELECT p.*, m.*, pi.image_path 
									FROM post p
									LEFT JOIN members m ON m.member_id = p.member_id
									LEFT JOIN post_images pi ON p.post_id = pi.post_id
									WHERE (p.content LIKE :search)
									ORDER BY p.date_posted DESC
								");
								$searchParam = "%$search%";
								$queryPosts->bindParam(':search', $searchParam, PDO::PARAM_STR);
								$queryPosts->execute();
								while ($row = $queryPosts->fetch()) {
									$posted_by = $row['firstname'] . " " . $row['lastname'];
									$content = $row['content'];
									$profile_image = $row['image'];
									$post_image = $row['image_path'];
									$date_posted = date('H:i - d/m/Y', strtotime($row['date_posted']));
									$post_id = $row['post_id'];
								?>
									<br>
									<div class="col-md-12">
										<div class="alert">
											<a href="<?php echo ($row['member_id'] == $session_id) ? 'profile.php' : 'profile_friend.php?member_id=' . $row['member_id']; ?>">
												<img src="<?php echo $profile_image; ?>" style="width:50px;height:50px;" class="img-circle">
											</a>
											<strong><?php echo $posted_by; ?></strong> <?php echo $date_posted; ?>
											<hr>
											<p><?php echo $content; ?></p>
											<?php if (!empty($post_image)) { ?>
												<div class="post-image">
													<center><img src="<?php echo $post_image; ?>" style="width:450px; height:450px; margin-top:10px; border-radius:10px;" alt="Post Image"></center>
												</div>
											<?php } ?>
											<!-- Botón para ir a la publicación -->
											<div style="margin-top: 10px;">
												<a href="home.php?post_id=<?php echo $row['post_id']; ?>" class="btn btn-primary">
													<i class="fa fa-eye"></i> Ver publicación
												</a>
											</div>
										</div>
									</div>
								<?php
								}
							}
							// Mensaje si no se encuentran resultados
							if ($countMembers == 0 && $countPosts == 0) { ?>
								&nbsp;&nbsp;&nbsp;&nbsp; No se encontraron resultados.
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<footer>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="View/JS/add_friend.js"></script>
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/notifications.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>

</html>