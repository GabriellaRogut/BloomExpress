<?php
	session_start();

	$signUpSuccess = false;
	$userIn = false;

	// to set $userIn out of Session:
	// if ( isset( $_SESSION['userIn'] ) && $_SESSION['userIn'] == true ){
	//         $userIn = true; 
	// }

	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "BloomExpress";

	// once the page loads, this does not save, unless it's saved in session

	try {
		$connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
?>



