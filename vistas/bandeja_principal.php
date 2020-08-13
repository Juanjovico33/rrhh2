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
    $password=$user->getPassword();
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
                <!--<ul data-submenu-title="Pages">
                    <li><a href="page-pricing.html"><i class="uil-bill"></i> <span> Pricing</span> </a>
                    </li>
                    <li><a href="page-faq.html"><i class="uil-comment-dots"></i> <span> Faq </span></a></li>
                    <li><a href="page-term.html"><i class="uil-info-circle"></i> <span>Terms</span> </a></li>
                    <li><a href="page-privacy.html"><i class="uil-shield-question"></i><span>Privecy</span> </a>
                    </li>
                </ul>
                <ul data-submenu-title="Development">
                    <li><a href="development-elements.html"><i class="uil-layers"></i><span> Elements </span></a></li>
                    <li><a href="development-compounents.html"><i class="uil-layer-group"></i><span> Compounents</span>
                        </a>
                    </li>
                    <li><a href="development-icons.html"><i class="icon-feather-flag"></i><span> Icons </span></a></li>
                </ul>
                <ul data-submenu-title="User Account">
                    <li><a href="user-profile.html"><i class="uil-user-circle"></i><span> Profile </span></a></li>
                    <li><a href="user-profile-edit.html"><i class="uil-edit-alt"></i> <span>Account</span> </a>
                    </li>
                    <li><a href="#"><i class="uil-check-circle"></i> <span>Forms</span> </a>
                        <ul>
                            <li>
                                <a href="form-login.html"> Login </a>
                                <a href="form-register.html"> Register </a>
                                <a href="form-modern-login.html"> Simple Register</a>
                                <a href="form-modern-singup.html"> Simple Register</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul data-submenu-title="Specialty pages">
                    <li><a href="specialty-comming-soon.html"><i class="icon-material-outline-dashboard"></i>
                            <span> Comming-soon</span> </a></li>
                    <li><a href="specialty-maintanence.html"><i
                                class="icon-feather-help-circle"></i><span>Maintanence</span>
                        </a></li>
                </ul>-->
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

                                <!-- notification contents -->
                                <!-- <div class="dropdown-notifications-content" data-simplebar> -->

                                    <!-- notiviation list -->
                                    <!-- <ul>
                                        <li class="notifications-not-read">
                                            <a href="course-intro.html">
                                                <span class="notification-image">
                                                    <img src="../assets/images/course/1.png" alt=""> </span>
                                                <span class="notification-text">
                                                    <span class="course-title">Ultimate Web Designer & Web Developer
                                                    </span>
                                                    <span class="course-number">6/35 </span>
                                                    <span class="course-progressbar">
                                                        <span class="course-progressbar-filler"
                                                            style="width:95%"></span>
                                                    </span>
                                                </span> -->

                                                <!-- option menu -->
                                                <!-- <span class="btn-option">
                                                    <i class="icon-feather-more-vertical"></i>
                                                </span>
                                                <div class="dropdown-option-nav"
                                                    uk-dropdown="pos: bottom-right ;mode : hover">
                                                    <ul>
                                                        <li>
                                                            <span>
                                                                <i class="icon-material-outline-dashboard"></i>
                                                                Course Dashboard</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-video"></i>
                                                                Resume Course</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-x"></i>
                                                                Remove Course</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="course-intro.html">
                                                <span class="notification-image">
                                                    <img src="../assets/images/course/3.png" alt=""> </span>
                                                <span class="notification-text">
                                                    <span class="course-title">The Complete JavaScript Course Build Real
                                                        Projects !</span>
                                                    <span class="course-number">6/35 </span>
                                                    <span class="course-progressbar">
                                                        <span class="course-progressbar-filler"
                                                            style="width:95%"></span>
                                                    </span>
                                                </span> -->

                                                <!-- option menu -->
                                                <!-- <span class="btn-option">
                                                    <i class="icon-feather-more-vertical"></i>
                                                </span>
                                                <div class="dropdown-option-nav"
                                                    uk-dropdown="pos: bottom-right ;mode : hover">
                                                    <ul>
                                                        <li>
                                                            <span>
                                                                <i class="icon-material-outline-dashboard"></i>
                                                                Course Dashboard</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-video"></i>
                                                                Resume Course</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-x"></i>
                                                                Remove Course</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </a>
                                        </li>
                                        <li>
                                            <a href="course-intro.html">
                                                <span class="notification-image">
                                                    <img src="../assets/images/course/2.png" alt=""> </span>
                                                <span class="notification-text">
                                                    <span class="course-title">Learn Angular Fundamentals From The
                                                        Beginning</span>
                                                    <span class="course-number">6/35 </span>
                                                    <span class="course-progressbar">
                                                        <span class="course-progressbar-filler"
                                                            style="width:95%"></span>
                                                    </span>
                                                </span> -->

                                                <!-- option menu -->
                                                <!-- <span class="btn-option">
                                                    <i class="icon-feather-more-vertical"></i>
                                                </span>
                                                <div class="dropdown-option-nav"
                                                    uk-dropdown="pos: bottom-right ;mode : hover">
                                                    <ul>
                                                        <li>
                                                            <span>
                                                                <i class="icon-material-outline-dashboard"></i>
                                                                Course Dashboard</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-video"></i>
                                                                Resume Course</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-x"></i>
                                                                Remove Course</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="course-intro.html">
                                                <span class="notification-image">
                                                    <img src="../assets/images/course/1.png" alt=""> </span>
                                                <span class="notification-text">
                                                    <span class="course-title">Ultimate Web Designer & Web Developer
                                                    </span>
                                                    <span class="course-number">6/35 </span>
                                                    <span class="course-progressbar">
                                                        <span class="course-progressbar-filler"
                                                            style="width:95%"></span>
                                                    </span>
                                                </span> -->

                                                <!-- option menu -->
                                                <!-- <span class="btn-option">
                                                    <i class="icon-feather-more-vertical"></i>
                                                </span>
                                                <div class="dropdown-option-nav"
                                                    uk-dropdown="pos: top-right ;mode : hover">
                                                    <ul>
                                                        <li>
                                                            <span>
                                                                <i class="icon-material-outline-dashboard"></i>
                                                                Course Dashboard</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-video"></i>
                                                                Resume Course</span>
                                                        </li>
                                                        <li>
                                                            <span>
                                                                <i class="icon-feather-x"></i>
                                                                Remove Course</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                                <div class="dropdown-notifications-footer">
                                    <a href="#"> sell all</a>
                                </div> -->
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

                                <!-- notification contents -->
                                <!-- <div class="dropdown-notifications-content" data-simplebar> -->

                                    <!-- notiviation list -->
                                    <!-- <ul>
                                        <li class="notifications-not-read">
                                            <a href="#">
                                                <span class="notification-icon btn btn-soft-danger disabled">
                                                    <i class="icon-feather-thumbs-up"></i></span>
                                                <span class="notification-text">
                                                    <strong>Adrian Mohani</strong> Like Your Comment On Course
                                                    <span class="text-primary">Javascript Introduction </span>
                                                    <br> <span class="time-ago"> 9 hours ago </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="notification-icon btn btn-soft-primary disabled">
                                                    <i class="icon-feather-message-circle"></i></span>
                                                <span class="notification-text">
                                                    <strong>Stella Johnson</strong> Replay Your Comments in
                                                    <span class="text-primary">Programming for Games</span>
                                                    <br> <span class="time-ago"> 12 hours ago </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="notification-icon btn btn-soft-success disabled">
                                                    <i class="icon-feather-star"></i></span>
                                                <span class="notification-text">
                                                    <strong>Alex Dolgove</strong> Added New Review In Course
                                                    <span class="text-primary">Full Stack PHP Developer</span>
                                                    <br> <span class="time-ago"> 19 hours ago </span>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="notifications-not-read">
                                            <a href="#">
                                                <span class="notification-icon btn btn-soft-danger disabled">
                                                    <i class="icon-feather-share-2"></i></span>
                                                <span class="notification-text">
                                                    <strong>Jonathan Madano</strong> Shared Your Discussion On Course
                                                    <span class="text-primary">Css Flex Box </span>
                                                    <br> <span class="time-ago"> Yesterday </span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul> -->

                                <!-- </div> -->


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

                                <!-- notification contents -->
                                <!-- <div class="dropdown-notifications-content" data-simplebar> -->

                                    <!-- notiviation list -->
                                    <!-- <ul>
                                        <li class="notifications-not-read">
                                            <a href="#">
                                                <span class="notification-avatar">
                                                    <img src="img/avatar-2.jpg" alt="">
                                                </span>
                                                <div class="notification-text notification-msg-text">
                                                    <strong>Jonathan Madano</strong>
                                                    <p>Okay.. Thanks for The Answer I will be waiting for your...
                                                    </p>
                                                    <span class="time-ago"> 2 hours ago </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="notification-avatar">
                                                    <img src="assets/images/avatars/avatar-3.jpg" alt="">
                                                </span>
                                                <div class="notification-text notification-msg-text">
                                                    <strong>Stella Johnson</strong>
                                                    <p> Alex will explain you how to keep the HTML structure and all
                                                        that...</p>
                                                    <span class="time-ago"> 7 hours ago </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="notification-avatar">
                                                    <img src="assets/images/avatars/avatar-1.jpg" alt="">
                                                </span>
                                                <div class="notification-text notification-msg-text">
                                                    <strong>Alex Dolgove</strong>
                                                    <p> Alia Joseph just joined Messenger! Be the first to send a
                                                        welcome message..</p>
                                                    <span class="time-ago"> 19 hours ago </span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="notification-avatar">
                                                    <img src="assets/images/avatars/avatar-4.jpg" alt="">
                                                </span>
                                                <div class="notification-text notification-msg-text">
                                                    <strong>Adrian Mohani</strong>
                                                    <p> Okay.. Thanks for The Answer I will be waiting for your...
                                                    </p>
                                                    <span class="time-ago"> Yesterday </span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul> -->

                                <!-- </div>
                                <div class="dropdown-notifications-footer">
                                    <a href="#"> sell all <i class="icon-line-awesome-long-arrow-right"></i> </a>
                                </div> -->
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
                                    <!-- <li>
                                        <a href="#">
                                            <i class="icon-material-outline-dashboard"></i> Dashboard</a>
                                    </li>
                                    <li><a href="#">
                                            <i class="icon-feather-bookmark"></i> Bookmark </a>
                                    </li> -->
                                    <li><a href="#">
                                            <i class="icon-feather-settings"></i> Editar Cuenta</a>
                                    </li>
                                    <!-- <li><a href="#" style="color:#62d76b">
                                            <i class="icon-feather-star"></i> Upgrade To Premium</a>
                                    </li> -->
                                    <!-- <li>
                                        <a href="#" id="night-mode" class="btn-night-mode">
                                            <i class="icon-feather-moon"></i> Night mode
                                            <span class="btn-night-mode-switch">
                                                <span class="uk-switch-button"></span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="menu-divider">
                                    <li><a href="#">
                                            <i class="icon-feather-help-circle"></i> Help</a>
                                    </li> -->
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

                        <!-- <div class="uk-card-default rounded mt-5">
                            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                                <h5 class="mb-0"> Progress </h5>
                                    <a href="#"> more </a>
                            </div>
                            <hr class="m-0">
                            <div class="p-3">

                                <div class="uk-grid-small uk-flex-middle" uk-grid>

                                    <div class="uk-width-auto">
                                        <button type="button" class="btn btn-danger btn-icon-only">
                                            <span class="d-flex justify-content-center">
                                       <i class="icon-brand-angular icon-small"></i>
                                            </span>
                                          </button>
                                    </div>
                                    <div class="uk-width-expand">
                                        <h5 class="mb-2"> Angular </h5>
                                        <div class="course-progressbar">
                                            <div class="course-progressbar-filler" style="width:100%"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="uk-grid-small uk-flex-middle" uk-grid>

                                    <div class="uk-width-auto">
                                        <button type="button" class="btn btn-warning btn-icon-only">
                                            <span class="d-flex justify-content-center">
                                                <i class="icon-brand-html5 icon-small"></i>
                                            </span>
                                          </button>
                                    </div>
                                    <div class="uk-width-expand">
                                        <h5 class="mb-2"> html5 </h5>
                                        <div class="course-progressbar">
                                            <div class="course-progressbar-filler" style="width:35%"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="uk-grid-small uk-flex-middle" uk-grid>

                                    <div class="uk-width-auto">
                                        <button type="button" class="btn btn-primary btn-icon-only">
                                            <span class="d-flex justify-content-center">
                                                <i class="icon-brand-css3-alt icon-small"></i>
                                            </span>
                                          </button>
                                    </div>
                                    <div class="uk-width-expand">
                                        <h5 class="mb-2"> css3 </h5>
                                        <div class="course-progressbar">
                                            <div class="course-progressbar-filler" style="width:65%"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div> -->

                    </div>                   
                    <div class="uk-width-expand@m">

                        <div class="uk-card-default rounded">
                            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                                <h6 class="uk-text-bold">Detalles Del/La Estudiante</h6>                                                                   
                                <a href="#" uk-tooltip="title:Editar Cuenta; pos: left"> 
                                    <i class="icon-feather-settings"></i> </a>
                            </div>                            
                            <hr class="m-0">                                
                            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid> 
                                <table class="table ">
                                    <tr>
                                        <td colspan="2"><h6 class="uk-text-bold"> Nombre completo </h6></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p> <?php echo $nombcompleto;?> </p></td>
                                    </tr>
                                    <tr>
                                        <td><h6 class="uk-text-bold">Carrera</h6></td><td><h6 class="uk-text-bold">Código</h6></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $carrera;?></td><td><?php echo $codigo;?></td>
                                    </tr>
                                    <tr>
                                        <td><h6 class="uk-text-bold">Correo</h6></td><td><h6 class="uk-text-bold">Celular</h6></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $correo;?></td><td><?php echo $telefono;?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><h6 class="uk-text-bold"> Contraseña </h6></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><?php echo $password;?></td>
                                    </tr>
                                </table>                             
                            </div>
                        </div>

                        <!-- <div class="uk-card-default rounded mt-4">
                            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                                <h5 class="mb-0"></h5>
                                <a href="#" uk-tooltip="title: Edit Billing address; pos: left"> <i
                                        class="icon-feather-settings"></i> </a>
                            </div>
                            <hr class="m-0">
                            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
                                <div>
                                    <h6 class="uk-text-bold"> Number </h6>
                                        <p> 23, Block C2 </p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold"> Street </h6>
                                        <p> Church Street </p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold"> City </h6>
                                        <p> Los Angeles </p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold"> Postal Code </h6>
                                        <p> 100065 </p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold"> State </h6>
                                        <p> CA </p>
                                </div>
                                <div>
                                    <h6 class="uk-text-bold"> Country </h6>
                                    <p> United States </p>
                                </div>
                            </div>
                        </div> -->
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