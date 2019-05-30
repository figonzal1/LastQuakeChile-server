<?php

date_default_timezone_set('America/Santiago');
require_once("bd_interface.php");
require_once("bd_config.php");

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class DynamoAdapter implements BdAdapter
{

    private $dynamodb;

    public function __construct()
    {
        $this->dynamodb = connect_amazon();
    }

    public function addQuake($quake)
    {
        $marshaler = new Marshaler();

        $tableName = "Quakes";

        $fecha_local = $quake->getFechaLocal();
        $fecha_utc = $quake->getFechaUtc();
        $ciudad = $quake->getCiudad();
        $referencia = $quake->getRefGeograf();
        $magnitud = $quake->getMagnitud();
        $escala = $quake->getEscala();
        $sensible = $quake->getSensible();
        $latitud = $quake->getLatitud();
        $longitud = $quake->getLongitud();
        $profundidad = $quake->getProfundidad();
        $agencia = $quake->getAgencia();
        $imagen = $quake->getImagen();
        $estado = $quake->getEstado();

        $quake_id = explode("/", $imagen);
        $quake_id = str_replace(".jpeg", "", $quake_id[7]);

        $json = json_encode([
            'quake_id' => $quake_id,
            'fecha_local' => $fecha_local,
            'fecha_utc' => $fecha_utc,
            'ciudad' => $ciudad,
            'referencia' => $referencia,
            'magnitud' => $magnitud,
            'escala' => $escala,
            'sensible' => $sensible,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'profundidad' => $profundidad,
            'agencia' => $agencia,
            'imagen' => $imagen,
            'estado' => $estado
        ]);

        $params = [
            'TableName' => $tableName,
            'Item' => $marshaler->marshalJson($json),
            'ReturnValues' => 'ALL_OLD'
        ];

        try {
            $result = $this->dynamodb->putItem($params);
        } catch (DynamoDbException $e) {
            echo "Unable to add quake: " . $e->getMessage()."\n";
        }
    }

    public function updateQuake($quake)
    {
        $marshaler = new Marshaler();

        $tableName = "Quakes";

        $fecha_local = $quake->getFechaLocal();
        $fecha_utc = $quake->getFechaUtc();
        $ciudad = $quake->getCiudad();
        $referencia = $quake->getRefGeograf();
        $magnitud = $quake->getMagnitud();
        $escala = $quake->getEscala();
        $sensible = $quake->getSensible();
        $latitud = $quake->getLatitud();
        $longitud = $quake->getLongitud();
        $profundidad = $quake->getProfundidad();
        $agencia = $quake->getAgencia();
        $imagen = $quake->getImagen();
        $estado = $quake->getEstado();

        $quake_id = explode("/", $imagen);
        $quake_id = str_replace(".jpeg", "", $quake_id[7]);

        $key = $marshaler->marshalJson('
            {
                "quake_id": "' . $quake_id . '",
                "fecha_local": "' . $fecha_local . '"
            }
        ');

        $eav = $marshaler->marshalJson('
            {
                ":f_local": "' . $fecha_local . '",
                ":f_utc": "' . $fecha_utc . '",
                ":c": "' . $ciudad . '",
                ":ref": "' . $referencia . '",
                ":mag": ' . $magnitud . ',
                ":esc": "' . $escala . '",
                ":sen": "' . $sensible . '",
                ":lat": "' . $latitud . '",
                ":long": "' . $longitud . '",
                ":prof": "' . $profundidad . '",
                ":agen":  "' . $agencia . '",
                ":img":  "' . $imagen . '",
                ":est": "' . $estado . '"
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key,
            'UpdateExpression' => 'set fecha_local = :f_local, fecha_utc=:f_utc, ciudad=:c, referencia=:ref, magnitud=:mag, escala=:esc, sensible=:sen, latitud=:lat, longitud=:long, profundidad=:prof, agencia=:agen, imagen = :img,estado=:est',
            'ExpressionAttributeValues' => $eav
        ];

        try {
            $this->dynamodb->updateItem($params);
        
        } catch (DynamoDbException $e) {
            echo "Unable to update item: ".$e->getMessage() . "\n";
        }
    }

    public function findQuake($quake)
    {
        $marshaler = new Marshaler();

        $tableName = "Quakes";
        $imagen = $quake->getImagen();
        $fecha_local = $quake->getFechaLocal();

        $quake_id = explode("/", $imagen);
        $quake_id = str_replace(".jpeg", "", $quake_id[7]);

        $key = $marshaler->marshalJson('
            {
                "quake_id": "' . $quake_id . '",
                "fecha_local": "' . $fecha_local . '"
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key
        ];

        $result = $this->dynamodb->getItem($params);
        //Si el sismo se encontro en BD
        //RETURN DATOS
        if (!empty($result['Item'])) {
            return array(
                'finded' => true,
                'estado' => $result['Item']['estado']['S']
            );
        } else {
            return array(
                'finded' => false
            );
        }
    }
}
?>
