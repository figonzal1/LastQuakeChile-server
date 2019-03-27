<?php

header('Content-type: application/json; charset=UTF-8');
require_once "../../configs/bd_config.php";


$sql = "SELECT * FROM quakes";

/**
 * Procesa solicitudes de fecha con limite y sin limite
 */
if (isset($_GET['anno']) and isset($_GET['mes']) ) {

    $anno = $_GET['anno'];
    $mes = $_GET['mes'];
    $dia = null;
    
    $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=$anno and Month(fecha_local)=$mes";

    $args = array(
        'anno' => $anno,
        'mes' => $mes
    );

    if (isset($_GET['dia']) and !empty($_GET['dia'])) {
        $dia = $_GET['dia'];
        $sql = $sql." and Day(fecha_local)=$dia";

        $args['dia'] = $dia;
    }

    $sql = $sql." ORDER BY fecha_local DESC";

    //Limite dado por usuario
    if (isset($_GET['limite']) and !empty($_GET['limite'])){

        $limite = $_GET['limite'];
        $sql = $sql." LIMIT $limite";
        $args['limite'] = $limite;

    }else{
        //Limite por defecto
        $sql= $sql." LIMIT 400";
        $args['default_limite'] = 400;
    }
    

    $quakes = doQuery($sql);
    echo json_encode(
        array(
            'http-code' => 200,
            'parameters' => $args,
            'quakes' => $quakes
        ),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );
}

/**
 * Resuelve peticiones de ciudades
 */
else if(isset($_GET['ciudad'])and !empty($_GET['ciudad'])){

    $ciudad = $_GET['ciudad'];
    $sql = $sql." WHERE referencia LIKE '%$ciudad%'";

    $args['ciudad']=$ciudad;

    $sql = $sql." ORDER BY fecha_local DESC";

    //Limite dado por usuario
    if (isset($_GET['limite']) and !empty($_GET['limite'])){

        $limite = $_GET['limite'];
        $sql = $sql." LIMIT $limite";
        $args['limite'] = $limite;

    }else{
        //Limite por defecto
        $sql= $sql." LIMIT 400";
        $args['default_limite'] = 400;
    }

    $quakes = doQuery($sql);
    echo json_encode(
        array(
            'http-code' => 200,
            'parameters' => $args,
            'quakes' => $quakes
        ),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );
}

/**
 * Procesa solo limtes
 */
else if (isset($_GET['limite']) and !empty($_GET['limite'])){
    $limite = $_GET['limite'];

    $sql = $sql." ORDER BY fecha_local DESC LIMIT $limite";
    $args['limite']=$limite;

    $quakes = doQuery($sql);
    echo json_encode(
        array(
            'http-code' => 200,
            'parameters' => $args,
            'quakes' => $quakes
        ),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );
}

else{
    http_response_code(404);
    exit();
}


function doQuery($sql)
{

    $pdo = connect_pdo();
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $rows = $stmt->fetchAll();
    $list = array();
    foreach ($rows as $key => $value) {

        array_push(
            $list,
            array(
                'fecha_local' => $value['fecha_local'],
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
                'estado' => $value['estado']
            )
        );
    }
    $pdo = null;

    return $list;
}

 