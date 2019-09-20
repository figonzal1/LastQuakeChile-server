<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers \Sismo
 */
final class SismoTest extends TestCase
{


    public function testSismoClass()
    {

        $this->assertClassHasAttribute('fecha_local', Sismo::class);
        $this->assertClassHasAttribute('fecha_utc', Sismo::class);
        $this->assertClassHasAttribute('ciudad', Sismo::class);
        $this->assertClassHasAttribute('referencia', Sismo::class);
        $this->assertClassHasAttribute('magnitud', Sismo::class);
        $this->assertClassHasAttribute('escala', Sismo::class);
        $this->assertClassHasAttribute('sensible', Sismo::class);
        $this->assertClassHasAttribute('latitud', Sismo::class);
        $this->assertClassHasAttribute('longitud', Sismo::class);
        $this->assertClassHasAttribute('profundidad', Sismo::class);
        $this->assertClassHasAttribute('agencia', Sismo::class);
        $this->assertClassHasAttribute('imagen', Sismo::class);
        $this->assertClassHasAttribute('estado', Sismo::class);
    }

    public function testGettersAndSetters()
    {

        $sismo = new Sismo();

        $sismo->setCiudad("La Serena");
        $sismo->setSensible(1);
        $sismo->setEscala("Mw");
        $sismo->setMagnitud(5.4);
        $sismo->setProfundidad(112.1);
        $sismo->setEstado(1);
        $sismo->setFechaLocal("2019-12-12 22:22:22");
        $sismo->setFechaUTC("2020-01-12 15:22:11");
        $sismo->setImagen("urlImagen");
        $sismo->setRefGeograf("XX km de XX");
        $sismo->setAgencia("GUC");
        $sismo->setLongitud("1233123.1231");
        $sismo->setLatitud("12351634.1313");

        $this->assertEquals("La Serena", $sismo->getCiudad());
        $this->assertIsString($sismo->getCiudad());

        $this->assertEquals(1, $sismo->getSensible());
        $this->assertIsNumeric($sismo->getSensible());

        $this->assertEquals("Mw", $sismo->getEscala());
        $this->assertIsString($sismo->getEscala());

        $this->assertEquals(5.4, $sismo->getMagnitud());
        $this->assertIsNumeric($sismo->getMagnitud());

        $this->assertEquals(112.1, $sismo->getProfundidad());
        $this->assertIsNumeric($sismo->getProfundidad());

        $this->assertEquals(1, $sismo->getEstado());
        $this->assertIsNumeric($sismo->getEstado());

        $this->assertEquals("2019-12-12 22:22:22", $sismo->getFechaLocal());
        $this->assertIsString($sismo->getFechaLocal());

        $this->assertEquals("2020-01-12 15:22:11", $sismo->getFechaUTC());
        $this->assertIsString($sismo->getFechaUTC());

        $this->assertEquals("urlImagen", $sismo->getImagen());
        $this->assertIsString($sismo->getImagen());

        $this->assertEquals("XX km de XX", $sismo->getRefGeograf());
        $this->assertIsString($sismo->getRefGeograf());

        $this->assertEquals("GUC", $sismo->getAgencia());
        $this->assertIsString($sismo->getAgencia());

        $this->assertEquals("1233123.1231", $sismo->getLongitud());
        $this->assertIsString($sismo->getLongitud());

        $this->assertEquals("12351634.1313", $sismo->getLatitud());
        $this->assertIsString($sismo->getLatitud());
    }
}
