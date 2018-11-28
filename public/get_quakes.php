<?php
	header('Content-type: application/json; charset=UTF-8');
	require_once "../configs/bd_config.php";
	$pdo = connect_pdo();

	$sql="SELECT * FROM quakes ORDER BY fecha_local DESC";
	$stmt=$pdo ->query($sql);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$list=array();
	foreach ($rows as $key) {
		array_push($list, array(
			'fecha_local' => $key['fecha_local'],
			'fecha_utc' => $key['fecha_utc'],
			'latitud' => $key['latitud'],
			'longitud' => $key['longitud'],
			'magnitud' => $key['magnitud'],
			'agencia' => $key['agencia'],
			'referencia' => $key['referencia'],
			'imagen' => $key['imagen']
		));
	}

	$pdo =null;

	echo json_encode($list,JSON_UNESCAPED_UNICODE |  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
