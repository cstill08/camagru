<?php
//профиль пользователя
session_start();
if (isset($_SESSION['user'])) {
	header('Location: profile.php');
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset='utf-8'>
		<title>Camagru</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/main.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
</head>
<body background="img/unnamed.jpg" style="background-size: cover;">
		<h1>New password</h1>
		<form action="include/forgot.php" method="post">
			<label>Email</label>
			<input type="text" name="email" placeholder="Enter your email">
			<label>Password</label>
			<input type="text" name="password" placeholder="Enter your password">
			<label>Confirm password</label>
			<input type="password" name="password_confirm" placeholder="Confirm your password">
			<?php if ($_GET['hash']): ?>
				<input type="hidden" name="hash" value='<?=$_GET['hash']?>'>
			<?php endif; ?>
			<button type="submit">Send</button>
			<?php
				if (isset($_SESSION['message'])) {
					echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
				}
				unset($_SESSION['message']);
			?>
		</form>
	</body>
</html>