<?php

declare(strict_types=1);

namespace LastQuakeChile\Scripts;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;

/**
 * [sendNotification description]
 * Funcion encargada de enviar notificaciones FCM a los dispositivos suscritos al canal 'Quakes'
 * @param  [type] $prefijo     Prefijo utilizado para el titulo de la notificacion
 * @param  [type] $fecha_utc   FEcha UTC del sismos
 * @param  [type] $ciudad      Ciudad cercana al epicentro del sismo
 * @param  [type] $latitud     Latitud de sismo
 * @param  [type] $longitud    Longitud del sismo
 * @param  [type] $profundidad Profundidad en km del sismo
 * @param  [type] $magnitud    Magnitud del sismo
 * @param  [type] $escala      Escala en Mw o Ml del sismo
 * @param  [type] $sensible    Sismo sensible por la comunidad
 * @param  [type] $referencia  Referencia georafica del sismo (x km del x ciudad)
 * @param  [type] $imagen      Link (url) de la imagen desde sismologia.cl
 * @param  [type] $estado      Sismo preliminar o sismo verificado
 * @return [type]              S/D
 */
function sendNotification(
	string $tipo_mensaje,
	string $prefijo,
	string $fecha_utc,
	string $ciudad,
	string $latitud,
	string $longitud,
	string $profundidad,
	string $magnitud,
	string $escala,
	string $sensible,
	string $referencia,
	string $imagen,
	string $estado
): void {

	$factory = (new Factory())->withServiceAccount(getenv("FIREBASE_CREDENTIALS"));
	#$factory = (new Factory())->withServiceAccount("C:\\xampp\\htdocs\\LastQuakeChile-server\\lqch-server-credentials.json");

	try {

		$messaging = $factory->createMessaging();
		$message = "";

		//Sismo verificado o sismo corregido (prefijo)
		if ($estado == 'verificado') {

			//Configuracion mensaje ANDROID
			$config = AndroidConfig::fromArray([
				'ttl' => '3600s',   // 1 Hora de expiracion para sismo verificado
				'priority' => 'high'  //Prioridad HIGH
			]);

			$data = setNotificationData($prefijo, $fecha_utc, $ciudad, $latitud, $longitud, $profundidad, $magnitud, $escala, $sensible, $referencia, $imagen, $estado);
			$message = configNotification($tipo_mensaje, $config, $data);
		} else {

			//Configuracion mensaje ANDROID
			$config = AndroidConfig::fromArray([
				'ttl' => '600s',   // 10 minutos de expiracion para sismo preliminar
				'priority' => 'high'  //Prioridad HIGH
			]);

			$data = setNotificationData('[Preliminar] ', $fecha_utc, $ciudad, $latitud, $longitud, $profundidad, $magnitud, $escala, $sensible, $referencia, $imagen, $estado);
			$message = configNotification($tipo_mensaje, $config, $data);
		}

		$response = $messaging->send($message);

		echo json_encode($response, JSON_PRETTY_PRINT);

		$messaging->validate($message);
	} catch (InvalidMessage $e) {
		error_log("Invalid FCM: " . $e->getMessage(), 0);
	}
}

function configNotification(string $tipo_mensaje, object $config, array $data): object
{
	//Revisar token de dispositivo en android (SOLO DEBUG)
	//$deviceToken = "daZsqoQlQxScFbOz_H7h56:APA91bHg9LxI1wkqruFqkvg8VL7HXoa-6NhaY6AE-fnYW_kF8xNT9E2LdAdwRAa3r7lJW0zrTIsj93Gs6Kad2z8_LAJ55DL9g5V9PK8YsiAZl0JEXkpWt4rCJw8OiYw4D-OeDGvX3v0u";

	//Envia solo a un dispositivo
	/*if ($tipo_mensaje == 'Test') {
		$message = CloudMessage::withTarget('token', $deviceToken)
			->withAndroidConfig($config)
			->withData($data);
	}*/

	//Envia a todos los dispositivos en Quakes
	if ($tipo_mensaje == 'Quakes') {
		$topic = 'Quakes';
		$message = CloudMessage::withTarget('topic', $topic)
			->withAndroidConfig($config)
			->withData($data);
	}

	return $message;
}

/**
 * [setNotificationData description]
 * Funcion encargada de setear el mensaje de la notifiacacion
 * @param  [type] $prefijo     Prefijo utilizado para el titulo de la notificacion
 * @param  [type] $fecha_utc   FEcha UTC del sismos
 * @param  [type] $ciudad      Ciudad cercana al epicentro
 * @param  [type] $latitud     Latitud de sismo
 * @param  [type] $longitud    Longitud del sismo
 * @param  [type] $profundidad Profundidad en km del sismo
 * @param  [type] $magnitud    Magnitud del sismo
 * @param  [type] $escala      Escala en Mw o Ml del sismo
 * @param  [type] $sensible    Sismo sensible por la comunidad
 * @param  [type] $referencia  Referencia georafica del sismo (x km del x ciudad)
 * @param  [type] $imagen      Link (url) de la imagen desde sismologia.cl
 * @param  [type] $estado      Sismo preliminar o sismo verificado
 * @return [type]              S/D
 */
function setNotificationData(
	string $prefijo,
	string $fecha_utc,
	string $ciudad,
	string $latitud,
	string $longitud,
	string $profundidad,
	string $magnitud,
	string $escala,
	string $sensible,
	string $referencia,
	string $imagen,
	string $estado
): array {

	return [
		'titulo' => $prefijo . '¡Alerta sísmica!',
		'descripcion' => 'Sismo de ' . $magnitud . ' registrado a ' . $referencia,
		'ciudad' => $ciudad,
		'latitud' => $latitud,
		'longitud' => $longitud,
		'fecha_utc' => $fecha_utc,
		'magnitud' => $magnitud,
		'escala' => $escala,
		'profundidad' => $profundidad,
		'sensible' => $sensible,
		'referencia' => $referencia,
		'imagen_url' => $imagen,
		'estado' => $estado
	];
}
