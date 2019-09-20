<?php
date_default_timezone_set('America/Santiago');

require_once('bd_files/DynamoAdapter.php');
require_once('bd_files/MysqlAdapter.php');
require_once('Sismo.php');

$prev_month = date('n', strtotime('-1 Month'));
$day_of_month = date('j');
$hour = date('H');


//4 AM del primer dia del mes, hacer respaldo del mes anterior


if ($day_of_month == 1 and $hour == '04') {
    echo "------------BACKUP SISMOS - DYNAMO DB------------\n";

    $mysql_adapter = new MysqlAdapter("prod");
    $dynamo_adapter = new DynamoAdapter();

    $result = $mysql_adapter->findQuakesOfMonth($prev_month);

    foreach ($result as $item) {
        $quake = new Sismo();

        $quake->setFechaLocal($item['fecha_local']);
        $quake->setFechaUTC($item['fecha_utc']);
        $quake->setCiudad($item['ciudad']);
        $quake->setRefGeograf($item['referencia']);
        $quake->setMagnitud($item['magnitud']);
        $quake->setEscala($item['escala']);
        $quake->setSensible($item['sensible']);
        $quake->setLatitud($item['latitud']);
        $quake->setLongitud($item['longitud']);
        $quake->setProfundidad($item['profundidad']);
        $quake->setAgencia($item['agencia']);
        $quake->setImagen($item['imagen']);
        $quake->setEstado($item['estado']);

        $dynamo_adapter->addQuake($quake);
    }

    $mysql_adapter->close();
} else {
    echo "BACKUP NO PERMITIDO\n";
}
