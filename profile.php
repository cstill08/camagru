<?php
//профиль пользователя
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
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

    <!-- Профиль -->

    <form>
        <h2 style="margin: 10px 0;">Welcome, <?= $_SESSION['user']['full_name'] ?></h2>
        <a href="#"><?= $_SESSION['user']['email'] ?></a>
        <br/>
        <?php if (!($_SESSION['user']['verify'])): ?>
            <p> You need to verify your email address!
            Sign into your email account and click
            on the verification link we just emailed you
            at <?= ($_SESSION['user']['email']) ?> </p>
        <?php else: ?>
            <button>I'm verified!!!</button>
        <?php endif; ?>
        <br/>
        <a href="include/logout.php" class="logout" style="color: #b7625c;">Exit</a>
    </form>

</body>
</html>