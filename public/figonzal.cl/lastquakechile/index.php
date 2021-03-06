<!DOCTYPE html>
<html lang="es">

<!--
  PAGINA WEB: LastQuakeChile
  ARCHIVO: index.php
-->

<head>
  <meta charset="utf-8">
  <title>Últimos sismos en Chile - LastQuakeChile</title>
  <meta name="description" content="Aplicación para revisar los últimos sismos en Chile. Recibe notificaciones en tu celular. Reportes sismológicos todos los meses" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="google-site-verification" content="3s7di-xAGBPqXOlS9EbC3Ox_RZwzbG82pHa7IRlRx80" />
  <link rel="canonical" href="https://www.figonzal.cl/lastquakechile" />


  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">

  <!-- Your custom styles (optional) -->
  <link href="css/style.css" rel="stylesheet">

  <!--FAvicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="https://www.figonzal.cl/lastquakechile/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://www.figonzal.cl/lastquakechile/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://www.figonzal.cl/lastquakechile/img/favicon-16x16.png">
  <link rel="manifest" href="https://www.figonzal.cl/lastquakechile/img/site.webmanifest">
  <link rel="mask-icon" href="https://www.figonzal.cl/lastquakechile/img/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="shortcut icon" href="https://www.figonzal.cl/lastquakechile/img/favicon.ico">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-config" content="https://www.figonzal.cl/lastquakechile/img/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">
</head>

