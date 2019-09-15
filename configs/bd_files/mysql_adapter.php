<?php

date_default_timezone_set('America/Santiago');

require_once 'bd_config.php';


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
        try {
            $insert = $this->conn->prepare(
                "INSERT INTO quakes (fecha_local,fecha_utc,ciudad,referencia,magnitud,escala,sensible,latitud,longitud,profundidad,agencia,imagen,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );
            $insert->execute(array(
                $quake->getFechaLocal(), 
                $quake->getFechaUtc(), 
                $quake->getCiudad(), 
                $quake->getRefGeograf(), 
                $quake->getMagnitud(), 
                $quake->getEscala(), 
                $quake->getSensible(), 
                $quake->getLatitud(), 
                $quake->getLongitud(), 
                $quake->getProfundidad(), 
                $quake->getAgencia(), 
                $quake->getImagen(), 
                $quake->getEstado()
            ));
        } catch (PDOException $e) {
            echo 'Falla en insert: ' . $e->getMessage();
        }
    }

    /**
     * Actualizar sismo
     */
    public function updateQuake($quake)
    {
        try {
            $update = $this->conn->prepare(
                "UPDATE quakes SET fecha_local=?,fecha_utc=?,ciudad=?,referencia=?,magnitud=?,escala=?,sensible=?,latitud=?,longitud=?,profundidad=?,agencia=?,imagen=?,estado=? WHERE imagen=?"
            );

            $update->execute(array(
                $quake->getFechaLocal(), 
                $quake->getFechaUtc(), 
                $quake->getCiudad(), 
                $quake->getRefGeograf(), 
                $quake->getMagnitud(), 
                $quake->getEscala(), 
                $quake->getSensible(), 
                $quake->getLatitud(),
                $quake->getLongitud(), 
                $quake->getProfundidad(), 
                $quake->getEstado(), 
                $quake->getImagen(), 
                $quake->getEstado(), 
                $quake->getImagen()
            ));
        } catch (PDOException $e) {
            echo 'Falla en update: ' . $e->getMessage();
        }
    }

    /**
     * Buscar si sismo existe en base a imagen
     */
    public function findQuake($quake)
    {

        $stmt = $this->conn->prepare("SELECT quakes_id,estado FROM quakes WHERE imagen=?");
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
    public function findQuakesOfMonth($prev_month)
    {

        $select = $this->conn->prepare(
            "SELECT * FROM quakes WHERE Month(fecha_local)=?"
        );
        $select->execute([$prev_month]);

        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function close()
    {
        $this->conn = null;
    }
}
