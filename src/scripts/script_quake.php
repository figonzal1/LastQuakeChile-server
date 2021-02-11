<?php

declare(strict_types=1);

require_once __DIR__ . '../../configs/MysqlAdapter.php';
require_once __DIR__ . '../../helper/Helpers.php';
require_once __DIR__ . '../../domain/Sismo.php';
require_once 'send_notification.php';

use LastQuakeChile\Helpers;
use LastQuakeChile\Database\MysqlAdapter;
use LastQuakeChile\Domain;
use LastQuakeChile\Scripts;

date_default_timezone_set('America/Santiago');

//Test | Prod Mode
$run_in = $argv[1];


function parseHtml(): array
{
	$list = array();

	$sitioweb = Helpers\quakeRequest('http://www.sismologia.cl/links/ultimos_sismos.html');

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

			//CHECKEAR SENSIBILIDAD DE SISMOS BUSCANDO EL ATRIBUTO
			if ($clase[1] == 's_sensible') {
				$sensible = '1';
			} else {
				$sensible = '2';
			}

			//CREACION INSTANCIA SISMO
			$sismo = new Domain\Sismo(
				$fecha_local,
				$fecha_utc,
				$ciudad,
				$referencia,
				$magnitud,
				$escala,
				$sensible,
				$latitud,
				$longitud,
				$profundidad,
				$agencia,
				$imagen,
				$estado
			);


			//PUSHEAR INSTANCIA DE SISMO A LISTA DE SISMOS
			array_push($list, $sismo);
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

		//SE USA IMAGEN PARA DISTINUIR PRELIMINAR VS TERMINADO (Debido a que los de sismologia 
		//cambian la mayoria de los campos por lo que el sismo se detecta como nuevo)
		//Buscar si existe el sismo
		$result = $mysql_adapter->findQuake($item);
		$finded = $result['finded'];

		if (isset($result['sismo']) and $result['sismo'] != null) {
			$sismoBD = $result['sismo'];
		}

		//Obtener diferencia en minutos
		//tiempo actual con tiempo sismo
		$diff = Helpers\checkQuakeNowDiff($item->getFechaLocal());
		$diff_horas = $diff[0];
		$diff_minutes = $diff[1];

		//SI EL SISMO DE LA LISTA SCRAPEADA NO ESTA GUARDADO EN LA BASE DE DATOS
		//SE PROCEDE A INSERCIÓN
		if (!$finded) {

			$added = $mysql_adapter->addQuake($item);

			if ($added) {

				$sismoAdded = $mysql_adapter->findQuake($item)['sismo'];

				//SI EL SISMO DE LA LISTA SCRAPEADA ES MAYOR DE 5 GRADOS
				//ENVIO DE NOTIFICACION A CELULARES DEPENDIENDO DEL ESTADO
				if ($sismoAdded['magnitud'] >= 5.0 and $diff_horas == 0 and $diff_minutes <= 15) {

					Scripts\sendNotification(
						'Quakes',
						'',
						$sismoAdded['fecha_utc'],
						$sismoAdded['ciudad'],
						$sismoAdded['latitud'],
						$sismoAdded['longitud'],
						$sismoAdded['profundidad'],
						$sismoAdded['magnitud'],
						$sismoAdded['escala'],
						$sismoAdded['sensible'],
						$sismoAdded['referencia'],
						$sismoAdded['imagen'],
						$sismoAdded['estado']
					);
					echo "Notificacion enviada\n";
				}
				echo "Sismo insertado\n";
			} else {
				echo "Error al insertar sismo\n";
				error_log("Error al insertar sismo", 0);
			}
		}

		//SI YA EXISTE UN SISMO CON LA MISMA IMAGEN Y SU ESTADO (ESTADO = PRELIMINAR)
		//Y EL QUE SE PRETENDE INSERTAR ES UN SISMO VERIFICADO (ESTADO = VERIFICADO)
		//- SE PROCEDE A INSERTAR EL SISMO VERIFICADO A BD
		//- SE PROCEDE A NOTIFICAR NUEVAMENTE EL SISMO CON ESTADO VERIFICADO
		else if ($finded and $sismoBD['estado'] == 'preliminar' and $item->getEstado() == 'verificado') {

			$updated = $mysql_adapter->updateQuake($item);

			if ($updated) {

				//SI EL SISMO DE LA LISTA SCRAPEADA ES MAYOR DE 5 GRADOS
				//ENVIO DE NOTIFICACION DE SISMO VERIFICADO
				if ($item->getMagnitud() >= 5.0 and $diff_horas == 0 and $diff_minutes <= 30) {

					Scripts\sendNotification(
						'Quakes',
						'[Corrección] ',
						$sismoBD['fecha_utc'],
						$sismoBD['ciudad'],
						$sismoBD['latitud'],
						$sismoBD['longitud'],
						$sismoBD['profundidad'],
						$sismoBD['magnitud'],
						$sismoBD['escala'],
						$sismoBD['sensible'],
						$sismoBD['referencia'],
						$sismoBD['imagen'],
						$sismoBD['estado']
					);

					echo "Notificacion enviada\n";
				}

				echo "Sismo actualizado (preliminar -> verificado)\n";
			} else {
				echo "Error al actualizar sismo\n";
				error_log("Error al actualizar sismo", 0);
			}
		}

		//SI YA EXISTE UN SISMO CON LA MISMA IMAGEN Y ESTE ES (VERIFICADO O PRELIMINAR)
		//Y EN BASE DE DATOS TIENE SU ESTADO CORRESPONDIENTE (VERIFICADO O PRELIMINAR) IGUAL
		//ENTONCES NO SE DEBE HACER NINGUNA OPERACION AL RESPECTO Y ES IGNORADO

		else if (
			$finded and
			(($item->getEstado() == 'verificado' and $sismoBD['estado'] == 'verificado') or
				($item->getEstado() == 'preliminar' and $sismoBD['estado'] == 'preliminar'))
		) {

			//USAR SOLO PARA DEBUGUEAR
			/*if ($contador == 1) {

				Scripts\sendNotification(
					"Test",
					"[DEBUG] ",
					$sismoBD['fecha_utc'],
					$sismoBD['ciudad'],
					$sismoBD['latitud'],
					$sismoBD['longitud'],
					$sismoBD['profundidad'],
					$sismoBD['magnitud'],
					$sismoBD['escala'],
					$sismoBD['sensible'],
					$sismoBD['referencia'],
					$sismoBD['imagen'],
					$sismoBD['estado']
				);
				$contador += 1;
			}*/

			echo "No hay sismos nuevos\n";
		}
	}
	$mysql_adapter->close();
} else {
	error_log("Script quakes fail", 0);
	http_response_code(500);
}
