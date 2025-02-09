<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
$search = isset($_POST['search']) ? trim($_POST['search']) : (isset($_GET['search']) ? trim($_GET['search']) : '');
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
		<div class="row">
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body">
						<div class="row">
							<?php
							// Búsqueda de miembros
							$queryMembers = $conn->prepare("SELECT member_id, firstname, lastname, image, privacy FROM members
							WHERE role != 'moderador' AND role != 'admin'
							AND (CONCAT(firstname, ' ', lastname) LIKE :search OR firstname LIKE :search OR lastname LIKE :search)
							AND member_id NOT IN (SELECT blocked_id FROM blocked_users WHERE user_id = :session_id)
							AND member_id NOT IN (SELECT user_id FROM blocked_users WHERE blocked_id = :session_id)
							");
							$searchParam = "%$search%";
							$queryMembers->bindParam(':search', $searchParam, PDO::PARAM_STR);
							$queryMembers->bindParam(':session_id', $session_id, PDO::PARAM_INT);
							$queryMembers->execute();
							// Búsqueda de publicaciones
							$queryPosts = $conn->prepare("SELECT p.*, m.firstname, m.lastname, m.image, m.privacy, pi.image_path, pv.video_path 
                             FROM post p
                             LEFT JOIN members m ON m.member_id = p.member_id
                             LEFT JOIN post_images pi ON p.post_id = pi.post_id
                             LEFT JOIN post_videos pv ON p.post_id = pv.post_id
                             WHERE (p.content LIKE :search) 
                             AND p.member_id NOT IN (SELECT blocked_id FROM blocked_users WHERE user_id = :session_id)
                             AND p.member_id NOT IN (SELECT user_id FROM blocked_users WHERE blocked_id = :session_id)
                             ORDER BY p.date_posted DESC");
							$queryPosts->bindParam(':search', $searchParam, PDO::PARAM_STR);
							$queryPosts->bindParam(':session_id', $session_id, PDO::PARAM_INT);
							$queryPosts->execute();
							$countMembers = $queryMembers->rowCount();
							$countPosts = $queryPosts->rowCount();
							// Mostrar usuarios
							if ($countMembers > 0) {
								echo "<h4 style='margin-left: 15px;'>Usuarios</h4><br>";
								while ($row = $queryMembers->fetch()) {
									$friend_id = $row['member_id'];
									$posted_by = htmlspecialchars($row['firstname'] . " " . $row['lastname']);
									$posted_image = htmlspecialchars($row['image']);
									$privacy = ($row['privacy'] === 'public') ? "(Perfil público)" : "(Perfil privado)";
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
													<span style="font-size: 16px; font-weight: bold;">
														<?php echo $posted_by . " " . $privacy; ?>
													</span>
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
							// Mostrar publicaciones
							if ($countPosts > 0) {
								echo "<br><h4 style='margin-left: 15px;'>Publicaciones</h4>";
								while ($row = $queryPosts->fetch()) {
									$posted_by = htmlspecialchars($row['firstname'] . " " . $row['lastname']);
									$profile_image = htmlspecialchars($row['image']);
									$post_image = htmlspecialchars($row['image_path']);
									$post_video = htmlspecialchars($row['video_path'] ?? '');
									$date_posted = date('H:i - d/m/Y', strtotime($row['date_posted']));
									$post_id = $row['post_id'];
									$member_id = $row['member_id'];
									$privacy = $row['privacy'];
									// Si es privado, verificar si el usuario es amigo
									$canViewPost = false;
									if ($privacy === 'public' || $member_id == $session_id) {
										$canViewPost = true;
									} else {
										$friendCheck = $conn->prepare("SELECT 1 FROM friends WHERE (my_id = :session_id AND my_friend_id = :member_id) OR (my_friend_id = :session_id AND my_id = :member_id)");
										$friendCheck->bindParam(':session_id', $session_id, PDO::PARAM_INT);
										$friendCheck->bindParam(':member_id', $member_id, PDO::PARAM_INT);
										$friendCheck->execute();
										if ($friendCheck->rowCount() > 0) {
											$canViewPost = true;
										}
									}
									// Si no se puede ver la publicación, mostrar "Contenido privado"
									$content = $canViewPost ? htmlspecialchars($row['content']) : "<i>No se puede mostrar el contenido por la privacidad del usuario</i>";
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
											<!-- Mostrar imagen si la publicación la tiene -->
											<?php if ($canViewPost && !empty($post_image)): ?>
												<div class="post-image">
													<center>
														<img src="<?php echo $post_image; ?>" style="width:450px; height:450px; margin-top:10px; border-radius:10px;" alt="Post Image">
													</center>
												</div>
											<?php endif; ?>
											<!-- Mostrar video si la publicación lo tiene -->
											<?php if ($canViewPost && !empty($post_video)): ?>
												<div class="post-video">
													<center>
														<video src="<?php echo $post_video; ?>" controls style="max-width:100%; height:auto; margin-top:10px; border-radius:10px;">
															Tu navegador no soporta la etiqueta de video.
														</video>
													</center>
												</div>
											<?php endif; ?>
											<!-- Botón para ver la publicación -->
											<?php if ($canViewPost): ?>
												<div style="margin-top: 10px;">
													<a href="home.php?post_id=<?php echo $post_id; ?>" class="btn btn-primary">
														<i class="fa fa-eye"></i> Ver publicación
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php
								}
							}
							// Mensaje si no se encuentran resultados
							if ($countMembers == 0 && $countPosts == 0) {
								?>
								<div style="text-align: center; margin-top: 20px;">
									<img src="View/Images/app_images/no_results.png"
										alt="Sin resultados"
										style="width: 120px; opacity: 0.8;">
									<h4 style="color: #999; margin-top: 10px;">
										¡Ups! No se encontraron resultados.
									</h4>
									<p style="color: #999;">
										Intenta con otras palabras o revisa la ortografía.
									</p>
									<button class="btn btn-primary"
										onclick="window.location.href='home.php';">
										Volver al Inicio
									</button>
								</div>
							<?php
							}
							?>
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