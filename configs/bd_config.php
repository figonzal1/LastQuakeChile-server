<?php

/*
	Clear DB config connection to Heroku
 */

	function connect_pdo(){

	/*
		VISIT: https://www.cleardb.com/dashboard
	 */

		//OLD SERVER
	/*$servername ="us-cdbr-iron-east-01.cleardb.net";
	$username="b3859152bed189";
	$password="1dc50d18";
	$bd="heroku_166fce7778cfb80";*/

	$servername="us-cdbr-iron-east-01.cleardb.net";
	$username="bd0e87518d1d3c";
	$password="e0e3de85";
	$bd="heroku_94ad8af7a05c2f1";

	/*$servername= "localhost";
	$username ="figonzal";
	$password = "rokita6195236";
	$bd="lastquake";*/

	try{
		$conn = new PDO("mysql:host=$servername;dbname=$bd",$username,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		//echo "Connected Succesfully";
		return $conn;
	}
	catch(PDOException $e){
		echo "Connection failed: ".$e->getMessage();
	}
}
?>