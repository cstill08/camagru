<?php
	session_start();
	if (isset($_SESSION['user'])) {
		header('Location: profile.php');
	}
	require_once('config/setup.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title>Camagru</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/main.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
	</head>
	<body background="img/unnamed.jpg" style="background-size: cover;">
		<h1>Sign in</h1>
		<form action="include/signin.php" method="post">
			<label>Login</label>
			<input type="text" name="login" placeholder="Enter your login">
			<label>Password</label>
			<input type="password" name="password" placeholder="Enter your password">
			<button type="submit">Sign in</button>
			<p>
				You are not registred? - <a href="register.php">Sign up</a>!
			</p>
			<p>
				Forgot password? - <a href="RestorePassword.php">Restore password</a>!
			</p>
			<?php
				if (isset($_SESSION['message'])) {
					echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
				}
				unset($_SESSION['message']);
			?>
		</form>
	</body>
</html>