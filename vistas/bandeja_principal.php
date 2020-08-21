<!DOCTYPE html>
<html lang="en">
<?php
    $nombre=$user->getNombre();
    $apellido=$user->getApellido();
    $codigo=$user->getCodigo();
    $carrera=$user->getCarrera();
    $semestre=$user->getSemestre();
    $nombcompleto=$user->getNombcompleto();
    $telefono=$user->getTelefono();
    $correo=$user->getCorreo();
    $ci=$user->getci();
    $password=$user->getPassword();
    $part_cel=explode(" ", $telefono);   
    $part2=strtolower($part_cel[0]);     
    $part_nomb=explode(" ", $nombre);   
    $part1=strtolower($part_nomb[0]);    
    if ($ci=='0' || $ci=='') {
        $pass=$part2;
    }else{
        $pass=$ci;
    }
    $mail=$part1.$codigo."@uecologica.edu.bo"; 


    if ($pass=='') {
        $pass= $part1.$codest;
    }else{        
        $contar=strlen($pass);
        if ($contar<8) {
            $pass= $pass.$part1;
        }else{
            //echo $pass;
        }
    }
?>
<head>

    <!-- Basic Page Needs
    ================================================== -->
    <title>UNE - Plataforma</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plataforma estudiantil UNE">

    <!-- Favicon -->
    <link href="img/favicon.png" rel="icon" type="image/png">

    <!-- CSS 
    ================================================== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/night-mode.css">
    <link rel="stylesheet" href="css/framework.css">
    <link rel="stylesheet" href="css/bootstrap.css"> 

    <!-- icons
    ================================================== -->
    <link rel="stylesheet" href="css/icons.css">


</head>

