<?php

/*
	BD config conexion
 */

function connect(){
	$servername ="localhost";
	$username="lastquake";
	$password="rokita6195236";
	$bd="lastquake";

	try{
		$conn = new PDO("mysql:host=$servername;dbname=$bd",$username,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		echo "Connected Succesfully<br>";
		return $conn;
	}
	catch(PDOException $e){
		echo "Connection failed: ".$e->getMessage();
	}
}

?>