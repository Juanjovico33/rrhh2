<?php
    // require '../js/kint.phar';

    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";
    include "../includes/_practicas.php";
    include "../includes/_regExcepciones.php";
    include "../includes/_eval_diagnostica.php";
    include "../includes/conexion.php";
    include "../includes/_event_log.php";

    $codest = $_GET['_codest'];
    $carr = $_GET['_carrera'];
    $excepciones = new reg_excepciones();
    $eval_diag=new eval_diagnostica();
    $estudiante =  new estudiante($codest);    
    $gestion = new gestion();     // PRINCIPAL - PERIODO ACTUAL
    $gestion->getgestionactual(); // PRINCIPAL - PERIODO ACTUAL
    $estudiante->getdatosest($gestion->getGestion());
    $materias=null; // PRINCIPAL - PERIODO ACTUAL

    $materias2023=null; // 2023
    $gestion2023 = new gestion();
    $gestion2023->getgestion_id(34);

    $e = new evento();
    $e->e_log_inicio_evento($codest, 3);
    // echo $e->getError(); exit;
    
    $materias=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion->getId(), $gestion->getperiodoactuales()), $gestion->getId()); // PRINCIPAL - PERIODO ACTUAL
    $materias2023=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion2023->getId(), $gestion2023->getperiodoactuales()), $gestion2023->getId()); // 2023

    $ex_periodos=$excepciones->getRegExcepcionEst_periodos($codest);
    $cantidad_excepciones=0;
    if(isset($ex_periodos)){
        $cantidad_excepciones=count($ex_periodos);
    }

    // echo $estudiante->getError().'<br>';
    // var_dump($materias);
    $periodo='202202';
    $carrera=$estudiante->getCodCarrera();
    $sem=$estudiante->getSemestreEst();

    ?>
    <h1>Materias Registradas</h1>

    <h4> Elija una materia, para ver los materiales, registro de asistencia, planificacion academica y notas </h4>
        <?php 
        //==============================================DEVELOPING_INI
        
        // ------------IF_INICIO --- NORMAL Y ACTUAL
        if (!is_null($materias)) {
            //aqui arranca la autoevaluacion
            $cod_eval=$eval_diag->obt_cod_actividad($periodo,$carrera,$sem);
            if ($cod_eval=='0') {
                //no hay actividad seguir con lo normal
            }else{
                //verificamos si ya hizo la evaluacion diagnostica
                $cod_banco=$eval_diag->getCodBanco();
                $verif=$eval_diag->verif_realizada($cod_eval,$codest);

                if ($verif>0) {
                    // el estudiante ya tiene evaluacion y no hacemos nada mas que msotrar las materias registradas
                }else{
                    //no hizo la evlauacion diagnostica, mostrar el examen.
                   // echo "no hizo la evlauacion diagnostica, mostrar el examen. ";
                    $q_p = $eval_diag->cons_diagnos($cod_banco);
                    $q_p->execute(); 
                    ?>
                    <div class="card" id="contenido_cv">
                        <form method="post" onsubmit="guardar_diagnostico()" id="frm_diagnostico">
                            <input type="hidden" name="codest" value="<?=$codest?>">
                            <input type="hidden" name="codban" value="<?=$cod_banco?>">
                            <input type="hidden" name="cod_eval" value="<?=$cod_eval?>">
                            <input type="hidden" name="carrera" value="<?=$carrera?>">
                            <input type="hidden" name="semestre" value="<?=$sem?>">
                            <div align="center"><h1>EVALUACIÓN DIAGNÓSTICA</h1></div>
                            <table class="table table-condensed">
                                <tr>
                                    <th>N°</th>
                                    <th>PREGUNTA</th>
                                </tr>
                                <?php 
                                $cont=1;
                                $nb_uov="";
                                while ($f_p = $q_p->fetch(PDO::FETCH_ASSOC)) {
                                    $idp=$f_p['id'];
                                    $tipo=$f_p['tipo'];
                                    $uov=$f_p['uov'];
                                    $img=$f_p['imagen'];
                                    switch ($tipo) {
                                        case '1':
                                            $nb_tipo="Opción Multiple";
                                            break;
                                        case '2':
                                            $nb_tipo="Respuesta Corta";
                                            break;
                                        case '3':
                                            $nb_tipo="Verdadero / Falso";
                                            break;
                                        case '4':
                                            $nb_tipo="Descripción";
                                            break;
                                        default:
                                            $nb_tipo="N/A";
                                            break;
                                    }
                                    if ($uov=='1') {
                                        //solo una respuesta radio
                                        $nb_uov="(Una sola válida)";
                                    }else{
                                        if ($uov=='2') {
                                            //varias respuestas check
                                            $nb_uov="(Varias válidas)"; 
                                        }else{
                                            $nb_uov="";
                                        }
                                    }
                                    ?>
                                    <tr class="active">
                                        <th><?php echo $cont.".-"; ?><input type="hidden" name="preg[]" value="<?php echo $idp; ?>"></th>
                                        <th>
                                            <?php 
                                            echo $f_p['pregunta']; 
                                            if ($img!='') {
                                                ?>
                                                <img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $img; ?>">
                                                <?php
                                            }
                                            ?>              
                                        </th>
                                    </tr>
                                    <?php
                                    $q_r = $bdcon->prepare("SELECT id, eleccion, calif, imagen FROM plat_doc_banco_resp WHERE id_preg='$idp'");
                                    $q_r->execute();
                                    $fil=$q_r->rowCount();
                                    $fil=$fil*2;
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <?php 
                                            if ($tipo=='1') {
                                                //opcion multiple
                                                if ($uov=='1') {
                                                    //solo una respuesta radio
                                                    $type="radio";
                                                    $func="";
                                                }else{
                                                    //varias respuestas check
                                                    $type="checkbox";
                                                    $func="onclick='verificar_uno(".$idp.")'";
                                                }
                                                ?>
                                                <table class="table table-condensed">
                                                    <tr>
                                                        <?php
                                                        while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
                                                            $idr=$fr['id'];
                                                            $cal=$fr['calif'];
                                                            $imgr=$fr['imagen'];
                                                            $conten=$cal."|".$tipo."|".$idr;
                                                            if ($uov=='1') {
                                                                //solo una respuesta radio
                                                                $type="radio";
                                                                $func="";
                                                            }else{
                                                                //varias respuestas check
                                                                $type="checkbox";
                                                                $func="onclick='verificar_uno(".$idp.",".$idr.")'";
                                                            }
                                                            ?>
                                                            <td>
                                                                <input class="checkbox-success" type="<?=$type?>" id="<?=$idr?>" name="<?=$idp?>[]" value="<?=$conten?>" <?=$func?> onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/>
                                                                <label class="form-check-label" for="<?php echo $conten;?>"><?php echo $fr['eleccion'];?><?php 
                                                                //echo $fr['eleccion']; 
                                                                if ($imgr!='') {
                                                                    ?>
                                                                    <img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $imgr; ?>">
                                                                    <?php
                                                                }
                                                                ?></label>
                                                            </td>                                               
                                                            <?php
                                                                }                       
                                                            ?>
                                                    </tr>                           
                                                </table>
                                                <?php
                                                $nb_uov="";
                                            }else{
                                                if ($tipo=='2') {
                                                    //respuesta corta
                                                    ?>
                                                    <table class="table table-condensed">
                                                        <tr>
                                                            <td><input type="text" name="<?php echo $idp; ?>[]" class="form-control" value=""></td>
                                                        </tr>
                                                    </table>
                                                    <?php
                                                }else{
                                                    if ($tipo=='3') {
                                                        //Verdadero Falso
                                                        ?>
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <?php
                                                                while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
                                                                //while ($fr=mysql_fetch_array($q_r)) {
                                                                    $idre=$fr['id'];
                                                                    $val=$fr['calif'];
                                                                    $conten=$val."|".$tipo."|".$idre;
                                                                    ?>
                                                                    <td><input type="radio" name="<?=$idp?>[]" value="<?=$conten?>" onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/></td>
                                                                    <td><?php echo $fr['eleccion']; ?></td>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tr>
                                                        </table>
                                                        <?php
                                                    }else{
                                                        if ($tipo=='4') {
                                                            //descripcion
                                                            ?>
                                                            <table class="table table-condensed">
                                                                <tr>
                                                                    <th colspan="<?php echo $fil; ?>">RESPUESTA</th>
                                                                </tr>
                                                                <tr>
                                                                    <td><textarea name="<?php echo $idp; ?>[]" class="form-control" rows="3"></textarea></td>
                                                                </tr>
                                                            </table>
                                                            <?php
                                                        }else{
                                                            //no mostrar respuestas
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $cont++;
                                    $nb_uov="";
                                    $uov="";
                                }
                                ?>
                                </table>
                                <div align="center"><button type="submit" id="btn_guardar_eval" class="btn btn-success md-4">GUARDAR EVALUACION</button></div>
                            </form>
                        </div>
                        <?php
                    exit();
                }
            }
            
            //termina la autoevaluacion                         
            for($j=0;$j<count($materias);$j++){                
                ?>
                <?php 
                    $per=$materias[$j]['idperiodo'];
                    $nb_periodo=$materias[$j]['nbperiodo'];
                    ?>     
                <h1><?=$nb_periodo?></h1>    
                <div class="panel-body">
                            <div class="section-small">
                                <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                            <?php              
                                if(!is_null($materias[$j]['materias_array'])){
                                    $limite_mat=count($materias[$j]['materias_array']);
                                }else{
                                    $limite_mat=0;
                                }
                                // print_r($materias[$j]['materias_array']);
                                for($i=0;$i<$limite_mat;$i++){
                                    $id_grupo=$materias[$j]['materias_array'][$i]['idgrupo'];
                                    ?>
                                    <!--tr align="center">
                                        <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                        <td class="text-muted small"><?php echo $codmat=$materias[$j]['materias_array'][$i]['codmateria'];?></td>
                                        <td class="text-muted small"><?php echo $n_mat=$materias[$j]['materias_array'][$i]['nb_materia'];?></td>
                                        <td class="text-muted small"><?php echo $materias[$j]['materias_array'][$i]['grupo'];?></td>
                                        <td class="text-muted small">
                                            <?php  $id_grupo=$materias[$j]['materias_array'][$i]['idgrupo'];?>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                                <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                                </svg>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                            <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                            <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                            </svg>
                                        </button>
                                        </td>                                    
                                    </tr-->
                                    <div>
                                        <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>', '<?=$carr;?>')">
                                            <div class="course-card">
                                                <div class="course-card-thumbnail ">
                                                    <img src="img/materias/<?php echo $i+1; ?>.png">
                                                    <span class="play-button-trigger"></span>
                                                </div>
                                                <div class="course-card-body">
                                                    <div class="course-card-info">
                                                        <div>
                                                            <span class="catagroy"><?php echo $codmat=$materias[$j]['materias_array'][$i]['codmateria'];?></span>
                                                        </div>
                                                        <div>
                                                            <i class="icon-feather-bookmark icon-small"></i>
                                                        </div>
                                                    </div>
                                                    <h4><?php echo $n_mat=$materias[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                                    <P><?= 'GRUPO - '.$materias[$j]['materias_array'][$i]['grupo'];?> </P>
                                                    <p> <?php echo $materias[$j]['nbperiodo'];?></p>
                                                    <div class="course-card-footer">
                                                        <h5> <i class="icon-feather-film"></i> Hrs Pr. </h5>
                                                        <h5> <i class="icon-feather-clock"></i> Hrs Te. </h5>
                                                    </div>
                                                </div>

                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            ?>
                                </div>
                            </div>
                        <br>
                </div>
                <?php 
            }
        }else {
            echo "<div class='alert alert-warning alert-danger'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>¡Error! </strong> No tiene materias registradas para el periodo actual</div>";
        }
        // ------------IF_FIN --- NORMAL Y ACTUAL

            // -------DEV_test---echo $estudiante->getSemestre();
            // -------DEV_test---echo $estudiante->getCodCarrera()
            // echo $cantidad_excepciones."<br>";
            // exit;
            
        for($t=0;$t<$cantidad_excepciones;$t++){
            $t_periodo = $ex_periodos[$t]["periodo"];
            $t_idgestion = $ex_periodos[$t]["idgestion"];
            // echo $t_periodo."<br>";
           

            $otras_materias = $estudiante->getmateriaspor_UnSolo_periodo($t_periodo, $t_idgestion);

            if (!is_null($otras_materias)) {
                # code...                            
                for($j=0;$j<count($otras_materias);$j++){                
                    ?>
                    <?php 
                        $per=$otras_materias[$j]['idperiodo'];
                        $nb_periodo=$otras_materias[$j]['nbperiodo'];
                        ?>     
                    <h1><?=$nb_periodo?></h1>    
                    <div class="panel-body">
                                <div class="section-small">
                                    <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                                <?php              
                                    if(!is_null($otras_materias[$j]['materias_array'])){
                                        $limite_mat=count($otras_materias[$j]['materias_array']);
                                    }else{
                                        $limite_mat=0;
                                    }
                                    // print_r($materias[$j]['materias_array']);
                                    for($i=0;$i<$limite_mat;$i++){
                                        $id_grupo=$otras_materias[$j]['materias_array'][$i]['idgrupo'];
                                        ?>
                                        <!--tr align="center">
                                            <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                            <td class="text-muted small"><?php echo $codmat=$otras_materias[$j]['materias_array'][$i]['codmateria'];?></td>
                                            <td class="text-muted small"><?php echo $n_mat=$otras_materias[$j]['materias_array'][$i]['nb_materia'];?></td>
                                            <td class="text-muted small"><?php echo $otras_materias[$j]['materias_array'][$i]['grupo'];?></td>
                                            <td class="text-muted small">
                                                <?php  $id_grupo=$otras_materias[$j]['materias_array'][$i]['idgrupo'];?>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                    <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                                    <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                                <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                                </svg>
                                            </button>
                                            </td>                                    
                                        </tr-->
                                        <div>
                                            <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>', '<?=$carr;?>')">
                                                <div class="course-card">
                                                    <div class="course-card-thumbnail ">
                                                        <img src="img/materias/<?php echo $i+1; ?>.png">
                                                        <span class="play-button-trigger"></span>
                                                    </div>
                                                    <div class="course-card-body">
                                                        <div class="course-card-info">
                                                            <div>
                                                                <span class="catagroy"><?php echo $codmat=$otras_materias[$j]['materias_array'][$i]['codmateria'];?></span>
                                                            </div>
                                                            <div>
                                                                <i class="icon-feather-bookmark icon-small"></i>
                                                            </div>
                                                        </div>
                                                        <h4><?php echo $n_mat=$otras_materias[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                                        <P><?= 'GRUPO - '.$otras_materias[$j]['materias_array'][$i]['grupo'];?> </P>
                                                        <p> <?php echo $otras_materias[$j]['nbperiodo'];?></p>
                                                        <div class="course-card-footer">
                                                            <h5> <i class="icon-feather-film"></i> Hrs Pr. </h5>
                                                            <h5> <i class="icon-feather-clock"></i> Hrs Te. </h5>
                                                        </div>
                                                    </div>

                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                ?>
                                    </div>
                                </div>
                            <br>
                    </div>
                    <?php 
                }
            }
        }
        

    //==============================================DEVELOPING_FIN



    //---------------------------------------------Aqui comienza el if N
    if (!is_null($materias2023)) {
        //aqui arranca la autoevaluacion
        $cod_eval=$eval_diag->obt_cod_actividad($periodo,$carrera,$sem);
        if ($cod_eval=='0') {
            //no hay actividad seguir con lo normal
        }else{
            //verificamos si ya hizo la evaluacion diagnostica
            $cod_banco=$eval_diag->getCodBanco();
            $verif=$eval_diag->verif_realizada($cod_eval,$codest);

            if ($verif>0) {
                // el estudiante ya tiene evaluacion y no hacemos nada mas que msotrar las materias registradas
            }else{
                //no hizo la evlauacion diagnostica, mostrar el examen.
               // echo "no hizo la evlauacion diagnostica, mostrar el examen. ";
                $q_p = $eval_diag->cons_diagnos($cod_banco);
                $q_p->execute(); 
                ?>
                <div class="card" id="contenido_cv">
                    <form method="post" onsubmit="guardar_diagnostico()" id="frm_diagnostico">
                        <input type="hidden" name="codest" value="<?=$codest?>">
                        <input type="hidden" name="codban" value="<?=$cod_banco?>">
                        <input type="hidden" name="cod_eval" value="<?=$cod_eval?>">
                        <input type="hidden" name="carrera" value="<?=$carrera?>">
                        <input type="hidden" name="semestre" value="<?=$sem?>">
                        <div align="center"><h1>EVALUACIÓN DIAGNÓSTICA</h1></div>
                        <table class="table table-condensed">
                            <tr>
                                <th>N°</th>
                                <th>PREGUNTA</th>
                            </tr>
                            <?php 
                            $cont=1;
                            $nb_uov="";
                            while ($f_p = $q_p->fetch(PDO::FETCH_ASSOC)) {
                                $idp=$f_p['id'];
                                $tipo=$f_p['tipo'];
                                $uov=$f_p['uov'];
                                $img=$f_p['imagen'];
                                switch ($tipo) {
                                    case '1':
                                        $nb_tipo="Opción Multiple";
                                        break;
                                    case '2':
                                        $nb_tipo="Respuesta Corta";
                                        break;
                                    case '3':
                                        $nb_tipo="Verdadero / Falso";
                                        break;
                                    case '4':
                                        $nb_tipo="Descripción";
                                        break;
                                    default:
                                        $nb_tipo="N/A";
                                        break;
                                }
                                if ($uov=='1') {
                                    //solo una respuesta radio
                                    $nb_uov="(Una sola válida)";
                                }else{
                                    if ($uov=='2') {
                                        //varias respuestas check
                                        $nb_uov="(Varias válidas)"; 
                                    }else{
                                        $nb_uov="";
                                    }
                                }
                                ?>
                                <tr class="active">
                                    <th><?php echo $cont.".-"; ?><input type="hidden" name="preg[]" value="<?php echo $idp; ?>"></th>
                                    <th>
                                        <?php 
                                        echo $f_p['pregunta']; 
                                        if ($img!='') {
                                            ?>
                                            <img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $img; ?>">
                                            <?php
                                        }
                                        ?>              
                                    </th>
                                </tr>
                                <?php
                                $q_r = $bdcon->prepare("SELECT id, eleccion, calif, imagen FROM plat_doc_banco_resp WHERE id_preg='$idp'");
                                $q_r->execute();
                                $fil=$q_r->rowCount();
                                $fil=$fil*2;
                                ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <?php 
                                        if ($tipo=='1') {
                                            //opcion multiple
                                            if ($uov=='1') {
                                                //solo una respuesta radio
                                                $type="radio";
                                                $func="";
                                            }else{
                                                //varias respuestas check
                                                $type="checkbox";
                                                $func="onclick='verificar_uno(".$idp.")'";
                                            }
                                            ?>
                                            <table class="table table-condensed">
                                                <tr>
                                                    <?php
                                                    while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
                                                        $idr=$fr['id'];
                                                        $cal=$fr['calif'];
                                                        $imgr=$fr['imagen'];
                                                        $conten=$cal."|".$tipo."|".$idr;
                                                        if ($uov=='1') {
                                                            //solo una respuesta radio
                                                            $type="radio";
                                                            $func="";
                                                        }else{
                                                            //varias respuestas check
                                                            $type="checkbox";
                                                            $func="onclick='verificar_uno(".$idp.",".$idr.")'";
                                                        }
                                                        ?>
                                                        <td>
                                                            <input class="checkbox-success" type="<?=$type?>" id="<?=$idr?>" name="<?=$idp?>[]" value="<?=$conten?>" <?=$func?> onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/>
                                                            <label class="form-check-label" for="<?php echo $conten;?>"><?php echo $fr['eleccion'];?><?php 
                                                            //echo $fr['eleccion']; 
                                                            if ($imgr!='') {
                                                                ?>
                                                                <img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $imgr; ?>">
                                                                <?php
                                                            }
                                                            ?></label>
                                                        </td>                                               
                                                        <?php
                                                            }                       
                                                        ?>
                                                </tr>                           
                                            </table>
                                            <?php
                                            $nb_uov="";
                                        }else{
                                            if ($tipo=='2') {
                                                //respuesta corta
                                                ?>
                                                <table class="table table-condensed">
                                                    <tr>
                                                        <td><input type="text" name="<?php echo $idp; ?>[]" class="form-control" value=""></td>
                                                    </tr>
                                                </table>
                                                <?php
                                            }else{
                                                if ($tipo=='3') {
                                                    //Verdadero Falso
                                                    ?>
                                                    <table class="table table-condensed">
                                                        <tr>
                                                            <?php
                                                            while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
                                                            //while ($fr=mysql_fetch_array($q_r)) {
                                                                $idre=$fr['id'];
                                                                $val=$fr['calif'];
                                                                $conten=$val."|".$tipo."|".$idre;
                                                                ?>
                                                                <td><input type="radio" name="<?=$idp?>[]" value="<?=$conten?>" onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/></td>
                                                                <td><?php echo $fr['eleccion']; ?></td>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </table>
                                                    <?php
                                                }else{
                                                    if ($tipo=='4') {
                                                        //descripcion
                                                        ?>
                                                        <table class="table table-condensed">
                                                            <tr>
                                                                <th colspan="<?php echo $fil; ?>">RESPUESTA</th>
                                                            </tr>
                                                            <tr>
                                                                <td><textarea name="<?php echo $idp; ?>[]" class="form-control" rows="3"></textarea></td>
                                                            </tr>
                                                        </table>
                                                        <?php
                                                    }else{
                                                        //no mostrar respuestas
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $cont++;
                                $nb_uov="";
                                $uov="";
                            }
                            ?>
                            </table>
                            <div align="center"><button type="submit" id="btn_guardar_eval" class="btn btn-success md-4">GUARDAR EVALUACION</button></div>
                        </form>
                    </div>
                    <?php
                exit();
            }
        }
        
        //termina la autoevaluacion                         
        for($j=0;$j<count($materias2023);$j++){                
            ?>
            <?php 
                $per=$materias2023[$j]['idperiodo'];
                $nb_periodo=$materias2023[$j]['nbperiodo'];
                ?>     
            <h1><?=$nb_periodo?></h1>    
            <div class="panel-body">
                        <div class="section-small">
                            <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                        <?php              
                            if(!is_null($materias2023[$j]['materias_array'])){
                                $limite_mat=count($materias2023[$j]['materias_array']);
                            }else{
                                $limite_mat=0;
                            }
                            // print_r($materias[$j]['materias_array']);
                            for($i=0;$i<$limite_mat;$i++){
                                $id_grupo=$materias2023[$j]['materias_array'][$i]['idgrupo'];
                                ?>
                                <!--tr align="center">
                                    <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                    <td class="text-muted small"><?php echo $codmat=$materias2023[$j]['materias_array'][$i]['codmateria'];?></td>
                                    <td class="text-muted small"><?php echo $n_mat=$materias2023[$j]['materias_array'][$i]['nb_materia'];?></td>
                                    <td class="text-muted small"><?php echo $materias2023[$j]['materias_array'][$i]['grupo'];?></td>
                                    <td class="text-muted small">
                                        <?php  $id_grupo=$materias2023[$j]['materias_array'][$i]['idgrupo'];?>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                            <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                            <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                        <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                        <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                        </svg>
                                    </button>
                                    </td>                                    
                                </tr-->
                                <div>
                                    <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>', '<?=$carr;?>')">
                                        <div class="course-card">
                                            <div class="course-card-thumbnail ">
                                                <img src="img/materias/<?php echo $i+1; ?>.png">
                                                <span class="play-button-trigger"></span>
                                            </div>
                                            <div class="course-card-body">
                                                <div class="course-card-info">
                                                    <div>
                                                        <span class="catagroy"><?php echo $codmat=$materias2023[$j]['materias_array'][$i]['codmateria'];?></span>
                                                    </div>
                                                    <div>
                                                        <i class="icon-feather-bookmark icon-small"></i>
                                                    </div>
                                                </div>
                                                <h4><?php echo $n_mat=$materias2023[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                                <P><?= 'GRUPO - '.$materias2023[$j]['materias_array'][$i]['grupo'];?> </P>
                                                <p> <?php echo $materias2023[$j]['nbperiodo'];?></p>
                                                <div class="course-card-footer">
                                                    <h5> <i class="icon-feather-film"></i> Hrs Pr. </h5>
                                                    <h5> <i class="icon-feather-clock"></i> Hrs Te. </h5>
                                                </div>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        ?>
                            </div>
                        </div>
                    <br>
            </div>
            <?php 
        }
    }
?>