<?php
	include_once '../config/database.php';
	if (!file_exists('../filters')){
		mkdir('../filters', 0777, true);
	}

	$ph = $_POST['img'];
	$ph = str_replace('data:image/png;base64,', '', $ph);
	$info = base64_decode($ph);
	$imag = '../filters/'.time().'.png';
	$itog = file_put_contents($imag, $info);

	try {
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('INSERT INTO pictures (login, img) VALUES (:login, :imag)');
		$sth->bindParam(':login', $_POST['user']['login'], PDO::PARAM_STR);
		$sth->bindParam(':file', $imag, PDO::PARAM_STR);
		$sth->execute();
	} catch (PDOException $e) {
		echo "Error".$e->getMessage();
	}

?>
