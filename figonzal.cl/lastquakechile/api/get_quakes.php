<?php

header('Content-type: application/json; charset=UTF-8');
require_once "../../../configs/bd_files/bd_config.php";


if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    if (queryDates()[0]) {
        $quakes = queryDates()[1];
        $params = queryDates()[2];
    } else if (queryCity()[0]) {
        $quakes = queryCity()[1];
        $params = queryCity()[2];
    } else if (queryMagnitud()[0]) {
        $quakes = queryMagnitud()[1];
        $params = queryMagnitud()[2];
    } else if (queryTopRanking()[0]) {
        $quakes = queryTopRanking()[1];
        $params = queryTopRanking()[2];
    } else if (queryToLimit()[0]) {
        $quakes = queryToLimit()[1];
        $params = queryToLimit()[2];
    }
    //URL mal escrita error 400
    //Error para rutas mal escritas
    else {
        http_response_code(400);
        exit();
    }

    echo json_encode(
        array(
            'parametros' => $params,
            'sismos' => $quakes
        ),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );
}

//Si no es get , error 501
else {
    http_response_code(501);
    exit();
}



/**
 * Funcion encargada de procesar los registros y pasarlos a un arreglo con columnas específicas.
 */
function processRows($stmt)
{
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
    return $list;
}

/**
 * Funcion que maneja las consultas referentes a fechas
 */
function queryDates()
{
    $conn = connect_pdo();
    $status_query = false;
    //Todos los parametros
    if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['dia']) and !empty($_GET['dia']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        $anno = $_GET['anno'];
        $mes = $_GET['mes'];
        $dia = $_GET['dia'];
        $limite = $_GET['limite'];

        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=:anno and Month(fecha_local)=:mes and Day(fecha_local)=:dia ORDER BY fecha_local DESC LIMIT :limite";

        $params = array(
            'anno' => $anno,
            'mes' => $mes,
            'dia' => $dia,
            'limite' => $limite
        );

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':anno', $anno);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }

    //Año-mes-dia sin limite
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['dia']) and !empty($_GET['dia'])) {

        $anno = $_GET['anno'];
        $mes = $_GET['mes'];
        $dia = $_GET['dia'];

        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=:anno and Month(fecha_local)=:mes and Day(fecha_local)=:dia ORDER BY fecha_local DESC ";

        $params = array(
            'anno' => $anno,
            'mes' => $mes,
            'dia' => $dia
        );
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':anno', $anno);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':dia', $dia);

        $status_query = true;
    }

    //Ano-mes con limite
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['limite']) and !empty($_GET['limite'])) {
        $anno = $_GET['anno'];
        $mes = $_GET['mes'];
        $limite = $_GET['limite'];

        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=:anno and Month(fecha_local)=:mes ORDER BY fecha_local DESC LIMIT :limite";

        $params = array(
            'anno' => $anno,
            'mes' => $mes,
            'limite' => $limite
        );

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':anno', $anno);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }
    //Año mes sin limite
    else if (isset($_GET['anno']) and isset($_GET['mes'])) {
        $anno = $_GET['anno'];
        $mes = $_GET['mes'];

        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=:anno and Month(fecha_local)=:mes ORDER BY fecha_local DESC ";

        $params = array(
            'anno' => $anno,
            'mes' => $mes
        );

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':anno', $anno);
        $stmt->bindParam(':mes', $mes);

        $status_query = true;
    }


    if ($status_query) {
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else {
        $conn = null;
        return [$status_query];
    }
}

/**
 * Funcion encargada de procesar las peticiones de ciudades.
 */
