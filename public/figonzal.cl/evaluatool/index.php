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
                <h1 class="h1 font-weight-bold">Beta pública disponible</h1>
              </div>

              <!-- Separador  -->
              <div class="text-center wow fadeInDown">
                <hr class="hr-light">
              </div>

              <!-- Descripcion -->
              <div class="text-sm-center text-left mt-2 wow fadeInDown">
                <p class="text-center">Evalúa de forma fácil los diferentes items de la batería psicopedagógica Evalúa.</p>
              </div>

              <!-- Imagen boton google play -->
              <div class="my-4 wow fadeInDown delay-1s">
                <p class="text-center">Inscríbete en la fase de pruebas usando este botón</p>
                <a href="https://play.google.com/apps/testing/cl.figonzal.evaluatool" target="_blank">
                  <img src="img/google-play-badge.png" class="mx-auto img-fluid" style="width: 35%;" alt="Google play beta link">
                </a>

              </div>

              <div class="my-4 wow fadeInDown delay-1s" data-wow-delay="1.5s">
                <p class="text-center">Grupo Whatsapp para problemas, dudas o consultas sobre la app. (Link en el icono)</p>
                <a href="https://chat.whatsapp.com/HY53a5RlUvvFNwFwstXHHy" target="_blank">
                  <img src="img/logo_wsp.png" class="mx-auto img-fluid" style="width: 20%;" alt="Grupo WhatSapp">
                </a>

              </div>

            </div>

            <!-- Columna derecha -->
            <div class="col-md-6 wow fadeInDown align-self-center">
              <!-- Imagen celular -->
              <img src="img/evaluatool_pantalla.png" alt="Imagen Celular" class="mx-auto img-fluid" style="width: 40%;">
            </div>

          </div>

        </div>
      </div>
    </div>

  </header>

  <!--Main layout-->
  <main>


  </main>
  <?php readfile("footer.html"); ?>
</body>

</html>