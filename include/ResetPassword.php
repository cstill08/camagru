<?php
	/*тут выполняется выполяется сброс ссылки для восстановления пароля */
	session_start();
	header('Location: ../RestorePassword.php');
	include_once '../config/database.php';

	$email = $_POST['email'];
	$hash = md5(rand(1, 1000));

	if (empty($email))
	{
		$_SESSION['message'] = 'Please fill in all the blanks';
		header('Location: ../RestorePassword.php');
		exit();
	}
	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
		$sth->bindParam(':email', $email, PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	if ($sth->fetchColumn()) {
		try {
			$sth = $dbh->prepare("UPDATE users SET forgot = '$hash' WHERE email = :email");
			$sth->bindParam(':email', $email, PDO::PARAM_STR);
			$sth->execute();
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}

		$to = $email;
		$subject = 'Camagru - reset password';
		$message = "Click on the following link to reactivate your account with a new password:
		http://localhost:8080/camagru/fogot.php?email=$email&hash=$hash ";
		$headers = 'From:cstill@student.21-school.ru';
		if (mail($to, $subject, $message, $headers))
		{
			$_SESSION['message'] = 'The letter has been sent to the post office. Confirm mailing address';
			header('Location: ../index.php');
		} else {
			$_SESSION['message'] = 'An error occurred while sending the letter. Try again';
			header('Location: ../RestorePassword.php');
		}
	} else {
		$_SESSION['message'] = 'Account does not exist';
		header('Location: ../RestorePassword.php');
		exit();
	}

?>
