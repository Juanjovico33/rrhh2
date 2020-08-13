<?php
    include "../includes/conexion.php";
    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";

    function nombre_periodos($nb){
                if($nb=="TUTO"){
                    $nbper="CURSO TUTORIAL";
                    return $nbper;
                }else{
                    $nb1=substr($nb,4,5);
                    $ges=substr($nb,0,4);
                }
                //echo $nb1;
                switch ($nb1){
                    case '01':
                        $nbper="PERIODO I/".$ges;
                        return $nbper;
                    case '02':
                        $nbper="PERIODO II/".$ges;
                        return $nbper;
                    case '03':
                        $nbper="VERANO ".$ges;
                        return $nbper;
                    case '04':
                        $nbper="INVIERNO ".$ges;
                        return $nbper;
                    case '05':
                        $ges=$ges-1;
                        $nbper="RECUPERATORIO II/".$ges;
                        return $nbper;
                    case '06':
                        $nbper="NIVELACION I/".$ges;
                        return $nbper;
                    case '07':
                        $nbper="RECUPERATORIO I/".$ges;
                        return $nbper;
                    case '08':
                        $nbper="NIVELACION II/".$ges;
                        return $nbper;
                    case '09':
                        $nbper="INTENSIVO INGLES - COMPUTACION I/".$ges;
                        return $nbper;
                    case '10':
                        $nbper="INTENSIVO INGLES - COMPUTACION II/".$ges;
                        return $nbper;
                    case '11':
                        $nbper="EXAMEN DE SUFICIENCIA ".$ges;
                        return $nbper;
                    case '12':
                        $nbper="TUTORIALES I/".$ges;
                        return $nbper;
                    case '13':
                        $nbper="TUTORIALES II/".$ges;
                        return $nbper;
                    case '14':
                        $nbper="ADMISION INTERNADO ROTATORIO II/".$ges;
                        return $nbper;
                    case '15':
                        $nbper="RECUPERATORIO NIVELACION I/".$ges;
                        return $nbper;
                    case '16':
                        $nbper="INTERNADO ROTATORIO I/".$ges;
                        return $nbper;
                    case '17':
                        $nbper="INTERNADO ROTATORIO II/".$ges;
                        return $nbper;
                }
    }
    $codest = $_GET['_codest'];
    $estudiante =  new estudiante($codest);    
    $gestion = new gestion();   
    $query_gest= $bdcon->prepare("SELECT id FROM gestion WHERE estado='1'");
    $query_gest->execute();
    while ($row = $query_gest->fetch(PDO::FETCH_ASSOC)) {       
        $idges=$row['id'];                         
    }   
    $query_cod= $bdcon->prepare("SELECT * FROM estudiante_codigo where codigo_estudiante='$codest' order by codigo_estudiante");
    $query_cod->execute();
    $parcial=0;
    while ($fde = $query_cod->fetch(PDO::FETCH_ASSOC)) {       
       $car=$fde['carrera'];      
       $tp=$fde['tipo_pre'];                        
    } 
    if ($car=='02AUD' || $car=='02DER' || $car=='02ADM') {
        $gest_ant=$idges-1;
        $qrg=$bdcon->prepare("SELECT * from aca_registroestmat where codest='$codest' and (gestion='$idges' or gestion='$gest_ant') order by periodo");
        $qrg->execute();
    }else{
        $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo FROM aca_registroestmat WHERE codest='$codest' and gestion='$idges' and (periodo LIKE '%01' or periodo LIKE '%02' or periodo LIKE '%06' or periodo LIKE '%08' or periodo LIKE '%03' or periodo LIKE '%04'or periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%05' or periodo LIKE '%07' or periodo LIKE '%11' or periodo LIKE '%12' or periodo LIKE '%13') ORDER BY periodo, codmateria"); 
         $qrg->execute();   
        // echo "SELECT periodo, codmateria, grupo FROM aca_registroestmat WHERE codest='$codest1' and gestion='$idges' and (periodo LIKE '%01' or periodo LIKE '%02' or periodo LIKE '%06' or periodo LIKE '%08' or periodo LIKE '%03' or periodo LIKE '%04'or periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%05' or periodo LIKE '%07' or periodo LIKE '%11' or periodo LIKE '%12' or periodo LIKE '%13') ORDER BY periodo, codmateria";

    }   
?>
        <div class="panel-body">
         <div class="section-small">
            <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                <div class="col-md-12" align="center">
                                <h4> Exámenes Programados</h4>
                </div> 
                    <?php
                            $i=1;
                            $pagado=0;
                            $tot_deu=0;
                            $tot_pag=0;
                            $per_aux="";
                            $fecha=new DateTime();
                            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
                            $fec_act=$fecha->format('Y-m-d');
                            $hr_act=$fecha->format('H:i');
                            //$fec_act=date("Y-m-d");
                            //$hr_act=date("H:i");
                            $cod_gru_aux=0;    

                    //while($fcons=mysql_fetch_array($qrg)){   
                    ?>
                         <p><?php echo "Hora Actual SERVER: ".$hr_act;?></p>
                    <?php                        
                     while ($fcons = $qrg->fetch(PDO::FETCH_ASSOC)) {
                         //echo "entra";
                        $per=$fcons['periodo'];
                        $mat=$fcons['codmateria'];                    
                        $gru=$fcons['grupo'];
                        $gescor=substr($per, 0, 4);
                        $percor=substr($per, 4, 5);
                        if ($percor=='06' || $percor=='08') {
                            if ($percor=='06') {
                                $peri=$gescor."01";    
                            }else{
                                if ($percor=='08') {
                                    $peri=$gescor."02";
                                }else{
                                    $peri=$gescor."01";
                                }
                            }
                            $query_codgrup= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$peri' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                            $query_codgrup->execute();
                            while ($rowgrup = $query_codgrup->fetch(PDO::FETCH_ASSOC)) {       
                                $codgru=$rowgrup['CodGrupo'];                         
                            }                              
                        //$codgru=$cons->cons_simple('grupos',"periodo='$peri' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                            if ($codgru=='') {
                                $query_mat_nm= $bdcon->prepare("SELECT sigla_mn FROM materias_equivalencia WHERE codca_ma='$car' and sigla_ma='$mat'");
                                $query_mat_nm->execute();
                                while ($rownm = $query_mat_nm->fetch(PDO::FETCH_ASSOC)) {       
                                    $mat_nm=$rownm['sigla_mn'];                         
                                }
                        //$mat_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$mat'",'sigla_mn');
                                $query_car_nm= $bdcon->prepare("SELECT codca_mn FROM materias_equivalencia WHERE codca_ma='$car' and sigla_ma='$mat'");
                                $query_car_nm->execute();
                                while ($rowm = $query_car_nm->fetch(PDO::FETCH_ASSOC)) {       
                                    $car_nm=$rowm['codca_mn'];                         
                                }
                        //$car_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$mat'",'codca_mn');
                                $query_gru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$peri' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$gru'");
                                $query_gru->execute();
                                while ($rowu = $query_gru->fetch(PDO::FETCH_ASSOC)) {       
                                    $codgru=$rowu['CodGrupo'];                         
                                }
                        //$codgru=$cons->cons_simple('grupos',"periodo='$peri' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$gru'",'CodGrupo');
                            }
                            $query_gru_aux= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                                $query_gru_aux->execute();
                                while ($rowux = $query_gru_aux->fetch(PDO::FETCH_ASSOC)) {       
                                    $cod_gru_aux=$rowux['CodGrupo'];                         
                                }
                        //$cod_gru_aux=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                            $obserado=$codgru;
                        }else{
                            if ($percor=='09' || $percor=='10' || $percor=='11') {
                                if ($car=='01MED') {
                                    $querygrup= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                                    $querygrup->execute();
                                    while ($rowp = $querygrup->fetch(PDO::FETCH_ASSOC)) {       
                                        $codgru=$rowp['CodGrupo'];                         
                                    }
                        //$codgru=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                                }else{
                                    $querymat= $bdcon->prepare("SELECT cod_mat_ma FROM materias_fusionadas WHERE cod_car_mn='$car' and cod_mat_mn='$mat'");
                                    $querymat->execute();
                                    while ($rowmt = $querymat->fetch(PDO::FETCH_ASSOC)) {       
                                        $mat_nm=$rowmt['cod_mat_ma'];                         
                                    }
                        //$mat_nm=$cons->cons_simple('materias_fusionadas',"cod_car_mn='$car' and cod_mat_mn='$mat'",'cod_mat_ma');
                                    $querycar= $bdcon->prepare("SELECT cod_car_ma FROM materias_fusionadas WHERE cod_car_mn='$car' and cod_mat_mn='$mat'");
                                    $querycar->execute();
                                    while ($rowt = $querycar->fetch(PDO::FETCH_ASSOC)) {       
                                        $car_nm=$rowt['cod_car_ma'];                         
                                    }
                        //$car_nm=$cons->cons_simple('materias_fusionadas',"cod_car_mn='$car' and cod_mat_mn='$mat'",'cod_car_ma');
                                    $querycd= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$gru'");
                                    $querycd->execute();
                                    while ($rowg = $querycd->fetch(PDO::FETCH_ASSOC)) {       
                                        $codgru=$rowg['CodGrupo'];                         
                                    }
                        //$codgru=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$gru'",'CodGrupo');
                                    $querygx= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                                    $querygx->execute();
                                    while ($rowgx = $querygx->fetch(PDO::FETCH_ASSOC)) {       
                                        $cod_gru_aux=$rowgx['CodGrupo'];                         
                                    }
                        //$cod_gru_aux=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                                }
                                $obserado=$codgru;
                            }else{
                                //verificamos si esta fusionada la materia
                                $queryfus= $bdcon->prepare("SELECT * FROM grupos_fusionados WHERE codcar_rama='$car' and per_rama='$per' and mat_rama='$mat' ORDER BY cod");
                                $queryfus->execute();
                        // $q_fus=$cons->cons_cond('grupos_fusionados',"codcar_rama='$car' and per_rama='$per' and mat_rama='$mat'",'cod');
                                if($queryfus->rowCount()>0){
                                //$hay=mysql_num_rows($q_fus);
                                // if ($hay>0) {
                                    while ($rowfus = $queryfus->fetch(PDO::FETCH_ASSOC)) {
                                        $car_ra=$rowfus['codcar_raiz'];
                                        $per_ra=$rowfus['per_raiz'];
                                        $mat_ra=$rowfus['mat_raiz'];
                                        $gru_ra=$rowfus['gru_raiz'];
                                    }

                                    $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per_ra' and CodCarrera='$car_ra' and CodMateria='$mat_ra' and Descripcion='$gru_ra'");
                                    $querygru->execute();
                                    while ($rowgx = $querygru->fetch(PDO::FETCH_ASSOC)) {       
                                        $codgru=$rowgx['CodGrupo'];                         
                                    }    
                            //$codgru=$cons->cons_simple('grupos',"periodo='$per_ra' and CodCarrera='$car_ra' and CodMateria='$mat_ra' and Descripcion='$gru_ra'",'CodGrupo');
                                    $queryorigi= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                                     $queryorigi->execute();
                                    while ($rowori = $queryorigi->fetch(PDO::FETCH_ASSOC)) {       
                                        $codgru_orig=$rowori['CodGrupo'];                         
                                    }     
                            //$codgru_orig=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                                    $cod_gru_aux=$codgru_orig;
                                }else{
                                    $quergru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                                    $quergru->execute();
                                    while ($rogru= $quergru->fetch(PDO::FETCH_ASSOC)) {       
                                        $codgru=$rogru['CodGrupo'];                         
                                    }  
                            //$codgru=$cons->cons_simple('grupos',"periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'",'CodGrupo');
                                    $cod_gru_aux=$codgru;    
                                }
                            }
                        }                        
                        $fec_ini="";
                        $fec_fin="";
                        $hr_ini="";
                        $hr_fin="";
                      
                        //$q_act=mysql_query("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$codgru' and (desde<='$fec_act' and hasta>='$fec_act') and parcial='$parcial' ORDER BY id");
                        $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$codgru' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                        $q_act->execute();
                        //$q_act=mysql_query("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$codgru' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
                        //echo mysql_num_rows($q_act);
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
                        /*if ($percor=='06') {
                            $id_gru=$cons->cons_simple('grupos',"periodo='$peri' and CodCarrera='$car' and CodMateria='$sigla' and Descripcion='$grupo'",'CodGrupo');
                            if ($id_gru=='') {
                                $mat_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$sigla'",'sigla_mn');
                                $car_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$sigla'",'codca_mn');
                                $q_gru=$cons->cons_simple('grupos',"periodo='$peri' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$grupo'",'CodGrupo');
                                while ($fqgru=mysql_fetch_array($q_gru)) {
                                    $mat=$fqgru[''];
                                }
                            }
                        }*/
                        if ($fec_ini=='') {
                            /*if ($tp==3) {
                                $parcial=3;
                            }else{
                                //$parcial=3;
                            }*/
                            if ($percor=='06') {
                                $peri=$gescor."01";
                                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$peri' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                                $q_crono->execute(); 
                                //$q_crono=mysql_query("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$peri' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                                //$exis=mysql_num_rows($q_crono);
                                if($q_crono->rowCount()>0){
                                    //ok, existe cronograma
                                }else{
                                    //pero si no hay en su malla, buscamos en la nueva
                                    $quermat= $bdcon->prepare("SELECT sigla_mn FROM materias_equivalencia WHERE codca_ma='$car' and sigla_ma='$mat'");
                                    $quermat->execute();
                                    while ($romat= $quermat->fetch(PDO::FETCH_ASSOC)) {       
                                        $mat_nm=$romat['sigla_mn'];                         
                                    }  
                           //$mat_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$mat'",'sigla_mn');
                                    $quercar= $bdcon->prepare("SELECT codca_mn FROM materias_equivalencia WHERE codca_ma='$car' and sigla_ma='$mat'");
                                    $quercar->execute();
                                    while ($roca= $quercar->fetch(PDO::FETCH_ASSOC)) {       
                                        $car_nm=$roca['codca_mn'];                         
                                    }                                   
                            //$car_nm=$cons->cons_simple('materias_equivalencia',"codca_ma='$car' and sigla_ma='$mat'",'codca_mn');
                                    $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$peri' and parcial='$parcial' and materia='$mat_nm' and grupo='$gru' and carrera='$car_nm'");
                                    $q_crono->execute();
                            //$q_crono=mysql_query("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$peri' and parcial='$parcial' and materia='$mat_nm' and grupo='$gru' and carrera='$car_nm'");

                                }
                            }else{
                                //$parcial=1;
                                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                                $q_crono->execute();
                            //$q_crono=mysql_query("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                            }
                            while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)) { 
                                $fec_ini=$fcrono['fecha_programada'];
                                $fec_fin="";
                                $hr_ini=$fcrono['hora'];
                                $hr_fin="";
                            }
                        }
                        if ($per!=$per_aux) {
                            ?>                            
                            <div class="col-md-12">
                                <h6> <?php echo nombre_periodos($per);?></h6>
                            </div>  
                            <?php
                                $per_aux=$per;
                        }
                            ?>
                                <div>
                                    <a href="#">
                                        <div class="course-card">   
                                            <!--<div class="course-card-thumbnail ">
                                                <img src="img/materias/<?php //echo $i+1; ?>.png">
                                                <span class="play-button-trigger"></span>
                                            </div>-->                                            
                                            <div align="center"><i class="icon-feather-edit skill-card-icon" style="color:#64d25d"></i>
                                                                                        
                                            <div class="course-card-body">
                                                 <div class="course-card-info">
                                                    <strong> 
                                                    <?php 
                                                        $qnbmat= $bdcon->prepare("SELECT Descripcion FROM materias WHERE CodCarrera='$car' and Sigla='$mat' and sigla_pensum<>''");
                                                        $qnbmat->execute();
                                                        while ($romats= $qnbmat->fetch(PDO::FETCH_ASSOC)) {       
                                                            $cons=$romats['Descripcion'];                         
                                                        } 
                                                          echo $mat." - ".$cons;
                                                         // echo "<br>";
                                                            //echo $codgru;
                                                        //echo $mat." - ".$cons->cons_simple('materias',"CodCarrera='$car' and Sigla='$mat' and sigla_pensum<>''",'Descripcion'); 
                                                     ?> 
                                                    </strong>
                                                </div>                                          
                                                <p> Grupo - <?php echo $gru; ?></p>
                                                <?php
                                                    if ($fec_ini=='') {
                                                        echo "<p style='color:red;'>No programado</p>";  
                                                    }else{
                                                        echo "<p style='color:green;'><strong>".$fec_fin."</strong></p>";
                                                    }
                                                    ?>                                             
                                                <?php 
                                                    if ($hr_ini=='') {
                                                        echo "";
                                                    }else{
                                                        echo "<p style='color:green;'><strong>".$hr_ini." a ".$hr_fin."</strong></p>";
                                                    }
                                                ?> 
                            <!-- aqui iniciaba otro td-->
                                <?php
                                if ($fec_ini=='') {
                                }else{
                                    if ($fec_ini==$fec_act) {
                                        if (($hr_ini<=$hr_act) && ($hr_fin>=$hr_act)) {
                                            if ($id_ban=='0') {
                                                echo "<small>No tiene un banco de preguntas asignado.</small>";
                                            }else{
                                                $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE cod_act='$cod_act' and cod_ban='$id_ban' and codest='$codest'");
                                                $queryt->execute();
                                                $nota_exis=0;
                                                while ($rot= $queryt->fetch(PDO::FETCH_ASSOC)) {       
                                                    $nota_exis=$rot['reco'];                         
                                                } 
                                               // $nota_exis=$cons->cons_simple('plat_doc_intentos_est',"cod_act='$cod_act' and cod_ban='$id_ban' and codest='$codest1'",'reco');
                                                if ($nota_exis>0) {
                                                    echo "<font color='green'>Evaluación realizada</font>";
                                                }else{
                                                    if ($obser=='2') {
                                                        $querep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$mat' and codest='$codest' and parcial='$parcial'");
                                                        $querep->execute();
                                                        $q_rep=0;
                                                        while ($rorep= $querep->fetch(PDO::FETCH_ASSOC)) {       
                                                            $q_rep=$rorep['cod_repro'];                         
                                                        } 
                                                        //$q_rep=$cons->cons_cond('aca_registroestrepro',"periodo='$per' and materia='$mat' and codest='$codest1' and parcial='$parcial'",'cod_repro');
                                                        if($querep->rowCount()>0){                                                       
                                                            echo "<font color='red'>No reprogramado</font>";
                                                        }else{
                                                            ?>                                                           
                                                            <div class="course-card-footer">
                                                                <div align="center">
                                                                     <button class="btn btn-success btn-sm" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                                </div>
                                                            </div>                                                       
                                                            <?php
                                                        }
                                                    }else{
                                                        if ($obser=='3'){
                                                            # 2da instancias
                                                            $q_seg=0;
                                                            $q_seg=$cons->cons_cond('plat_est_segunda_inst',"codgrupo='$cod_gru_aux' and codest='$codest1'",'cod');
                                                            $hay=mysql_num_rows($q_seg);
                                                            if ($hay==0) {
                                                                @$q_seg=$cons->cons_cond('plat_est_segunda_inst_espejo',"codgrupo='$cod_gru_aux' and codest='$codest1'",'cod');
                                                                @$hay=mysql_num_rows($q_seg);
                                                            }
                                                            //echo "periodo='$per' and materia='$mat' and codest='$codest1' and parcial='$parcial'";
                                                            if ($hay==0) {
                                                                echo "<font color='red'>No registrado a 2da Instancia</font>";
                                                            }else{
                                                                ?>                                                                
                                                                <div class="course-card-footer">
                                                                    <div align="center">
                                                                        <button  class="btn btn-success btn-sm" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR
                                                                        </button>
                                                                    </div>
                                                                </div>                                                            
                                                                <?php
                                                            }
                                                        }else{
                                                            ?>                                                           
                                                            <div align="center" class="course-card-footer">
                                                                
                                                                   <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                               
                                                            </div>                                                           
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <!--button class="btn btn-success btn-sm" onclick="iniciar_evaluacion('<?php //echo $cod_act; ?>','<?php //echo $id_ban; ?>','<?php //echo $codest1; ?>')">INICIAR 1</button-->
                                            <!--button class="btn btn-success btn-sm" onclick="iniciar_evaluacion_dos('<?php //echo $cod_act; ?>','<?php //echo $id_ban; ?>','<?php //echo $codest1; ?>')">INICIAR</button-->
                                            <?php    
                                        }else{
                                            echo "No esta en hora";
                                        }
                                    }
                                }
                                /*if ($codest1=='43903') {
                                    echo $obserado;
                                }*/
                                ?> 
                                </div>
                            </div>
                        </div>
                    </a>
                </div> 
                  <?php
                    $cod_act=0;
                    $i++;
                    }
                  ?>                                
           </div>
      </div>
   </div>
            <br> 
            <br> 
            <br> 