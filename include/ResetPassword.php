<?php
	/*тут выполняется выполяется сброс ссылки для восстановления пароля */
	session_start();
	#header('Location: ../index.php');
	include_once '../config/database.php';

	$email = $_POST['email'];
	$hash = md5(rand(0, 1000));

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
		$sth->bindParam(':email', $_GET[email], PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	if ($sth->fetchColumn()) {
		$to = $email;
		$subject = 'Camagru - reset password';
		$message = "Your account has been created, you can connect with the following username after activating your account. Click on the following link to activate your account:
		http://localhost:8080/camagru/include/fogot.php?email=$email&hash=$hash ";
		$headers = 'From:cstill@student.21-school.ru';
		if (mail($to, $subject, $message, $headers))
		{
			$_SESSION['message'] = 'The letter has been sent to the post office. Confirm mailing address';
		}
		header('Location: ../index.php');
	} else {
		$_SESSION['message'] = 'Account does not exist';
		header('Location: ../index.php');
		exit();
	}

?>
