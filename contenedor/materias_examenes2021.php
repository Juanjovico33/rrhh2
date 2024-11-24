<?php
    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";
    include "../includes/_practicas.php";
    include "../includes/_cuentascont.php";
    include "../includes/conexion.php";
    $codest = $_GET['_codest'];
    $carr = $_GET['_carrera'];
    $estudiante =  new estudiante($codest);    
    $gestion = new gestion();
    $gestion2020= new gestion();
    $gestion->getgestionactual($carr);    
    $gestion2020->getgestion_especifica($carr, 2020);    
    $estudiante->getdatosest($gestion->getGestion());
    $codcarr=$estudiante->getCodCarrera();
    $materias=null;
    $otras_matPeriodos=null;
    $periodo2020all=null;
    $periodoII2020=null;    
    $materias=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion->getId(), $gestion->getperiodoactuales()), $gestion->getId()); 
    $otras_matPeriodos=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion2020->getId(), $gestion2020->getperiodoPersonalizados()), $gestion2020->getId());
    $periodo2020all=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion2020->getId(), $gestion2020->getperiodoactuales()), $gestion2020->getId());
    $periodoII2020=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion2020->getId(), $gestion2020->getPeriodoIINormal()), $gestion2020->getId()); 
        
    $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $fec_act=$fecha->format('Y-m-d');
    $hr_act=$fecha->format('H:i:s'); 
