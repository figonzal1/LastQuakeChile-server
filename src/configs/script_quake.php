<?php
date_default_timezone_set('America/Santiago');
require("bd_files/MysqlAdapter.php");
require("Sismo.php");
require("send_notification.php");

//Test | Prod Mode
$run_in = $argv[1];


function parseHtml()
{
	$list = array();

	$sitioweb = curl('http://www.sismologia.cl/links/ultimos_sismos.html');

	/*** a new dom object ***/
	$dom = new domDocument;

	/*** load the html into the object ***/
	$dom->loadHTML($sitioweb);

	/*** discard white space ***/
	$dom->preserveWhiteSpace = false;

	/*** the table by its tag name ***/
	$tables = $dom->getElementsByTagName('table');
	foreach ($tables as $table) {
		$tbodys = $table->getElementsByTagName('tbody');
		foreach ($tbodys as $tbody) {
			$rows = $tbody->getElementsByTagName('tr');
		}
	}

	//RECORRER EL LISTADO DE SISMOLOGIA.CL Y SCRAPEAR DATOS DE LOS SISMOS
	//INSERCION EN LISTA
	foreach ($rows as $key => $value) {

		//EXIGIR KEY MAYOR QUE 0 PARA ELIMINAR ENCABEZADO DE TABLA
		if ($key > 0) {

			$cols = $value->getElementsByTagName('td');

			//Captura el tag "a" con la referencia de la imagen
			$links = $value->getElementsByTagName('a');
			$href = trim($links->item(0)->getAttribute('href'));
			$href = str_replace('events', 'mapas', $href);
			$href = str_replace('html', 'jpeg', $href);
			$imagen = 'http://www.sismologia.cl' . $href;

			//FORMATEAR FECHAS ESTILO XXXX-XX-XX XX:XX:XX
			$fecha_local = trim($cols->item(0)->nodeValue);
			$fecha_local = date_create($fecha_local);
			$fecha_local = $fecha_local->format('Y-m-d H:i:s');

			$fecha_utc = trim($cols->item(1)->nodeValue);
			$fecha_utc = date_create($fecha_utc);
			$fecha_utc = $fecha_utc->format('Y-m-d H:i:s');

			$latitud = trim($cols->item(2)->nodeValue);
			$longitud = trim($cols->item(3)->nodeValue);
			$profundidad = trim($cols->item(4)->nodeValue);

			$magnitud_escala = explode(' ', trim($cols->item(5)->nodeValue));
			$magnitud = $magnitud_escala[0];
			$escala = $magnitud_escala[1];

			$agencia = trim($cols->item(6)->nodeValue);

			$referencia = trim($cols->item(7)->nodeValue);
			$referencia = str_replace('.', '', $referencia);
			$ciudad = trim(substr($referencia, strpos($referencia, 'de') + 2));

			//SI EL SISMO TIENE EL PREFIJO erb_ EN EL LINK DE LA IMAGEN -> EL SIMOS ES PRELIMINAR
			if (strpos($imagen, 'erb_') === FALSE) {
				$estado = 'verificado';
			} else {
				$estado = 'preliminar';
				$imagen = str_replace('erb_', '', $imagen);
			}

			//BUSCAR EL ATRIBUTO EN LA TABLA DE SISMOLOGIA.CL QUE INDICA SENSIBILIDAD DE SISMOS
			$clase = explode(' ', $value->getAttribute('class') . ' ');


			//CREACION INSTANCIA SISMO
			$obj = new Sismo();
			$obj->setFechaLocal($fecha_local);
			$obj->setFechaUTC($fecha_utc);
			$obj->setCiudad($ciudad);
			$obj->setRefGeograf($referencia);
			$obj->setMagnitud($magnitud);
			$obj->setEscala($escala);
			$obj->setLatitud($latitud);
			$obj->setLongitud($longitud);
			$obj->setProfundidad($profundidad);
			$obj->setAgencia($agencia);
			$obj->setImagen($imagen);
			$obj->setEstado($estado);

			//CHECKEAR SENSIBILIDAD DE SISMOS BUSCANDO EL ATRIBUTO
			if ($clase[1] == 's_sensible') {
				$obj->setSensible('1');
			} else {
				$obj->setSensible('0');
			}
			//PUSHEAR INSTANCIA DE SISMO A LISTA DE SISMOS
			array_push($list, $obj);
		}
	}

	return $list;
}

$mysql_adapter = new MysqlAdapter($run_in);
$conn = $mysql_adapter->connect();
$list = parseHtml();

