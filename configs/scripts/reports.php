<?php

date_default_timezone_set('America/Santiago');
require_once('../bd_files/bd_config.php');

$conn = connect_pdo();

//Mes y año del mes anterior
$prev_month = date('n', strtotime("-1 Month"));
$prev_year = date('Y', strtotime("-1 Month"));

//Dia y hora del mes que corre el script
$day_of_month = date('j');
$hour = date('H');


if ($day_of_month == 1 and $hour == '04') {

  echo "-------------- Realizando reporte de sismos " . $prev_month . "-" . $prev_year . " --------------\n";

  //Numero de sismos sensibles
  $stmt = $conn->prepare('SELECT COUNT(*) as n_sensibles FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local) = ? and sensible=1');
  $stmt->execute([$prev_month, $prev_year]);
  $n_sensibles = $stmt->fetch(PDO::FETCH_ASSOC);

  //Promedios, Conteo, Maximos y Minimos mensuales
  $stmt = $conn->prepare('SELECT COUNT(*) as n_sismos,AVG(magnitud) as prom_magnitud , AVG(profundidad) as prom_profundidad, MAX(magnitud) as max_magnitud, MIN(profundidad) as min_profundidad FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local) = ?');
  $stmt->execute([$prev_month, $prev_year]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $n_sismos = $result['n_sismos'];
  $prom_magnitud = $result['prom_magnitud'];
  $prom_profundidad = $result['prom_profundidad'];
  $max_magnitud = $result['max_magnitud'];
  $min_profundidad = $result['min_profundidad'];


  $stmt = $conn->prepare('INSERT INTO reports (n_sensibles, n_sismos, prom_magnitud, prom_profundidad, max_magnitud, min_profundidad) VALUES (?,?,?,?,?,?)');
  $stmt->execute(array(
    $n_sensibles['n_sensibles'], $n_sismos, $prom_magnitud, $prom_profundidad, $max_magnitud, $min_profundidad
  ));
  $last_id = $conn->lastInsertId();

  //Top 4 ciudades mas sismicas
  $stmt = $conn->prepare('SELECT ciudad,COUNT(*) as n_sismos_ciudad FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local)=? GROUP BY ciudad ORDER BY COUNT(*) DESC LIMIT 4');
  $stmt->execute([$prev_month, $prev_year]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  for ($i = 0; $i < 4; $i++) {

    $ciudad = $result[$i];
    //Insertar ciudades top sismos
    $stmt = $conn->prepare('INSERT INTO quakes_city (id_reports, ciudad, n_sismos) VALUES (?,?,?)');
    $stmt->execute(array(
      $last_id, $ciudad['ciudad'], $ciudad['n_sismos_ciudad']
    ));
  }
} else {
  echo "REPORTE NO PERMITIDO\n";
}

$conn = null;
