<?php
	header('Location: ../gallery.php?page=$_GET[page]');
	session_start();
	include_once '../config/database.php';

	//удаляем в таблице с изображениями где совпадает логин и айди изображения
	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('DELETE FROM puctures WHERE login = :login AND id = :img');
		$sth->bindParam(':img', $_GET['img'], PDO::PARAM_INT);
		$sth->bindParam(':login', $_SESSION['user']['login'], PDO::PARAM_STR);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}
?>