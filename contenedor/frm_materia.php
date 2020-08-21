<?php 
    include "../includes/_linkMeet.php";
    include "../includes/_grupo.php";
    $id_grupo=$_REQUEST['_idgrupo']; 
    $codmat=$_REQUEST['_codmat'];
    $per=$_REQUEST['_periodo'];
    $codest=$_REQUEST['_codest'];
    $nb_materia=$_REQUEST['_nbmat'];

    $o_enl=new linkMeet();
    $grupo=new grupo();
    $grupo_aux=new grupo();

    $grupo->getDatosGrupo($id_grupo);
    $_idgrupoRaiz=0;

    if($grupo->es_rama()){
        $_idgrupoRaiz=$grupo->getIdramaRaiz();
        //  echo "Es rama ".$_idgrupoRaiz.'<br>';
        $o_enl->getDatosEnlace($_idgrupoRaiz);
    }else if($grupo->esNivelacion()){
        $_idgrupoRaiz=$grupo->nivGetIdgrupoMain();
        if($_idgrupoRaiz==0){
            $_idgrupoRaiz=$grupo->getIdgrupo_otraMateriaMalla();
            if($_idgrupoRaiz!=0){
                $grupo_aux->getDatosGrupo($_idgrupoRaiz);
                if($grupo_aux->es_rama()){
                    $_idgrupoRaiz=$grupo_aux->getIdramaRaiz();
                }
                $o_enl->getDatosEnlace($_idgrupoRaiz);        
            }else{
                $_idgrupoRaiz=0;
                echo "El grupo no esta abierto.<br>";
            }
        }else{
            //   echo "Es nivelacion -".$_idgrupoRaiz."<br>";
            $grupo_aux->getDatosGrupo($_idgrupoRaiz);
            if($grupo_aux->es_rama()){
                $_idgrupoRaiz=$grupo_aux->getIdramaRaiz();
            }
             $o_enl->getDatosEnlace($_idgrupoRaiz);
        }
    }else{
        //echo "NO es rama ".$id_grupo."<br>";//echo "No es nivelación<br>";   //Si no es fusionada y no es nivelación entonces obtener enlace
        $_idgrupoRaiz=0;
        $o_enl->getDatosEnlace($id_grupo);
    }
    // echo $grupo->getError();
    // echo $o_enl->getError();
    // echo "<br>".$o_enl->getEnlace();
    // exit;
