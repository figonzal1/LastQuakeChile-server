<?php

/*
	BD config conexion
 */

function connect(){
	$servername="mysql1";
	$username="lastquake";
	$password="rokita6195236";
	$bd="lastquake";

	$conn = new mysqli($servername,$username,$password,$bd);

	if($conn->connect_error){
		die("Connection failed: ".$conn->connect_error);
	}

	return $conn;
	

}
?>