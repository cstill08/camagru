<?php
	//тут регистрируем нового пользователя-сервер
	session_start();
	include '../config/database.php';
	//require_once 'connect.php';

	$full_name = $_POST['full_name'];
	$login = $_POST['login'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password_confirm = $_POST['password_confirm'];
	//проверяем заполнены ли все поля
	if (empty($login) || empty($full_name) || empty($email) || empty($password) || empty($password_confirm))
	{
		$_SESSION['message'] = 'Please fill in all the blanks';
		header('Location: ../register.php');
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
		exit();
	}

	if ($sth->fetchColumn()) {
		$_SESSION['message'] = 'User is already registered';
		header('Location: ../register.php');
		exit();
	}
	//проверяем чтобы количество символов в пароле не было меньше 8
	if (strlen($password) < 8)
	{
		$_SESSION['message'] = 'Password must be at least 8 characters';
		header('Location: ../register.php');
		exit();
	}
	if ($password != $password_confirm) {
		$_SESSION['message'] = 'Password mismatch';
		header('Location: ../register.php');
		exit();
	}
	//далее чтобы в пароле не было только цифр, или только нижнего регистра или только верхнего регистра
	else
	{
		$strong = 0;
	 	if (preg_match("/([0-9]+)/", $password))
		{
			$strong++;
		}
		if (preg_match("/([a-z]+)/", $password))
		{
			$strong++;
		}
		if (preg_match("/([A-Z]+)/", $password))
		{
			$strong++;
		}
		if ($strong < 3)
		{
			$_SESSION['message'] = 'Invalid password. Password must contain lower and upper case characters, as well as numbers';
			header('Location: ../register.php');
		}
		else{
			$password = hash('whirlpool', $password);
			$hash = md5(rand(0, 1000));
			try {

				$sth = $dbh->prepare('INSERT INTO users (`id`, `full_name`, `login`, `email`, `password`, `state`) VALUES (NULL, :full_name, :login, :email, :password, :hash)');
				$sth->bindParam(':full_name', $full_name, PDO::PARAM_STR);
				$sth->bindParam(':login', $login, PDO::PARAM_STR);
				$sth->bindParam(':email', $email, PDO::PARAM_STR);
				$sth->bindParam(':password', $password, PDO::PARAM_STR);
				$sth->bindParam(':hash', $hash, PDO::PARAM_STR);
				$sth->execute();
				$_SESSION['message'] = 'Registration completed successfully';
				header('Location: ../index.php');
		
			} catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
			}
		
			$to = $email;
			$subject = 'Camagru - сonfirmation of registration';
			$message = "Your account has been created, you can connect with the following username after activating your account. Username: $login . Click on the following link to activate your account:
			http://localhost:8080/camagru/include/verify.php?email=$email&hash=$hash ";
			$headers = 'From:cstill@student.21-school.ru';
			if (mail($to, $subject, $message, $headers))
			{
				$_SESSION['message'] = 'The letter has been sent to the post office. Confirm mailing address';
			}
			header('Location: ../index.php');
		}
	
	}
	/*$password = hash('whirlpool', $password);
	$hash = md5(rand(0, 1000));
	try {

		$sth = $dbh->prepare('INSERT INTO users (`id`, `full_name`, `login`, `email`, `password`, `state`) VALUES (NULL, :full_name, :login, :email, :password, :hash)');
		$sth->bindParam(':full_name', $full_name, PDO::PARAM_STR);
		$sth->bindParam(':login', $login, PDO::PARAM_STR);
		$sth->bindParam(':email', $email, PDO::PARAM_STR);
		$sth->bindParam(':password', $password, PDO::PARAM_STR);
		$sth->bindParam(':hash', $hash, PDO::PARAM_STR);
		$sth->execute();
		$_SESSION['message'] = 'Registration completed successfully';
		header('Location: ../index.php');

	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
	}

	$to = $email;
	$subject = 'Camagru - сonfirmation of registration';
	$headers = 'From:cstill@student.21-school.ru';
	$message = "
	Your account has been created, you can connect with the following username after activating your account.

	Username: $login

	Click on the following link to activate your account:
	http://localhost:8080/Camagru/verify.php?email=$email&hash=$hash
	";
	mail($to, $subject, $message, $headers);
	header('Location: ../index.php');*/
	/*else {
		//далее проверяем чтобы совпадали пароль и повтор пароля совпадали
		if ($password === $password_confirm)
		{//далее хешируем пароль
			$password = hash('whirlpool', $password);
			$hash = md5(rand(0, 1000));
			//далее если в БД сохраняем данные пользователя
			mysqli_query($connect, "INSERT INTO `users` (`id`, `full_name`, `login`, `email`, `password`) VALUES (NULL, '$full_name', '$login', '$email', '$password')");
			$_SESSION['message'] = 'Registration completed successfully';
			header('Location: ../index.php');

		} 
		else//если пароли не совпадают, то выводим сообщение
		{
			$_SESSION['message'] = 'Password mismatch';
			header('Location: ../register.php');
		}
	}*/
?>
