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
            <div class="col-md-12 white-text d-flex flex-column justify-content-center">

              <!-- Titulo -->
              <div class="text-sm-center text-left wow fadeInDown">
                <h1 class="h1 font-weight-bold">Dev Server</h1>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
    
  </header>

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script src="js/jquery-3.3.1.min.js"></script>

  <!-- Bootstrap tooltips -->
  <script src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script src="js/mdb.min.js"></script>

  <script>new WOW().init();</script>

</body>
</html>
