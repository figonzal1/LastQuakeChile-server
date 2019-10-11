<!DOCTYPE html>
<html lang="es">

<!--
  PAGINA WEB: figonzal.cl
  ARCHIVO: index.php
-->

<!-- Importacion de head.html-->
<?php readfile("head.html"); ?>

<body data-spy="scroll" data-target="navbar" data-offset="20" style="position: relative;">

  <header>

    <!-- Importacion de navbar -->
    <?php readfile("navbar.html"); ?>

    <!-- Mascara Imagen-->
    <div id="portada" class="view">
      <div class="mask rgba-black-light" id="inicio">

        <div class="container mt-5">

          <div class="row d-flex justify-content-center pt-5">

            <div class="col-md-9">

              <div class="jumbotron p-5 text-white wow fadeInDown">

                <!-- Fotografía + Nombre y Subtitulo -->
                <div class="d-flex flex-row justify-content-center align-items-center">

                  <div class="col-md-2 col-sm-2 wow fadeIn" data-wow-delay="1.0s">
                    <img src="img/profile.jpg" alt="Profile Image" class="mx-auto img-fluid d-block rounded">
                  </div>

                  <div class="col-md-10 col-sm-10 ">
                    <h2 class="title h2-responsive wow fadeInDown" data-wow-delay="0.5s">Felipe González</h2>
                    <h5 class="h5-responsive text-muted font-weight-bold wow fadeInDown" data-wow-delay="1.0s">Android Developer</h5>
                  </div>
                </div>

                <!-- Area descripcion -->
                <div class="d-flex flex-column justify-content-center align-items-center my-3">

                  <div class="px-3 wow fadeIn" data-wow-delay="1.5s">
                    <p class="text-left text-break">¡Hola amig@!, Bienvenid@:</p>
                    <p class="text-left text-break"> Este es mi pequeño espacio personal en este inmenso ambiente interconectado,
                      aquí encontrarás algunos proyectos propios en los que he estado trabajando durante un par de años
                      y quizás algún día, suba algunas ideas que no me he animado a realizar aún.
                    </p>
                    <p class="text-left text-break">Eres libre de revisar mi contenido, ¡que te diviertas!</p>
                  </div>
                </div>

                <div class="px-3 wow fadeIn" data-wow-delay="2.0s">
                  <hr class="visible white" style="opacity: 0.3">
                </div>

                <!-- Botones Sociales -->
                <div class="d-flex flex-row justify-content-center align-content-center">

                  <a href="https://www.facebook.com/figonzal1" target="_blank" class="btn btn-outline-white waves-effect waves-light wow fadeIn" data-wow-delay="2.3s">

                    <i class="fab fa-facebook-f"></i>
                  </a>

                  <a href="https://www.instagram.com/_figonzal" target="_blank" class="btn btn-outline-white wow fadeIn" data-wow-delay="2.5s">

                    <i class="fab fa-instagram"></i>
                  </a>

                  <a href="https://www.linkedin.com/in/figonzal/" target="_blank" class="btn btn-outline-white wow fadeIn" data-wow-delay="2.7s">

                    <i class="fab fa-linkedin-in"></i>
                  </a>

                  <a href="https://github.com/figonzal1" target="_blank" class="btn btn-outline-white wow fadeIn" data-wow-delay="2.9s">

                    <i class="fab fa-github"></i>
                  </a>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </header>
  <?php readfile("footer.html"); ?>

</body>

</html>