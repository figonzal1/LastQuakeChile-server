<?php

declare(strict_types=1);

require_once __DIR__ . '../../configs/MysqlAdapter.php';

use LastQuakeChile\Database\MysqlAdapter;

date_default_timezone_set('America/Santiago');

//Test | Prod Mode
$run_in = $argv[1];

//Mes y año del mes anterior
$prev_month = date('n', strtotime('-1 Month'));
$prev_year = date('Y', strtotime('-1 Month'));
$month_report = date('Y-m', strtotime('-1 Month'));
$script_date = date('Y-m-d');

//Dia y hora del mes que corre el script
$day_of_month = date('j');

if ($day_of_month == 1) {

    echo "GENERANDO REPORTE\n";
    echo "MES REPORTE: " . $month_report . "\n";
    echo "FECHA SCRIPT: " . $script_date . "\n";

    $mysql_adapter = new MysqlAdapter($run_in);
    $conn = $mysql_adapter->connect();

    if ($conn !== false) {

        try {

            //Numero de sismos sensibles
            $stmt = $conn->prepare("SELECT COUNT(*) as n_sensibles FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local) = ? and sensible=1");
            $stmt->execute([$prev_month, $prev_year]);
            $n_sensibles = $stmt->fetch(PDO::FETCH_ASSOC);

            $conn->beginTransaction();

            //Promedios, Conteo, Maximos y Minimos mensuales
            $stmt = $conn->prepare("SELECT COUNT(*) as n_sismos,AVG(magnitud) as prom_magnitud , AVG(profundidad) as prom_profundidad, MAX(magnitud) as max_magnitud, MIN(profundidad) as min_profundidad FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local) = ?");
            $stmt->execute([$prev_month, $prev_year]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $n_sismos = $result['n_sismos'];
            $prom_magnitud = $result['prom_magnitud'];
            $prom_profundidad = $result['prom_profundidad'];
            $max_magnitud = $result['max_magnitud'];
            $min_profundidad = $result['min_profundidad'];

            $stmt = $conn->prepare("INSERT INTO reports (fecha_script,mes_reporte,n_sensibles, n_sismos, prom_magnitud, prom_profundidad, max_magnitud, min_profundidad) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute(array(
                $script_date, $month_report, $n_sensibles['n_sensibles'], $n_sismos, $prom_magnitud, $prom_profundidad, $max_magnitud, $min_profundidad
            ));
            $last_id = $conn->lastInsertId();

            //Top 4 ciudades mas sismicas
            $stmt = $conn->prepare("SELECT ciudad,COUNT(*) as n_sismos_ciudad FROM quakes WHERE Month(fecha_local) = ? and Year(fecha_local)=? GROUP BY ciudad ORDER BY COUNT(*) DESC LIMIT 4");
            $stmt->execute([$prev_month, $prev_year]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < 4; $i++) {

                $ciudad = $result[$i];
                //Insertar ciudades top sismos
                $stmt = $conn->prepare("INSERT INTO quakes_city (id_reports, ciudad, n_sismos) VALUES (?,?,?)");
                $stmt->execute(array(
                    $last_id, $ciudad['ciudad'], $ciudad['n_sismos_ciudad']
                ));
            }

            $conn->commit();
        } catch (PDOException $e) {
            error_log("Fallo en la transaccion: " . $e->getMessage(), 0);
            $conn->rollBack();
        }

        $mysql_adapter->close();
    }

    //Status de error de conexion
    else {
        error_log("Error de conexion", 0);
        http_response_code(500);
        exit();
    }
} else {
    error_log("REPORTE NO PERMITIDO", 0);
    exit();
}
