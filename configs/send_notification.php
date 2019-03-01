<?php

require_once __DIR__."/../vendor/autoload.php";
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Exception\Messaging\InvalidMessage;



function sendNotification($prefijo,$fecha_utc,$latitud,$longitud,$profundidad,$magnitud,$escala,$sensible,$referencia,$imagen,$estado){

	/*
		Configuracion de Servidor
	 */
		//$api_key ='BB8Ocj5exQP6-5TlIdfTyYpuUY6TuKeKAku8_C4x1PcgUbbYLa6Yr6tInJ2nxvozW7JJWpcu779SfmMGFTMtanM';
		$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/lastquake_credentials.json');
		
	/*
		Config de Firebase
	 */
		$firebase = (new Factory)
		->withServiceAccount($serviceAccount)
		->create();

		$messaging = $firebase->getMessaging();

	/*
		Configuracion MSG para ANDROID
	 */
		$config = AndroidConfig::fromArray([
		'ttl' => '3600s',   // 1 Hora de expiracion
		'priority' => 'high'  //Prioridad HIGH
	]);

		if ($estado=="preliminar") {
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
				'imagen_url' => $imagen
			];
		}
		else if ($estado=="verificado") {
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
				'imagen_url' => $imagen
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
	?>