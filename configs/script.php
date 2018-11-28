<?php
	date_default_timezone_set('America/Santiago');
	require_once("bd_config.php");
	require_once("sismo_class.php");

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

    foreach ($rows as $key => $value) {

    	if ($key>0) {

    		$cols = $value->getElementsByTagName('td');

            $links = $value->getElementsByTagName('a');
            $href= $links->item(0)->getAttribute('href');
            $href = str_replace("events","mapas",$href);
            $href = str_replace("html","jpeg",$href);
            $imagen = "http://www.sismologia.cl".$href;

    		$fecha_local = $cols->item(0)->nodeValue;          
    		$fecha_utc = $cols->item(1)->nodeValue;
    		$latitud = $cols->item(2)->nodeValue;
    		$longitud = $cols->item(3)->nodeValue;
    		$profundidad = $cols->item(4)->nodeValue;
    		$magnitud = $cols->item(5)->nodeValue;
    		$agencia = $cols->item(6)->nodeValue;
    		$ref_geografica = $cols->item(7)->nodeValue;

    		$obj = new Sismo();
    		$obj->setFechaLocal($fecha_local);
    		$obj->setFechaUTC($fecha_utc);
    		$obj->setLatitud($latitud);
    		$obj->setLongitud($longitud);
    		$obj->setMagnitud($magnitud);
    		$obj->setProfundidad($profundidad);
    		$obj->setAgencia($agencia);
    		$obj->setRefGeograf($ref_geografica);
            $obj->setImage($imagen);

    		array_push($list,$obj);


    	}

    }

    

    if (isset($_GET['web']) && $_GET['web']==1) {
    	echo "========== Actualizacion ".date("Y-m-d H:i:s")."==========<br>";
    }
    else{
    	echo "========== Actualizacion ".date("Y-m-d H:i:s")."==========\n";
    }

	foreach (array_reverse($list) as $item) {


		$fecha_local = $item->getFechaLocal();
		$fecha_utc = $item->getFechaUTC();
		$latitud = $item->getLatitud();
		$longitud = $item->getLongitud();
		$profundidad= $item->getProfundidad();
		$magnitud= $item->getMagnitud();
		$agencia = $item->getAgencia();
		$referencia = $item->getRefGeograf();
        $imagen = $item->getImage();

		$stmt=$conn->prepare('SELECT quakes_id FROM quakes WHERE fecha_local=?');
		$stmt->execute([$fecha_local]);

		if ($stmt->rowCount()==0) {

			$insert=$conn->prepare(
				"INSERT INTO quakes (fecha_local,fecha_utc,latitud,longitud,profundidad,magnitud,agencia,referencia,imagen) VALUES (?,?,?,?,?,?,?,?,?)"
			);
			$insert->execute(array(
				$fecha_local,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$agencia,$referencia,$imagen
			));

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
			continue;
		}
	}
    $conn = null;    
?>