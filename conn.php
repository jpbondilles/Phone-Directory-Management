<?php

$dbname = "employmentexam";
$username = "root";
$password = "";
$host = "mysql:host=localhost;dbname=$dbname";

try {
	
	$conn = new PDO($host, $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	/*echo "Connected successfully";*/

} catch (Exception $e) {

	echo "Connection failed: " . $e->getMessage();
}

?>