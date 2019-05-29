<?php

date_default_timezone_set('America/Santiago');

require_once("bd_config.php");
require_once("bd_interface.php");


class MysqlAdapter implements BdAdapter
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect_pdo();
    }

    public function addQuake($quake)
    { 
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

        try {
			$insert = $this->conn->prepare(
				"INSERT INTO quakes (fecha_local,fecha_utc,ciudad,referencia,magnitud,escala,sensible,latitud,longitud,profundidad,agencia,imagen,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"
			);
			$insert->execute(array(
				$fecha_local, $fecha_utc, $ciudad, $referencia, $magnitud, $escala, $sensible, $latitud, $longitud, $profundidad, $agencia, $imagen, $estado
			));
		} catch (PDOException $e) {
			echo "Falla en insert: " . $e->getMessage();
		}
    }

    public function updateQuake($quake)
    { 
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

        try {
			$update = $this->conn->prepare(
				"UPDATE quakes SET fecha_local=?,fecha_utc=?,ciudad=?,referencia=?,magnitud=?,escala=?,sensible=?,latitud=?,longitud=?,profundidad=?,agencia=?,imagen=?,estado=? WHERE imagen=?"
			);

			$update->execute(array(
				$fecha_local, $fecha_utc, $ciudad, $referencia, $magnitud, $escala, $sensible, $latitud,
				$longitud, $profundidad, $agencia, $imagen, $estado, $imagen
			));
		} catch (PDOException $e) {
			echo "Falla en update: " . $e->getMessage();
		}
    }

    /**
     * Buscar si sismo existe en base a imagen
     */
    public function findQuake($quake)
    {

        $stmt = $this->conn->prepare('SELECT quakes_id,estado FROM quakes WHERE imagen=?');
        $stmt->execute([$quake->getImagen()]);

        $sismo_bd = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() == 0) {
            return array(
                'finded' => false
            );
        } else if ($stmt->rowCount() == 1) {
            return array(
                'finded' => true,
                'quake_id' => $sismo_bd['quakes_id'],
                'estado' => $sismo_bd['estado']
            );
        }
        $this->conn = null;
    }
}
