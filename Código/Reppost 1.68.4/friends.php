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
					<li><a style="margin-right:0%" href="logout.php"><i class="fa fa-right-from-bracket"></i> Salir</a></li>
					<li><button id="darkModeToggle" class="btn btn-light" style="padding: 14px 5px;"><i class="fa fa-moon"></i></button></li>
					<!-- Botón de Notificaciones -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="position: relative;">
							<i class="fa fa-bell"></i>
							<span id="notification-badge" class="badge badge-danger" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; font-size: 12px; display: none;">0</span>
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
							<input type="text" name="search" class="form-control search-query" id="span5" placeholder="Buscar" style="margin-left: 265px;">
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
							<br><br>
							<?php
							$query = $conn->query("SELECT members.member_id, 
							members.firstname, 
							members.lastname, 
							members.image, 
							friends.friends_id   
							FROM members
							JOIN friends ON (members.member_id = friends.my_friend_id OR members.member_id = friends.my_id)
							WHERE (friends.my_friend_id = '$session_id' OR friends.my_id = '$session_id') 
							AND members.member_id != '$session_id'");
							while ($row = $query->fetch()) {
								$friend_name = $row['firstname'] . " " . $row['lastname'];
								$friend_image = $row['image'];
								$id = $row['friends_id'];
							?>
								<div class="row">
									<div class="col-md-2 text-center">
										<a href="profile_friend.php?member_id=<?php echo $row['member_id']; ?>">
											<img src="<?php echo $friend_image; ?>" style="width:75px;height:75px" class="img-circle">
										</a><br><br>
									</div>
									<div class="col-md-9">
										<div class="alert"><?php echo $friend_name; ?></div>
										<div class="pull-right"><br>
											<a href="delete_friend.php?id=<?php echo $row['member_id']; ?>" class="btn btn-danger">
												<i class="fa fa-user-minus"></i> Eliminar amigo
											</a>
										</div><br><br><br>
										<hr>
									</div>
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
	<script src="View/JS/dark_mode.js"></script>
	<script src="View/JS/close_navbar.js"></script>
	<script src="View/JS/notifications.js"></script>
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>