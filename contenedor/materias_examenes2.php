<?php
    include "../includes/conexion.php";
    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";
    $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    //$nbges=$fecha->format('Y');
    $nbges='2021';
    $nbmes=$fecha->format('m');
    if ($nbmes<=8) {
    $periodo=$nbges."01";
    $periodoniv=$nbges."06";
    }else{
        $periodo=$nbges."02";
        $periodoniv=$nbges."08";
    }
    function nombre_periodos($nb){
        if($nb=="TUTO"){
            $nbper="CURSO TUTORIAL";
            return $nbper;
        }else{
            $nb1=substr($nb,4,5);
            $ges=substr($nb,0,4);
        }
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
     $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $fec_act=$fecha->format('Y-m-d');
    $hr_act=$fecha->format('H:i:s'); 
    $codest = $_GET['_codest'];
    $estudiante =  new estudiante($codest);    
    $gestion = new gestion();   
    $query_gest= $bdcon->prepare("SELECT id FROM gestion WHERE estado='1'");
    $query_gest->execute();
    while ($row = $query_gest->fetch(PDO::FETCH_ASSOC)) {       
        //$idges=$row['id'];                         
        $idges='32';
    }   
    $query_cod= $bdcon->prepare("SELECT carrera, tipo_pre FROM estudiante_codigo where codigo_estudiante='$codest' order by codigo_estudiante");
    $query_cod->execute();
    $parcial=1;
    while ($fde = $query_cod->fetch(PDO::FETCH_ASSOC)) {       
       $car=$fde['carrera'];      
       $tp=$fde['tipo_pre'];                        
    } 
   
    if ($car=='02AUD' || $car=='02DER' || $car=='02ADM') {
        $gest_ant=$idges-1;
        $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and (gestion='$idges' or gestion='$gest_ant') order by periodo");
        $qrg->execute();
    }else{
        $querysemestre= $bdcon->prepare("SELECT semestre FROM aca_estudiantesemestre where codest='$codest' order by semestre");
        $querysemestre->execute();
        while ($rowsemestre = $querysemestre->fetch(PDO::FETCH_ASSOC)) {       
           $codsemestre=$rowsemestre['semestre']; 
        } 
        if ($car=='01MED' and  $codsemestre=='10') {
            $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='$idges' order by periodo");
            $qrg->execute();
        }else{
            $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and gestion='$idges' and (periodo LIKE '%02' or periodo LIKE '%08' or periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%11' or periodo LIKE '%13' or periodo LIKE '%04' or periodo LIKE '%03' or periodo LIKE '%12') ORDER BY periodo, codmateria");
        //"SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and gestion='$idges' and (periodo LIKE '%02' or periodo LIKE '%08' or periodo LIKE '%03' or periodo LIKE '%04'or periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%05' or periodo LIKE '%07' or periodo LIKE '%11' or periodo LIKE '%12' or periodo LIKE '%13') ORDER BY periodo, codmateria"
        $qrg->execute();
        }
    
    }
        ?>

<div class="section-small">     
    <div align="center">  
        <div class="alert alert-info">
            <strong>¡Atención!</strong><?php echo " Fecha y Hora Actual del Servidor: ".$fec_act." ".$hr_act;?>
        </div>                                               
        <h4> Exámenes Programados </h4>  
    </div>
    <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
    <?php    

    if($codest=='49392' || $codest=='47535' || $codest=='43470' || $codest=='45262' || $codest=='45977' || $codest=='46509' || $codest=='46010' || $codest=='46021' || $codest=='39690' || $codest=='46414' || $codest=='39827' || $codest=='42697' || $codest=='42084' || $codest=='38802' || $codest=='45919' || $codest=='46932'){
        $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='31' and periodo='202004' order by periodo";
    }else if($codest=='44579' || $codest=='46489' || $codest=='46643' || $codest=='46580')
    {
         $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='31' and periodo='202003' order by periodo";
    }else if($codest=='45844' || $codest=='44760' || $codest=='36816' || $codest=='44428' || $codest=='46136' || $codest=='44761')
    {
         $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='32' and periodo='202114' order by periodo";
    }else{
        $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='32' and (periodo='202103' or periodo='202105') order by periodo";
        // periodo='202103' or periodo='202104' or periodo='202105' or periodo='202107' or periodo='202109' or periodo='202111' or periodo='202114' or periodo='202116'
    }
    $q_practicas="SELECT g.periodo, g.CodMateria as codmateria,sub.grupo, sub.idgrupo_padre as idgrupo, sublist.cod_subgru FROM sainc_cloud.grupos_sub_listas as sublist left join sainc_cloud.grupos_sub as sub on (sub.cod=sublist.cod_subgru) left join sainc_cloud.grupos as g on (g.CodGrupo=sub.idgrupo_padre)  where sublist.codest='$codest' and sub.grupo like '%VIRTUAL%'";
    // $q_rg_sub=$bdcon->prepare($q_practicas);
    $q_rg_sub=$bdcon->prepare($q_veranos);
    $q_rg_sub->execute();
    $codgru=0;
    // echo $q_practicas;
    // exit;
    $entra1=0; 
    $entra2=0;              
    while ($fcons = $q_rg_sub->fetch(PDO::FETCH_ASSOC)) {
            $codgru=$fcons['idgrupo'];
            $per=$fcons['periodo'];
            $mat=$fcons['codmateria'];                    
            $gru=$fcons['grupo'];          
            $queryfus= $bdcon->prepare("SELECT codcar_raiz, per_raiz, mat_raiz, gru_raiz FROM grupos_fusionados WHERE codcar_rama='$car' and per_rama='$per' and mat_rama='$mat' and gru_rama='$gru' ORDER BY cod");
            $queryfus->execute();
            if($queryfus->rowCount()>0){
                $fec_ini="";
                $fec_fin="";
                $hr_ini="";
                $hr_fin="";  
                while ($rowfus = $queryfus->fetch(PDO::FETCH_ASSOC)) {
                    $car_ra=$rowfus['codcar_raiz'];
                    $per_ra=$rowfus['per_raiz'];
                    $mat_ra=$rowfus['mat_raiz'];
                    $gru_ra=$rowfus['gru_raiz'];
                }
                $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per_ra' and CodCarrera='$car_ra' and CodMateria='$mat_ra' and Descripcion='$gru_ra'");
                $querygru->execute();
                while ($rowgx = $querygru->fetch(PDO::FETCH_ASSOC)){       
                    $codgru=$rowgx['CodGrupo'];                         
                }
                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per_ra' and parcial='$parcial' and materia='$mat_ra' and grupo='$gru_ra' and carrera='$car_ra'");
                        $q_crono->execute(); 
                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                    $fec_ini=$fcrono['fecha_programada'];
                    $hr_ini=$fcrono['hora'];                   
                }                
                $q_gru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                $q_gru->execute();
                while ($row_gru = $q_gru->fetch(PDO::FETCH_ASSOC)){       
                    $cod_original=$row_gru['CodGrupo'];                         
                }  
                $cod_gru_aux=$cod_original; 
                $fusion=1;                              
            }else{
                $fec_ini="";
                $fec_fin="";
                $hr_ini="";
                $hr_fin=""; 
                $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                $querygru->execute();
                while ($rowgx = $querygru->fetch(PDO::FETCH_ASSOC)) {       
                    $codgru=$rowgx['CodGrupo'];                         
                }                   
                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                $q_crono->execute(); 
                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)) { 
                    $fec_ini=$fcrono['fecha_programada'];
                    $hr_ini=$fcrono['hora'];
                }
                $cod_gru_aux=$codgru;                         
            }
            $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$codgru' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
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
       <div>
       <!--<?php //echo $codgru; ?>-->
        <a href="#">
            <div class="course-card">                                 
                <div class="course-card-body" align="center">
                    <i class="icon-feather-edit skill-card-icon" style="color:#64d25d"></i>                     
                    <p>
                     <?php echo "<h6>".nombre_periodos($per)."</h6>";?> 
                    <strong> 
                        <?php 
                            $qnbmat= $bdcon->prepare("SELECT Descripcion FROM materias WHERE CodCarrera='$car' and Sigla='$mat' and sigla_pensum<>''");
                            $qnbmat->execute();
                            while ($romats= $qnbmat->fetch(PDO::FETCH_ASSOC)) {       
                                $cons=$romats['Descripcion'];                         
                            } 
                            echo $mat." - ".$cons;
                            //echo "<br>";
                            // echo $cod_gru_aux;
                        ?> 
                    </strong>
                    </p>
            <p>Grupo - <?php echo $gru; ?></p>
            <p> 
                <?php                                               
                    if ($fec_ini=='') {
                        echo "<p style='color:red;'>No programado</p>";  
                    }else{
                        echo "<p style='color:green;'><strong>".$fec_ini."</strong></p>";
                    }                 
                    if ($hr_ini=='') {
                        echo "";
                    }else{
                        echo "<p style='color:green;'><strong> Hr. inicio: ".substr($hr_ini,0,5)."</strong></p>";
                    }
                ?>
            </p>
            <p>
            <?php             
                if ($fec_ini==$fec_act) {                   
                    if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                        if ($id_ban=='0') {
                            echo "<small>No tiene un banco de preguntas asignado.</small>";
                        }else{                          
                            $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$cod_gru_aux' and parcial='$parcial' and codest='$codest' and estado='1'");
                            $queryt->execute();                           
                            if($queryt->rowCount()>0){
                                echo "<font color='green'>Evaluación realizada</font>";
                            }else{ 
                                if ($obser=='2') {                                       
                                    $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$mat' and codest='$codest' and parcial='$parcial'");
                                    $q_rep->execute();
                                    if($q_rep->rowCount()>0){
                                        ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php
                                    }else{
                                        echo "<font color='red'>No reprogramado</font>";
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
                    }else{
                        $actividad=$q_act->rowCount();
                        if ($actividad==0) {
                             echo "<strong style='color:red;'>No tiene actividad</strong>";
                        }else{
                             echo "<font>No esta en hora</font>";
                        }                            
                    }                                           
                } 
                ?>
            </p>        
    <?php
    $parcial=1;
    // $parcial=10;
    ?>
    </div> 
    </div>
    </a>
  </div> 
</div>



<?php
    }
    
        $q_plancontado=$bdcon->prepare("SELECT codest from all_stars where codest='$codest' and estado='1'");
        $q_plancontado->execute();
        $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest'");
        $q_plan->execute();
        if($q_plancontado->rowCount()>0 || $q_plan->rowCount()>0){
        }else{
            if ($car=='02AUD' || $car=='02DER' || $car=='02ADM') {
                //no hacer nada
            }else{
                /////////////************************ para verificar si esta habilitado el chic@ *****************///////////////
                $q_monto= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc");
                $q_monto->execute();
                $q_montoniv= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodoniv' and tipodeuda<> '75' order by cod_reg Desc");
                $q_montoniv->execute();            
                $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest'");
                $q_plan->execute(); 
                $_qnormal="";
                $_qnivelacion="";
                if($q_monto->rowCount()>0){ 
                        $q_colegiatura= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc");
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
                        $pagar=$colemenos*1;
                        $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38'");
                        $q_colepagado->execute();
                        while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                            $pagadocole=$rcole['pagado'];
                        }
                        if ($pagadocole>=$pagar) {
                            $_qnormal="periodo LIKE '%01' or periodo LIKE '%02' or";
                        }else{
                            ?>
                            </div>
                            <br>
                            <div class="alert alert-danger" align="center"><strong>¡NO estas Habilitad@! para el periodo Normal I/2021</strong><br></div>                    
                            
                            <div align="center"><button class="btn btn-success" type="button" onclick="estado_pago('<?php echo $codest; ?>')">Verificar estado de cuenta</button></div>
                            <?php
                            $_qnormal="";
                            // exit();
                        }            
                }
                if($q_monto->rowCount()>0){
                }else{
                    if($q_montoniv->rowCount()>0){
                        $n_cuotitasniv=0;
                        $nivsumacuotapagada=0;
                        $q_colegiaturaniv= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodoniv' and tipodeuda<> '75' order by cod_reg Desc");
                        $q_colegiaturaniv->execute(); 
                        $montocoleniv=0;                                    
                        while ($rowcole= $q_colegiaturaniv->fetch(PDO::FETCH_ASSOC)){
                            $tipocoleniv=$rowcole['tipodeuda'];
                            $montosssniv=$rowcole['monto'];                                          
                            $montocoleniv=$montocoleniv+$montosssniv;                   
                        }                                      
                        $colemenosniv=$montocoleniv/4;
                        $pagarniv=$colemenosniv*4;
                        $q_verifuotaniv= $bdcon->prepare("SELECT sum(bolivianos) as totalniv FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213'");
                        $q_verifuotaniv->execute(); 
                        while ($rowcole= $q_verifuotaniv->fetch(PDO::FETCH_ASSOC)){
                            $cuotapagada=$rowcole['totalniv'];
                        }
                        if ($cuotapagada>=$pagarniv) {
                            $_qnivelacion="periodo LIKE '%06' or periodo LIKE '%08' or ";
                            # code...
                        }else{
                             echo "<div class='alert alert-danger' align='center'><strong>¡No Habilitad@! para el periodo Nivelación II/2020</strong><br></div>";
                             ?>
                            <div align="center"><button class="btn btn-success" type="button" onclick="estado_pago('<?php echo $codest; ?>')">Verificar estado de cuenta</button></div>
                            <?php
                            $_qnivelacion="";
                            // exit();
                        }
                    }
                }?>
                <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid><?php
                $q_cond="SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and gestion='$idges' and ($_qnormal $_qnivelacion periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%11' or periodo LIKE '%13' or periodo LIKE '%04' or periodo LIKE '%03' or periodo LIKE '%12') ORDER BY periodo, codmateria";
                $qrg=$bdcon->prepare($q_cond);
                $qrg->execute();
            }
      }  
        $per_aux="";                                                   
        $cod_gru_aux=0;
        $fusion=0; 
   
    $entra1=0; 
    $entra2=0;              
    while ($fcons = $qrg->fetch(PDO::FETCH_ASSOC)) {                           
            $per=$fcons['periodo'];
            $mat=$fcons['codmateria'];                    
            $gru=$fcons['grupo'];          
            $queryfus= $bdcon->prepare("SELECT codcar_raiz, per_raiz, mat_raiz, gru_raiz FROM grupos_fusionados WHERE codcar_rama='$car' and per_rama='$per' and mat_rama='$mat' and gru_rama='$gru' ORDER BY cod");
            $queryfus->execute();
            if($queryfus->rowCount()>0){
                $fec_ini="";
                $fec_fin="";
                $hr_ini="";
                $hr_fin="";  
                while ($rowfus = $queryfus->fetch(PDO::FETCH_ASSOC)) {
                    $car_ra=$rowfus['codcar_raiz'];
                    $per_ra=$rowfus['per_raiz'];
                    $mat_ra=$rowfus['mat_raiz'];
                    $gru_ra=$rowfus['gru_raiz'];
                }
                $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per_ra' and CodCarrera='$car_ra' and CodMateria='$mat_ra' and Descripcion='$gru_ra'");
                $querygru->execute();
                while ($rowgx = $querygru->fetch(PDO::FETCH_ASSOC)){       
                    $codgru=$rowgx['CodGrupo'];                         
                }
                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per_ra' and parcial='$parcial' and materia='$mat_ra' and grupo='$gru_ra' and carrera='$car_ra'");
                        $q_crono->execute(); 
                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)){ 
                    $fec_ini=$fcrono['fecha_programada'];
                    $hr_ini=$fcrono['hora'];                   
                }                
                $q_gru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                $q_gru->execute();
                while ($row_gru = $q_gru->fetch(PDO::FETCH_ASSOC)){       
                    $cod_original=$row_gru['CodGrupo'];                         
                }  
                $cod_gru_aux=$cod_original; 
                $fusion=1;                              
            }else{
                $fec_ini="";
                $fec_fin="";
                $hr_ini="";
                $hr_fin=""; 
                $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                $querygru->execute();
                while ($rowgx = $querygru->fetch(PDO::FETCH_ASSOC)) {       
                    $codgru=$rowgx['CodGrupo'];                         
                }                   
                $q_crono= $bdcon->prepare("SELECT hora, fecha_programada FROM aca_cronograma WHERE periodo='$per' and parcial='$parcial' and materia='$mat' and grupo='$gru' and carrera='$car'");
                $q_crono->execute(); 
                while ($fcrono= $q_crono->fetch(PDO::FETCH_ASSOC)) { 
                    $fec_ini=$fcrono['fecha_programada'];
                    $hr_ini=$fcrono['hora'];
                }
                $cod_gru_aux=$codgru;                         
            }
            $q_act= $bdcon->prepare("SELECT id, desde, hasta, hora_d, hora_h, id_cat, parcial, obser FROM plat_doc_actividades WHERE idgrupo='$codgru' and (desde<='$fec_act' and hasta>='$fec_act') ORDER BY id");
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
  
       <div>
       <!--<?php //echo $codgru; ?>-->
        <a href="#">
            <div class="course-card">                                 
                <div class="course-card-body" align="center">
                    <i class="icon-feather-edit skill-card-icon" style="color:#64d25d"></i>                     
                    <p>
                     <?php echo "<h6>".nombre_periodos($per)."</h6>";?> 
                    <strong> 
                        <?php 
                            $qnbmat= $bdcon->prepare("SELECT Descripcion FROM materias WHERE CodCarrera='$car' and Sigla='$mat' and sigla_pensum<>''");
                            $qnbmat->execute();
                            while ($romats= $qnbmat->fetch(PDO::FETCH_ASSOC)) {       
                                $cons=$romats['Descripcion'];                         
                            } 
                            echo $mat." - ".$cons;
                            //echo "<br>";
                            // echo $cod_gru_aux;
                        ?> 
                    </strong>
                    </p>
            <p>Grupo - <?php echo $gru; ?></p>
            <p> 
                <?php                                               
                    if ($fec_ini=='') {
                        echo "<p style='color:red;'>No programado</p>";  
                    }else{
                        echo "<p style='color:green;'><strong>".$fec_ini."</strong></p>";
                    }                 
                    if ($hr_ini=='') {
                        echo "";
                    }else{
                        echo "<p style='color:green;'><strong> Hr. inicio: ".substr($hr_ini,0,5)."</strong></p>";
                    }
                ?>
            </p>
            <p>
            <?php             
                if ($fec_ini==$fec_act) {                   
                    if ( ($hr_ini<=$hr_act) && ($hr_fin>=$hr_act) ) { 
                        if ($id_ban=='0') {
                            echo "<small>No tiene un banco de preguntas asignado.</small>";
                        }else{                          
                            $queryt= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$cod_gru_aux' and parcial='$parcial' and codest='$codest' and estado='1'");
                            $queryt->execute();                           
                            if($queryt->rowCount()>0){
                                echo "<font color='green'>Evaluación realizada</font>";
                            }else{ 
                                if ($obser=='2') {                                       
                                    $q_rep= $bdcon->prepare("SELECT cod_repro FROM aca_registroestrepro WHERE periodo='$per' and materia='$mat' and codest='$codest' and parcial='$parcial'");
                                    $q_rep->execute();
                                    if($q_rep->rowCount()>0){
                                        ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php
                                    }else{
                                        echo "<font color='red'>No reprogramado</font>";
                                    }
                                }else{
                                    if ($per=='202114') {
                                        $monto=0;
                                        $pagado=0;
                                        $query_regpre= $bdcon->prepare("SELECT monto FROM aca_registroestgest WHERE periodo='$per' and codest='$codest'");
                                        $query_regpre->execute();
                                        while ($row_adm= $query_regpre->fetch(PDO::FETCH_ASSOC)) {       
                                            $monto=$row_adm['monto'];                                              
                                        } 
                                        $query_pagadoad= $bdcon->prepare("SELECT bolivianos FROM ca_diario WHERE semestre='$per' and codest='$codest'");
                                        $query_pagadoad->execute();
                                        while ($row_admpaga= $query_pagadoad->fetch(PDO::FETCH_ASSOC)){       
                                            $pagado=$row_admpaga['bolivianos'];                         
                                        }
                                        if($pagado==0 || $pagado==""){
                                           echo "<font color='red'>No habilitado</font>";      
                                        }else{
                                            if ($monto==$pagado) {
                                                ?>
                                                    <div align="center" class="course-card-footer">
                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                    </div>
                                                <?php
                                            }else{
                                                 echo "<font color='red'>No habilitado</font>";
                                            }                                            
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
                    }else{
                        $actividad=$q_act->rowCount();
                        if ($actividad==0) {
                             echo "<strong style='color:red;'>No tiene actividad</strong>";
                        }else{
                             echo "<font>No esta en hora</font>";
                        }                            
                    }                                           
                } 
                ?>
            </p>        
    <?php
    $parcial=1;
    ?>
    </div> 
</div>
</a>
</div>  
<?php
    }
    ?>
    <!-- <div align="center">                               
        <h4> Exámenes de prácticas I/2020</h4>  <BR>
    </div> -->

    
 </div>
 