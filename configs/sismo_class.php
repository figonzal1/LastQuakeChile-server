<?php
/*
SISMO CLASS
*/
class Sismo {

	private $fecha_local=false;
	private $fecha_utc=false;
	private $latitud=false;
	private $longitud=false;
	private $profundidad=false;
	private $magnitud=false;
	private $agencia=false;
	private $ref_geografica=false;
	
	public function getFechaLocal(){
		return $this->fecha_local;
	}

	public function getFechaUTC(){
		return $this->fecha_utc;
	}

	public function getLatitud(){
		return $this->latitud;
	}

	public function getLongitud(){
		return $this->longitud;
	}

	public function getProfundidad(){
		return $this->profundidad;
	}

	public function getMagnitud(){
		return $this->magnitud;
	}

	public function getAgencia(){
		return $this->agencia;
	}

	public function getRefGeograf(){
		return $this->ref_geografica;
	}

	public function setFechaLocal($fecha_local){
		$this->fecha_local =$fecha_local;
	}

	public function setFechaUTC($fecha_utc){
		$this->fecha_utc=$fecha_utc;
	}

	public function setLatitud($latitud){
		$this->latitud=$latitud;
	}

	public function setLongitud($longitud){
		$this->longitud=$longitud;
	}

	public function setProfundidad($profundidad){
		$this->profundidad=$profundidad;
	}

	public function setMagnitud($magnitud){
		$this->magnitud=$magnitud;
	}

	public function setAgencia($agencia){
		$this->agencia=$agencia;
	}

	public function setRefGeograf($ref_geografica){
		$this->ref_geografica=$ref_geografica;
	}
}
?>