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
			'imagen_url' => $value['imagen'])
	);

		//TESTING NOTIFICATION
	if (isset($_GET['send']) && !empty($_GET['send'])) {

		if ($value['magnitud']>5.0 && $_GET['send']=="1") {
			//SEND NOTIFICATION WITH UTC
			sendNotification($value['fecha_utc'],$value['profundidad'],$value['magnitud'],$value['escala'],$value['sensible'],$value['referencia'],$value['imagen']);
		}
	}
}

$pdo =null;
echo $quakes_json=json_encode(array('quakes' => $list),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
