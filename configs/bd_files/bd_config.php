<?php

use JmesPath\Env;

require_once __DIR__ . "/../../vendor/autoload.php";

/**
 * Clear DB config connection to Heroku
 */
function connect_pdo()
{

	/*
		VISIT: https://www.cleardb.com/dashboard
	 */

	$servername = "s-cdbr-iron-east-01.cleardb.net";
	$username = "bd0e87518d1d3c";
	$password = "e0e3de85";
	$bd = "heroku_94ad8af7a05c2f1";
	/*
	$servername="localhost";
	$username="root";
	$password="";
	$bd="heroku_94ad8af7a05c2f1";
	*/
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$bd", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected Succesfully";
		return $conn;
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
}

/**
 * Amazon dynamoDB config
 */
function connect_amazon()
{

	$credentials = new Aws\Credentials\Credentials(getenv('AWS_ACCESS_KEY'), getenv('AWS_SECRET_ACCESS_KEY'));

	$sdk = new Aws\Sdk([
		'region'   => 'us-east-1',
		'version'  => 'latest',
		'credentials' => $credentials
	]);

	/*$sdk = new Aws\Sdk([
		'region'   => 'us-east-1',
		'version'  => 'latest'
	]);*/

	$dynamodb = $sdk->createDynamoDb();
	return $dynamodb;
}