?>
    <div align="center">  
        <div class="alert alert-info">
            <strong>¡Atención!</strong><?php echo " Fecha y Hora Actual del Servidor: ".$fec_act." ".$hr_act;?>
        </div>                                               
        <h4> Exámenes Programados </h4> 
    </div>
        <?php 
    //==============================================DEVELOPING_INI
        //$periodos=$materias[0]['idperiodo'];
        $periodos=$periodo2020all[0]['idperiodo'];
        $q_plancontado=$bdcon->prepare("SELECT codest from all_stars where codest='$codest' and estado='1'");
        $q_plancontado->execute();
        $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest'");
        $q_plan->execute();
        $planalcontado=0;
        if($q_plancontado->rowCount()>0 || $q_plan->rowCount()>0){
        ///////**********PLAN AL CONTADO////////////*************
        $planalcontado=1;
        }else{
            $q_colegiatura= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodos' order by cod_reg Desc");
            $q_colegiatura->execute(); 
            $montocole=0;
            $descuentocole=0;
            $becacole=0;
            while ($rowcole= $q_colegiatura->fetch(PDO::FETCH_ASSOC)) {
                $tipocole=$rowcole['tipodeuda'];
                $montosss=$rowcole['monto'];
                if ($tipocole==1) {
                   $montocole=$montosss;
                }  
                if ($tipocole==656) {
                    $descuentocole=$montosss;
                }
                if ($tipocole>=20 and $tipocole<=34) {
                    $becacole=$montosss;
                }
            }
            $colepagar=$montocole-$descuentocole-$becacole;     
            $colemenos=$colepagar/4;
            $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodos' and cuenta='38'");
            $q_colepagado->execute();
            while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                $pagadocole=$rcole['pagado'];                         
            }
        } 
        // ------------IF_INICIO --- NORMAL Y ACTUAL
        if (!is_null($materias)) {
                        # code...                            
            for($j=0;$j<count($materias);$j++){                
                ?>
                <?php 
                    $per=$materias[$j]['idperiodo'];
                    $nb_periodo=$materias[$j]['nbperiodo'];
                                       
                    ?>     
                <h4><?=$nb_periodo?></h4>    
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
                                    </trver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')-->
                                    <div>                                        
                                        <div class="course-card">                                           
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
                                                <P><?= 'GRUPO - '.$grupoo=$materias[$j]['materias_array'][$i]['grupo'];?> </P>
                                                <p><?php echo $materias[$j]['nbperiodo'];?></p>
                                           
                                                    <?php
                                                ////////////verificacion de pagos para activar el boton inicio o inhabilitado/////
                                                        $fec_ini="";
                                                        $fec_fin="";
                                                        $hr_ini="";
                                                        $hr_fin=""; 
                                                        $parcial=2;
                                                        $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$codmat' and grupo='$grupoo' and carrera='$codcarr'");   
                                                        $q_crono->execute(); 
                                                        while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                                                            $fec_ini=$fcrono['fecha_programada'];
                                                            $hr_ini=$fcrono['hora'];                   
                                                        }  
                                                        $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$id_grupo' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                                                        $q_act->execute();                                                        
                                                        while ($fact= $q_act->fetch(PDO::FETCH_ASSOC)) {
                                                            $cod_act=$fact['id'];
                                                            $fec_ini=$fact['desde'];
                                                            $fec_fin=$fact['hasta'];
                                                            $hr_ini=$fact['hora_d'];
                                                            $hr_fin=$fact['hora_h'];
                                                            $id_ban=$fact['id_cat'];
                                                            $parcial=$fact['parcial'];
                                                            $obser=$fact['obser'];
                                                        }      
                                                    ?>
                                                    <?php                                               
                                                        if ($fec_ini=='') {
                                                            echo "<div class='alert alert-danger' role='alert'>
                                                                  ¡NO PROGRAMADO!
                                                                </div>"; 
                                                        }else{
                                                            echo "<h4>".$fec_ini." </h4>";
                                                        }                 
                                                        if ($hr_ini=='' || $hr_fin=='') {
                                                            echo "";
                                                        }else{
                                                            echo "<div class='alert alert-success' role='alert'> Hr. inicio: ".substr($hr_ini,0,5)." <br> Hr. fin:".substr($hr_fin,0,5)."
                                                             </div>";
                                                        }
                                                        ?>
                                                     <div class="course-card-footer">
                                                        <?php             
                                                            if ($fec_ini==$fec_act) {                   
                                                                if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                                                                    if ($id_ban=='0') {
                                                                        echo "<small>No tiene un banco de preguntas asignado.</small>";
                                                                    }else{                          
                                                                        $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and estado='1'");
                                                                        $queryt->execute();                           
                                                                        if($queryt->rowCount()>0){
                                                                            ///*****INVESTIGACION**********///
                                                                            if($parcial=='9'){
                                                                                $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                                                                $_inv= $bdcon->prepare($q_inv);
                                                                                $_inv->execute();
                                                                                if($_inv->rowCount()>0){
                                                                                    echo "<font color='green'>Evaluación realizada</font>";
                                                                                }else{
                                                                                    ?>
                                                                                    <font color="#00aae4">Investigación</font>
                                                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                                    <?php
                                                                                }
                                                                            }else{
                                                                                echo "<font color='green'>Evaluación realizada</font>";
                                                                            }
                                                                        }else{ 
                                                                            //********REPROGRAMACION*******//////
                                                                            if ($obser=='2'){  
                                                                                $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$codmat' and codest='$codest' and parcial='$parcial'");
                                                                                $q_rep->execute();
                                                                                if($q_rep->rowCount()>0){
                                                                                    $texto="";
                                                                                    if($parcial=="9"){
                                                                                        $texto="<font color='#00aae4'>Investigación</font>";
                                                                                    }
                                                                                    echo $texto;
                                                                                    ?>                         
                                                                                    <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>  
                                                                                    <?php
                                                                                }else{
                                                                                    echo "<div class='alert alert-danger' role='alert'>No reprogramado</div>";
                                                                                }
                                                                            }else{
                                                                                ///****HABILITAMOS BOTON**//// 
                                                                                    if ($planalcontado==1) {
                                                                                        ?>              
                                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                                        <?php 
                                                                                    }else{
                                                                                         $cole_pagar= @$colemenos* $parcial;
                                                                                         if ($cole_pagar<=$pagadocole){
                                                                                            ?>              
                                                                                                <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                                            <?php 
                                                                                            }else{ 
                                                                                             ?>    
                                                                                                <button class="btn btn-danger btn-lg btn-block" type="button" uk-toggle="target: #modal-pago" onclick="ver_pagos('<?=$codest;?>','<?=$per;?>','<?=$parcial;?>')">NO HABILITADO</button>
                                                                                            <?php 
                                                                                            }
                                                                                    } 
                                                                            }                                
                                                                        }
                                                                    }
                                                                }else{
                                                                    $actividad=$q_act->rowCount();
                                                                    if ($actividad==0) {
                                                                         echo "<div class='alert alert-danger' role='alert'>No tiene actividad</div>";
                                                                         //echo "<strong style='color:red;'>No tiene actividad</strong>";
                                                                    }else{
                                                                         echo "<div class='alert alert-info' role='alert'>¡ No esta en hora !   </div>";

                                                                    }                            
                                                                }                                           
                                                            } 
                                                            ?> 
                                                    </div>
                                            </div>
                                        </div>                                        
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
        // -------DEV_test---exit;

        // SI ESTUDIANTE ES ODT Y DEL 8 O 10 SEMESTRE
        // ------------IF_INICIO --- ODT - 8vo - 10mo --II/2020  
        $_semestre=$estudiante->getSemestreEst();
        if((($estudiante->getCodCarrera()=="01ODT")&&($_semestre==8||$_semestre==10)) || (($estudiante->getCodCarrera()=="01ENF")&& $_semestre==8)){
            // if($codest=='42350' || $codest=='40044'){
            //     $periodoII2020=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion->getId(), $gestion->getperiodoactuales()), $gestion->getId());
            // }
            if (!is_null($periodoII2020)) {
                # code...
                for($j=0;$j<count($periodoII2020);$j++){                
                ?>
                <?php 
                $per=$periodoII2020[$j]['idperiodo'];
                $nb_periodo=$periodoII2020[$j]['nbperiodo'];
                ?>     
                <h1><?=$nb_periodo?></h1>    
                <div class="panel-body">
                        <div class="section-small">
                            <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                        <?php              
                            if(!is_null($periodoII2020[$j]['materias_array'])){
                                $limite_mat=count($periodoII2020[$j]['materias_array']);
                            }else{
                                $limite_mat=0;
                            }
                            for($i=0;$i<$limite_mat;$i++){
                                $id_grupo=$periodoII2020[$j]['materias_array'][$i]['idgrupo'];
                                ?>
                                <!--tr align="center">
                                    <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                    <td class="text-muted small"><?php echo $codmat=$periodoII2020[$j]['materias_array'][$i]['codmateria'];?></td>
                                    <td class="text-muted small"><?php echo $n_mat=$periodoII2020[$j]['materias_array'][$i]['nb_materia'];?></td>
                                    <td class="text-muted small"><?php echo $periodoII2020[$j]['materias_array'][$i]['grupo'];?></td>
                                    <td class="text-muted small">
                                        <?php  //$id_grupo=$otras_matPeriodos[$j]['materias_array'][$i]['idgrupo'];?>
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
                                    <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')">
                                        <div class="course-card">
                                            <div class="course-card-thumbnail ">
                                                <img src="img/materias/<?php echo $i+1; ?>.png">
                                                <span class="play-button-trigger"></span>
                                            </div>
                                            <div class="course-card-body">
                                                <div class="course-card-info">
                                                    <div>
                                                        <span class="catagroy"><?php echo $codmat=$periodoII2020[$j]['materias_array'][$i]['codmateria'];?></span>
                                                    </div>
                                                    <div>
                                                        <i class="icon-feather-bookmark icon-small"></i>
                                                    </div>
                                                </div>
                                                <h4><?php echo $n_mat=$periodoII2020[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                                <P><?= 'GRUPO - '.$grupoo=$periodoII2020[$j]['materias_array'][$i]['grupo'];?> </P>
                                                <p> <?php echo $periodoII2020[$j]['nbperiodo'];?></p>
                                                <?php
                                                $fec_ini="";
                                                $fec_fin="";
                                                $hr_ini="";
                                                $hr_fin=""; 
                                                $parcial=2;
                                                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$codmat' and grupo='$grupoo' and carrera='$codcarr'");   
                                                $q_crono->execute(); 
                                                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                                                    $fec_ini=$fcrono['fecha_programada'];
                                                    $hr_ini=$fcrono['hora'];                   
                                                }  
                                                $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$id_grupo' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                                                $q_act->execute();                                                        
                                                while ($fact= $q_act->fetch(PDO::FETCH_ASSOC)) {
                                                    $cod_act=$fact['id'];
                                                    $fec_ini=$fact['desde'];
                                                    $fec_fin=$fact['hasta'];
                                                    $hr_ini=$fact['hora_d'];
                                                    $hr_fin=$fact['hora_h'];
                                                    $id_ban=$fact['id_cat'];
                                                    $parcial=$fact['parcial'];
                                                    $obser=$fact['obser'];
                                                }      
                                            ?>
                                            <?php                                               
                                                if ($fec_ini=='') {
                                                    echo "<div class='alert alert-danger' role='alert'>
                                                          ¡NO PROGRAMADO!
                                                        </div>"; 
                                                }else{
                                                    echo "<h4>".$fec_ini." </h4>";
                                                }                 
                                                if ($hr_ini=='' || $hr_fin=='') {
                                                    echo "";
                                                }else{
                                                    echo "<div class='alert alert-success' role='alert'> Hr. inicio: ".substr($hr_ini,0,5)." <br> Hr. fin:".substr($hr_fin,0,5)."
                                                     </div>";
                                                }
                                                ?>
                                                <div class="course-card-footer">
                                            <?php             
                                                if ($fec_ini==$fec_act) {                   
                                                    if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                                                        if ($id_ban=='0') {
                                                            echo "<small>No tiene un banco de preguntas asignado.</small>";
                                                        }else{                          
                                                            $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and estado='1'");
                                                            $queryt->execute();                           
                                                            if($queryt->rowCount()>0){
                                                                ///*****INVESTIGACION**********///
                                                                if($parcial=='9'){
                                                                    $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                                                    $_inv= $bdcon->prepare($q_inv);
                                                                    $_inv->execute();
                                                                    if($_inv->rowCount()>0){
                                                                        echo "<font color='green'>Evaluación realizada</font>";
                                                                    }else{
                                                                        ?>
                                                                        <font color="#00aae4">Investigación</font>
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                        <?php
                                                                    }
                                                                }else{
                                                                    echo "<font color='green'>Evaluación realizada</font>";
                                                                }
                                                            }else{ 
                                                                //********REPROGRAMACION*******//////
                                                                if ($obser=='2'){  
                                                                    $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$codmat' and codest='$codest' and parcial='$parcial'");
                                                                    $q_rep->execute();
                                                                    if($q_rep->rowCount()>0){
                                                                        $texto="";
                                                                        if($parcial=="9"){
                                                                            $texto="<font color='#00aae4'>Investigación</font>";
                                                                        }
                                                                        echo $texto;
                                                                        ?>                         
                                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>  
                                                                        <?php
                                                                    }else{
                                                                        echo "<div class='alert alert-danger' role='alert'>No reprogramado</div>";
                                                                    }
                                                                }else{
                                                                    ///****HABILITAMOS BOTON**//// 
                                                                    if ($planalcontado==1 || $carr=='LICENCIATURA EN AUDITORIA FINANCIERA' || $carr=='LICENCIATURA EN ADMINISTRACION DE EMPRESAS' || $carr=='LICENCIATURA EN DERECHO') {
                                                                            ?>              
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                            <?php 
                                                                    }else{
                                                                        $cole_pagar= @$colemenos* $parcial;
                                                                        if ($cole_pagar<=$pagadocole){
                                                                        ?>              
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                            <?php 
                                                                        }else{ 
                                                                            ?>    
                                                                            <button class="btn btn-danger btn-lg btn-block" type="button" uk-toggle="target: #modal-pago" onclick="ver_pagos('<?=$codest;?>','<?=$per;?>','<?=$parcial;?>')">NO HABILITADO</button>
                                                                            <?php 
                                                                        }
                                                                    } 
                                                                }                                
                                                            }
                                                        }
                                                    }else{
                                                        $actividad=$q_act->rowCount();
                                                        if ($actividad==0) {
                                                             echo "<div class='alert alert-danger' role='alert'>No tiene actividad</div>";
                                                             //echo "<strong style='color:red;'>No tiene actividad</strong>";
                                                        }else{
                                                             echo "<div class='alert alert-info' role='alert'>¡ No esta en hora !   </div>";

                                                        }                            
                                                    }                                           
                                                } 
                                                ?> 

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
                <?php }
            } 
        }
        // ------------IF_INICIO --- ODT - 8vo - 10mo --II/2020

    //==============================================DEVELOPING_FIN


        // OTROS Ciclos para periodos personalizados
        //--INICIO IF 2
        $_codest=$estudiante->getCodest();
        if($_codest=='49392' || $_codest=='47535' || $_codest=='43470' || $_codest=='45262' || $_codest=='45977' || $_codest=='46509' || $_codest=='46010' || $_codest=='46021' || $_codest=='39690' || $_codest=='46414' || $codest=='39827' || $codest=='42697' || $codest=='44579' || $codest=='42084' || $codest=='38802' || $codest=='45919' || $codest=='46932' || $codest=='46489' || $codest=='46643' || $codest=='46580' || $codest=='49714' || $codest=='49848' || $codest=='49786' || $codest=='49829' || $codest=='49786' || $codest=='43182' || $codest=='43752' || $codest=='43896' || $codest=='44525' || $codest=='44188'){
            if (!is_null($otras_matPeriodos)) {
                # code...                            
            for($j=0;$j<count($otras_matPeriodos);$j++){                
            ?>
            <?php 
            $per=$otras_matPeriodos[$j]['idperiodo'];
            $nb_periodo=$otras_matPeriodos[$j]['nbperiodo'];
            ?>     
            <h1><?=$nb_periodo?></h1>     
            <div class="panel-body">
                    <div class="section-small">
                        <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                    <?php              
                        if(!is_null($otras_matPeriodos[$j]['materias_array'])){
                            $limite_mat=count($otras_matPeriodos[$j]['materias_array']);
                        }else{
                            $limite_mat=0;
                        }
                        for($i=0;$i<$limite_mat;$i++){
                            $id_grupo=$otras_matPeriodos[$j]['materias_array'][$i]['idgrupo'];
                            ?>
                            <!--tr align="center">
                                <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                <td class="text-muted small"><?php echo $codmat=$otras_matPeriodos[$j]['materias_array'][$i]['codmateria'];?></td>
                                <td class="text-muted small"><?php echo $n_mat=$otras_matPeriodos[$j]['materias_array'][$i]['nb_materia'];?></td>
                                <td class="text-muted small"><?php echo $otras_matPeriodos[$j]['materias_array'][$i]['grupo'];?></td>
                                <td class="text-muted small">
                                    <?php  $id_grupo=$otras_matPeriodos[$j]['materias_array'][$i]['idgrupo'];?>
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
                                <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')">
                                    <div class="course-card">
                                        <div class="course-card-thumbnail ">
                                            <img src="img/materias/<?php echo $i+1; ?>.png">
                                            <span class="play-button-trigger"></span>
                                        </div>
                                        <div class="course-card-body">
                                            <div class="course-card-info">
                                                <div>
                                                    <span class="catagroy"><?php echo $codmat=$otras_matPeriodos[$j]['materias_array'][$i]['codmateria'];?></span>
                                                </div>
                                                <div>
                                                    <i class="icon-feather-bookmark icon-small"></i>
                                                </div>
                                            </div>
                                            <h4><?php echo $n_mat=$otras_matPeriodos[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                            <P><?= 'GRUPO - '.$grupoo=$otras_matPeriodos[$j]['materias_array'][$i]['grupo'];?> </P>
                                            <p> <?php echo $otras_matPeriodos[$j]['nbperiodo'];?></p>
                                             <?php
                                                $fec_ini="";
                                                $fec_fin="";
                                                $hr_ini="";
                                                $hr_fin=""; 
                                                $parcial=2;
                                                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$codmat' and grupo='$grupoo' and carrera='$codcarr'");   
                                                $q_crono->execute(); 
                                                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                                                    $fec_ini=$fcrono['fecha_programada'];
                                                    $hr_ini=$fcrono['hora'];                   
                                                }  
                                                $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$id_grupo' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                                                $q_act->execute();                                                        
                                                while ($fact= $q_act->fetch(PDO::FETCH_ASSOC)) {
                                                    $cod_act=$fact['id'];
                                                    $fec_ini=$fact['desde'];
                                                    $fec_fin=$fact['hasta'];
                                                    $hr_ini=$fact['hora_d'];
                                                    $hr_fin=$fact['hora_h'];
                                                    $id_ban=$fact['id_cat'];
                                                    $parcial=$fact['parcial'];
                                                    $obser=$fact['obser'];
                                                }      
                                            ?>
                                            <?php                                               
                                                if ($fec_ini=='') {
                                                    echo "<div class='alert alert-danger' role='alert'>
                                                          ¡NO PROGRAMADO!
                                                        </div>"; 
                                                }else{
                                                    echo "<h4>".$fec_ini." </h4>";
                                                }                 
                                                if ($hr_ini=='' || $hr_fin=='') {
                                                    echo "";
                                                }else{
                                                    echo "<div class='alert alert-success' role='alert'> Hr. inicio: ".substr($hr_ini,0,5)." <br> Hr. fin:".substr($hr_fin,0,5)."
                                                     </div>";
                                                }
                                                ?>
                                                <div class="course-card-footer">
                                            <?php             
                                                if ($fec_ini==$fec_act) {                   
                                                    if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                                                        if ($id_ban=='0') {
                                                            echo "<small>No tiene un banco de preguntas asignado.</small>";
                                                        }else{                          
                                                            $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and estado='1'");
                                                            $queryt->execute();                           
                                                            if($queryt->rowCount()>0){
                                                                ///*****INVESTIGACION**********///
                                                                if($parcial=='9'){
                                                                    $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                                                    $_inv= $bdcon->prepare($q_inv);
                                                                    $_inv->execute();
                                                                    if($_inv->rowCount()>0){
                                                                        echo "<font color='green'>Evaluación realizada</font>";
                                                                    }else{
                                                                        ?>
                                                                        <font color="#00aae4">Investigación</font>
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                        <?php
                                                                    }
                                                                }else{
                                                                    echo "<font color='green'>Evaluación realizada</font>";
                                                                }
                                                            }else{ 
                                                                //********REPROGRAMACION*******//////
                                                                if ($obser=='2'){  
                                                                    $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$codmat' and codest='$codest' and parcial='$parcial'");
                                                                    $q_rep->execute();
                                                                    if($q_rep->rowCount()>0){
                                                                        $texto="";
                                                                        if($parcial=="9"){
                                                                            $texto="<font color='#00aae4'>Investigación</font>";
                                                                        }
                                                                        echo $texto;
                                                                        ?>                         
                                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>  
                                                                        <?php
                                                                    }else{
                                                                        echo "<div class='alert alert-danger' role='alert'>No reprogramado</div>";
                                                                    }
                                                                }else{
                                                                    ///****HABILITAMOS BOTON**//// 
                                                                        if ($planalcontado==1) {
                                                                            ?>              
                                                                                <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                            <?php 
                                                                        }else{
                                                                             $cole_pagar= @$colemenos* $parcial;
                                                                             if ($cole_pagar<=$pagadocole){
                                                                                ?>              
                                                                                    <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                                <?php 
                                                                                }else{ 
                                                                                 ?>    
                                                                                    <button class="btn btn-danger btn-lg btn-block" type="button" uk-toggle="target: #modal-pago" onclick="ver_pagos('<?=$codest;?>','<?=$per;?>','<?=$parcial;?>')">NO HABILITADO</button>
                                                                                <?php 
                                                                                }
                                                                        } 
                                                                }                                
                                                            }
                                                        }
                                                    }else{
                                                        $actividad=$q_act->rowCount();
                                                        if ($actividad==0) {
                                                             echo "<div class='alert alert-danger' role='alert'>No tiene actividad</div>";
                                                             //echo "<strong style='color:red;'>No tiene actividad</strong>";
                                                        }else{
                                                             echo "<div class='alert alert-info' role='alert'>¡ No esta en hora !   </div>";

                                                        }                            
                                                    }                                           
                                                } 
                                                ?> 
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
            <?php }
            } 
        }
        //--FIN IF 2
        //--------------------------------------
        //--Inicio IF 3
        if($_codest=='48645' || $_codest=='44787'){
            if (!is_null($periodo2020all)) {
                # code...                            
            for($j=0;$j<count($periodo2020all);$j++){                
            ?>
            <?php 
            $per=$periodo2020all[$j]['idperiodo'];
            $nb_periodo=$periodo2020all[$j]['nbperiodo'];
            ?>     
            <h1><?=$nb_periodo?></h1>       
            <div class="panel-body">
                    <div class="section-small">
                        <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                    <?php              
                        if(!is_null($periodo2020all[$j]['materias_array'])){
                            $limite_mat=count($periodo2020all[$j]['materias_array']);
                        }else{
                            $limite_mat=0;
                        }
                        for($i=0;$i<$limite_mat;$i++){
                            $id_grupo=$periodo2020all[$j]['materias_array'][$i]['idgrupo'];
                            ?>
                            <!--tr align="center">
                                <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                <td class="text-muted small"><?php echo $codmat=$periodo2020all[$j]['materias_array'][$i]['codmateria'];?></td>
                                <td class="text-muted small"><?php echo $n_mat=$periodo2020all[$j]['materias_array'][$i]['nb_materia'];?></td>
                                <td class="text-muted small"><?php echo $periodo2020all[$j]['materias_array'][$i]['grupo'];?></td>
                                <td class="text-muted small">
                                    <?php  $id_grupo=$periodo2020all[$j]['materias_array'][$i]['idgrupo'];?>
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
                                <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')">
                                    <div class="course-card">
                                        <div class="course-card-thumbnail ">
                                            <img src="img/materias/<?php echo $i+1; ?>.png">
                                            <span class="play-button-trigger"></span>
                                        </div>
                                        <div class="course-card-body">
                                            <div class="course-card-info">
                                                <div>
                                                    <span class="catagroy"><?php echo $codmat=$periodo2020all[$j]['materias_array'][$i]['codmateria'];?></span>
                                                </div>
                                                <div>
                                                    <i class="icon-feather-bookmark icon-small"></i>
                                                </div>
                                            </div>
                                            <h4><?php echo $n_mat=$periodo2020all[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                            <P><?= 'GRUPO - '.$grupoo=$periodo2020all[$j]['materias_array'][$i]['grupo'];?> </P>
                                            <p> <?php echo $periodo2020all[$j]['nbperiodo'];?></p>
                                             <?php
                                                $fec_ini="";
                                                $fec_fin="";
                                                $hr_ini="";
                                                $hr_fin=""; 
                                                $parcial=2;
                                                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$codmat' and grupo='$grupoo' and carrera='$codcarr'");   
                                                $q_crono->execute(); 
                                                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                                                    $fec_ini=$fcrono['fecha_programada'];
                                                    $hr_ini=$fcrono['hora'];                   
                                                }  
                                                $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$id_grupo' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                                                $q_act->execute();                                                        
                                                while ($fact= $q_act->fetch(PDO::FETCH_ASSOC)) {
                                                    $cod_act=$fact['id'];
                                                    $fec_ini=$fact['desde'];
                                                    $fec_fin=$fact['hasta'];
                                                    $hr_ini=$fact['hora_d'];
                                                    $hr_fin=$fact['hora_h'];
                                                    $id_ban=$fact['id_cat'];
                                                    $parcial=$fact['parcial'];
                                                    $obser=$fact['obser'];
                                                }      
                                            ?>
                                            <?php                                               
                                                if ($fec_ini=='') {
                                                    echo "<div class='alert alert-danger' role='alert'>
                                                          ¡NO PROGRAMADO!
                                                        </div>"; 
                                                }else{
                                                    echo "<h4>".$fec_ini." </h4>";
                                                }                 
                                                if ($hr_ini=='' || $hr_fin=='') {
                                                    echo "";
                                                }else{
                                                    echo "<div class='alert alert-success' role='alert'> Hr. inicio: ".substr($hr_ini,0,5)." <br> Hr. fin:".substr($hr_fin,0,5)."
                                                     </div>";
                                                }
                                                ?>
                                                <div class="course-card-footer">
                                            <?php             
                                                if ($fec_ini==$fec_act) {                   
                                                    if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                                                        if ($id_ban=='0') {
                                                            echo "<small>No tiene un banco de preguntas asignado.</small>";
                                                        }else{                          
                                                            $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and estado='1'");
                                                            $queryt->execute();                           
                                                            if($queryt->rowCount()>0){
                                                                ///*****INVESTIGACION**********///
                                                                if($parcial=='9'){
                                                                    $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                                                    $_inv= $bdcon->prepare($q_inv);
                                                                    $_inv->execute();
                                                                    if($_inv->rowCount()>0){
                                                                        echo "<font color='green'>Evaluación realizada</font>";
                                                                    }else{
                                                                        ?>
                                                                        <font color="#00aae4">Investigación</font>
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                        <?php
                                                                    }
                                                                }else{
                                                                    echo "<font color='green'>Evaluación realizada</font>";
                                                                }
                                                            }else{ 
                                                                //********REPROGRAMACION*******//////
                                                                if ($obser=='2'){  
                                                                    $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$codmat' and codest='$codest' and parcial='$parcial'");
                                                                    $q_rep->execute();
                                                                    if($q_rep->rowCount()>0){
                                                                        $texto="";
                                                                        if($parcial=="9"){
                                                                            $texto="<font color='#00aae4'>Investigación</font>";
                                                                        }
                                                                        echo $texto;
                                                                        ?>                         
                                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>  
                                                                        <?php
                                                                    }else{
                                                                        echo "<div class='alert alert-danger' role='alert'>No reprogramado</div>";
                                                                    }
                                                                }else{
                                                                    ///****HABILITAMOS BOTON**//// 
                                                                        if ($planalcontado==1) {
                                                                            ?>              
                                                                                <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                            <?php 
                                                                        }else{
                                                                            $cole_pagar= @$colemenos* $parcial;
                                                                            if ($cole_pagar<=$pagadocole){
                                                                                ?>              
                                                                                    <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                                <?php 
                                                                            }else{ 
                                                                                 ?>    
                                                                                    <button class="btn btn-danger btn-lg btn-block" type="button" uk-toggle="target: #modal-pago" onclick="ver_pagos('<?=$codest;?>','<?=$per;?>','<?=$parcial;?>')">NO HABILITADO</button>
                                                                                <?php 
                                                                            }
                                                                        } 
                                                                }                                
                                                            }
                                                        }
                                                    }else{
                                                        $actividad=$q_act->rowCount();
                                                        if ($actividad==0) {
                                                             echo "<div class='alert alert-danger' role='alert'>No tiene actividad</div>";
                                                             //echo "<strong style='color:red;'>No tiene actividad</strong>";
                                                        }else{
                                                             echo "<div class='alert alert-info' role='alert'>¡ No esta en hora !   </div>";

                                                        }                            
                                                    }                                           
                                                } 
                                                ?> 
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
            <?php }
            } 
        }
        //-- FIN IF 3

        // if($codest=='42350' || $codest=='40044'){
        //--Inicio IF 4
        $_practicas= new practica();
        $practiasII2020=null;
        $periodoII2020=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion2020->getId(), $gestion2020->getPeriodoIINormal_solo()), $gestion2020->getId());
        // $_practicas->materias_practicas($periodoII2020);
        $practiasII2020=$_practicas->materias_practicas($periodoII2020);

        if (!is_null($practiasII2020)) {
            # code...                            
        for($j=0;$j<count($practiasII2020);$j++){                
        ?>
        <?php 
        $per=$practiasII2020[$j]['idperiodo'];
        $nb_periodo=$practiasII2020[$j]['nbperiodo'];
        ?>     
        <h1><?=$nb_periodo." - PRÁCTICAS"?></h1>
        <div class="panel-body">
                <div class="section-small">
                    <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                <?php              
                    if(!is_null($practiasII2020[$j]['materias_array'])){
                        $limite_mat=count($practiasII2020[$j]['materias_array']);
                    }else{
                        $limite_mat=0;
                    }
                    for($i=0;$i<$limite_mat;$i++){
                        $id_grupo=$practiasII2020[$j]['materias_array'][$i]['idgrupo'];
                        ?>
                        <!--tr align="center">
                            <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                            <td class="text-muted small"><?php echo $codmat=$practiasII2020[$j]['materias_array'][$i]['codmateria'];?></td>
                            <td class="text-muted small"><?php echo $n_mat=$practiasII2020[$j]['materias_array'][$i]['nb_materia'];?></td>
                            <td class="text-muted small"><?php echo $practiasII2020[$j]['materias_array'][$i]['grupo'];?></td>
                            <td class="text-muted small">
                                <?php  $id_grupo=$practiasII2020[$j]['materias_array'][$i]['idgrupo'];?>
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
                            <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')">
                                <div class="course-card">
                                    <div class="course-card-thumbnail ">
                                        <img src="img/materias/<?php echo $i+1; ?>.png">
                                        <span class="play-button-trigger"></span>
                                    </div>
                                    <div class="course-card-body">
                                        <div class="course-card-info">
                                            <div>
                                                <span class="catagroy"><?php echo $codmat=$practiasII2020[$j]['materias_array'][$i]['codmateria'];?></span>
                                            </div>
                                            <div>
                                                <i class="icon-feather-bookmark icon-small"></i>
                                            </div>
                                        </div>
                                        <h4><?php echo $n_mat=$practiasII2020[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                        <P><?= 'GRUPO - '.$grupoo=$practiasII2020[$j]['materias_array'][$i]['grupo'];?> </P>
                                        <p> <?php echo $practiasII2020[$j]['nbperiodo'];?></p>
                                         <?php
                                        $fec_ini="";
                                        $fec_fin="";
                                        $hr_ini="";
                                        $hr_fin=""; 
                                        $parcial=2;
                                        $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$codmat' and grupo='$grupoo' and carrera='$codcarr'");   
                                        $q_crono->execute(); 
                                        while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                                            $fec_ini=$fcrono['fecha_programada'];
                                            $hr_ini=$fcrono['hora'];                   
                                        }  
                                        $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$id_grupo' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                                        $q_act->execute();                                                        
                                        while ($fact= $q_act->fetch(PDO::FETCH_ASSOC)) {
                                            $cod_act=$fact['id'];
                                            $fec_ini=$fact['desde'];
                                            $fec_fin=$fact['hasta'];
                                            $hr_ini=$fact['hora_d'];
                                            $hr_fin=$fact['hora_h'];
                                            $id_ban=$fact['id_cat'];
                                            $parcial=$fact['parcial'];
                                            $obser=$fact['obser'];
                                        }      
                                    ?>
                                    <?php                                               
                                        if ($fec_ini=='') {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                  ¡NO PROGRAMADO!
                                                </div>"; 
                                        }else{
                                            echo "<h4>".$fec_ini." </h4>";
                                        }                 
                                        if ($hr_ini=='' || $hr_fin=='') {
                                            echo "";
                                        }else{
                                            echo "<div class='alert alert-success' role='alert'> Hr. inicio: ".substr($hr_ini,0,5)." <br> Hr. fin:".substr($hr_fin,0,5)."
                                             </div>";
                                        }
                                        ?>
                                        <div class="course-card-footer">
                                    <?php             
                                        if ($fec_ini==$fec_act) {                   
                                            if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                                                if ($id_ban=='0') {
                                                    echo "<small>No tiene un banco de preguntas asignado.</small>";
                                                }else{                          
                                                    $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and estado='1'");
                                                    $queryt->execute();                           
                                                    if($queryt->rowCount()>0){
                                                        ///*****INVESTIGACION**********///
                                                        if($parcial=='9'){
                                                            $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$id_grupo' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                                            $_inv= $bdcon->prepare($q_inv);
                                                            $_inv->execute();
                                                            if($_inv->rowCount()>0){
                                                                echo "<font color='green'>Evaluación realizada</font>";
                                                            }else{
                                                                ?>
                                                                <font color="#00aae4">Investigación</font>
                                                                    <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                <?php
                                                            }
                                                        }else{
                                                            echo "<font color='green'>Evaluación realizada</font>";
                                                        }
                                                    }else{ 
                                                        //********REPROGRAMACION*******//////
                                                        if ($obser=='2'){  
                                                            $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$codmat' and codest='$codest' and parcial='$parcial'");
                                                            $q_rep->execute();
                                                            if($q_rep->rowCount()>0){
                                                                $texto="";
                                                                if($parcial=="9"){
                                                                    $texto="<font color='#00aae4'>Investigación</font>";
                                                                }
                                                                echo $texto;
                                                                ?>                         
                                                                <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>  
                                                                <?php
                                                            }else{
                                                                echo "<div class='alert alert-danger' role='alert'>No reprogramado</div>";
                                                            }
                                                        }else{
                                                            ///****HABILITAMOS BOTON**//// 
                                                                if ($planalcontado==1) {
                                                                    ?>              
                                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                    <?php 
                                                                }else{
                                                                     $cole_pagar= @$colemenos* $parcial;
                                                                     if ($cole_pagar<=$pagadocole){
                                                                        ?>              
                                                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $id_grupo; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                        <?php 
                                                                        }else{ 
                                                                         ?>    
                                                                            <button class="btn btn-danger btn-lg btn-block" type="button" uk-toggle="target: #modal-pago" onclick="ver_pagos('<?=$codest;?>','<?=$per;?>','<?=$parcial;?>')">NO HABILITADO</button>
                                                                        <?php 
                                                                        }
                                                                } 
                                                        }                                
                                                    }
                                                }
                                            }else{
                                                $actividad=$q_act->rowCount();
                                                if ($actividad==0) {
                                                     echo "<div class='alert alert-danger' role='alert'>No tiene actividad</div>";
                                                     //echo "<strong style='color:red;'>No tiene actividad</strong>";
                                                }else{
                                                     echo "<div class='alert alert-info' role='alert'>¡ No esta en hora !   </div>";

                                                }                            
                                            }                                           
                                        } 
                                        ?> 
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
        <?php }
        } 
        //-- FIN IF 4

        //---------------------------------------------Aqui comienza el if N
      ?>
      <div id="modal-pago" uk-modal>
        <div class="uk-modal-dialog uk-modal-body"> 
            <div class="modal-body" id="mos_pagos">
                
            </div>        
        </div>
    </div>