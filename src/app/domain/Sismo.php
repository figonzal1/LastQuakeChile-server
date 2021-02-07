<?php

declare(strict_types=1);

namespace LastQuakeChile\Domain;

/*
SISMO CLASS
*/

class Sismo
{

	private string $fechaLocal;
	private string $fechaUtc;
	private string $ciudad;
	private string $referencia;
	private string $magnitud;
	private string $escala;
	private string $sensible;
	private string $latitud;
	private string $longitud;
	private string $profundidad;
	private string $agencia;
	private string $imagen;
	private string $estado;

	public function __construct(
		string $fechaLocal,
		string $fechaUtc,
		string $ciudad,
		string $referencia,
		string $magnitud,
		string $escala,
		string $sensible,
		string $latitud,
		string $longitud,
		string $profundidad,
		string $agencia,
		string $imagen,
		string $estado
	) {
		$this->fechaLocal = $fechaLocal;
		$this->fechaUtc = $fechaUtc;
		$this->ciudad = $ciudad;
		$this->referencia = $referencia;
		$this->magnitud = $magnitud;
		$this->escala = $escala;
		$this->sensible = $sensible;
		$this->latitud = $latitud;
		$this->longitud = $longitud;
		$this->profundidad = $profundidad;
		$this->agencia = $agencia;
		$this->imagen = $imagen;
		$this->estado = $estado;
	}

	public function __toString(): string
	{
		return "Sismos[" .
			"Fecha Local:" . $this->fechaLocal .
			" Ciudad: " . $this->ciudad .
			" Referencia: " . $this->referencia .
			" Magnitud: " . $this->magnitud .
			" Profundidad: " . $this->profundidad .
			"]";
	}


	//GETTERS
	public function getFechaLocal(): string
	{
		return $this->fechaLocal;
	}

	public function getFechaUTC(): string
	{
		return $this->fechaUtc;
	}

	public function getCiudad(): string
	{
		return $this->ciudad;
	}

	public function getLatitud(): string
	{
		return $this->latitud;
	}

	public function getLongitud(): string
	{
		return $this->longitud;
	}

	public function getProfundidad(): string
	{
		return $this->profundidad;
	}

	public function getMagnitud(): string
	{
		return $this->magnitud;
	}

	public function getEscala(): string
	{
		return $this->escala;
	}

	public function getAgencia(): string
	{
		return $this->agencia;
	}

	public function getRefGeograf(): string
	{
		return $this->referencia;
	}

	public function getImagen(): string
	{
		return $this->imagen;
	}

	public function getSensible(): string
	{
		return $this->sensible;
	}

	public function getEstado(): string
	{
		return $this->estado;
	}
}
