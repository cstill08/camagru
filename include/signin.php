<?php
	/*тут выполняется вход существующего пользователя - сервер*/
	session_start();
	header('Location: ../index.php');
	include_once '../config/database.php';
	//require_once 'connect.php';

	$login = $_POST['login'];
	$password = hash('whirlpool', $_POST['password']);

	if (empty($_POST['login']) || empty($_POST['password'])) {
		$_SESSION['message'] = 'Please fill in all the blanks';
		header('Location: ../index.php');
		exit();
	}
	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
		$sth->bindParam(':login', $login, PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
	}
	if ($sth->fetchColumn()) {
		try {
			$sth = $dbh->prepare('SELECT COUNT(*) FROM users WHERE login = :login AND password = :password');
			$sth->bindParam(':login', $login, PDO::PARAM_STR);
			$sth->bindParam(':password', $password, PDO::PARAM_STR);
			$sth->execute();
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}
		if ($sth->fetchColumn()) {
			try {
				$sth = $dbh->prepare("SELECT COUNT(*) FROM users WHERE login = :login AND password = :password AND state = 'active'");
				$sth->bindParam(':login', $login, PDO::PARAM_STR);
				$sth->bindParam(':password', $password, PDO::PARAM_STR);
				$sth->execute();
			} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
			}
			if ($sth->fetchColumn()) {
				#$_SESSION['user'] = $login;
				$_SESSION['user'] = [
					"id" => $user['id'],
					"name" => $user['login'],
					"full_name" => $user['full_name'],
					"email" => $user['email'],
					"verify" => true,//тут еще нужно добавить про прошел ли верификацию на мыло
					"message" => "Your email address has been verified successfully"
				];
				header('Location: ../profile.php');
				exit();
			} else {
				$_SESSION['message'] = 'Activate your account';
				header('Location: ../index.php');
				exit();
			}
		} else {
			$_SESSION['message'] = 'Wrong password';
			header('Location: ../index.php');
			exit();
		}
	} else {
		$_SESSION['message'] = 'Account does not exist';
		header('Location: ../index.php');
		exit();
	}
	

	/*$check_users = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
	if (mysqli_num_rows($check_users) > 0){
		$user = mysqli_fetch_assoc($check_users);
		$_SESSION['user'] = [
            "id" => $user['id'],
            "full_name" => $user['full_name'],
            "email" => $user['email'],
			"verify" => true,//тут еще нужно добавить про прошел ли верификацию на мыло
			"message" => "Your email address has been verified successfully"
        ];
		header('Location: ../profile.php');
		exit(0);

	} else {
		$_SESSION['message'] = 'Incorrect login or password';
		header('Location: ../index.php');
	}*/

?>
