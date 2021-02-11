<?php

declare(strict_types=1);

use LastQuakeChile\Database\MysqlAdapter;

header('Cache-Control: no-cache');
header('X-Content-Type-Options: nosniff');
header('Content-type: application/json; charset=UTF-8');

require_once __DIR__ . '../../../../../src/configs/MysqlAdapter.php'; //Heroku
#require_once '/var/www/src/configs/MysqlAdapter.php'; //Docker

/**
 * @deprecated
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $mysql_adapter = new MysqlAdapter("prod");
    $conn = $mysql_adapter->connect();

    if ($conn != null) {

        $sql = "SELECT * FROM reports ORDER BY mes_reporte DESC";

        if (isset($_GET['anno']) and isset($_GET['mes'])) {

            $anno = htmlentities($_GET['anno']);
            $mes = htmlentities($_GET['mes']);

            $params['anno'] = $anno;
            $params['mes'] = $mes;

            $sql = "SELECT * FROM reports WHERE mes_reporte LIKE '%" . $anno . "-" . $mes . "%' ORDER BY mes_reporte DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$mes, $anno]);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }

        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = array();
        for ($i = 0; $i < sizeof($reports); $i++) {
            $id_reports = $reports[$i]['id_reports'];

            $sql = "SELECT * FROM quakes_city WHERE id_reports= ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_reports]);

            $citys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Preparar respuesta
            $item = array(
                'fecha_script' => $reports[$i]['fecha_script'],
                'mes_reporte' => $reports[$i]['mes_reporte'],
                'n_sismos' => $reports[$i]['n_sismos'],
                'n_sensibles' => $reports[$i]['n_sensibles'],
                'prom_magnitud' => $reports[$i]['prom_magnitud'],
                'prom_profundidad' => $reports[$i]['prom_profundidad'],
                'max_magnitud' => $reports[$i]['max_magnitud'],
                'min_profundidad' => $reports[$i]['min_profundidad'],
                'top_ciudades' => array(
                    array('ciudad' => $citys[0]['ciudad'], 'n_sismos' => $citys[0]['n_sismos']),
                    array('ciudad' => $citys[1]['ciudad'], 'n_sismos' => $citys[1]['n_sismos']),
                    array('ciudad' => $citys[2]['ciudad'], 'n_sismos' => $citys[2]['n_sismos']),
                    array('ciudad' => $citys[3]['ciudad'], 'n_sismos' => $citys[3]['n_sismos']),
                )
            );

            array_push($lista, $item);
        }

        if (isset($params) and sizeof($params) > 0) {
            echo json_encode(
                array(
                    'parametros' => $params,
                    'reportes' => $lista
                ),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        } else {
            echo json_encode(
                array(
                    'reportes' => $lista
                ),
                JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        }
        $conn = null;
    }

    //Status de error de conexion
    else {
        http_response_code(500);
        exit();
    }
}
//Si no es get , error 501
else {
    http_response_code(501);
    exit();
}
