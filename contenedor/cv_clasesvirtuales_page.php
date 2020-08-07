<?php
    include ("../includes/_actividades.php");
    $id_grupo=$_POST['_idgrupo'];
    $cod_est=$_POST['_codest'];
    // $codmat=$_POST['_codmat'];
    // $per=$_POST['_per'];

    $act=new actividad();
    $clase=0;
    $act->getClasesVirtuales($id_grupo);

    $_actividades=null;
    $_actividades=$act->getClases();
?>
<!doctype html>
<html lang="en">

<head>
    
    <!-- Basic Page Needs
    ================================================== -->
    <title>UNE - Clases virtuales</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Courseplus - Professional Learning Management HTML Template">

    <!-- Favicon -->
    <link href="images/favicon.png" rel="icon" type="image/png">

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


<body class="course-watch-page">
    
        <!-- Wrapper -->
        <div id="wrapper_cv">
            
            <div class="course-layouts">

                <div class="course-content bg-dark">
                    <div class="course-header">
                            <a href="#" class="btn-back" uk-toggle="target: .course-layouts; cls: course-sidebar-collapse">
                                <i class="icon-feather-chevron-left"></i>
                            </a>
                            <div>
                            <a href="#">
                                <i class="icon-feather-help-circle btns"></i> </a>
                            <div uk-drop="pos: bottom-right;mode : click">
                                <div class="uk-card-default p-4">
                                    <h4> Acerca de las clases virtuales</h4>
                                    <p class="mt-2 mb-0">Esta seccion listará todas las clases, materiales y tareas que el docente vaya registrando durante el periodo de avance académico.</p>
                                </div>
                            </div>
<!-- 
                            <a hred="#">
                                <i class="icon-feather-more-vertical btns"></i>
                            </a>
                            <div class="dropdown-option-nav " uk-dropdown="pos: bottom-right ;mode : click">
                                <ul>

                                    <li><a href="#">
                                            <i class="icon-feather-bookmark"></i>
                                            Add To Bookmarks</a></li>
                                    <li><a href="#">
                                            <i class="icon-feather-share-2"></i>
                                            Share With Friend </a></li>

                                    <li>
                                        <a href="#" id="night-mode" class="btn-night-mode">
                                            <i class="icon-line-awesome-lightbulb-o"></i> Night mode
                                            <label class="btn-night-mode-switch">
                                                <div class="uk-switch-button"></div>
                                            </label>
                                        </a>
                                    </li>
                                </ul>
                            </div> -->


                        </div>
                    </div>

                    <div id="contenido_cv" class="page-content-inner bg-light">

                    </div>
                </div>
               
                <!-- course sidebar -->

                <div class="course-sidebar">
                    <div class="course-sidebar-title">
                        <!-- <h3> Clases Virtuales</h3> --><div style="align-items:right;"><a href="#" onclick="regresarA_bandejaprincipal()">Regresar</div></p>
                    </div>
                    <div class="course-sidebar-container" data-simplebar>
                        <ul class="course-video-list-section" uk-accordion>
                            <li class="uk-open">
                                <a class="uk-accordion-title" href="#"> Lista de clases</a>
                                <div class="uk-accordion-content">
                                    <!-- course-video-list -->
                                    <ul id="menu_clases" class="course-video-list highlight-watched" uk-switcher=" connect: #video-slider  ; animation: uk-animation-slide-right-small, uk-animation-slide-left-medium">
                                        <?php
                                            for($i=0; $i<count($_actividades); $i++){
                                                $aux_clase = $_actividades[$i];
                                                $iclase=$aux_clase->getItem();
                                                $clase=$aux_clase->getId_clase();
                                                $fecha=$aux_clase->getFecha_pub();
                                                if($iclase==1){
                                                    ?>
                                                    <li class="watched"><a href="#" onclick="cargar_datos_clase(<?=$iclase;?>, <?=$id_grupo;?>, <?=$clase;?>, <?=$cod_est;?>)"><i class="uil-file-alt"></i> Clase <?=$iclase;?> (<?=$fecha;?>)</a></li> 
                                                    <?php
                                                }else{?>
                                                    <li><a href="#" onclick="cargar_datos_clase(<?=$iclase;?>, <?=$id_grupo;?>, <?=$clase;?>, <?=$cod_est;?>)"><i class="uil-file-alt"></i> Clase <?=$iclase;?> (<?=$fecha;?>)</a> </li><?php  
                                                }
                                        ?>
                                        <?php 
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function (window, document, undefined) {
                'use strict';
                if (!('localStorage' in window)) return;
                var nightMode = localStorage.getItem('gmtNightMode');
                if (nightMode) {
                    document.documentElement.className += ' night-mode';
                }
            })(window, document);


            (function (window, document, undefined) {

                'use strict';

                // Feature test
                if (!('localStorage' in window)) return;

                // Get our newly insert toggle
                var nightMode = document.querySelector('#night-mode');
                if (!nightMode) return;

                // When clicked, toggle night mode on or off
                nightMode.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.documentElement.classList.toggle('night-mode');
                    if (document.documentElement.classList.contains('night-mode')) {
                        localStorage.setItem('gmtNightMode', true);
                        return;
                    }
                    localStorage.removeItem('gmtNightMode');
                }, false);

            })(window, document);
        </script>

        <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            function make_button_active(tab) {
                //Get item siblings
                var siblings = tab.siblings();

                /* Remove active class on all buttons
                siblings.each(function(){
                    $(this).removeClass('active');
                }) */

                //Add the clicked button class
                tab.addClass('watched');
            }

            //Attach events to highlight-watched
            $(document).ready(function () {

                if (localStorage) {
                    var ind = localStorage['tab']
                    make_button_active($('.highlight-watched li').eq(ind));
                }

                $(".highlight-watched li").click(function () {
                    if (localStorage) {
                        localStorage['tab'] = $(this).index();
                    }
                    make_button_active($(this));
                });

            });
        </script>

    <!-- javaScripts
    ================================================== -->
    <script src="js/framework.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/simplebar.js"></script>
    <script src="js/main.js"></script>
    <script src="js/bootstrap-select.min.js"></script>


</body>

</html>