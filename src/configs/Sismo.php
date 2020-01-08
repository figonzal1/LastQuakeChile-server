<?php
/*
SISMO CLASS
*/
class Sismo
{

	private $fecha_local = false;
	private $fecha_utc = false;
	private $ciudad = false;
	private $referencia = false;
	private $magnitud = false;
	private $escala = false;
	private $sensible = false;
	private $latitud = false;
	private $longitud = false;
	private $profundidad = false;
	private $agencia = false;
	private $imagen = false;
	private $estado = false;

	public function __toString()
	{
		return "Sismos[".
		"Fecha Local:".$this->fecha_local.
		" Ciudad: ".$this->ciudad.
		" Referencia: ".$this->referencia.
		" Magnitud: ".$this->magnitud.
		" Profundidad: ".$this->profundidad.
		"]";
	}


	//GETTERS
	public function getFechaLocal()
	{
		return $this->fecha_local;
	}

	public function getFechaUTC()
	{
		return $this->fecha_utc;
	}

	public function getCiudad()
	{
		return $this->ciudad;
	}

	public function getLatitud()
	{
		return $this->latitud;
	}

	public function getLongitud()
	{
		return $this->longitud;
	}

	public function getProfundidad()
	{
		return $this->profundidad;
	}

	public function getMagnitud()
	{
		return $this->magnitud;
	}

	public function getEscala()
	{
		return $this->escala;
	}

	public function getAgencia()
	{
		return $this->agencia;
	}

	public function getRefGeograf()
	{
		return $this->referencia;
	}

	public function getImagen()
	{
		return $this->imagen;
	}

	public function getSensible()
	{
		return $this->sensible;
	}

	public function getEstado()
	{
		return $this->estado;
	}

	//SETERS
	public function setFechaLocal($fecha_local)
	{
		$this->fecha_local = $fecha_local;
	}

	public function setFechaUTC($fecha_utc)
	{
		$this->fecha_utc = $fecha_utc;
	}

	public function setCiudad($ciudad)
	{
		$this->ciudad = $ciudad;
	}

	public function setLatitud($latitud)
	{
		$this->latitud = $latitud;
	}

	public function setLongitud($longitud)
	{
		$this->longitud = $longitud;
	}

	public function setProfundidad($profundidad)
	{
		$this->profundidad = $profundidad;
	}

	public function setMagnitud($magnitud)
	{
		$this->magnitud = $magnitud;
	}

	public function setEscala($escala)
	{
		$this->escala = $escala;
	}

	public function setAgencia($agencia)
	{
		$this->agencia = $agencia;
	}

	public function setRefGeograf($referencia)
	{
		$this->referencia = $referencia;
	}

	public function setImagen($imagen)
	{
		$this->imagen = $imagen;
	}

	public function setSensible($sensible)
	{
		$this->sensible = $sensible;
	}

	public function setEstado($estado)
	{
		$this->estado = $estado;
	}
}
