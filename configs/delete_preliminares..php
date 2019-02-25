<?php

date_default_timezone_set('America/Santiago');
require_once("bd_config.php");
require_once("sismo_class.php");

$conn = connect_pdo();
	/*
		SECCION ELIMINACION DE PRELIMINARES
	 */
		$select = "SELECT * FROM quakes WHERE preliminar=1";
		$stmt= $conn->query($select);
		$stmt->execute();

		if ($stmt->rowCount()>0) {
			$delete="DELETE FROM quakes WHERE preliminar=1;";
			$stmt= $conn->query($delete);
			$stmt->execute();

			if (isset($_GET['web']) && $_GET['web']==1) {
				echo "Sismos preliminares eliminados<br>";
			}else{
				echo "Sismo preliminares eliminados\n";
			}
		}
		$conn = null;
		?>