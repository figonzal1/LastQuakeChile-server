<?php

declare(strict_types=1);

namespace LastQuakeChile\Database;

use \PDO;
use \PDOException;
use Dotenv\Dotenv;
use QuakeInterface;

date_default_timezone_set('America/Santiago');
require_once __DIR__ . '../../../vendor/autoload.php';
require_once __DIR__ . '../../interfaces/QuakeInterface.php';

class MysqlAdapter implements QuakeInterface
{
    private ?object $conn;
    private string $db;
    private string $hostname;
    private string $username;
    private string $password;

    public function __construct(string $tipo)
    {

        if ($tipo == "test") {

            //Activar dotenv solo para usar .env en local
            $dotenv = Dotenv::createUnsafeImmutable(__DIR__."../../../");
            $dotenv->load();

            $this->hostname = getenv('DB_HOSTNAME_TEST');
            $this->db = getenv('DB_DATABASE_TEST');
            $this->username = getenv('DB_USERNAME_TEST');
            $this->password = getenv('DB_PASSWORD_TEST');
        }
        // @codeCoverageIgnoreStart 
        else if ($tipo == "prod") {

            //Activar dotenv solo para usar .env en local
            $dotenv = Dotenv::createUnsafeImmutable(__DIR__."../../../");
            $dotenv->load();

            $this->hostname = getenv('DB_HOSTNAME');
            $this->username = getenv('DB_USERNAME');
            $this->password = getenv('DB_PASSWORD');
            $this->db = getenv('DB_DATABASE');
        }
        // @codeCoverageIgnoreEnd
    }

    public function connect(): ?object
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->hostname . ";dbname=" . $this->db . "",
                $this->username,
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            return $this->conn;
        }
        // @codeCoverageIgnoreStart
        catch (PDOException $e) {
            error_log("Connection Failed: " . $e->getMessage(), 0);
            return null;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Agregar sismo
     */
    public function addQuake(object $quake): bool
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
            return true;
        } catch (\PDOException $e) {
            error_log("Falla en insert quakes: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * Actualizar sismo
     */

    public function updateQuake(object $quake): bool
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

            //Check update
            if ($update->rowCount() == 0) {
                return false;
            } else if ($update->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Falla en update updates: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * Buscar si sismo existe en base a imagen
     */
    public function findQuake(object $quake): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM quakes WHERE imagen=?");
        $stmt->execute([$quake->getImagen()]);

        $sismo_bd = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() == 0) {
            return array(
                'finded' => false
            );
        } else if ($stmt->rowCount() == 1) {
            return array(
                'finded' => true,
                'sismo' => $sismo_bd
            );
        }
    }

    /**
     * Buscar sismos del mes
     */
    public function findQuakesOfMonth(string $prev_month): array
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
