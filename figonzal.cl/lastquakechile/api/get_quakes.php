<?php

header('Content-type: application/json; charset=UTF-8');
require_once "../../../configs/bd_files/bd_config.php";


if ($_SERVER['REQUEST_METHOD']=='GET'){

    $sql = "SELECT * FROM quakes";
    
    /**
     * Resuelve peticiones de fechas
     * procesa solicitudes de fecha con limite y sin limite
     */
    if (isset($_GET['anno']) and isset($_GET['mes']) ) {

        $anno = $_GET['anno'];
        $mes = $_GET['mes'];
        $dia = null;
        
        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=$anno and Month(fecha_local)=$mes";

        $params = array(
            'anno' => $anno,
            'mes' => $mes
        );

        if (isset($_GET['dia']) and !empty($_GET['dia'])) {
            $dia = $_GET['dia'];
            $sql = $sql." and Day(fecha_local)=$dia";

            $params['dia'] = $dia;
        }

        $sql = $sql." ORDER BY fecha_local DESC";

        //Limite dado por usuario
        if (isset($_GET['limite']) and !empty($_GET['limite'])){

            $limite = $_GET['limite'];

            if($limite>400){
                $sql = $sql." LIMIT 400";
                $params['default_limite'] = '400'; 
            }else{
                $sql = $sql." LIMIT $limite";
                $params['limite'] = $limite;
            }
            

        }else{
            //Limite por defecto
            $sql= $sql." LIMIT 400";
            $params['default_limite'] = '400';
        }
        

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }


    /**
     * Resuelve peticiones de ciudades con y sin limite
     */
    else if(isset($_GET['ciudad'])and !empty($_GET['ciudad'])){

        $ciudad = $_GET['ciudad'];
        $sql = $sql." WHERE ciudad LIKE '%$ciudad%'";

        $params['ciudad']=$ciudad;

        //NO VALE LA PENA ARGUMENTO MAGNITUD CON CIUDAD
        /*if (isset($_GET['magnitud']) and !empty($_GET['magnitud'])){
            $magnitud= $_GET['magnitud'];
            $params['magnitud']=$magnitud;
            $sql = $sql." AND magnitud=$magnitud";
        }*/

        $sql = $sql." ORDER BY fecha_local DESC";

        //Limite dado por usuario
        if (isset($_GET['limite']) and !empty($_GET['limite'])){

            $limite = $_GET['limite'];

            if($limite>400){
                $sql = $sql." LIMIT 400";
                $params['default_limite'] = '400'; 
            }else{
                $sql = $sql." LIMIT $limite";
                $params['limite'] = $limite;
            }

        }else{
            //Limite por defecto
            $sql= $sql." LIMIT 400";
            $params['default_limite'] = '400';
        }

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Resuelve peticiones de magnitud
     */
    else if (isset($_GET['magnitud']) and !empty($_GET['magnitud'])){

        $magnitud = $_GET['magnitud'];
        $sql = $sql." WHERE magnitud=$magnitud";

        $params['magnitud']=$magnitud;

        $sql = $sql." ORDER BY fecha_local DESC";
        
        //Limite dado por usuario
        if (isset($_GET['limite']) and !empty($_GET['limite'])){

            $limite = $_GET['limite'];

            if($limite>400){
                $sql = $sql." LIMIT 400";
                $params['default_limite'] = '400'; 
            }else{
                $sql = $sql." LIMIT $limite";
                $params['limite'] = $limite;
            }

        }else{
            //Limite por defecto
            $sql= $sql." LIMIT 400";
            $params['default_limite'] = '400';
        }

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Resuelve peticiones de top ranking
     */
    else if (isset($_GET['ranking']) and !empty($_GET['ranking'])){

        $ranking = $_GET['ranking'];

        if ($ranking=='magnitud'){
            $sql = $sql." ORDER BY magnitud DESC";
            $params['ranking']=$ranking;
        }
        else if ($ranking=='profundidad'){
            $sql= $sql." ORDER BY profundidad DESC";
            $params['ranking']=$ranking;
        }
        else{
            http_response_code(400);
            exit();
        }

        //Limite dado por usuario
        if (isset($_GET['limite']) and !empty($_GET['limite'])){

            $limite = $_GET['limite'];

            if($limite>400){
                $sql = $sql." LIMIT 400";
                $params['default_limite'] = '400'; 
            }else{
                $sql = $sql." LIMIT $limite";
                $params['limite'] = $limite;
            }

        }else{
            //Limite por defecto
            $sql= $sql." LIMIT 400";
            $params['default_limite'] = '400';
        }

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        
    }

    /**
     * Procesa solo limites de registros
     */
    else if (isset($_GET['limite']) and !empty($_GET['limite'])){
        $limite = $_GET['limite'];

        $sql = $sql." ORDER BY fecha_local DESC";
        $limite = $_GET['limite'];
    
        if($limite>=400){
            $sql = $sql." LIMIT 400";
            $params['default_limite'] = '400'; 
        }else{
            $sql = $sql." LIMIT $limite";
            $params['limite'] = $limite;
        }

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    //Si no user no entrega opciones, 400 limite por defecto
    else if (!isset($_GET['limite'])){
        $sql = $sql." ORDER BY fecha_local DESC LIMIT 400";
        $params['default_limite']='400';

        $quakes = doQuery($sql);
        echo json_encode(
            array(
                'parametros' => $params,
                'sismos' => $quakes
            ),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    //URL mal escrita error 400
    else{
        http_response_code(400);
        exit();
    }
}

//Si no es get , error 405
else{
    http_response_code(501);
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
                'ciudad' => $value['ciudad'],
                'referencia' => $value['referencia'],
                'magnitud' => $value['magnitud'],
                'escala' => $value['escala'],
                'sensible' => $value['sensible'],
                'latitud' => $value['latitud'],
                'longitud' => $value['longitud'],
                'profundidad' => $value['profundidad'],
                'agencia' => $value['agencia'],
                'imagen_url' => $value['imagen'],
                'estado' => $value['estado']
            )
        );
    }
    $pdo = null;

    return $list;
}

 