//Contador para debug
//$contador = 1; 
if ($conn != null) {

	echo "========== Actualizacion MYSQL" . date("Y-m-d H:i:s") . "==========\n";

	//RECORRER LA LISTA SCRAPEADA PARA REALIZAR LA INSERCION, ELIMINARCION Y NOTIFICACIONES
	foreach (array_reverse($list) as $item) {

		//OBTENER DATOS DE CADA SISMOS DE LA LISTA SCRAPEADA
		$fecha_local = $item->getFechaLocal();
		$fecha_utc = $item->getFechaUTC();
		$ciudad = $item->getCiudad();
		$latitud = $item->getLatitud();
		$longitud = $item->getLongitud();
		$profundidad = $item->getProfundidad();
		$magnitud = $item->getMagnitud();
		$escala = $item->getEscala();
		$agencia = $item->getAgencia();
		$referencia = $item->getRefGeograf();
		$imagen = $item->getImagen();
		$sensible = $item->getSensible();
		$estado = $item->getEstado();

		//SE USA IMAGEN PARA DISTINUIR PRELIMINAR VS TERMINADO (Debido a que los de sismologia 
		//cambian la mayoria de los campos por lo que el sismo se detecta como nuevo)
		//Buscar si existe el sismo
		$result = $mysql_adapter->findQuake($item);

		//Obtener diferencia en minutos
		//tiempo actual con tiempo sismo
		$diff = checkQuakeNowDiff($fecha_local);
		$diff_horas = $diff[0];
		$diff_minutes = $diff[1];

		//SI EL SISMO DE LA LISTA SCRAPEADA NO ESTA GUARDADO EN LA BASE DE DATOS
		//SE PROCEDE A INSERCIÓN
		if (!$result['finded']) {

			//PREPARACION DE INSERT
			$mysql_adapter->addQuake($item);

			//SI EL SISMO DE LA LISTA SCRAPEADA ES MAYOR DE 5 GRADOS
			//ENVIO DE NOTIFICACION A CELULARES DEPENDIENDO DEL ESTADO
			if ($magnitud >= 5.0 and $diff_horas == 0 and $diff_minutes <= 15) {
				sendNotification('Quakes', '', $fecha_utc, $ciudad, $latitud, $longitud, $profundidad, $magnitud, $escala, $sensible, $referencia, $imagen, $estado);
				echo "Notificacion enviada\n";
			}

			if (isset($_GET['web']) && $_GET['web'] == 1) {
				echo "Sismo insertado<br>";
			} else {
				echo "Sismo insertado\n";
			}
		}

		//SI YA EXISTE UN SISMO CON LA MISMA IMAGEN Y SU ESTADO (ESTADO = PRELIMINAR)
		//Y EL QUE SE PRETENDE INSERTAR ES UN SISMO VERIFICADO (ESTADO = VERIFICADO)
		//- SE PROCEDE A INSERTAR EL SISMO VERIFICADO A BD
		//- SE PROCEDE A NOTIFICAR NUEVAMENTE EL SISMO CON ESTADO VERIFICADO
		else if ($result['finded'] and $result['estado'] == 'preliminar' and $estado == 'verificado') {


			//PREPARACION DE UPDATE
			$mysql_adapter->updateQuake($item);

			//SI EL SISMO DE LA LISTA SCRAPEADA ES MAYOR DE 5 GRADOS
			//ENVIO DE NOTIFICACION DE SISMO VERIFICADO
			if ($magnitud >= 5.0 and $diff_horas == 0 and $diff_minutes <= 30) {
				sendNotification('Quakes', '[Corrección] ', $fecha_utc, $ciudad, $latitud, $longitud, $profundidad, $magnitud, $escala, $sensible, $referencia, $imagen, $estado);
				echo "Notificacion enviada\n";
			}


			echo "Sismo actualizado (preliminar -> verificado)\n";
		}

		//SI YA EXISTE UN SISMO CON LA MISMA IMAGEN Y ESTE ES (VERIFICADO O PRELIMINAR)
		//Y EN BASE DE DATOS TIENE SU ESTADO CORRESPONDIENTE (VERIFICADO O PRELIMINAR) IGUAL
		//ENTONCES NO SE DEBE HACER NINGUNA OPERACION AL RESPECTO Y ES IGNORADO

		else if ($result['finded'] and (($estado == 'verificado' and $result['estado'] == 'verificado') or ($estado == 'preliminar' and $result['estado'] == 'preliminar'))) {

			//USAR SOLO PARA DEBUGUEAR
			/*if ($contador == 1) {
				sendNotification("Test", "[DEBUG] ", $fecha_utc, $ciudad, $latitud, $longitud, $profundidad, $magnitud, $escala, $sensible, $referencia, $imagen, $estado);
				$contador += 1;
			}*/

			echo "No hay sismos nuevos\n";
		}
	}
	$mysql_adapter->close();
} else {
	echo "Script fail \n";
	http_response_code(500);
}

/**
 * +---------------------+
 * +     Funciones       +
 * +    de utilidad      +
 * +---------------------+
 */

/**
 * Funcion encargada de hacer la conexion a la url
 */
function curl($url)
{
	$ch = curl_init($url); // Inicia sesión cURL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Configura cURL para devolver el resultado como cadena
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Configura cURL para que no verifique el peer del certificado dado que nuestra URL utiliza el protocolo HTTPS
	$info = curl_exec($ch); // Establece una sesión cURL y asigna la información a la variable $info
	curl_close($ch); // Cierra sesión cURL
	return $info; // Devuelve la información de la función
}

/**
 * Funcion encargada de checkear la diferencia entre la hora actual y la hora del sismo
 */
function checkQuakeNowDiff($fecha_local)
{

	$hora_sismo = new DateTime($fecha_local);
	$hora_actual = new DateTime();
	$diff = $hora_actual->diff($hora_sismo);

	return [$diff->h, $diff->i];
}
