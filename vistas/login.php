<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UNE RRHH</title>
      <!-- CSS 
    ================================================== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/night-mode.css">
    <link rel="stylesheet" href="css/framework.css">
    <link rel="stylesheet" href="css/bootstrap.css"> 
      <!-- Favicon -->
    <link href="img/favicon.png" rel="icon" type="image/png">
   <style type="text/css">
        body {
              /*background-image: url("img/hojas.jpg");*/
              background: url(img/fondo20243.png) no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                -ms-background-size: cover;
                background-size: cover;
            }
    </style>
    <link rel="stylesheet" href="css/icons.css">
</head>
<body>  
      <!-- Content
    ================================================== -->
    <div uk-height-viewport class="uk-flex uk-flex-middle">
        <div class="uk-width-2-3@m uk-width-1-2@s m-auto rounded">
            <div class="uk-child-width-1-2@m uk-grid-collapse bg-gradient-grey" uk-grid>

                <!-- column one -->
                <div class="uk-margin-auto-vertical uk-text-center uk-animation-scale-up p-3 uk-light">
                    <img src="img/logo25.png" class="img-fluid" alt="Responsive image">
                    <!--<p>The Place You can learn Every Thing. </p>-->
                </div>

                <!-- column two -->
                <div class="uk-card-default p-5 rounded">
                    <div class="mb-4 uk-text-center">
                        <h3 class="mb-0"> Bienvenid@ Sistema De RRHH</h3>
                        <!--<p class="my-2">Inicio de Sessión</p>-->
                    </div>
                    <form action="" method="POST">

                        <div class="uk-form-group">
                            <label class="uk-form-label">Usuario: </label>

                            <div class="uk-position-relative w-100">
                                <span class="uk-form-icon">
                                    <i class="icon-feather-user"></i>
                                </span>
                                <input type="text" name="username" class="uk-input" placeholder="Usuario" autocomplete="off">
                            </div>
                        </div>
                        <div class="uk-form-group">
                            <label class="uk-form-label"> Contraseña:</label>

                            <div class="uk-position-relative w-100">
                                <span class="uk-form-icon">
                                    <i class="icon-feather-lock"></i>
                                </span>
                                <input type="password" name="password"  placeholder="Contraseña" class="uk-input">
                               
                            </div>

                        </div>

                        <div class="uk-form-group">                           
                            <div class="uk-position-relative w-100">
                                <?php                              
                                    if(isset($errorLogin)){
                                        //echo "<div class='alert alert-danger'></div>";
                                        echo "<div class='alert alert-warning alert-danger'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>¡Error! </strong>".$errorLogin."</div>";
                                    } 
                                ?>
                            </div>

                        </div>

                        <div class="mt-4 uk-flex-middle uk-grid-small" uk-grid>
                            <!--<div class="uk-width-expand@s">
                                <p> Te olvidaste la contraseña? <a href="#">Pulsa Aquí</a></p>
                            </div>-->
                            <div class="uk-width-auto@s">
                                <button type="submit" class="btn btn-success">Iniciar Sesión</button>
                            </div>
                        </div>
                    </form>
                </div><!--  End column two -->

            </div>
        </div>
    </div>
    <!-- Content -End
    ================================================== -->
      <!-- javaScripts
    ================================================== -->
    <script src="js/framework.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/simplebar.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
</body>
</html>