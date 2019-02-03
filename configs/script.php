<?php
date_default_timezone_set('America/Santiago');
require_once("bd_config.php");
require_once("sismo_class.php");
require_once("send_notification.php");

$conn = connect_pdo();
$list= array();

function curl($url){
		$ch = curl_init($url); // Inicia sesión cURL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Configura cURL para devolver el resultado como cadena
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Configura cURL para que no verifique el peer del certificado dado que nuestra URL utiliza el protocolo HTTPS
		$info = curl_exec($ch); // Establece una sesión cURL y asigna la información a la variable $info
		curl_close($ch); // Cierra sesión cURL
		return $info; // Devuelve la información de la función
	}
	$sitioweb = curl("http://www.sismologia.cl/links/ultimos_sismos.html");

	/*** a new dom object ***/ 
	$dom = new domDocument; 

	/*** load the html into the object ***/ 
	$dom->loadHTML($sitioweb); 

	/*** discard white space ***/ 
	$dom->preserveWhiteSpace = false; 

	/*** the table by its tag name ***/ 
	$tables = $dom->getElementsByTagName('table'); 

	/*** get all rows from the table ***/ 
	$rows = $tables->item(0)->getElementsByTagName('tr');
	
	/*
		SCRAPPING DE SISMOS HACIA LISTA
	 */
	foreach ($rows as $key => $value) {

		if ($key>0) {

			$cols = $value->getElementsByTagName('td');

			$links = $value->getElementsByTagName('a');
			$href= trim($links->item(0)->getAttribute('href'));
			$href = str_replace("events","mapas",$href);
			$href = str_replace("html","jpeg",$href);
			$imagen = "http://www.sismologia.cl".$href;

			$fecha_local = trim($cols->item(0)->nodeValue);          
			$fecha_utc = trim($cols->item(1)->nodeValue);
			$latitud = trim($cols->item(2)->nodeValue);
			$longitud = trim($cols->item(3)->nodeValue);
			$profundidad = trim($cols->item(4)->nodeValue);


			$magnitud_escala=explode(" ",trim($cols->item(5)->nodeValue));
			$magnitud= $magnitud_escala[0];
			$escala = $magnitud_escala[1];
			$agencia = trim($cols->item(6)->nodeValue);
			$ref_geografica = trim($cols->item(7)->nodeValue);
			$ref_geografica = str_replace(".","",$ref_geografica);

			//Checkear si sismo es sensible
			$clase= explode(" ",$value->getAttribute("class")." ");

			$obj = new Sismo();
			$obj->setFechaLocal($fecha_local);
			$obj->setFechaUTC($fecha_utc);
			$obj->setLatitud($latitud);
			$obj->setLongitud($longitud);
			$obj->setMagnitud($magnitud);
			$obj->setEscala($escala);
			$obj->setProfundidad($profundidad);
			$obj->setAgencia($agencia);
			$obj->setRefGeograf($ref_geografica);
			$obj->setImage($imagen);

			if ($clase[1] == "s_sensible") {
				$obj->setSensible('1');
			}
			else{
				$obj->setSensible('0');
			}

			array_push($list,$obj);
		}

	}



	if (isset($_GET['web']) && $_GET['web']==1) {
		echo "========== Actualizacion ".date("Y-m-d H:i:s")."==========<br>";
	}
	else{
		echo "========== Actualizacion ".date("Y-m-d H:i:s")."==========\n";
	}

	/*
		RECORRER LISTA PARA INSERSIONES EN BD
	 */
	foreach (array_reverse($list) as $item) {


		$fecha_local = $item->getFechaLocal();
		$fecha_utc = $item->getFechaUTC();
		$latitud = $item->getLatitud();
		$longitud = $item->getLongitud();
		$profundidad= $item->getProfundidad();
		$magnitud= $item->getMagnitud();
		$escala = $item->getEscala();
		$agencia = $item->getAgencia();
		$referencia = $item->getRefGeograf();
		$imagen = $item->getImage();
		$sensible = $item->getSensible();

		$stmt=$conn->prepare('SELECT quakes_id FROM quakes WHERE fecha_local=?');
		$stmt->execute([$fecha_local]);

		if ($stmt->rowCount()==0) {

			/*
				PREPARACION DE INSERT
			 */
			$insert=$conn->prepare(
				"INSERT INTO quakes (fecha_local,fecha_utc,latitud,longitud,profundidad,magnitud,escala,sensible,agencia,referencia,imagen) VALUES (?,?,?,?,?,?,?,?,?,?,?)"
			);
			$insert->execute(array(
				$fecha_local,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$escala,$sensible,$agencia,$referencia,$imagen
			));

			//Si el sismo es de 5+ grados se envia notificacion
			if ($magnitud>=5.0){
				echo "Notificacion enviada\n";
				sendNotification($fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$escala,$sensible,$referencia,$imagen);
			}

			if (isset($_GET['web']) && $_GET['web']==1) {
				echo "Sismo insertado<br>";
			}

			else{
				echo "Sismo insertado\n";
			}

		}
		else if ($stmt->rowCount()>0) {

			if (isset($_GET['web']) && $_GET['web']==1) {
				echo "No hay sismos nuevos<br>";
			}else{
				echo "No hay sismos nuevos\n";
			}
		}
	}
	$conn = null;   
	?>