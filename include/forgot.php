<?php
	/*тут выполняется смена пароля в БД*/
	session_start();
	header('Location: ../forgot.php');
	include_once '../config/database.php';

	$email = $_POST['email'];
	$password = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];

	if (empty($email) || empty($password) || empty($password_confirm) || empty($hash))
	{
		$_SESSION['message'] = 'Please fill in all the blanks';
		header('Location: ../forgot.php');
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
	if (!$sth->fetchColumn()){
		$_SESSION['message'] = 'Account does not exist';
		header('Location: ../index.php');
		exit();
	} else {
		/*проверяем чтобы количество символов в пароле не было меньше 8*/
		if (strlen($password) < 8)
		{
			$_SESSION['message'] = 'Password must be at least 8 characters';
			header('Location: ../forgot.php');
			exit();
		}

		if ($password != $password_confirm) {
			$_SESSION['message'] = 'Password mismatch';
			header('Location: ../forgot.php');
			exit();
		}
		/*далее чтобы в пароле не было только цифр, или только нижнего регистра или только верхнего регистра*/
		else
		{
			$strong = 0;
			if (preg_match("/([0-9]+)/", $password)){
				$strong++;
			}
			if (preg_match("/([a-z]+)/", $password)){
				$strong++;
			}
			if (preg_match("/([A-Z]+)/", $password)){
				$strong++;
			}
			if ($strong < 3){
				$_SESSION['message'] = 'Invalid password. Password must contain lower and upper case characters, as well as numbers';
				header('Location: ../register.php');
			} else {
				$password = hash('whirlpool', $password);
				try {
					$sth = $dbh->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND forgot = :hash');
					$sth->bindParam(':email', $email, PDO::PARAM_STR);
					$sth->bindParam(':hash', $_POST['hash'], PDO::PARAM_STR);
					$sth->execute();
				} catch(PDOException $e){
					echo 'Error: ' . $e->getMessage();
				}
				if ($sth->fetchColumn()) {
					try {
						$sth = $dbh->prepare("UPDATE users SET forgot = 'NULL' WHERE email = :email AND forgot = :hash AND password = :password");
						$sth->bindParam(':password', $password, PDO::PARAM_STR);
						$sth->bindParam(':email', $email, PDO::PARAM_STR);
						$sth->bindParam(':hash', $_POST['hash'], PDO::PARAM_STR);
						$sth->execute();
					} catch(PDOException $e){
						echo 'Error: ' . $e->getMessage();
						exit();
					}
					$_SESSION['message'] = 'Your password has been correctly changed';
					header('Location: ../index.php');
				} else {
					$_SESSION['message'] = 'An error has occurred. Please try again.';
					header('Location: ../index.php');
				}
			}
		}
	}
?>
