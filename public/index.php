<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="google-site-verification" content="3s7di-xAGBPqXOlS9EbC3Ox_RZwzbG82pHa7IRlRx80" />
	<title>LastQuakeChile</title>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Material Design Bootstrap -->
	<link href="css/mdb.min.css" rel="stylesheet">

	<!-- Your custom styles (optional) -->
	<link href="css/style.css" rel="stylesheet">

	<!--FAvicon -->
	<link rel="shortcut icon" type="image/png" href="img/favicon.ico">



	<!-- LiveReload Script-->
	<!--<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>-->
</head>

<body data-spy="scroll" data-target="navbar" data-offset="20" style="position: relative;">
	<header>
		
		<?php readfile("navbar.html");?>

		<!-- Mascara Imagen-->
		
		<div id="portada" class="view">
			<div class="mask rgba-black-strong" id="inicio">

				<div class="container mt-5" >

					<div class="row p-5">

						<!-- Columna izquierda -->
						<div class="col-md-6 white-text d-flex flex-column justify-content-center">

							<!-- Titulo -->
							<div class="text-sm-center text-left wow fadeInDown">
								<h1 class="h1 font-weight-bold">¡Descárgala ahora!</h1>
							</div>

							<!-- Separador  -->
							<div class="text-center wow fadeInDown">
								<hr class="hr-light">
							</div>

							<!-- Descripcion -->
							<div class="text-sm-center text-left mt-2 wow fadeInDown">
								<p class="text-justify">Revisa los últimos sismos en Chile y recibe notificaciones de los más importantes.</p>
							</div>

							<!-- Imagen boton google play -->
							<div class="my-4 wow fadeInDown delay-1s">
								<a href="https://play.google.com/store/apps/details?id=cl.figonzal.lastquakechile">
									<img src="img/google-play-badge.png" class="mx-auto img-fluid" style="width: 50%;" alt="Google play link">
								</a>
							</div>

						</div>

						<!-- Columna derecha -->
						<div class="col-md-6 wow fadeInDown">
							<!-- Imagen celular -->
							<img src="img/lastquakechile_pantalla.png" class="mx-auto img-fluid" style="width: 50%;">
						</div>

					</div>

				</div>
			</div>
		</div>
		
	</header>

	<!--Main layout-->
	<main>

		<!-- Section caracteristicas -->
		<section id="caractaristicas" class="py-5">

			<div class="container" id="caractaristicas">
				<h2 class="h2-responsive my-5 font-weight-bold text-center wow fadeInDown">Características</h2>

				<div class="row">
					<div class="col-md-4 mb-5">
						<div class="card wow fadeIn z-depth-1-half py-3">

							<!-- Icono caracteristica -->
							<img class="card-img-top mx-auto" src="img/svg/baseline-access_time-24px.svg" style="width: 25%;" alt="Icono tiempo">

							<!-- Descripcion del icono -->
							<div class="card-body">
								<h4 class="card-title text-center">Sismos diarios</h4>
								<p class="card-text text-center">
									Consulta en tiempo real el listado completo de los sismos ocurridos durante un día
								</p>
							</div>
						</div>
					</div>

					<div class="col-md-4 mb-5">

						<div class="card wow fadeIn z-depth-1-half py-3">

							<!-- Icono caracteristica -->
							<img class="card-img-top mx-auto" src="img/svg/baseline-notification_important-24px.svg" style="width: 25%;" alt="Icono notificaciones">

							<!-- Descripcion del icono -->
							<div class="card-body">
								<h4 class="card-title text-center">Notificaciones</h4>
								<p class="card-text text-center">
									Obtén notificaciones directamente a tu celular de los sismos mayores a 5.0 grados Richter
								</p>
							</div>

						</div>
					</div>
					<div class="col-md-4">

						<div class="card wow fadeIn z-depth-1-half py-3">

							<!-- Icono caracteristica -->
							<img class="card-img-top mx-auto" src="img/svg/baseline-beenhere-24px.svg" style="width: 25%;" alt="Icono mapa">

							<!-- Descripcion del icono -->
							<div class="card-body">
								<h4 class="card-title text-center">Ubicación</h4>
								<p class="card-text text-center">
									Revisa la posición del epicentro de cada sismo percibido
								</p>
							</div>

						</div>
					</div>

				</div>
			</div>
		</section>

		<div class="container">
			<hr>
		</div>

		<!-- Section desarrollo-->
		<section id="caracteristica_dev" class="pb-md-5 py-5">
			<div class="container">
				<h2 class="h2-responsive font-weight-bold text-center mt-md-5 wow fadeInDown">... en un tiempo más, podrás tener</h2>

				<div class="row d-flex flex-row align-items-center p-5">

					<div class="col-md-6 mb-5">

						<div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.3s">
							<div>
								<img src="img/svg/baseline-check_circle-24px.svg" class="img-fluid">
							</div>
							<div class="pl-2 text-left">
								<p class="mb-0 text-muted">Notificaciones basadas en georeferenciación.</p>
							</div>
						</div>

						<div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.5s">
							<div>
								<img src="img/svg/baseline-check_circle-24px.svg" class="img-fluid">
							</div>
							<div class="pl-2 text-left">
								<p class="mb-0 text-muted">Filtros de búsqueda personalizables.</p>
							</div>
						</div>

						<div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.7s" >
							<div>
								<img src="img/svg/baseline-check_circle-24px.svg" class="img-fluid">
							</div>
							<div class="pl-2 text-left">
								<p class="mb-0 text-muted">Votaciones de usuarios basadas en sismos sensibles.</p>
							</div>
						</div>
					</div>

					<div class="col-md-6 text-center ">
						<img src="img/ic_lastquakechile_1200.png" class="img-fluid animated heartBeat infinite" style="width: 50%;">
					</div>
				</div>

			</div>

		</section>


	</main>
	<?php readfile("footer.html");?>


	<!-- SCRIPTS -->
	<!-- JQuery -->
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>

	<!-- Bootstrap tooltips -->
	<script type="text/javascript" src="js/popper.min.js"></script>
	<!-- Bootstrap core JavaScript -->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!-- MDB core JavaScript -->
	<script type="text/javascript" src="js/mdb.min.js"></script>

	<script type="text/javascript">new WOW().init();</script>

</body>
</html>
