<?php
session_start();

$signUpSuccess = false;
$userIn = false;

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


function printrfunc($data){
        echo "<pre>";
        print_r( $data );
        exit;
}
?>



