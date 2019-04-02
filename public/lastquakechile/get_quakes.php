<?php
header('Content-type: application/json; charset=UTF-8');
require_once "../configs/bd_config.php";
require_once "../configs/send_notification.php";

$pdo = connect_pdo();

$sql="SELECT * FROM quakes ORDER BY fecha_local DESC LIMIT 15";
$stmt=$pdo ->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$list=array();
foreach ($rows as $key=>$value) {

	array_push($list,
		array('fecha_local' => $value['fecha_local'],
			'fecha_utc' => $value['fecha_utc'],
			'latitud' => $value['latitud'],
			'longitud' => $value['longitud'],
			'magnitud' => $value['magnitud'],
			'escala' => $value['escala'],
			'profundidad' => $value['profundidad'],
			'sensible' => $value['sensible'],
			'agencia' => $value['agencia'],
			'referencia' => $value['referencia'],
			'imagen_url' => $value['imagen'],
			'estado' => $value['estado'])
	);
}

$pdo =null;
echo $quakes_json=json_encode(array('quakes' => $list),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
