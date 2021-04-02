<?php
include 'database.php';
try {
	$pdo = new PDO("mysql:host=$DB_HOST", $DB_USERNAME, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec("CREATE DATABASE IF NOT EXISTS $DB_NAME");
	//echo "Database '$DB_NAME' created successfully.";
} catch(PDOException $e){
	echo 'Error creating to DataBase: ' . $e->getMessage();
}

try {
	$dbt = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;", $DB_USERNAME, $DB_PASSWORD);
	$dbt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbt->exec("CREATE TABLE IF NOT EXISTS `users` (
		    `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `full_name` varchar(355) NOT NULL,
			`login` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `password` varchar(255) NOT NULL,
			`state` varchar(255) NOT NULL,
			`forgot` varchar(255) DEFAULT 'NULL')");
	//echo " Table 'users' created successfully.";
} catch(PDOException $e){
	echo 'Error creating USERS table": ' . $e->getMessage();
}

try {
	$dbt = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;", $DB_USERNAME, $DB_PASSWORD);
	$dbt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbt->exec("CREATE TABLE IF NOT EXISTS `pictures` (
		    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`date` date NOT NULL,
			`login` varchar(100) NOT NULL,
			`img` varchar(255) NOT NULL)");
	//echo " Table 'pictures' created successfully.";
} catch(PDOException $e){
	echo 'Error creating PICTURES table": ' . $e->getMessage();
}

try {
	$dbt = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;", $DB_USERNAME, $DB_PASSWORD);
	$dbt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbt->exec("CREATE TABLE IF NOT EXISTS `like` (
		    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`login` varchar(100) NOT NULL,
			`img_id` varchar(255) NOT NULL)");
	//echo " Table 'like' created successfully.";
} catch(PDOException $e){
	echo 'Error creating LIKE table": ' . $e->getMessage();
}

try {
	$dbt = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;", $DB_USERNAME, $DB_PASSWORD);
	$dbt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbt->exec("CREATE TABLE IF NOT EXISTS `comment` (
		    `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`login` varchar(100) NOT NULL,
			`comment` varchar(255) NOT NULL,
			`img_id` varchar(255) NOT NULL)");
	//echo " Table 'comment' created successfully.";
} catch(PDOException $e){
	echo 'Error creating COMMENT table": ' . $e->getMessage();
}
?>
