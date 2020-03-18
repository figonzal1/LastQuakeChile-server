<?php

require("src/configs/bd_files/MysqlAdapter.php");
require("src/configs/Sismo.php");

use PHPUnit\Framework\TestCase;

/**
 * @covers \MysqlAdapter
 */
final class MysqlAdapterTest extends TestCase
{

    /**
     * Funcion para testear la conexion a BD
     */
    public function testConnectionOk()
    {
        $adapter = new MysqlAdapter("test");
        $conn = $adapter->connect();

        $this->assertNotNull($conn);

        return $adapter;
    }

    /**
     * Funcion para testear fallo de conexion
     */
    public function testConnectionFail()
    {
        $hostname = "120.0.0.1";
        $db = "testdb";
        $username = "root";
        $password = "";

        $result = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $hostname . ";dbname=" . $db . "",
                $username,
                $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            error_log("Connection Failed: ".$e->getMessage(),0);
            $result = false;
        }

        $this->assertFalse($result);
    }

    /**
     * Agregar un sismo a BD
     * @depends testConnectionOk
     */
    public function testAddQuake($adapter)
    {
        $quake = new Sismo();
        $quake->setFechaLocal('2018-11-26 05:34:28');
        $quake->setFechaUTC('2018-11-26 08:34:28');
        $quake->setCiudad('Pica');
        $quake->setRefGeograf('28 km al E de Pica');
        $quake->setMagnitud('3.3');
        $quake->setEscala('Ml');
        $quake->setSensible('0');
        $quake->setLatitud('-20.553');
        $quake->setLongitud('-69.065');
        $quake->setProfundidad('95.2');
        $quake->setAgencia('GUC');
        $quake->setImagen('http://www.sismologia.cl/mapas/sensibles/2018/11/26-0834-20L.S201811.jpeg');
        $quake->setEstado('verificado');


        $result = $adapter->addQuake($quake);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result);
    }

    /**
     * Encontrar un sismo en BD
     * @depends testConnectionOk
     */
    public function testFindQuake($adapter)
    {
        $quake = new Sismo();
        $quake->setImagen("http://www.sismologia.cl/mapas/sensibles/2018/11/26-0834-20L.S201811.jpeg");

        $result = $adapter->findQuake($quake);

        $this->assertNotNull($result);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('finded', $result);
        $this->assertIsBool($result['finded']);
        $this->assertTrue($result['finded']);

        $this->assertArrayHasKey('quake_id', $result);
        $this->assertIsNumeric($result['quake_id']);

        $this->assertArrayHasKey('estado', $result);
        $this->assertIsString($result['estado']);
    }

    /**
     * Buscar sismo no guardado en BD
     * @depends testConnectionOk
     */
    public function testNotFindQuake($adapter)
    {
        $quake = new Sismo();
        $quake->setImagen("http://www.sismologia.cl/mapas/sensibles/2010/11/26-0834-20L.S201810.jpeg");

        $result = $adapter->findQuake($quake);


        $this->assertNotNull($result);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('finded', $result);
        $this->assertIsBool($result['finded']);
        $this->assertFalse($result['finded']);

        $this->assertArrayNotHasKey('quake_id', $result);
        $this->assertArrayNotHasKey('estado', $result);
    }

    /**
     * Buscar sismos del mes en BD
     * @depends testConnectionOk
     */
    public function testFindQuakeOfMonth($adapter)
    {
        /**
         * Agregar sismos
         */
        $quake1 = new Sismo();
        $quake1->setFechaLocal("2017-12-12 12:44:22");
        $quake1->setLatitud("223124.123");
        $quake1->setImagen("imagen1");

        $quake2 = new Sismo();
        $quake2->setFechaLocal("2017-12-28 12:44:22");
        $quake2->setLatitud("89456.123");
        $quake2->setImagen("imagen2");

        $adapter->addQuake($quake1);
        $adapter->addQuake($quake2);


        /**
         * Buscar sismos
         */

        $result = $adapter->findQuakesOfMonth("12");

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        //Sismo 1
        $this->assertIsString($result[0]['fecha_local']);
        $this->assertIsString($result[0]['latitud']);
        $this->assertIsString($result[0]['imagen']);

        $this->assertEquals("2017-12-12 12:44:22", $result[0]['fecha_local']);
        $this->assertEquals("223124.123", $result[0]['latitud']);
        $this->assertEquals("imagen1", $result[0]['imagen']);

        //Sismo 2
        $this->assertIsString($result[1]['fecha_local']);
        $this->assertIsString($result[1]['latitud']);
        $this->assertIsString($result[1]['imagen']);

        $this->assertEquals("2017-12-28 12:44:22", $result[1]['fecha_local']);
        $this->assertEquals("89456.123", $result[1]['latitud']);
        $this->assertEquals("imagen2", $result[1]['imagen']);
    }

    /**
     * Test Cerrar conexion
     * @depends testConnectionOk
     */
    public function testCloseConnection($adapter)
    {
        $conn = $adapter->close();

        $this->assertNull($conn);
    }
}
