<?php
	/*тут выполняется выполяется верификация пользователя*/
	#session_start();
	header('Location: ../index.php');
	include_once '../config/database.php';

	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND state = :hash');
		$sth->bindParam(':email', $_GET[email], PDO::PARAM_STR);
		$sth->bindParam(':hash', $_GET[hash], PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	if ($sth->fetchColumn()) {
		try {
			$sth = $dbh->prepare("UPDATE users SET state = 'active' WHERE email = :email AND state = :hash");
			$sth->bindParam(':email', $_GET[email], PDO::PARAM_STR);
			$sth->bindParam(':hash', $_GET[hash], PDO::PARAM_STR);
			$sth->execute();
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}
		$_SESSION['message'] = 'Your account is now valid. You can log in.';
		header('Location: ../index.php');
	} else {
		$_SESSION['message'] = 'Error valid';
		header('Location: ../index.php');
		exit();
	}

?>
