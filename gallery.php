<?php
	session_start();
	if (isset($_SESSION['user'])) {
		if (!$_GET['page']) {
			header('Location: gallery.php?page=1');
		}
		
	} else {
		$_SESSION['message'] = 'You must log in to access this page';
		header('Location: ../index.php');
	}
	
	include_once 'config/database.php';
	$perpage = 10;
	$start_pos = ($_GET['page'] - 1) * $perpage;

	try {
		$dbh = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sth = $dbh->prepare('SELECT COUNT(*) FROM pictures LIMIT 10 OFFSET :start_pos');
		$sth->bindParam(':start_pos', $start_pos, PDO::PARAM_INT);
		$sth->execute();
	} catch(PDOException $e){
		echo 'Error: ' . $e->getMessage();
		exit();
	}

	$res_gallery = $sth->fetchAll();
	//если не нашлось результата с базы данных
	if(!$res_gallery) {
		//если нет картинок для новой страницы,то возвращаемся на предыдущую страницу
		if ($_GET['page'] > 1){
			$back = $_GET['page'] - 1;
			header('Location: gallery.php?page=$back');
			exit();
		//если вообще нет картинок
		} else {
			$_SESSION['message'] = 'You must log in to access this page';
			header('Location: ../index.php');
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<title>Camagru</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="main.css">
	</head>
	<body>
		<article class="bobo">
			<div class="cont">
				<?php
				echo 'Hello world';
				foreach ($res_gallery as $key => $value){
					echo "<div class='imgboxbox'>";
					echo "<img src='$value[img]' style='width:426px;'>
					<br/>
					Poster par : <i>$value[login]";

				}
				echo '</div><center>
				<ul class="pagination">';
				try {
					$sth = $dbh->prepare('SELECT COUNT(*) FROM pictures');
					$sth->execute();
				} catch (PDOException $e) {
					echo 'Error: '.$e->getMessage();
					exit;
				}
				//тут не совсем понимаю что происходит
				$nbpage = ($sth->fetchColumn() - 1) / 10 + 1;
				$prev = $_GET['page'] - 1;
				if ($prev > 0) {
					echo "<li><a href='?page=$prev'>«</a></li>";
				}
				for ($i = 1; $i <= $nbpage; ++$i) {
					echo "<li><a href='?page=$i'>$i</a></li>";
				}
				$next = $_GET['page'] + 1;
				if ($next < $nbpage) {
					echo "<li><a href='?page=$next'>»</a></li>";
				}
				echo '</ul></center>';
				?>
			</div>
		</article>
	</body>
</html>
