<?php

date_default_timezone_set('America/Santiago');

require_once("bd_config.php");


class MysqlAdapter
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect_pdo();
    }


    /**
     * METODOS PARA SISMOS
     */

    /**
     * Agregar sismo
     */
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

    /**
     * Actualizar sismo
     */
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
    }

    /**
     * Buscar sismos del mes
     */
    public function findQuakeOfMonth($prev_month)
    {

        $select = $this->conn->prepare(
            "SELECT * FROM quakes WHERE Month(fecha_local)=?"
        );
        $select->execute([$prev_month]);

        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * METODOS PARA CHANGE LOG
     */

    /**
     * Agregar release
     */
    function addRelease($id_github, $tag_name, $body)
    {
        try {
            $insert = $this->conn->prepare(
                "INSERT INTO changelog (github_id,tag_name,body) VALUES (?,?,?)"
            );

            $insert->execute(array(
                $id_github, $tag_name, $body
            ));
        } catch (PDOException $e) {
            echo "Falla insert: " . $e->getMessage();
        } finally {
            echo "Release version insertada\n";
        }
    }

    /**
     * Actualizar release
     */
    function updateRelease($id_github, $tag_name, $body)
    {
        try {
            $update = $this->conn->prepare(
                "UPDATE changelog SET github_id=?,tag_name=?,body=? WHERE github_id=?"
            );

            $update->execute(array(
                $id_github, $tag_name, $body, $id_github
            ));
        } catch (PDOException $e) {
            echo "Falla update: " . $e->getMessage();
        } finally {
            echo "Release version actualizada\n";
        }
    }

    /**
     * Checkear si release existe en bd
     */
    function checkIfExistRelease($id_github)
    {

        $select = $this->conn->prepare(
            "SELECT * FROM changelog WHERE github_id=?"
        );

        $select->execute([$id_github]);

        if ($select->rowCount() == 0) {
            return false;
        } else if ($select->rowCount() == 1) {
            return true;
        }
    }

    function close()
    {
        $this->conn = null;
    }
}
