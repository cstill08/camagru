<?php
	session_start();
	//если пользователь не зарегистрирован то говорим что он не может просматривать страницу
	if (!isset($_SESSION['user']))
	{
		$_SESSION['message'] = 'You must log in to access this page';
		header('Location: ../index.php');
		exit();
	}
	//
	if (!isset($_POST['comment']) || !isset($_GET['img_id'])){
		header('Location: ../gallery.php?page=$_GET[page]');
		exit();
	}

	include_once '../config/database.php';
	header('Location: ../gallery.php?page=$_GET[page]');

	//ищем в таблице лайк данные с таким же логином и айди изображения
	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('INSERT INTO comment (login, img_id, comment) VALUES (:login, :img_id, :comment)');
		$sth->bindParam(':img_id', $_GET['img_id'], PDO::PARAM_INT);
		$sth->bindParam(':login', $_SESSION['user']['login'], PDO::PARAM_STR);
		$sth->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
		$sth->execute();
		$sth = $dbh->prepare('SELECT users.email FROM users INNER JOIN pictures ON users.login = pictures.login WHERE pictures.id = :img_id');
		$sth->bindParam(':img_id', $_GET['img_id'], PDO::PARAM_INT);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}

	$email = $sth->fetchColumn();
	$to = $email;
	$subject = 'Camagru - New comment';
	$message = "A new comment has been posted on your photo by: $_SESSION[user][login]. Comment: $_POST[comment]";
	$headers = 'From:cstill@student.21-school.ru';
	if (mail($to, $subject, $message, $headers))
	{
		$_SESSION['message'] = 'The letter has been sent to the post office. Confirm mailing address';
		header('Location: ../gallery.php');
	} else {
		$_SESSION['message'] = 'An error occurred while sending the letter. Try again';
		header('Location: ..gallery/.php');
	}
?>
