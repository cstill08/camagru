<?php
	//регистрация пользователя - клиент
	session_start();
	if (isset($_SESSION['user'])) {
		header('Location: profile.php');
	}
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset='utf-8'>
		<title>Camagru</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/main.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
	</head>
	<body background="img/unnamed.jpg" style="background-size: cover;">
		<h1>Foto-Fotor</h1>
		<form action="include/signup.php" method="post" enctype="multipart/form-data">
			<label>FIO</label>
			<input type="text" name="full_name" placeholder="Enter your FIO">
			<label>Login</label>
			<input type="text" name="login" placeholder="Enter your login">
			<label>Email</label>
			<input type="email" name="email" placeholder="Enter your email">
			<label>Password</label>
			<input type="password" name="password" placeholder="Enter your password">
			<label>Confirm password</label>
			<input type="password" name="password_confirm" placeholder="Confirm your password">
			<button type="submit">Sign up</button>
			<p>
				You already have an account- <a href="index.php">Log in</a>!
			</p>
			<?php
				if (isset($_SESSION['message']))
				{
					echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
				}
				unset($_SESSION['message']);
			?>
		</form>
	</body>
</html>