?>
<div class="page-content">

            <div class="container">

                <h1> MATERIA : <?php echo $nb_materia; ?> </h1>

                <div class="section-header mb-4">
                    <div class="section-header-left">
                        <nav class="responsive-tab style-4">
                            <ul>
                                <li class="uk-active"><a href="#">Opciones de la materia</a></li>
                                <!-- <li><a href="#">Nuevas</a></li> -->
                            </ul>
                        </nav>
                    </div>
                    <!-- <div class="section-header-right">

                        <div class="display-as">
                            <a href="courses.html" uk-tooltip="title: Course list; pos: top-right">
                                <i class="icon-feather-list"></i></a>
                            <a href="#" class="active" uk-tooltip="title: Course Grid; pos: top-right">
                                <i class="icon-feather-grid"></i></a>
                        </div>

                        <select class="selectpicker ml-3">
                            <option value=""> Newest </option>
                            <option value="1">Popular</option>
                            <option value="2">Free</option>
                            <option value="3">Premium</option>
                        </select>

                    </div> -->
                </div>

                <div class="uk-grid-large" uk-grid>
                    <div class="uk-width-3-4@m">
                    <!-- INSERTAR POR AQUI EL ENLACE MEET -->
                    <?php
                    if($o_enl->getEnlace()!=''){
                        ?>
                        <div id="div_googlemeet_link" class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                
                                <a href="<?=$o_enl->getEnlace();?>" onclick="registrar_asistencias_meet(<?=$id_grupo;?>,<?=$codest;?>)" target="_blank"><img src="img/iconos/googlemeet.png"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="<?=$o_enl->getEnlace();?>" onclick="registrar_asistencias_meet(<?=$id_grupo;?>,<?=$codest;?>)" target="_blank">
                                    <h4> Enlace clase virtual</h4>
                                </a>
                                <p> Este enlace nos redirecciona al Google Meet, debemos tener abierta nuestra cuenta de correo institucional.</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <?php 
                    }
                    ?>
                    

                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/recursos.png">
                                <a href="#" class="play-button-trigger" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>)"></a>
                            </div>

                            <div class="course-card-body" >
                                <a href="#" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>)">
                                    <h4>Recursos didacticos </h4>
                                </a>
                                <p>Todos los recursos didacticos, material de apoyo, clases virtuales grabadas en la plataforma de Google Meet!</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/planificacion.png">
                                <a href="#" onclick="ver_plamat(<?=$id_grupo;?>)" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="#" onclick="ver_plamat(<?=$id_grupo;?>)">
                                    <h4> Planificacion academica </h4>
                                </a>
                                <p>Visualizacion de la planificacion academica, cargada por el docente.</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/boletin.png">
                                <a href="#" onclick="abrir_cerrar('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>')" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="#" onclick="abrir_cerrar('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>')">
                                    <h4> Boletin de Notas</h4>
                                </a>
                                <p>Ver las notas obtenidas en detalle por parcial.</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <!--div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="../assets/images/course/7.png">
                                <a href="course-intro.html" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="course-intro.html">
                                    <h4> Bootstrap 4 From Scratch With 5 Real Projects </h4>
                                </a>
                                <p> Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt
                                    ut labore et dolore magna aliqua. enim ad minim veniam,</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> <i class="icon-feather-sliders"></i> Binginner level </li>
                                        <li> By <a href="user-profile-1.html">Jonathan Madano </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 4.4 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star half"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="../assets/images/course/6.png">
                                <a href="course-intro.html" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="course-intro.html">
                                    <h4> The Complete 2020 Web Development Bootcamp</h4>
                                </a>
                                <p> HTML is the building blocks of the web. It gives pages structure and applies meaning
                                    to
                                    content. Take this course to learn how it all works and create your own pages!</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> <i class="icon-feather-sliders"></i> Binginner level </li>
                                        <li> By <a href="#"> Stella Johnson </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 4.9 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star half"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div-->




                        <!-- pagination menu -->
                        <!-- <ul class="uk-pagination my-5 uk-flex-center" uk-margin>
                            <li><a href="#"><span uk-pagination-previous></span></a></li>
                            <li class="uk-disabled"><span>...</span></li>
                            <li><a href="#">4</a></li>
                            <li class="uk-active"><span>7</span></li>
                            <li><a href="#">8</a></li>
                            <li><a href="#">10</a></li>
                            <li class="uk-disabled"><span>...</span></li>
                            <li><a href="#"><span uk-pagination-next></span></a></li>
                        </ul>
 -->


                    </div>
                    <!-- <div class="uk-width-expand">
                        <button class="btn-sidebar-filter"
                            uk-toggle="target: .sidebar-filter; cls: sidebar-filter-visible">Filter </button>
                        <div class="sidebar-filter" uk-sticky="offset:30 ; media : @s: bottom: true"> -->

                            <!--<div class="sidebar-filter-contents">


                                <h4> Filter By </h4>

                                <ul class="sidebar-filter-list uk-accordion" uk-accordion="multiple: true">

                                    <li class="uk-open">
                                        <a class="uk-accordion-title" href="#"> Skill Levels </a>
                                        <div class="uk-accordion-content" aria-hidden="false">
                                            <div class="uk-form-controls">
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio1">
                                                    <span class="test"> Beginner <span> (25) </span> </span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio1">
                                                    <span class="test"> Entermidate<span> (32) </span></span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio1">
                                                    <span class="test"> Expert <span> (12) </span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="uk-open">
                                        <a class="uk-accordion-title" href="#"> Course type </a>
                                        <div class="uk-accordion-content" aria-hidden="false">
                                            <div class="uk-form-controls">
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio2">
                                                    <span class="test"> Free (42) </span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio2">
                                                    <span class="test"> Paid </span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="uk-open">
                                        <a class="uk-accordion-title" href="#"> Duration time </a>
                                        <div class="uk-accordion-content" aria-hidden="false">
                                            <div class="uk-form-controls">
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio3">
                                                    <span class="test"> +5 Hourse (23) </span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio3">
                                                    <span class="test"> +10 Hourse (12)</span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio3">
                                                    <span class="test"> +20 Hourse (5)</span>
                                                </label>
                                                <label>
                                                    <input class="uk-radio" type="radio" name="radio3">
                                                    <span class="test"> +30 Hourse (2)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>


                                </ul>



                            </div>-->

                        <!-- </div>

                    </div> -->
                </div>

                <!-- footer
               ================================================== -->
                <div class="footer">
                    <div class="uk-grid-collapse" uk-grid>
                        <div class="uk-width-expand@s uk-first-column">
                            <p>© 2020 <strong>Universidad Nacional Ecologica</strong>. All Rights Reserved. </p>
                        </div>
                        <div class="uk-width-auto@s">
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

        </div>