<body data-spy="scroll" data-target="navbar" data-offset="20" style="position: relative;">

  <?php readfile("navbar.html"); ?>

  <!-- Mascara Imagen-->
  <div id="portada" class="view">
    <div class="mask rgba-black-strong" id="inicio">

      <div class="container-fluid mt-5">

        <div class="row p-5">

          <!-- Columna izquierda -->
          <div class="col-md-6 white-text d-flex flex-column justify-content-center">

            <!-- Titulo -->
            <div class="text-sm-center text-center wow fadeInDown">
              <h1 class="h1 font-weight-bold">¡Descárgala!</h1>
              <hr class="hr-light mx-5">
              <h1 class="h4 font-weight-bold">Información actualizada, reportes y notificaciones de los últimos sismos ocurridos en Chile.</h1>
            </div>

            <!-- Imagen boton google play -->
            <div class="my-4 wow fadeInDown delay-1s">
              <a href="https://play.google.com/store/apps/details?id=cl.figonzal.lastquakechile" target="_blank">
                <img src="img/google-play-badge.png" class="mx-auto img-fluid" style="width: 50%;" alt="Google play link">
              </a>
            </div>

          </div>

          <!-- Columna derecha -->
          <div class="col-md-6 wow fadeInDown">
            <div class="d-flex flex-row">
              <img src="https://www.figonzal.cl/lastquakechile/img/pantalla_lastquakechile.png" alt="Imagen Celular" class="ml-5 img-fluid" style="width: 40%;">
              <img src="https://www.figonzal.cl/lastquakechile/img/pantalla_lastquakechile_mapa.png" alt="Imagen Celular" class="ml-3 img-fluid" style="width: 40%;">
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <!--Main layout-->
  <main>

    <!-- Section caracteristicas -->
    <section id="caractaristicas" class="py-5">

      <div class="container">
        <h2 class="h2-responsive my-5 font-weight-bold text-center wow fadeInDown">Características de la aplicación</h2>

        <div class="row">
          <div class="col-md-4 mb-5">
            <div class="card wow fadeIn z-depth-1-half py-3">

              <!-- Icono caracteristica -->
              <img class="card-img-top mx-auto" src="https://www.figonzal.cl/lastquakechile/img/svg/baseline-access_time-24px.svg" style="width: 25%;" alt="Icono tiempo">

              <!-- Descripcion del icono -->
              <div class="card-body">
                <h1 class="card-title text-center h3">Sismos diarios</h1>
                <h1 class="card-text text-center">
                  Consulta el listado completo de los sismos ocurridos durante un día
                </h1>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-5">

            <div class="card wow fadeIn z-depth-1-half py-3">

              <!-- Icono caracteristica -->
              <img class="card-img-top mx-auto" src="https://www.figonzal.cl/lastquakechile/img/svg/baseline-notification_important-24px.svg" style="width: 25%;" alt="Icono notificaciones">

              <!-- Descripcion del icono -->
              <div class="card-body">
                <h1 class="card-title text-center h3">Notificaciones</h1>
                <h1 class="card-text text-center">
                  Obtén notificaciones directamente a tu celular de los sismos mayores a 5.0 grados Richter
                </h1>
              </div>

            </div>
          </div>
          <div class="col-md-4 mb-5">

            <div class="card wow fadeIn z-depth-1-half py-3">

              <!-- Icono caracteristica -->
              <img class="card-img-top mx-auto" src="https://www.figonzal.cl/lastquakechile/img/svg/room-24px.svg" style="width: 25%;" alt="Icono mapa">

              <!-- Descripcion del icono -->
              <div class="card-body">
                <h1 class="card-title text-center h3">Ubicación</h1>
                <h1 class="card-text text-center">
                  Revisa el epicentro de los sismos en el mapa de la aplicación
                </h1>
              </div>

            </div>
          </div>

          <div class="col-md-6 mb-5">

            <div class="card wow fadeIn z-depth-1-half py-3">

              <!-- Icono caracteristica -->
              <img class="card-img-top mx-auto" src="https://www.figonzal.cl/lastquakechile/img/svg/analytics-24px.svg" style="width: 25%;" alt="Icono mapa">

              <!-- Descripcion del icono -->
              <div class="card-body">
                <h1 class="card-title text-center h3">Reportes Mensuales</h1>
                <h1 class="card-text text-center">
                  Recibe resúmenes de información sísmica una vez al mes
                </h1>
              </div>

            </div>
          </div>

          <div class="col-md-6 mb-5">

            <div class="card wow fadeIn z-depth-1-half py-3">

              <!-- Icono caracteristica -->
              <img class="card-img-top mx-auto" src="https://www.figonzal.cl/lastquakechile/img/svg/share-24px.svg" style="width: 25%;" alt="Icono mapa">

              <!-- Descripcion del icono -->
              <div class="card-body">
                <h1 class="card-title text-center h3">Compartir sismos</h1>
                <h1 class="h1 card-text text-center">
                  Comparte los sismos fácilmente vía WhatsApp o vía Email con tus cercanos
                </h1>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!--<div class="container">
      <hr>
    </div>-->

    <!-- Section desarrollo-->
    <!--<section id="caracteristica_dev" class="pb-md-5 py-5">
      <div class="container">
        <h2 class="h2-responsive font-weight-bold text-center mt-md-5 wow fadeInDown">... en un tiempo más, podrás tener</h2>

        <div class="row d-flex flex-row align-items-center p-5">

          <div class="col-md-6 mb-5">

            <div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.3s">
              <div>
                <img src="img/svg/baseline-check_circle-24px.svg" alt="Icono check" class="img-fluid">
              </div>
              <div class="pl-2 text-left">
                <p class="mb-0 text-muted">Notificaciones basadas en georeferenciación.</p>
              </div>
            </div>

            <div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.5s">
              <div>
                <img src="img/svg/baseline-check_circle-24px.svg" alt="Icono check" class="img-fluid">
              </div>
              <div class="pl-2 text-left">
                <p class="mb-0 text-muted">Filtros de búsqueda personalizables.</p>
              </div>
            </div>

            <div class="d-flex flex-row justify-content-start py-1 wow fadeInDown" data-wow-delay="0.7s">
              <div>
                <img src="img/svg/baseline-check_circle-24px.svg" alt="Imagen check" class="img-fluid">
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

    </section>-->


  </main>
  <?php readfile("footer.html"); ?>
</body>

</html>