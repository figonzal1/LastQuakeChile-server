<?php

require_once __DIR__."/../vendor/autoload.php";
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;



function sendNotification($prefijo,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$escala,$sensible,$referencia,$imagen,$estado){

	
	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/lastquake_credentials.json');
	
	$firebase = (new Factory)
	->withServiceAccount($serviceAccount)
	->create();

	$messaging = $firebase->getMessaging();



	if ($estado=="preliminar") {

			//Configuracion mensaje ANDROID
		$config = AndroidConfig::fromArray([
				'ttl' => '1200s',   // 20 minutos de expiracion para sismo preliminar
				'priority' => 'high'  //Prioridad HIGH
			]);
		$data=[
			'titulo' => '[Preliminar] ¡Alerta sísmica!',
			'descripcion' => 'Sismo de '.$magnitud.' registrado a '.$referencia,
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
		//Sismo verificado o sismo corregido (prefijo)
	else if ($estado=="verificado") {

			//Configuracion mensaje ANDROID
		$config = AndroidConfig::fromArray([
				'ttl' => '3600s',   // 1 Hora de expiracion para sismo verificado
				'priority' => 'high'  //Prioridad HIGH
			]);

		$data=[
			'titulo' => $prefijo.'¡Alerta sísmica!',
			'descripcion' => 'Sismo de '.$magnitud.' registrado a '.$referencia,
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


	$topic='Quakes';

	$message = CloudMessage::withTarget('topic',$topic)
	->withAndroidConfig($config)
	->withData($data);

	$response =$messaging->send($message);

	echo json_encode($response,JSON_PRETTY_PRINT);

	try {
		$firebase->getMessaging()->validate($message);
	} catch (InvalidMessage $e) {
		print_r($e->errors());
	}

}


/*
function sendNotificationToDevice($prefijo,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$escala,$sensible,$referencia,$imagen,$estado){


	$deviceToken="dgto7240DZw:APA91bHxHhU8yDUkmdaB4XJbVGz1hIc6hFfGWGIyiUn6l0T8Nbl7SYxg9-fLAZ7jzraH8gsrB1OwZhSEk4iGnbTUlaJg9IdCQtBVQlp-6Txco0Og_4-mQybrBQfB6eki_HGTQBGksBrX";
	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/lastquake_credentials.json');

	$firebase = (new Factory)
	->withServiceAccount($serviceAccount)
	->create();

	$messaging = $firebase->getMessaging();



	if ($estado=="preliminar") {

			//Configuracion mensaje ANDROID
		$config = AndroidConfig::fromArray([
				'ttl' => '1200s',   // 20 minutos de expiracion para sismo preliminar
				'priority' => 'high'  //Prioridad HIGH
			]);
		$data=[
			'titulo' => '[Preliminar] ¡Alerta sísmica!',
			'descripcion' => 'Sismo de '.$magnitud.' registrado a '.$referencia,
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
		//Sismo verificado o sismo corregido (prefijo)
	else if ($estado=="verificado") {

			//Configuracion mensaje ANDROID
		$config = AndroidConfig::fromArray([
				'ttl' => '3600s',   // 1 Hora de expiracion para sismo verificado
				'priority' => 'high'  //Prioridad HIGH
			]);

		$data=[
			'titulo' => $prefijo.'¡Alerta sísmica!',
			'descripcion' => 'Sismo de '.$magnitud.' registrado a '.$referencia,
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

	$message = CloudMessage::withTarget('token',$deviceToken)
	->withAndroidConfig($config)
	->withData($data);

	$response =$messaging->send($message);

	echo json_encode($response,JSON_PRETTY_PRINT);

	try {
		$firebase->getMessaging()->validate($message);
	} catch (InvalidMessage $e) {
		print_r($e->errors());
	}
}*/

?>