<?php
	session_start();
	//если пользователь не зарегистрирован то говорим что он не может просматривать страницу
	if (!isset($_SESSION['user']))
	{
		$_SESSION['message'] = 'You must log in to access this page';
		header('Location: ../index.php');
		exit();
	}

	include_once '../config/database.php';
	header('Location: ../gallery.php?page=$_GET[page]');
	//если не пришли данные про айди изображения то сообщенька
	if (!isset($_GET['img_id'])){
		$_SESSION['message'] = 'Please fill in all the blanks';
		header('Location: ../gallery.php');
		exit();
	}
	//ищем в таблице лайк данные с таким же логином и айди изображения
	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('SELECT COUNT(*) FROM like WHERE login = :login AND img_id = :img_id');
		$sth->bindParam(':img_id', $_GET['img_id'], PDO::PARAM_INT);
		$sth->bindParam(':login', $_SESSION['user']['login'], PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}
	//если нашли в таблице лайк данные с таким же логином и айди изображения
	//то удаляем такие данные
	if ($sth->fetchColumn()) {
		try { 
			$sth = $dbh->prepare('DELETE FROM like WHERE login = :login AND img_id = :img_id');
			$sth->bindParam(':img_id', $_GET['img_id'], PDO::PARAM_INT);
			$sth->bindParam(':login', $_SESSION['user']['login'], PDO::PARAM_STR);
			$sth->execute();
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
			exit();
		}
	} else {
	//если не нашли в таблице лайк данные с таким же логином и айди изображения
	//то добавляем такие данные
		try { 
			$sth = $dbh->prepare('INSERT INTO like (login, img_id) VALUES (:login, :img_id)');
			$sth->bindParam(':img_id', $_GET['img_id'], PDO::PARAM_INT);
			$sth->bindParam(':login', $_SESSION['user']['login'], PDO::PARAM_STR);
			$sth->execute();
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
			exit();
		}
	}

?>
