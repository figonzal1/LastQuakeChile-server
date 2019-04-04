<!DOCTYPE html>
<html lang="es">

<!--
  PAGINA WEB: LastQuakeChile
  ARCHIVO: index.php
-->

<!-- Importacion de head.html-->
<?php readfile("head.html"); ?>

<body data-spy="scroll" data-target="navbar" data-offset="20" style="position: relative;">
  <header>

    <?php readfile("navbar.html"); ?>

    <!-- Mascara Imagen-->

    <div id="portada" class="view">
      <div class="mask rgba-black-strong" id="inicio">

        <div class="container mt-5">

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
              <img src="img/lastquakechile_pantalla.png" alt="Imagen Celular" class="mx-auto img-fluid" style="width: 50%;">
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

      <div class="container">
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

    </section>


  </main>
  <?php readfile("footer.html"); ?>
</body>

</html>