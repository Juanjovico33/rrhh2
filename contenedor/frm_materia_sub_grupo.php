<?php 
    include "../includes/_linkMeet.php";
    include "../includes/_grupo.php";
    include "../includes/conexion.php";
    include "../includes/_event_log.php";
    function nb_dia($dia){
    switch ($dia) {
      case '1':
        $nb_dia="Lunes";
        break;
      case '2':
        $nb_dia="Martes";
        break;
      case '3':
        $nb_dia="Miercoles";
        break;
      case '4':
        $nb_dia="Jueves";
        break;
      case '5':
        $nb_dia="Viernes";
        break;
      case '6':
        $nb_dia="Sabado";
        break;
      case '7':
        $nb_dia="Domingo";
        break;
      default:
        $nb_dia="S/A";
        break;
    }
    return $nb_dia;
  }
    $id_grupo=$_REQUEST['_idgrupo']; 
    $codmat=$_REQUEST['_codmat'];
    $per=$_REQUEST['_periodo'];
    $codest=$_REQUEST['_codest'];
    $nb_materia=$_REQUEST['_nbmat'];
    $sub_grupo=$_REQUEST['_subgrupo'];
    $sub_docente=$_REQUEST['_doc'];
    $subgru=$_REQUEST['_subgru']; // nb_subgrupo
    $grupo=new grupo();
    $grupo_aux=new grupo();
    $e = new evento();
    $e->setIdGrupo($id_grupo);
    $e->setIdSubGrupo($sub_grupo);
    $e->e_log_inicio_evento($codest, 18);
    $grupo->getDatosGrupo($id_grupo);
    $_idgrupoRaiz=0;
    //verificar evaluacion de encuesta
    $cod_encu=4;
    $qev=$bdcon->prepare("SELECT cod FROM plat_est_encu_respuestas where codest='$codest' and codgrupo='$id_grupo' and cod_encu='$cod_encu' and codsubgru='$sub_grupo'");
    $qev->execute();
    $cod_er=0;
    while ($rqev = $qev->fetch(PDO::FETCH_ASSOC)) {
        $cod_er=$rqev['cod']; 
    }
    $cod_er=1;
    if ($cod_er>0) {
    //ya tiene evaluacion
    }else{
    //no tiene evaluacion, obligarlo a hacer 
    ?>
    <div uk-grid>
      <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content" id="resultado">
              <h2>ENCUESTA DE SATISFACCIÓN ESTUDIANTIL DE CLASES PRÁCTICAS - GESTIÓN 1-2022</h2>
              <hr>
              <form name="encues" method="post" id="frm_encuesta_prac" onsubmit="guardar_encuesta_prac()">
              <input type="hidden" name="codest" value="<?php echo $codest; ?>">
              <input type="hidden" name="cod_encu" value="<?php echo $cod_encu; ?>">
              <input type="hidden" name="codgru" value="<?php echo $id_grupo; ?>">
              <input type="hidden" name="codsubgru" value="<?php echo $sub_grupo; ?>">
              <p class="uk-text-emphasis uk-text-lead uk-text-justify">
                Estimado estudiante:<br><br>
                Te presentamos la siguiente Encuesta, con la finalidad de conocer tu opinión respecto al desarrollo de las clases prácticas, las Guías de prácticas y los servicios que te brinda la Plataforma estudiantil UNE.  Necesitamos que respondas con toda sinceridad, porque tus respuestas se manejarán con confidencialidad y nos ayudarán a tomar decisiones para seguir mejorando. <br><br>
                Cada una de las preguntas, debe ser respondida con una de las opciones que se plantean.<br>Al terminar la Encuesta, encontrarás un espacio para escribir alguna(s) sugerencia(s) (no es obligatorio hacerlo). Finalmente, debes guardar el documento.<br><br>Agradecemos tu participación.<br>
              </p>
              <table class="table table-sm">
                <!--tr>
                  <th colspan="3" class="uk-text-emphasis">RESPECTO LAS CLASES VIRTUALES:</th>
                </tr-->
              <?php
              $qpreg=$bdcon->prepare("SELECT cod, pregunta, tipo_respuesta, num_preg FROM plat_est_preguntas where cod_encu='$cod_encu' and estado='1'");
              $qpreg->execute();
              $resp=$bdcon->prepare("SELECT cod, cod_preg, respuesta FROM plat_est_preguntas_respuestas WHERE estado='1'");
              $resp->execute();
              while ($fresp=$resp->fetch(PDO::FETCH_ASSOC)) {
                $cod=$fresp['cod_preg'];
                $respu=$fresp['respuesta'];
                $cod_resp=$fresp['cod'];
                $respuestas[]=$cod."|".$respu."|".$cod_resp;
              }
              //print_r($respuestas);
              $nume=1;
              $part[]="";
              while ($fpreg=$qpreg->fetch(PDO::FETCH_ASSOC)) {
                $codpreg=$fpreg['cod'];
                $preg=$fpreg['pregunta'];
                $t_res=$fpreg['tipo_respuesta'];
                $num_preg=$fpreg['num_preg'];
                ?>
                <tr>
                  <th><?php echo $nume; ?></th>
                  <th colspan="2"><?php echo $preg; ?><input type="hidden" name="codpreg[]" value="<?php echo $codpreg; ?>"></th>
                </tr>
                <?php
                if ($t_res=='1') {
                  ?>
                  <tr>
                    <td></td>
                      <?php 
                      foreach ($respuestas as $key => $value) {
                        $part=explode("|", $value);
                        if ($part[0]==$codpreg) {
                          ?>
                          <td><?php //echo $part[2]; ?>
                            <div align="center">
                              <input type="radio" name="<?php echo $nume; ?>" value="<?php echo $part[1]; ?>"><?php echo $part[1]; ?>
                            </div>
                          </td>
                          <?php
                        }
                      }
                      ?>
                  </tr>
                  <?php
                }else{
                  ?>
                  <tr>
                    <td></td>
                    <td colspan="2"><input class="uk-input" type="text" name="<?php echo $nume; ?>" class="form-control" autocomplete="off"></td>
                  </tr>
                  <?php
                }
                /*if ($nume==12) {
                  ?>
                  <tr>
                    <th colspan="3">RESPECTO LA PLATAFORMA ESTUDIANTIL:</th>
                  </tr>
                  <?php
                }*/
                $nume++;
              }
              ?>
              </table>
              <button class="btn btn-success">GUARDAR ENCUESTA</button>
              </form>
          </div>
        </div>
      </div>
    </div>
    <?php
    exit();
    }
