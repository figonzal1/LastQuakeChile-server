<?php

declare(strict_types=1);

use LastQuakeChile\Database\MysqlAdapter;

header('Cache-Control: public,no-cache,max-age=660,s-maxage=600,must-revalidate');
header('X-Content-Type-Options: nosniff');
header('Content-type: application/json; charset=UTF-8');

require_once __DIR__ . '../../../../../../src/configs/MysqlAdapter.php'; //Heroku
#require_once '/var/www/src/configs/MysqlAdapter.php'; //Docker

function startEndpoins()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $mysql_adapter = new MysqlAdapter("prod");
        $conn = $mysql_adapter->connect();

        if ($conn !== false) {
            $quakes = [];
            $params = [];


            if (isset($_GET['anno']) and isset($_GET['mes'])) {

                $result = queryDates($conn);
                if ($result[0]) {
                    $quakes = $result[1];
                    $params = $result[2];
                } else {
                    http_response_code(400);
                    exit();
                }
            } else if (isset($_GET['ciudad'])) {

                $result = queryCity($conn);
                if ($result[0]) {
                    $quakes = $result[1];
                    $params = $result[2];
                    unset($_GET['limite']);
                } else {
                    http_response_code(400);
                    exit();
                }
            } else if (isset($_GET['magnitud'])) {
                $result = queryMagnitud($conn);
                if ($result[0]) {
                    $quakes = $result[1];
                    $params = $result[2];
                } else {
                    http_response_code(400);
                    exit();
                }
            } else if (isset($_GET['ranking'])) {
                $result = queryTopRanking($conn);
                if ($result[0]) {
                    $quakes = $result[1];
                    $params = $result[2];
                } else {
                    http_response_code(400);
                    exit();
                }
            } else if (isset($_GET['limite'])) {
                $result = queryToLimit($conn);
                if ($result[0]) {
                    $quakes = $result[1];
                    $params = $result[2];
                } else {
                    http_response_code(400);
                    exit();
                }
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
        //Si conexion falla
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
}
startEndpoins();

/**
 * +---------------------+
 * +     Funciones       +
 * +    de utilidad      +
 * +---------------------+
 * 
 * 
 * TODO: Mejorar esta seccion y mover a app/helpers
 */
/**
 * Funcion encargada de procesar los registros y pasarlos a un arreglo con columnas específicas.
 */
function processRows(object $stmt): array
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

function queryDates(object $conn): array
{
    $status_query = false;


    //Año, mes ,dia con limite
    //Si el anno mes y dia estan definidos y son distintos de vacio
    //si el limite esta definido y es distinto de vacio
    if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['dia']) and !empty($_GET['dia']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        $anno = htmlentities($_GET['anno']);
        $mes = htmlentities($_GET['mes']);
        $dia = htmlentities($_GET['dia']);
        $limite = htmlentities($_GET['limite']);

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

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }

    //Año-mes-dia sin limite
    //Año mes y dia estan definidos y diferentes de vacio
    //Limite esta definido y es vacio
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['dia']) and !empty($_GET['dia']) and isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }
    //Añno mes y dia existentes y no vacios
    //Limite no esta definido
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['dia']) and !empty($_GET['dia']) and !isset($_GET['limite'])) {

        $anno = htmlentities($_GET['anno']);
        $mes = htmlentities($_GET['mes']);
        $dia = htmlentities($_GET['dia']);

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

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }

    //Ano-mes con limite
    //Año - mes existen y no distintos de nulo
    //Limite esta definido y es diferente de vacio
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['limite']) and !empty($_GET['limite'])) {
        $anno = htmlentities($_GET['anno']);
        $mes = htmlentities($_GET['mes']);
        $limite = htmlentities($_GET['limite']);

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

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
    //Ano-mes sin limite
    //Año - mes existen y son distintos de nulo
    //Limite esta definido y es vacio
    else if (isset($_GET['anno']) and isset($_GET['mes']) and isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }
    //Año mes sin limite
    //Año - mes existen y son distintos de nulo
    //Limite NO esta definido

    else if (isset($_GET['anno']) and isset($_GET['mes']) and !isset($_GET['limite'])) {
        $anno = htmlentities($_GET['anno']);
        $mes = htmlentities($_GET['mes']);

        $sql = "SELECT * FROM quakes WHERE Year(fecha_local)=:anno and Month(fecha_local)=:mes ORDER BY fecha_local DESC ";

        $params = array(
            'anno' => $anno,
            'mes' => $mes
        );

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':anno', $anno);
        $stmt->bindParam(':mes', $mes);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
}

/**
 * Funcion encargada de procesar las peticiones de ciudades.
 */
