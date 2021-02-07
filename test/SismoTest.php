<?php

use PHPUnit\Framework\TestCase;
use LastQuakeChile\Domain\Sismo;

/**
 * @covers \Sismo
 */
final class SismoTest extends TestCase
{


    public function testSismoClass()
    {

        $this->assertClassHasAttribute('fechaLocal', Sismo::class);
        $this->assertClassHasAttribute('fechaUtc', Sismo::class);
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

        $sismo = new Sismo(
            "2019-12-12 22:22:22",
            "2020-01-12 15:22:11",
            "La Serena",
            "XX km de XX",
            "5.4",
            "Mw",
            "1",
            "12351634.1313",
            "1233123.1231",
            "112.1",
            "GUC",
            "urlImagen",
            "1"
        );

        $this->assertEquals("La Serena", $sismo->getCiudad());
        $this->assertIsString($sismo->getCiudad());

        $this->assertEquals("1", $sismo->getSensible());
        $this->assertIsString($sismo->getSensible());

        $this->assertEquals("Mw", $sismo->getEscala());
        $this->assertIsString($sismo->getEscala());

        $this->assertEquals("5.4", $sismo->getMagnitud());
        $this->assertIsString($sismo->getMagnitud());

        $this->assertEquals("112.1", $sismo->getProfundidad());
        $this->assertIsString($sismo->getProfundidad());

        $this->assertEquals("1", $sismo->getEstado());
        $this->assertIsString($sismo->getEstado());

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
