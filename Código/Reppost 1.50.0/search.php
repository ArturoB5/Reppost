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
							<input type="text" name="search" class="form-control search-query" id="span5" placeholder="Buscar">
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
							<br>
							<?php
							$query = $conn->query("SELECT * FROM members WHERE firstname LIKE '%$search%' OR lastname LIKE '%$search%'");
							$count = $query->rowCount();
							if ($count > 0) {
								while ($row = $query->fetch()) {
									$posted_by = $row['firstname'] . " " . $row['lastname'];
									$posted_image = $row['image'];
									$friend_id = $row['member_id'];
							?>
									<div class="col-md-2 col-sm-3 text-center">
										<a href="<?php echo ($friend_id == $session_id) ? 'profile.php' : 'profile_friend.php?member_id=' . $friend_id; ?>">
											<img src="<?php echo $posted_image; ?>" style="width:75px;height:75px;" class="img-circle">
										</a>
									</div>
									<div class="col-md-9">
										<div class="alert"><?php echo $posted_by; ?></div>
										<div class="row">
											<div class="col-xs-9">
												<form method="post" action="add_friend.php" id="addFriendForm">
													<div class="col-xs-3">
														<input type="hidden" name="my_friend_id" value="<?php echo $friend_id; ?>">
														<?php
														$query1 = $conn->query("SELECT * FROM friends WHERE (my_friend_id = '$friend_id' AND my_id = '$session_id') OR (my_friend_id = '$session_id' AND my_id = '$friend_id')");
														$count1 = $query1->rowCount();
														if ($friend_id == $session_id) {
															echo 'Tu usuario';
														} elseif ($count1 > 0) {
															echo 'Tu amigo';
														} else {
														?>
															<br>
															<div class="pull-right">
																<button type="button" class="btn btn-info addFriendButton" data-id="<?php echo $friend_id; ?>">
																	<i class="fa fa-user-plus"></i> Agregar amigo
																</button>
															</div>
														<?php } ?>
														<br>
													</div>
												</form>
											</div>
										</div>
										<br><br>
									</div>
								<?php }
							} else { ?>
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
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {});
	</script>
</footer>

</html>