function queryCity(object $conn): array
{
    $status_query = false;

    //Resuelve peticiones de ciudades con limite
    //Si ciudad esta definido y es distino de vacio
    //Si limite esta definido y es distinto de vacio
    if (isset($_GET['ciudad']) and !empty($_GET['ciudad']) and isset($_GET['limite']) and !empty($_GET['limite'])) {


        $sql = "SELECT * FROM quakes WHERE ciudad LIKE CONCAT('%',:ciudad,'%') ORDER BY fecha_local DESC LIMIT :limite";
        $ciudad = htmlentities($_GET['ciudad']);
        $limite = htmlentities($_GET['limite']);

        $params['ciudad'] = $ciudad;
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;
        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
    //Si ciudad esta definido y es distino de vacio
    //Si limite esta esta definido y es vacio
    else if (isset($_GET['ciudad']) and !empty($_GET['ciudad']) and isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }

    //Si ciudad esta definido y es distino de vacio
    //Si limite NO esta esta definido
    else if (isset($_GET['ciudad']) and !empty($_GET['ciudad']) and !isset($_GET['limite'])) {

        $sql = "SELECT * FROM quakes WHERE ciudad LIKE CONCAT('%',:ciudad,'%') ORDER BY fecha_local DESC";
        $ciudad = htmlentities($_GET['ciudad']);

        $params['ciudad'] = $ciudad;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ciudad', $ciudad);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
}

/**
 * Funcion encargada de procesar las peticiones de magnitud
 */

function queryMagnitud(object $conn): array
{
    $status_query = false;

    //Resuelve peticiones de magnitudes con limite
    //Si magnitud esta definido y es distinto de vacio
    //Si limite esta definido y es distinto de vacio.
    if (isset($_GET['magnitud']) and !empty($_GET['magnitud']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        $magnitud = htmlentities($_GET['magnitud']);
        $limite = htmlentities($_GET['limite']);

        $params['magnitud'] = $magnitud;
        $params['limite'] = $limite;

        $sql = "SELECT * FROM quakes WHERE magnitud=:magnitud ORDER BY fecha_local DESC LIMIT :limite";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':magnitud', $magnitud);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
    //Si magnitud esta definido y es distinto de vacio
    //Si limite esta definido y es vacio
    else if (isset($_GET['magnitud']) and !empty($_GET['magnitud']) and isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }
    //Resuelve peticiones de magnitud sin limite
    //Si magnitud esta definido y es distinto de vacio
    //Si limite no esta definido
    else if (isset($_GET['magnitud']) and !empty($_GET['magnitud']) and !isset($_GET['limite'])) {

        $magnitud = htmlentities($_GET['magnitud']);

        $params['magnitud'] = $magnitud;

        $sql = "SELECT * FROM quakes WHERE magnitud=:magnitud ORDER BY fecha_local DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':magnitud', $magnitud);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
}

/**
 * Funcion encargada de procesar las peticiones de rankings
 */

function queryTopRanking(object $conn): array
{
    $status_query = false;
    //Procesa ranking con limite incluido
    //Si el ranking esta definido y es distinto de vacio
    //Si el limite esta definido y es distinto de vacio
    if (isset($_GET['ranking']) and !empty($_GET['ranking']) and isset($_GET['limite']) and !empty($_GET['limite'])) {

        /**
         * ORDER BY solo funciona en PDO con el numero de la columna en BD
         */

        $sql = "SELECT * FROM quakes ORDER BY :ranking DESC LIMIT :limite";

        $ranking = htmlentities($_GET['ranking']);
        $limite = htmlentities($_GET['limite']);

        if ($ranking == 'magnitud') {
            //Mirar tabla MYSQL
            $ranking_column = 6;
        } else if ($ranking == 'profundidad') {
            //Mirar tabla MYSQL
            $ranking_column = 11;
        } else {
            http_response_code(400);
            exit();
        }
        $params['ranking'] = $ranking;
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ranking', $ranking_column, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
    //Si el ranking esta definido y es distinto de vacio
    //Si el limite esta definido y es vacio
    else if (isset($_GET['ranking']) and !empty($_GET['ranking']) and isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }

    //Procesa ranking sin limite incluido
    //Si el ranking esta definido y es distinto de vacio
    //Si el limite NO esta definido
    else if (isset($_GET['ranking']) and !empty($_GET['ranking']) and !isset($_GET['limite'])) {
        $sql = "SELECT * FROM quakes ORDER BY :ranking DESC";

        $ranking = htmlentities($_GET['ranking']);

        if ($ranking == 'magnitud') {
            //Mirar tabla MYSQL
            $ranking_column = 6;
        } else if ($ranking == 'profundidad') {
            //Mirar tabla MYSQL
            $ranking_column = 11;
        } else {
            http_response_code(400);
            exit();
        }
        $params['ranking'] = $ranking;

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':ranking', $ranking_column, PDO::PARAM_INT);
        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    }
}

/**
 * Funcion encargada de limitar la cantidad de registros entregados
 */

function queryToLimit(object $conn): array
{
    $status_query = false;

    //Procesa solicitudes con limite
    if (isset($_GET['limite']) and !empty($_GET['limite'])) {

        $sql = "SELECT * FROM quakes ORDER BY fecha_local DESC LIMIT :limite";

        $limite = htmlentities($_GET['limite']);
        $params['limite'] = $limite;

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $status_query = true;

        $quakes = processRows($stmt);
        $conn = null;
        return [$status_query, $quakes, $params];
    } else if (isset($_GET['limite']) and empty($_GET['limite'])) {
        $conn = null;
        return [$status_query];
    }
}
