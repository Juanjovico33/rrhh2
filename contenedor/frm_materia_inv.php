<?php 
    include "../includes/_linkMeet.php";
    include "../includes/_grupo.php";
    include "../includes/conexion.php";   
    include "../includes/_horarios.php";
    include "../includes/_event_log.php";

    $id_grupo=$_REQUEST['_idgrupo'];
    $_idgrupoRaiz=$_REQUEST['_idgrupo_raiz'];
    $codest=$_REQUEST['_codest'];
    $nb_materia=$_REQUEST['_nbmat'];
    $sub_grupo=$_REQUEST['_sub_grupo'];

    $grupo=new grupo();
    $horario= new horarios();
    $grupo->getDatosGrupo($id_grupo);
    $codmat=$grupo->getIdmateria();
    $per=$grupo->getPeriodo();
    
    $e = new evento();
    $e->setIdGrupo($id_grupo);
    $e->setIdSubGrupo($sub_grupo);
    $e->e_log_inicio_evento($codest, 18);

    if($_idgrupoRaiz!=0){
        $grupito=$_idgrupoRaiz;
    }else{
        $grupito=$id_grupo;
    }

    // echo "P $id_grupo - R $_idgrupoRaiz<br>";
?>
<div class="page-content">
    <div class="container"> 
        <!-- HORARIOS -->
        <div>
            <h1> MATERIA : <?php echo $nb_materia; ?> </h1>
            <b><?=$horario->horario_de_hoy_subgrupos($sub_grupo)."  (INVESTIGACION)"?></b>
        </div>                       
        <div class="section-header mb-4">
            <div class="section-header-left">
                <nav class="responsive-tab style-4">
                    <ul>
                        <li class="uk-active"><a href="#">OPCIONES DE INVESTIGACIÓN</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="uk-grid-large" uk-grid>
            <div class="uk-width-3-4@m">
            <!-- INSERTAR POR AQUI EL ENLACE MEET -->
            <?php
                $enlacemeet="";
                $query_meet= $bdcon->prepare("SELECT url from plat_doc_meet_practica where idgrupo='$grupito'");
                $query_meet->execute();
                while ($row = $query_meet->fetch(PDO::FETCH_ASSOC)) {
                    $enlacemeet=$row['url'];  
                    $prim_let=substr($enlacemeet, 0, 4);
                    if ($prim_let!='http') {
                        $enlacemeet="https://".$enlacemeet;
                    }                          
                }                        
            if($enlacemeet!=''){
                ?>
                <div id="div_googlemeet_link">
                    <!-- Posibles mensajes, van aqui -->
                </div>
                <div id="div_googlemeet_link" class="course-card course-card-list">
                    <div class="course-card-thumbnail">                                
                        <a href="<?php echo $enlacemeet;?>" onclick="registrar_asistencias_meet_sub('<?=$id_grupo;?>','<?=$_idgrupoRaiz;?>','<?=$codest;?>','<?=$sub_grupo;?>')" target="_blank"><img src="img/iconos/googlemeet.png"></a>
                    </div>
                    <div class="course-card-body">
                        <a href="<?php echo $enlacemeet;?>" onclick="registrar_asistencias_meet_sub('<?=$id_grupo;?>','<?=$_idgrupoRaiz;?>','<?=$codest;?>','<?=$sub_grupo;?>')" target="_blank">
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
                        <a href="#" class="play-button-trigger" onclick="ver_clasesvirtuales('<?=$id_grupo;?>', '<?=$_idgrupoRaiz?>', '<?=$codest;?>', '<?=$sub_grupo;?>')"></a>                      
                    </div>
                    <div class="course-card-body" >
                        <a href="#" onclick="ver_clasesvirtuales('<?=$id_grupo;?>', '<?=$_idgrupoRaiz?>','<?=$codest;?>', '<?=$sub_grupo;?>')">
                            <h4>Recursos didacticos</h4>
                        </a>
                        <p>Todos los recursos didacticos, material de apoyo, clases virtuales grabadas en la plataforma de Google Meet!</p>
                        <div class="course-details-info">
                            <ul>
                                <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                <li>
                                    <div class="star-rating"><span class="avg"> 5 </span> 
                                        <span class="star"></span><span class="star"></span>
                                        <span class="star"></span><span class="star"></span>
                                        <span class="star"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="course-card course-card-list">
                    <div class="course-card-thumbnail">
                        <img src="img/iconos/planificacion.png">
                        <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$id_grupo?>, <?=$sub_grupo;?>)" class="play-button-trigger"></a></a>
                    </div>
                    <div class="course-card-body">
                     <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$id_grupo?>, <?=$sub_grupo;?>)">
                       
                            <h4> Planificacion academica</h4>
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
                        <a href="#" onclick="abrir_cerrar_inv('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>')" class="play-button-trigger"></a>
                    </div>
                    <div class="course-card-body">
                        <a href="#" onclick="abrir_cerrar_inv('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>')">
                            <h4> Boletin de Notas</h4>
                        </a>
                        <p>Ver las notas obtenidas en detalle por práctica.</p>
                        <div class="course-details-info">
                            <ul>
                                <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                <li>
                                    <div class="star-rating">
                                        <span class="avg"> 5 </span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>