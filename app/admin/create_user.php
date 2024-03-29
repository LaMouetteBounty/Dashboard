<?php include('../functions.php') ?>
<!DOCTYPE html>
<html>

<head>
<link rel="icon" type="image/png" href="../assets/maquettes/favicon_bkc.png">
	<title>Créer un compte</title>
	<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body class="body_createUser">
	<div class="container-fluid bg_createUser">
		<div class="container">
			<div class="row main_createUser">
				<div class="header">
					<h2>CRÉER UN COMPTE</h2>
				</div>
				<form method="post" action="create_user.php" class="form_createUser">
					<?php echo display_error(); ?>

					<div class="input-group">
						<label>IDENTIFIANT</label>
						<input type="text" name="username" value="<?php echo $username; ?>">
					</div>
					<div class="input-group">
						<label>EMAIL</label>
						<input type="email" name="email" value="<?php echo $email; ?>">
					</div>

					<div class="input-group">
						<label>MOT DE PASSE</label>
						<input type="password" name="password_1">
					</div>
					<div class="input-group">
						<label>CONFIRMATION MOT DE PASSE</label>
						<input type="password" name="password_2">
					</div>

					<div class="selecteur">

						<div class="input-group">
							<label>SAISON</label>
							<input type="date_saison" name="date_saison" value="<?php echo $date_saison; ?>">
						</div>
						<div class="input-group">
							<label>TYPE D'UTILISATEUR</label>
							<select name="user_type" id="user_type">
								<option value=""></option>
								<option value="admin">Administrateur</option>
								<option value="user">Utilisateur</option>
							</select>
						</div>
					</div>


					
						<button type="submit" class="btn" name="register_btn"> VALIDER</button>
					

				</form>

			</div>
		</div>
	</div>
</body>

</html>