?>
<div class="page-content">
    <div class="container">
        <div align="center">
            <h1 style="color: #0E8CB4;"> <?php echo $nb_materia." <br> SUB-GRUPO ". $subgru;?><br></h1>
            <div class="alert alert-success"><strong>HORARIO</strong><br>
                <?php
                    $q_horario= $bdcon->prepare("SELECT  dia, hr_entrada, hr_salida FROM grupos_horario  where cod_subgru='$sub_grupo'");
                    $q_horario->execute();
                    while ($rhora = $q_horario->fetch(PDO::FETCH_ASSOC)) { 
                      $dia=$rhora['dia'];
                      $hr_entrada=$rhora['hr_entrada'];  
                      $hr_salida=$rhora['hr_salida'];                                         
                      echo nb_dia($dia)." (".$hr_entrada." - ".$hr_salida .") <br>" ;
                    }
                ?>                
            </div>

        </div>                
        <div class="section-header mb-4">
            <div class="section-header-left">
                <nav class="responsive-tab style-4">
                    <ul>
                        <li class="uk-active"><a href="#">Opciones del sub-grupo</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="uk-grid-large" uk-grid>
            <div class="uk-width-3-4@m">
            <!-- INSERTAR POR AQUI EL ENLACE MEET -->
            <?php
                $enlacemeet="";
                $query_meet= $bdcon->prepare("SELECT url from plat_doc_meet_practica where idgrupo='$id_grupo'");
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
                <div id="div_googlemeet_link" class="course-card course-card-list">
                    <div class="course-card-thumbnail">                                
                        <a href="<?php echo $enlacemeet;?>" onclick="registrar_asistencias_meet_sub('<?=$id_grupo;?>','<?=$codest;?>','<?=$sub_grupo;?>')" target="_blank"><img src="img/iconos/googlemeet.png"></a>
                    </div>
                    <div class="course-card-body">
                        <a href="<?php echo $enlacemeet;?>" onclick="registrar_asistencias_meet_sub('<?=$id_grupo;?>','<?=$codest;?>','<?=$sub_grupo;?>')" target="_blank">
                            <h4> Enlace clase virtual práctica</h4>
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
                        <a href="#" class="play-button-trigger" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>, <?=$sub_grupo?>)"></a>                        
                    </div>
                    <div class="course-card-body" >
                        <a href="#" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>, <?=$sub_grupo?>)">
                            <h4>Recursos didacticos práctica</h4>
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
                        <img src="img/iconos/pdf.jpg">
                        <a href="#" class="play-button-trigger" onclick="ver_guia(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>)"></a>                      
                    </div>
                    <div class="course-card-body" >
                        <a href="#" onclick="ver_guia(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>)">
                            <h4>Guía Práctica</h4>
                        </a>
                        <p>Guía práctica, es un material de apoyo para las prácticas virtuales o presenciales</p>
                        <div class="course-details-info">
                            <ul>
                                <li> Registrado por <a href="#"> Jefatura de Carrera </a> </li>
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
                        <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$sub_grupo;?>)" class="play-button-trigger"></a>
                    </div>
                    <div class="course-card-body">
                     <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$sub_grupo;?>)">
                       
                            <h4> Planificacion academica práctica</h4>
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
                        <a href="#" onclick="abrir_cerrar_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>','<?=$sub_grupo;?>')" class="play-button-trigger"></a>
                    </div>
                    <div class="course-card-body">
                        <a href="#" onclick="abrir_cerrar_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>','<?=$sub_grupo;?>')">
                            <h4> Boletin de Notas practica detalle</h4>
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