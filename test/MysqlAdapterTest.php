<?php

declare(strict_types=1);

require_once __DIR__ . '../../src/app/configs/MysqlAdapter.php';
require_once __DIR__ . '../../src/app/domain/Sismo.php';

use PHPUnit\Framework\TestCase;
use LastQuakeChile\Database\MysqlAdapter;
use LastQuakeChile\Domain\Sismo;

/**
 * @covers \MysqlAdapter
 */
final class MysqlAdapterTest extends TestCase
{

    /**
     * Funcion para testear la conexion a BD
     */
    public function testConnectionOk(): object
    {
        $adapter = new MysqlAdapter("test");
        $conn = $adapter->connect();

        $this->assertNotNull($conn);

        return $adapter;
    }

    /**
     * Funcion para testear fallo de conexion
     */
    public function testConnectionFail(): void
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
            error_log("Connection Failed: " . $e->getMessage(), 0);
            $result = false;
        }

        $this->assertFalse($result);
    }

    /**
     * Agregar un sismo a BD
     * @depends testConnectionOk
     */
    public function testAddQuake(object $adapter): void
    {
        $quake = new Sismo(
            '2018-11-26 05:34:28',
            '2018-11-26 08:34:28',
            'Pica',
            '28 km al E de Pica',
            '3.3',
            'Ml',
            '0',
            '-20.553',
            '-69.065',
            '95.2',
            'GUC',
            'http://www.sismologia.cl/mapas/sensibles/2018/11/26-0834-20L.S201811.jpeg',
            'verificado'
        );


        $result = $adapter->addQuake($quake);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result);
    }

    /**
     * Encontrar un sismo en BD
     * @depends testConnectionOk
     */
    public function testFindQuake(object $adapter): void
    {
        $quake = new Sismo(
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "http://www.sismologia.cl/mapas/sensibles/2018/11/26-0834-20L.S201811.jpeg",
            ""
        );

        $result = $adapter->findQuake($quake);

        $this->assertNotNull($result);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('finded', $result);
        $this->assertIsBool($result['finded']);
        $this->assertTrue($result['finded']);

        $this->assertArrayHasKey('sismo', $result);
    }

    /**
     * Buscar sismo no guardado en BD
     * @depends testConnectionOk
     */
    public function testNotFindQuake(object $adapter): void
    {
        $quake = new Sismo(
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "http://www.sismologia.cl/mapas/sensibles/2010/11/26-0834-20L.S201810.jpeg",
            ""
        );

        $result = $adapter->findQuake($quake);

        $this->assertNotNull($result);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('finded', $result);
        $this->assertIsBool($result['finded']);
        $this->assertFalse($result['finded']);

        $this->assertArrayNotHasKey('sismo', $result);
    }

    /**
     * Buscar sismos del mes en BD
     * @depends testConnectionOk
     */
    public function testFindQuakeOfMonth(object $adapter): void
    {
        /**
         * Agregar sismos
         */
        $quake1 = new Sismo(
            "2017-12-12 12:44:22",
            "",
            "",
            "",
            "",
            "",
            "",
            "223124.123",
            "",
            "",
            "",
            "imagen1",
            ""
        );

        $quake2 = new Sismo(
            "2017-12-28 12:44:22",
            "",
            "",
            "",
            "",
            "",
            "",
            "89456.123",
            "",
            "",
            "",
            "imagen2",
            ""
        );

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
