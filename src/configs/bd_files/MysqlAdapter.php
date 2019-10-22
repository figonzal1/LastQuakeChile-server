<?php

date_default_timezone_set('America/Santiago');

require(__DIR__ . "../../../../vendor/autoload.php");

class MysqlAdapter
{
    private $conn;
    private $db;
    private $hostname;
    private $username;
    private $password;

    public function __construct($tipo)
    {

        if ($tipo == "test") {

            //Activar dotenv solo para usar .env en local
            //$dotenv = Dotenv\Dotenv::create(__DIR__ . "../../../../");
            //$dotenv->load();

            $this->hostname = getenv('DB_HOSTNAME_TEST');
            $this->db = getenv('DB_DATABASE_TEST');
            $this->username = getenv('DB_USERNAME_TEST');
            $this->password = getenv('DB_PASSWORD_TEST');
        }
        // @codeCoverageIgnoreStart 
        else if ($tipo == "prod") {

            //Activar dotenv solo para usar .env en local
            //$dotenv = Dotenv\Dotenv::create(__DIR__ . "../../../../");
            //$dotenv->load();

            $this->hostname = getenv('DB_HOSTNAME');
            $this->username = getenv('DB_USERNAME');
            $this->password = getenv('DB_PASSWORD');
            $this->db = getenv('DB_DATABASE');
        }
        // @codeCoverageIgnoreEnd
    }

    public function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->hostname . ";dbname=" . $this->db . "",
                $this->username,
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            //echo "Connected Succesfully \n";
            return $this->conn;
        }
        // @codeCoverageIgnoreStart
        catch (PDOException $e) {
            //echo 'Connection Failed: ' . $e->getMessage() . "\n";
            return false;
        }
        // @codeCoverageIgnoreEnd
    }

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
            return 1;
        } catch (PDOException $e) {
            echo 'Falla en insert: ' . $e->getMessage();
            return 0;
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

    public function close()
    {
        $this->conn = null;
        return $this->conn;
    }
}
