<?php

header('Content-type: application/json; charset=UTF-8');
require_once "../../../configs/bd_files/bd_config.php";

$conn = connect_pdo();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM reports ORDER BY fecha_reporte DESC";

    if (isset($_GET['anno']) and isset($_GET['mes'])) {

        $anno = $_GET['anno'];
        $mes = $_GET['mes'];

        $params['anno'] = $anno;
        $params['mes'] = $mes;

        $sql = "SELECT * FROM reports WHERE Month(fecha_reporte)= ? and Year(fecha_reporte)= ? ORDER BY fecha_reporte DESC";
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

        $item = array(
            "fecha_reporte" => $reports[$i]['fecha_reporte'],
            "n_sismos" => $reports[$i]['n_sismos'],
            "n_sensibles" => $reports[$i]['n_sensibles'],
            "prom_magnitud" => $reports[$i]['prom_magnitud'],
            "prom_profundidad" => $reports[$i]['prom_profundidad'],
            "max_magnitud" => $reports[$i]['max_magnitud'],
            "min_profundidad" => $reports[$i]['min_profundidad'],
            "top_ciudades" => array(
                array("ciudad" => $citys[0]['ciudad'], "n_sismos" => $citys[0]['n_sismos']),
                array("ciudad" => $citys[1]['ciudad'], "n_sismos" => $citys[1]['n_sismos']),
                array("ciudad" => $citys[2]['ciudad'], "n_sismos" => $citys[2]['n_sismos']),
                array("ciudad" => $citys[3]['ciudad'], "n_sismos" => $citys[3]['n_sismos']),
            )
        );

        array_push($lista, $item);
    }

    if (sizeof($params) > 0) {
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
//Si no es get , error 501
else {
    http_response_code(501);
    exit();
}
