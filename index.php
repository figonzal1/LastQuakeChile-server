<?php
header('Content-type: text/html; charset=UTF-8');
date_default_timezone_set('America/Santiago');
require_once("bd_config.php");
$conn = connect();

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

require("simplehtmldom/simple_html_dom.php");

$list= array();

$html= file_get_html("http://www.sismologia.cl/links/ultimos_sismos.html");

$table=$html->find('table tr');

foreach ($table as $key => $value) {

	/*
	Capturacion de datos
	*/
	if($key >0){
		$fecha_local = $value -> find('td a',0)->plaintext;
		$fecha_utc = $value -> find('td',1)->plaintext;
		$latitud = $value -> find('td',2)->plaintext;
		$longitud = $value -> find('td',3)->plaintext;
		$profundidad = $value -> find('td',4)->plaintext;
		$magnitud = $value -> find('td',5)->plaintext;
		$agencia = $value -> find('td',6)->plaintext;
		$ref_geografica = $value -> find('td',7)->plaintext;

		$obj = new Sismo();
		$obj->setFechaLocal($fecha_local);
		$obj->setFechaUTC($fecha_utc);
		$obj->setLatitud($latitud);
		$obj->setLongitud($longitud);
		$obj->setMagnitud($magnitud);
		$obj->setProfundidad($profundidad);
		$obj->setAgencia($agencia);
		$obj->setRefGeograf($ref_geografica);

		array_push($list,$obj);
	}
}

echo "========= Actualizacion ".date("Y-m-d H:i:s")."=========\n";
foreach (array_reverse($list) as $item) {


	$fecha_local = $item->getFechaLocal();
	$fecha_utc = $item->getFechaUTC();
	$latitud = $item->getLatitud();
	$longitud = $item->getLongitud();
	$profundidad= $item->getProfundidad();
	$magnitud= $item->getMagnitud();
	$agencia = $item->getAgencia();
	$referencia = $item->getRefGeograf();

	$stmt=$conn->prepare("SELECT quakes_id FROM quakes WHERE fecha_local=?");
	$stmt->bind_param("s",$fecha_local);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows==0) {
		
		$stmt=$conn->prepare('INSERT INTO quakes (fecha_local,fecha_utc,latitud,longitud,profundidad,magnitud,agencia,referencia) VALUES (?,?,?,?,?,?,?,?)');
		$stmt->bind_param("ssssddss",$fecha_local,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$agencia,$referencia);
		$stmt->execute();

		echo "Sismo insertado\n";
	}
	else if ($stmt->num_rows>0) {
		echo "No hay sismos nuevos\n";
		continue;
	}
}
$conn -> close();
?>