function queryCity()
{

    $conn = connect_pdo();
    $status_query = false;

    //Resuelve peticiones de ciudades con limite
    if (isset($_GET['ciudad']) and !empty($_GET['ciudad']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        $sql = "SELECT * FROM quakes WHERE ciudad LIKE CONCAT('%',:ciudad,'%') ORDER BY fecha_local DESC LIMIT :limite";
        $ciudad = $_GET['ciudad'];
        $limite = $_GET['limite'];

        $params['ciudad'] = $ciudad;
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }

    //Resuelve peticion de ciudades sin limite 
    else if (isset($_GET['ciudad']) and !empty($_GET['ciudad'])) {
        $sql = "SELECT * FROM quakes WHERE ciudad LIKE CONCAT('%',:ciudad,'%') ORDER BY fecha_local DESC";
        $ciudad = $_GET['ciudad'];

        $params['ciudad'] = $ciudad;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ciudad', $ciudad);

        $status_query = true;
    }

    //logica de returns
    if ($status_query) {
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else {
        $conn = null;
        return [$status_query];
    }
}

/**
 * Funcion encargada de procesar las peticiones de magnitud
 */
function queryMagnitud()
{

    $conn = connect_pdo();
    $status_query = false;

    //Resuelve peticiones de magnitudes con limite
    if (isset($_GET['magnitud']) and !empty($_GET['magnitud']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        $magnitud = $_GET['magnitud'];
        $limite = $_GET['limite'];

        $params['magnitud'] = $magnitud;
        $params['limite'] = $limite;

        $sql = "SELECT * FROM quakes WHERE magnitud=:magnitud ORDER BY fecha_local DESC LIMIT :limite";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':magnitud', $magnitud);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }
    //Resuelve peticiones de magnitud sin limite
    else if (isset($_GET['magnitud']) and !empty($_GET['magnitud'])) {

        $magnitud = $_GET['magnitud'];

        $params['magnitud'] = $magnitud;

        $sql = "SELECT * FROM quakes WHERE magnitud=:magnitud ORDER BY fecha_local DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':magnitud', $magnitud);

        $status_query = true;
    }

    if ($status_query) {
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else {
        $conn = null;
        return [$status_query];
    }
}

/**
 * Funcion encargada de procesar las peticiones de rankings
 */
function queryTopRanking()
{

    $conn = connect_pdo();
    $status_query = false;

    //Procesa ranking con limite incluido
    if (isset($_GET['ranking']) and !empty($_GET['ranking']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        /**
         * ORDER BY solo funciona en PDO con el numero de la columna en BD
         */
        $sql = "SELECT * FROM quakes ORDER BY :ranking DESC LIMIT :limite";

        $ranking = $_GET['ranking'];
        $limite = $_GET['limite'];

        if ($ranking == 'magnitud') {
            //Mirar tabla MYSQL
            $ranking_column = 6;
        } else if ($ranking == 'profundidad') {
            //Mirar tabla MYSQL
            $ranking_column = 11;
        }
        $params['ranking'] = $ranking;
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ranking', $ranking_column, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }

    //Procesa ranking sin limite incluido
    else if (isset($_GET['ranking']) and !empty($_GET['ranking'])) {
        $sql = "SELECT * FROM quakes ORDER BY :ranking DESC";

        $ranking = $_GET['ranking'];

        if ($ranking == 'magnitud') {
            //Mirar tabla MYSQL
            $ranking_column = 6;
        } else if ($ranking == 'profundidad') {
            //Mirar tabla MYSQL
            $ranking_column = 11;
        }
        $params['ranking'] = $ranking;

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ranking', $ranking_column, PDO::PARAM_INT);
        $status_query = true;
    }

    if ($status_query) {
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else {
        $conn = null;
        return [$status_query];
    }
}

/**
 * Funcion encargada de limitar la cantidad de registros entregados
 */
function queryToLimit()
{

    $conn = connect_pdo();
    $status_query = false;

    //Procesa solicitudes con limite
    if (isset($_GET['limite']) and !empty($_GET['limite'])) {

        $sql = "SELECT * FROM quakes ORDER BY fecha_local DESC LIMIT :limite";

        $limite = $_GET['limite'];
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
    }

    if ($status_query) {
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else {
        $conn = null;
        return [$status_query];
    }
}
