<!DOCTYPE html>
<html lang="en">
<?php
    $nombre=$user->getNombre();
    $apellido=$user->getApellido();
    $codigo=$user->getCodigo();
    $semestre=$user->getSemestre();
    $nombcompleto=$user->getNombcompleto();
    $telefono=$user->getTelefono();
    $correo=$user->getCorreo();
    $ci=$user->getci();
    $foto=$user->getfoto();
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
        $pass= $part1.$codigo;
    }else{        
        $contar=strlen($pass);
        if ($contar<8) {
            $pass= $pass.$part1;
        }else{
            //echo $pass;
        }
    }
    $_calendarioAcademico="http://uecologica.edu.bo/pdf/calendario_academico.pdf";
    $_drive_lnk="https://drive.google.com/drive/folders/1St53zj--W4KYrZTE4TfPfKRq4d2coiPI?usp=sharing";
?>
<head>

    <!-- Basic Page Needs
    ================================================== -->
    <title>UNE - RRHH</title>
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
    <!-- icons
    ================================================== -->
    <link rel="stylesheet" href="css/icons.css">   
</head>
<body >
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
                <a href="index.php"><img src="img/trebol.png" alt=""> <span> UNE - RRHH</span> </a>
            </div>
            <div class="page-menu-inner" data-simplebar>
                <ul data-submenu-title="Menú">
                    <li><a href="index.php"><i class="icon-material-outline-dashboard"></i><span>Inicio</span></a></li>
                    <li><a href="#" onclick="r_personal('<?php echo $codigo;?>')"><i class="icon-feather-users"></i><span>Registrar Personal</span></a></li>   
                    <li><a href="#" onclick="r_personalmod('<?php echo $codigo;?>')"><i class="icon-feather-user-plus"></i><span>Modificar Personal</span></a></li>                  
                    <li><a href="#" onclick="horarios('<?php echo $codigo;?>')"><i class="icon-material-outline-access-time"></i><span>Horarios</span></a></li>
                    <li><a href="#" onclick="horarios_cambio('<?php echo $codigo;?>')"><i class="icon-line-awesome-clock-o"></i><span>Cambio de Horario</span></a></li>
                    <li><a href="#" onclick="reportes()"><i class="uil-edit-alt"></i> <span>Reportes Marcación</span></a> </li>
                    <li><a href="#" onclick="reportesadm()"><i class="uil-edit-alt"></i> <span>Personal Listado</span></a> </li>
                    <!--<li><a href="#" onclick="biblioteca('<?php //echo $codigo;?>')" ><i class="icon-material-outline-library-books"></i> <span>Biblioteca Virtual</span></a></li>
                    <li><a href="#" onclick="estado_pago('<?php //echo $codigo;?>')"><i class="icon-material-outline-account-balance-wallet"></i> <span>Estado de Cuenta</span></a></li>
                    <li><a href="#" onclick="historico_pagos('<?php //echo $codigo;?>')"><i class="icon-material-outline-monetization-on"></i> <span> Historico de Pagos</span></a></li>
                    <li><a href="#"  onclick="reglamentos()"><i class="icon-material-outline-book"></i> <span>Reglamentos</span></a></li> -->
                    <li><a href="includes/logout.php"><i class="icon-feather-log-out"></i> <span> Salir</span></a> </li>
                    <li><a>&nbsp;</a></li>                    
                    <!--<li><a href="book.html"><i class="uil-book-alt"></i> <span> Book</span></a> </li>
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
                            <!--<img src="../assets/images/logo-dark.svg" alt="">-->
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
                                    <h4>Tus Clases</h4>
                                    <a href="#">
                                        <i class="icon-feather-settings"
                                            uk-tooltip="title: Notifications settings ; pos: left"></i>
                                    </a>
                                </div>                            
                            </div>
                            <!-- notificiation icon  -->
                            <a href="#" class="header-widget-icon">
                                <i class="uil-bell"></i>
                                <!--<span>1</span>-->
                            </a>
                            <!-- notificiation dropdown -->
                            <div uk-dropdown="pos: top-right;mode:click ; animation: uk-animation-slide-bottom-small"
                                class="dropdown-notifications">                                
                                <!-- notivication header -->
                                <div class="dropdown-notifications-headline">
                                    <p style="color:red;">Notificaciones </p>
                                    <a href="#">
                                        <!--<i class="icon-feather-settings"
                                            uk-tooltip="title: Te recordamos que los segundos parciales se inician el próximo 12 de octubre, para estar habilitado(a), deberás haber cancelado tu segunda cuota ; pos: left"></i>-->
                                    </a>
                                    <!--<p>Te recordamos que el cuarto parcial inician el próximo 7 de Diciembre, para estar habilitado(a), deberás haber cancelado tu cuarta cuota</p>-->
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
                                    <h4>Mensajes</h4>
                                    <a href="#">
                                        <i class="icon-feather-settings"
                                            uk-tooltip="title: Message settings ; pos: left"></i>
                                    </a>
                                </div>

                            </div>


                            <!-- profile-icon-->
                            <a href="#" class="header-widget-icon profile-icon">
                                <?php
                                    if ($foto=="") {
                                        ?>
                                            <img src="img/archivo/sin_imagen.jpg" alt="" class="header-profile-icon">
                                        <?php
                                    }else{
                                        ?>
                                            <img src="https://storage.googleapis.com/une_segmento-one/estudiantes/<?php echo $foto; ?>" alt="" class="header-profile-icon">
                                        <?php
                                    }
                                ?>
                               
                            </a>
                            <div uk-dropdown="pos: top-right ;mode:click" class="dropdown-notifications small">

                                <!-- User Name / Avatar -->
                                <a href="#">
                                    <div class="dropdown-user-details">                                        
                                        <div class="dropdown-user-name">
                                            <?php echo $nombre; ?> <span><?php echo $apellido; ?></span>
                                        </div>
                                    </div>
                                </a>

                                <!-- User menu -->
                                <ul class="dropdown-user-menu">                                   
                                    <li><a href="#" uk-toggle="target: #modal-editar" onclick="edit_pass('<?php echo $codigo;?>')">
                                            <i class="icon-feather-settings"></i> Editar Contraseña</a>
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
                        <a href="#" class="uil-user icon-small uk-hidden@s" uk-toggle="target: .header-widget ; cls: is-active">
                        </a>

                    </div>
                    <!-- End Right Side Content / End -->
                </nav>
            </div>
            <!-- container  / End -->
        </header>
            <!-- Div para trabajar los contenidos-->
            <div class="page-content-inner" id="page-content-inner">
                <!--<nav class="responsive-tab mb-4">
                    <li class="uk-active"><a href="#">Account</a></li>
                    <li><a href="#">Billing</a></li>
                    <li><a href="user-profile-edit.html">Setting</a></li>
                </nav>-->
                <!--<div class="alert alert-warning"><strong>¡Atención!</strong><br>Te recordamos que el cuarto parcial inician el próximo 7 de Diciembre, para estar habilitado(a), deberás haber cancelado tu cuarta cuota</div>-->
                <div class="uk-grid">
                    <div class="uk-width-2-5@m">
                        <div class="uk-card-default rounded text-center p-4">
                            <h2>PERSONAL</h2>
                            <div class="user-profile-photo  m-auto">
                                <?php
                                if ($foto=="") {
                                    ?>
                                    <img src="img/archivo/sin_imagen.jpg" alt="">
                                    <?php
                                }else{
                                    ?>
                                <img src="https://storage.googleapis.com/une_segmento-one/estudiantes/<?php echo $foto; ?>">
                                <!--<img src="img/<?php //echo $foto;?>" alt="">-->
                                    <?php
                                }
                                ?> 
                            </div>
                            <h6 class="mb-2 mt-3"><?php echo $nombcompleto;?></h6>
                            <p class="m-0"><?php echo $codigo;?></p>
                        </div>                       
                    </div>  
                    <div class="uk-width-3-5@m">
                        <div class="uk-card-default rounded p-4">                            
                            <h6 class="uk-text-bold">DATOS ADICIONALES DEL PERSONAL</h6> 
                            <hr class="ms-1">
                            <div class="uk-child-width-1-3@s uk-grid-small p-4" uk-grid> 
                               <div>
                                    <h6 class="uk-text-bold">Nombres</h6>
                                    <p><?php echo $nombre;?></p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold">Apellidos</h6>
                                    <p><?php echo $apellido;?></p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold">Celular</h6>
                                    <p><?php echo $telefono;?></p>
                                    
                                </div>  
                                <div>
                                    <h6 class="uk-text-bold">Correo institucional</h6>
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
                                   
                                </div> 
                                                                                         
                            </div>
                        </div>
                    </div> 
                    <!-- <div class="icon-set-container">                     
                        <div class="glyph fs1">
                            <div class="clearfix bshadow0 pbs">
                              <a href="#"  onclick="reglamentos()">
                                <span class="icon-material-outline-dashboard">

                                </span>
                                <span class="mls"> REGLAMENTOS</span>
                                </a>
                            </div>
                        </div>
                    
                        <div class="glyph fs1">
                            <div class="clearfix bshadow0 pbs">
                              <a target="_blank\" href="https://drive.google.com/drive/folders/1St53zj--W4KYrZTE4TfPfKRq4d2coiPI?usp=sharing" >
                                <span class="icon-material-outline-contact-support">

                                </span>
                                <span class="mls"> TUTORIALES</span>
                                </a>
                            </div>
                        </div>
                                       
                        <div class="glyph fs1">
                            <div class="clearfix bshadow0 pbs">
                              <a target="_blank\" href="http://uecologica.edu.bo/pdf/calendario_academico.pdf">
                                <span class="icon-line-awesome-calendar-check-o">

                                </span>
                                <span class="mls"> CALENDARIO ACADÉMICO</span>
                                </a>
                            </div>
                        </div>
                    
                        <div class="glyph fs1">
                            <div class="clearfix bshadow0 pbs">
                              <a target="_blank\" href="http://uecologica.edu.bo/pdf/debanc.pdf">
                                <span class="icon-material-outline-dashboard">

                                </span>
                                <span class="mls"> MODELO EDUCATIVO DEBANC</span>
                                </a>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>               
        </div>        
            
            <!--Modal para editar numero de celular y contarseña-->
            <div id="modal-editar" uk-modal>
                <div class="uk-modal-dialog uk-modal-body"> 
                    <div id="modals"></div>                              
                </div>
            </div>
        </div>
    </div>
     <!-- footer
       ================================================== -->
    <!--<div class="footer">
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
    <script src="contenedor/js/direcciones.js?v=1.1.10"></script>
    <script src="contenedor/js/cv_funciones.js?v=1.1.8"></script>
    <script src="contenedor/js/mi_examen.js?1300"></script>
</body>
</html>