<?php include('../functions.php') ?>
<!DOCTYPE html>
<html>

<head>
	<title>Registration system PHP and MySQL - Create user</title>
	<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body>
	<div class="header">
		<h2>Admin - create user</h2>
	</div>

	<form method="post" action="create_user.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label>User type</label>
			<select name="user_type" id="user_type">
				<option value=""></option>
				<option value="admin">Admin</option>
				<option value="user">User</option>
			</select>
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Confirm password</label>
			<input type="password" name="password_2">
		</div>

		<div class="input-group">
			<label>Saison</label>
			<select name="season_user" id="saison_user">

			<?php
			//déclaration requete sql
				$req1 = $db->query('SELECT * FROM saison');
				$rows = $req1->rowCount();

				//boucle pour recuperer plusieurs lignes
				if ($rows > 0) {
					while ($rows = $req1->fetch()) {
						?><option value="<?php echo $rows["date_saison"]?>"><?php echo $rows["date_saison"]?></option><?php
					}
				} 
				?>
			</select>

		</div>

		<div class="input-group">
			<button type="submit" class="btn" name="register_btn"> + Create user</button>
		</div>
	</form>
</body>

</html>