<body>
    <div id="wrapper_cv">
    </div>
    <div id="wrapper"> 
        <!-- search overlay-->
        <div id="searchbox">
            <div class="search-overlay"></div>
            <div class="search-input-wrapper">
                <div class="search-input-container">
                    <div class="search-input-control">
                        <span class="icon-feather-x btn-close uk-animation-scale-up"
                            uk-toggle="target: #searchbox; cls: is-active"></span>
                        <div class=" uk-animation-slide-bottom">
                            <input type="text" name="search" autofocus required>
                            <p class="search-help">Type the name of the Course and book you are looking for</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- overlay seach on mobile-->
        <div class="nav-overlay uk-navbar-left uk-position-relative uk-flex-1 bg-grey uk-light p-2" hidden
            style="z-index: 10000;">
            <div class="uk-navbar-item uk-width-expand" style="min-height: 60px;">
                <form class="uk-search uk-search-navbar uk-width-1-1">
                    <input class="uk-search-input" type="search" placeholder="Search..." autofocus>
                </form>
            </div>
            <a class="uk-navbar-toggle" uk-close uk-toggle="target: .nav-overlay; animation: uk-animation-fade"
                href="#"></a>
        </div>

        <!-- menu -->
        <div class="page-menu">
            <!-- btn close on small devices -->
            <span class="btn-menu-close" uk-toggle="target: #wrapper ; cls: mobile-active"></span>
            <!-- traiger btn -->
            <span class="btn-menu-trigger" uk-toggle="target: .page-menu ; cls: menu-large"></span>

            <!-- logo -->
            <div class="logo uk-visible@s">
                <a href="index.php"><img src="img/trebol.png" alt=""> <span> U.N.E.</span> </a>
            </div>
            <div class="page-menu-inner" data-simplebar>
                <ul data-submenu-title="Menú">
                <li><a href="index.php"><i class="icon-material-outline-person-pin"></i><span>Inicio</span></a></li>
                    <li><a href="#" onclick="inscripcion_online('<?php echo $codigo;?>','<?php echo $semestre;?>','<?php echo $carrera;?>')"><i class="icon-line-awesome-credit-card"></i><span> Inscripciones Online</span></a></li>
                    <li><a href="#" onclick="ver_materias('<?php echo $codigo;?>')"><i class="uil-layer-group"></i><span> Materias Registradas</span></a> </li>
                    <li><a href="#" onclick="ver_materias_examenes('<?php echo $codigo;?>')"><i class="uil-edit-alt"></i> <span> Mis Exámenes</span></a> </li>
                    <li><a href="includes/logout.php"><i class="icon-feather-log-out"></i> <span> Salir</span></a> </li>
                    <!-- <li><a href="book.html"><i class="uil-book-alt"></i> <span> Book</span></a> </li>
                    <li><a href="blog-1.html"><i class="uil-file-alt"></i> <span> Blog</span></a> </li> -->
                </ul>                
            </div>
        </div>


        <!-- content -->
        <div class="page-content">

         <!-- Header Container
        ================================================== -->
        <header class="header" uk-sticky="top:20 ; cls-active:header-sticky">

            <div class="container">
                <nav uk-navbar>

                    <!-- left Side Content -->
                    <div class="uk-navbar-left">

                        <span class="btn-mobile" uk-toggle="target: #wrapper ; cls: mobile-active"></span>



                        <!-- logo -->
                        <a href="dashboard.html" class="logo">
                            <img src="../assets/images/logo-dark.svg" alt="">
                            <span> Courseplus</span>
                        </a>



                    </div>


                    <!--  Right Side Content   -->

                    <div class="uk-navbar-right">

                        <div class="header-widget">
                            <!-- User icons close mobile-->
                            <span class="icon-feather-x icon-small uk-hidden@s"
                                uk-toggle="target: .header-widget ; cls: is-active"> </span>


                            <a href="#" class="header-widget-icon">
                                <i class="uil-youtube-alt"></i>
                            </a>

                            <!-- courses dropdown List -->
                            <div uk-dropdown="pos: top;mode:click;animation: uk-animation-slide-bottom-small"
                                class="dropdown-notifications my-courses-dropdown">

                                <!-- notivication header -->
                                <div class="dropdown-notifications-headline">
                                    <h4>Your Courses</h4>
                                    <a href="#">
                                        <i class="icon-feather-settings"
                                            uk-tooltip="title: Notifications settings ; pos: left"></i>
                                    </a>
                                </div>
                            
                            </div>

                            <!-- notificiation icon  -->

                            <a href="#" class="header-widget-icon">
                                <i class="uil-bell"></i>
                                
                            </a>

                            <!-- notificiation dropdown -->
                            <div uk-dropdown="pos: top-right;mode:click ; animation: uk-animation-slide-bottom-small"
                                class="dropdown-notifications">

                                <!-- notivication header -->
                                <div class="dropdown-notifications-headline">
                                    <h4>Notifications </h4>
                                    <a href="#">
                                        <i class="icon-feather-settings"
                                            uk-tooltip="title: Notifications settings ; pos: left"></i>
                                    </a>
                                </div>
                             


                            </div>


                            <!-- Message  -->

                            <a href="#" class="header-widget-icon" uk-tooltip="title: Message ; pos: bottom ;offset:21">
                                <i class="uil-envelope-alt"></i>
                                <!-- <span>1</span> -->
                            </a>

                            <!-- Message  notificiation dropdown -->
                            <div uk-dropdown=" pos: top-right;mode:click" class="dropdown-notifications">

                                <!-- notivication header -->
                                <div class="dropdown-notifications-headline">
                                    <h4>Messages</h4>
                                    <a href="#">
                                        <i class="icon-feather-settings"
                                            uk-tooltip="title: Message settings ; pos: left"></i>
                                    </a>
                                </div>

                            </div>


                            <!-- profile-icon-->
                            <a href="#" class="header-widget-icon profile-icon">
                                <img src="img/avatar-2.jpg" alt="" class="header-profile-icon">
                            </a>
                            <div uk-dropdown="pos: top-right ;mode:click" class="dropdown-notifications small">

                                <!-- User Name / Avatar -->
                                <a href="profile-1.html">
                                    <div class="dropdown-user-details">                                        
                                        <div class="dropdown-user-name">
                                            <?php echo $nombre; ?> <span><?php echo $carrera; ?></span>
                                        </div>
                                    </div>
                                </a>

                                <!-- User menu -->

                                <ul class="dropdown-user-menu">
                                   
                                    <li><a href="#">
                                            <i class="icon-feather-settings"></i> Editar Cuenta</a>
                                    </li>
                                 
                                    <li><a href="includes/logout.php">
                                            <i class="icon-feather-log-out"></i> Salir</a>
                                    </li>
                                </ul>


                            </div>


                        </div>



                        <!-- icon search-->
                        <a class="uk-navbar-toggle uk-hidden@s"
                            uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#">
                            <i class="uil-search icon-small"></i>
                        </a>
                        
                        <!-- User icons -->
                            <a href="#" class="uil-user icon-small uk-hidden@s"
                            uk-toggle="target: .header-widget ; cls: is-active">
                            </a>

                    </div>
                    <!-- End Right Side Content / End -->


                </nav>

            </div>
            <!-- container  / End -->
        </header>
            <!-- Div para trabajar los contenidos-->
            <div class="page-content-inner" id="page-content-inner">
                <h2>Estudiante</h2>
                <!--<nav class="responsive-tab mb-4">
                    <li class="uk-active"><a href="#">Account</a></li>
                    <li><a href="#">Billing</a></li>
                    <li><a href="user-profile-edit.html">Setting</a></li>
                </nav>-->
                <div class="uk-grid">
                    <div class="uk-width-2-5@m">

                        <div class="uk-card-default rounded text-center p-4">

                            <div class="user-profile-photo  m-auto">
                                <img src="img/archivo/sin_imagen.jpg" alt="">
                            </div>

                            <h6 class="mb-2 mt-3"><?php echo $nombcompleto;?></h6>
                            <p class="m-0"><?php echo $codigo;?></p>

                        </div>

                    </div>                   
                    <div class="uk-width-expand@m">
                        <div class="uk-card-default rounded">
                            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                                <h6 class="uk-text-bold">Datos adicionales Del Estudiante</h6>  
                                <a href="#" uk-tooltip="title:Editar Cuenta; pos: left"> 
                                    <i class="icon-feather-settings"></i> </a>
                            </div>                            
                            <hr class="m-0">                                
                            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid> 
                                   <div>
                                        <h6 class="uk-text-bold">Carrera</h6>
                                        <p><?php echo $carrera;?></p>
                                    </div>
                                    <div>
                                        <h6 class="uk-text-bold">Código</h6>
                                        <p><?php echo $codigo;?></p>
                                    </div>
                                    <div>
                                        <h6 class="uk-text-bold">Correo</h6>
                                        <p><?php 
                                        if ($correo=="") {
                                            echo $mail;
                                        }else{
                                            echo $correo;
                                        }
                                        ?>
                                        </p>
                                    </div>  
                                    <div>
                                        <h6 class="uk-text-bold">Celular</h6>
                                        <p><?php echo $telefono;?></p>
                                    </div>
                                    <div>
                                        <h6 class="uk-text-bold">Contraseña</h6>
                                        <p><?php 
                                        if ($password=="") {
                                            echo $pass;
                                            echo "<br>";
                                            echo "<div class='alert alert-warning'><strong>¡Atención!</strong> Para activar su correo solicitelo a su agente de marketing</div>";
                                        }else{
                                            echo $password; 
                                        }
                                        ?>
                                        </p>
                                    </div>                                                           
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
             <!-- footer
               ================================================== -->
                <!-- <div class="footer">
                    <div class="uk-grid-collapse" uk-grid>
                        <div class="uk-width-expand@s uk-first-column">
                            <p>© 2020 <strong>SAINCO - Plataforma Estudiante </strong>Dept.Sistemas - Todos los Derechos Reservados. </p>
                        </div> -->
                        <!--<div class="uk-width-auto@s">
                            <nav class="footer-nav-icon">
                                <ul>
                                    <li><a href="#"><i class="icon-brand-facebook"></i></a></li>
                                    <li><a href="#"><i class="icon-brand-dribbble"></i></a></li>
                                    <li><a href="#"><i class="icon-brand-youtube"></i></a></li>
                                    <li><a href="#"><i class="icon-brand-twitter"></i></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
        </div>
    </div>   --> 
    <!-- javaScripts
    ================================================== -->
    <script src="js/framework.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/simplebar.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="contenedor/js/direcciones.js"></script>
    <script src="contenedor/js/cv_funciones.js"></script>
</body>
</html>