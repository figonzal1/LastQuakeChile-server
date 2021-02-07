<?php

declare(strict_types=1);

namespace LastQuakeChile\Helpers;

use \DateTime;

/**
 * Funcion encargada de hacer la conexion a la url
 */
function quakeRequest(string $url): string
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
function checkQuakeNowDiff(string $fecha_local): array
{

	$hora_sismo = new DateTime($fecha_local);
	$hora_actual = new DateTime();
	$diff = $hora_actual->diff($hora_sismo);

	return [$diff->h, $diff->i];
}
