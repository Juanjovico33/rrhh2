<?php
 
    include "../includes/conexion.php";
    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";    
    include "../includes/_regExcepciones.php";

    $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    //$nbges=$fecha->format('Y');
    $nbges='2022';
    $nbmes=$fecha->format('m');
    $ctrl_internado=true;
    if ($nbmes<8) {
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
                $nbper="ADMISION INTERNADO ROTATORIO ".$ges;
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
    $excepciones = new reg_excepciones();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $fec_act=$fecha->format('Y-m-d');
    $hr_act=$fecha->format('H:i:s'); 
    $codest = $_GET['_codest'];

    $query_gest= $bdcon->prepare("SELECT id FROM gestion WHERE estado='1'");
    $query_gest->execute();
    while ($row = $query_gest->fetch(PDO::FETCH_ASSOC)) {       
        $idges=$row['id']; // ID DE GESTION ACTUAL                        
    }           
    $nbgrupo="";                          
    $query_nbgru= $bdcon->prepare("SELECT grupo FROM aca_registroestmat WHERE codest='$codest' and (periodo=202201 or periodo=202202)");
    $query_nbgru->execute();
    while ($row25 = $query_nbgru->fetch(PDO::FETCH_ASSOC)) {
            $nbgrupo=$row25['grupo']; 
    }
    $cuomos=0;

    $querysemestre= $bdcon->prepare("SELECT semestre FROM aca_estudiantesemestre where codest='$codest' order by semestre");
    $querysemestre->execute();
    while ($rowsemestre = $querysemestre->fetch(PDO::FETCH_ASSOC)) {       
        $codsemestre=$rowsemestre['semestre']; 
    } 

    $query_cod= $bdcon->prepare("SELECT carrera, tipo_pre FROM estudiante_codigo where codigo_estudiante='$codest' order by codigo_estudiante");
    $query_cod->execute();
    while ($fde = $query_cod->fetch(PDO::FETCH_ASSOC)) {       
       $car=$fde['carrera'];      
       $tp=$fde['tipo_pre'];                        
    } 

    $q_TCarrera= $bdcon->prepare("SELECT Titulo FROM carreras where Codigo='$car' group by Titulo");
    $q_TCarrera->execute();
    while ($rcarr = $q_TCarrera->fetch(PDO::FETCH_ASSOC)) {       
       $carrera=$rcarr['Titulo'];      
    } 

    // if($nbgrupo=="TS" <-ANTES
    if(($car=="01MED" || $car=="03MED") && $codsemestre=="10"){
        $parcial=4;
        $cuomos=4;
    }else if($car=="03ENF" && ($codsemestre=="7")){
        $parcial=4;
        $cuomos=4;
    }else if($car=="03ODT" && ($codsemestre=="7" || $codsemestre=="9")){
        $parcial=4;
        $cuomos=4;
    }else if($car=="03BYF" && $codsemestre=="3" && $nbgrupo=="TS"){
        $parcial=4;
        $cuomos=4;
    }else{
        $parcial=4;
        $cuomos=4;
    }

    $car_antigua= $car;
    if ($car=='02AUD' || $car=='02DER' || $car=='02ADM') {
        $gest_ant=$idges-1;
        $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and (gestion='$idges' or gestion='$gest_ant' or gestion=33) order by periodo");
        $qrg->execute();
    }
    ?>
<div class="section-small">     
    <div align="center">  
        <div class="alert alert-info">
            <strong>¡Atención!</strong><?php echo " Fecha y Hora Actual del Servidor: ".$fec_act." ".$hr_act;?> Controlando cuota N° <?php echo $cuomos;?>
        </div>                                               
        <h4> Exámenes Programados </h4>  
    </div>
    <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
    <?php    

    // if($codest=='46328')    {
    //     //Excepciones para VERANO/INVIERNO 2020
    //      $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='31' and (periodo='202003' OR periodo='202004') order by periodo";
    // } else if($codest=='46096')    {
    //     //Excepciones para II/2021
    //      $q_veranos="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest='$codest' and gestion='32' and periodo='202102' order by periodo";
    // } else {
    //     goto excepciones;
    // }

    $ex_periodos=$excepciones->getRegExcepcionEst_periodos($codest);
    $cantidad_excepciones=0;
    if(isset($ex_periodos)){
        $cantidad_excepciones=count($ex_periodos);
    }
    // echo count($ex_periodos);
    if ($cantidad_excepciones>0){
        $q_excepciones=$excepciones->generarConsulta();
        // echo $q_excepciones;
    }else{
        goto excepciones;
    }
  
    $q_rg_sub=$bdcon->prepare($q_excepciones);
    $q_rg_sub->execute();

    $codgru=0;
    $entra1=0; 
    $entra2=0;

    // BUCLE DE EXCEPCIONES VERANOS Y OTROS
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

            if($gru=="TS"){
                $q_gru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE (periodo='$per' or periodo=202303) and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
            }else{
                $q_gru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
            }
            
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
            $fusion=0; 
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

                            $qnbmat= $bdcon->prepare("SELECT Descripcion FROM materias WHERE (CodCarrera='$car' or CodCarrera='$car_antigua') and Sigla='$mat' and sigla_pensum<>''");
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
                                if($parcial=='9'){
                                    $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$cod_gru_aux' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                    $_inv= $bdcon->prepare($q_inv);
                                    $_inv->execute();
                                    if($_inv->rowCount()>0){
                                        echo "<font color='green'>Evaluación realizada</font>";
                                    }else{
                                        ?>
                                        <font color="#00aae4">Investigación</font>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php
                                    }
                                }else{
                                    echo "<font color='green'>Evaluación realizada</font>";
                                }
                            }else{ 
                                if ($obser=='2') {                                       
                                    $q_rep= $bdcon->prepare("SELECT cod_repro, costo, periodo FROM aca_registroestrepro WHERE periodo='$per' and materia='$mat' and codest='$codest' and parcial='$parcial'");
                                    $q_rep->execute();
                                    $_reproapagar=0;
                                    $r_per=0;
                                    while ($reg_rep = $q_rep->fetch(PDO::FETCH_ASSOC)){
                                        $_reproapagar = $_reproapagar + $reg_rep['costo'];
                                        $r_per = $reg_rep['periodo'];
                                    }

                                    $q_pagorepro = $bdcon->prepare("SELECT SUM(t10.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario where codest=$codest and semestre=$r_per and cuenta=75 and codmateria='$mat' and nro_cuota=$parcial UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco where codest=$codest and semestre=$r_per and cuenta=75 and codmateria='$mat' and nro_cuota=$parcial) t10");
                                    $q_pagorepro->execute();
                                    $pagos_rep = $q_pagorepro->fetch(PDO::FETCH_ASSOC);
                                    $pagado_rep = $pagos_rep['pagado'];

                                    // verificar pago repro
                                    if($q_rep->rowCount()>0){
                                        $texto="";
                                        if($parcial=="9"){
                                            $texto="<font color='#00aae4'>Investigación</font>";
                                        }
                                        if($ctrl_internado && ($pagado_rep >= $_reproapagar)){
                                        ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php }else{
                                            $texto="<font color='red'>Reprogramación no habilitada por falta de pago</font><br>";
                                        }
                                        echo $texto;
                                    }else{
                                        echo "<font color='red'>No reprogramado</font>";
                                    }
                                }else{
                                    $texto="";
                                        if($parcial=="9"){
                                            $texto="<font color='#00aae4'>Investigación</font>";
                                        }
                                        echo $texto;
                                        if($ctrl_internado){
                                    ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                    <?php }                             
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
        if(($car=="01MED" || $car=="03MED") && $codsemestre=="10"){
            $parcial=4;
        }else if($car=="03ENF" && $codsemestre=="7"){
            $parcial=4;
        }else if($car=="03ODT" && ($codsemestre=="7" || $codsemestre=="9")){
            $parcial=4;
        }else if($car=="03BYF" && $codsemestre=="3" && $nbgrupo=="TS"){
            $parcial=4;
        }else{
            $parcial=4;
        }
        ?>
        </div>
        </div>
        </a>
    </div> 
    <?php
    }
    excepciones:
    // FIN BUCLE DE EXCEPCIONES VERANOS Y OTROS
    

    ///////*********************************MODIFICAR DESDE AQUI POR PARCIAL Y CUOTA/////////////*********************
        $q_plancontado=$bdcon->prepare("SELECT codest from all_stars where codest='$codest' and estado='1'");
        $q_plancontado->execute();
        $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest' and monto>0");
        $q_plan->execute();
        if($q_plancontado->rowCount()>0 || $q_plan->rowCount()>0){
            if ($car=='02AUD' || $car=='02DER' || $car=='02ADM') {}else{
                $qrg=$bdcon->prepare("SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and (gestion='$idges') and (periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%11' or periodo LIKE '%13' or periodo LIKE '%04' or periodo LIKE '%03' or periodo LIKE '%12' or periodo LIKE '%02' or periodo LIKE '%08' or periodo LIKE '%07' or periodo LIKE '%05' or periodo LIKE '%15'  or periodo LIKE '%14') ORDER BY periodo, codmateria"); 
                // periodo LIKE '%01' or periodo LIKE '%06' or 
                $qrg->execute(); 
            }
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
                        $tienetallerdegrado=0;
                        while ($rowcole= $q_colegiatura->fetch(PDO::FETCH_ASSOC)) {
                            $tipocole=$rowcole['tipodeuda'];
                            $montosss=$rowcole['monto'];
                            if ($car=='01BYF' || $car=='01ENF') {
                                if ($tipodeuda==146) {
                                    # taller de grado
                                    $tienetallerdegrado=1;
                                }
                            }
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
                        $pagar=$colemenos*$cuomos;

                        $q_colepagado= $bdcon->prepare("SELECT SUM(t1.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='38') t1");
                        $q_colepagado->execute();
                        while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                            $pagadocole=$rcole['pagado'];
                        }
                        if ($pagadocole>=$pagar) {
                            //HABILITADO PARA I Y II
                            if ($tienetallerdegrado==1) {
                                //"TIENE TALLER DE GRADO";
                                 $q_taller= $bdcon->prepare("SELECT SUM(t2.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='146' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='146') t2");
                                 $q_taller->execute();
                                 while ($rtaller= $q_taller->fetch(PDO::FETCH_ASSOC)) {
                                     $pagadotaller=$rtaller['pagado'];
                                 }                                             
                                 $pagotaller=2970 - $pagadotaller;
                                 if ($pagotaller<=0) {
                                     $_qnormal="periodo LIKE '%01' or periodo LIKE '%02' or"; // periodo LIKE '%01' or 
                                 }else{
                                    ?>
                                        <br>
                                        </div>
                                        <div class="alert alert-danger" align="center"><strong>¡NO estas Habilitad@! para el periodo Normal II/2022</strong><br></div>                    
                                        
                                        <div align="center"><button class="btn btn-success" type="button" onclick="estado_pago('<?=$codest?>', '<?=$codsemestre?>', '<?=$carrera?>')">Verificar estado de cuenta</button></div><br>
                                        <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                                    <?php
                                    $_qnormal="";
                                 }
                             }else{
                                 $_qnormal="periodo LIKE '%01' or periodo LIKE '%02' or"; // periodo LIKE '%01' or p
                             }
                        }else{
                            ?>
                            
                            <br>
                            </div>
                            <div class="alert alert-danger" align="center"><strong>¡NO estas Habilitad@! para el periodo Normal II/2022</strong><br></div>                    
                            
                            <div align="center"><button class="btn btn-success" type="button" onclick="estado_pago('<?=$codest?>', '<?=$codsemestre?>', '<?=$carrera?>')">Verificar estado de cuenta</button></div><br>
                            <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                            <?php
                            $_qnormal="";
                            // exit;
                        }            
                }
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
                        $pagarniv=$colemenosniv*$cuomos;
                        
                        $q_verifuotaniv= $bdcon->prepare("SELECT SUM(t3.totalniv) totalniv FROM (SELECT sum(bolivianos) as totalniv FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213' UNION ALL SELECT sum(bolivianos) as totalniv FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213') t3");
                        $q_verifuotaniv->execute(); 
                        while ($rowcole= $q_verifuotaniv->fetch(PDO::FETCH_ASSOC)){
                            $cuotapagada=$rowcole['totalniv'];
                        }
                        if ($cuotapagada>=$pagarniv) {
                            $_qnivelacion="periodo LIKE '%06' or periodo LIKE '%08' or "; // periodo LIKE '%08' or 
                        }else{
                            if($car=="03BYF" && $nbgrupo=="TS" && ($codsemestre=="6" || $codsemestre=="5")){
                                $_qnivelacion="periodo LIKE '%06' or periodo LIKE '%08' or "; // periodo LIKE '%08' or
                            }else{
                                echo "</div><div class='alert alert-danger' align='center'><strong>¡No Habilitad@! para el periodo Nivelación II/2022</strong><br></div>";
                                ?>
                                <div align="center"><button class="btn btn-success" type="button" onclick="estado_pago('<?=$codest?>', '<?=$codsemestre?>', '<?=$carrera?>')">Verificar estado de cuenta</button></div>
                                <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                                <?php
                                $_qnivelacion="";
                            }
                            // exit;
                        }
                    }
                ?>
        <?php
                if ($car=='01MED' && $codsemestre=='10') {
                    $q_cond="SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and (gestion='$idges') and ($_qnormal $_qnivelacion periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%11' or periodo LIKE '%13' or periodo LIKE '%04' or periodo LIKE '%03' or periodo LIKE '%12' or periodo LIKE '%15' or periodo LIKE '%05' or periodo LIKE '%07' or periodo LIKE '%14' or periodo LIKE '%16') ORDER BY periodo, codmateria";
                }else{
                    $q_cond="SELECT periodo, codmateria, grupo, idgrupo FROM aca_registroestmat WHERE codest='$codest' and (gestion='$idges') and ($_qnormal $_qnivelacion periodo LIKE '%09' or periodo LIKE '%10' or periodo LIKE '%11' or periodo LIKE '%13' or periodo LIKE '%04' or periodo LIKE '%03' or periodo LIKE '%12' or periodo LIKE '%07' or periodo LIKE '%05') ORDER BY periodo, codmateria";
                }             
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

            // SI PERIODO ES ADMINISION DE INTERNADO ROTATORIO
            $controlando="";
            if($per==202215){
                $pago_internado=0;

                $q_internado="SELECT SUM(t1.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest=$codest and semestre=$per and (cuenta=118 or cuenta=341 or cuenta=340 or cuenta=342 or cuenta=378 or cuenta=534) UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest=$codest and semestre=$per and (cuenta=118 or cuenta=341 or cuenta=340 or cuenta=342 or cuenta=378 or cuenta=534)) t1";
                //341-340-342-378-534
                $q_pago_internado=$bdcon->prepare($q_internado);
                $q_pago_internado->execute();
                while ($rinter= $q_pago_internado->fetch(PDO::FETCH_ASSOC)) {
                    $pago_internado=$rinter['pagado'];
                }

                if($pago_internado<800){
                    $controlando="Bloqueado por pago<br>";
                    $ctrl_internado=false;
                }                            
            }

            // VER SI TIENE FUSION
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
                if($gru=="TS"){
                    $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE (periodo='$per' or periodo=202303) and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'");
                }else{
                   $querygru= $bdcon->prepare("SELECT CodGrupo FROM grupos WHERE periodo='$per' and CodCarrera='$car' and CodMateria='$mat' and Descripcion='$gru'"); 
                }
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
                $fusion=0; 
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
                            $qnbmat= $bdcon->prepare("SELECT Descripcion FROM materias WHERE (CodCarrera='$car' or CodCarrera='$car_antigua') and Sigla='$mat' and sigla_pensum<>''");
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
                        echo "<p style='color:red;'>".$controlando."No programado</p>";  
                    }else if($controlando!=""){
                        echo "<p style='color:red;'><strong>".$controlando."</strong></p>";
                        echo "<p style='color:green;'><strong>".$fec_ini."</strong></p>";
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
                                if($parcial=='9'){
                                    $q_inv="SELECT reco FROM plat_doc_intentos_est WHERE codgrupo='$cod_gru_aux' and parcial='$parcial' and codest='$codest' and fecha='$fec_act' and estado='1'";
                                    $_inv= $bdcon->prepare($q_inv);
                                    $_inv->execute();
                                    if($_inv->rowCount()>0){
                                        echo "<font color='green'>Evaluación realizada</font>";
                                    }else{
                                        ?><font color="#00aae4">Investigación</font>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php
                                    }
                                }else{
                                    echo "<font color='green'>Evaluación realizada</font>";
                                }
                            }else{ 
                                if ($obser=='2') {                                       
                                    $q_rep= $bdcon->prepare("SELECT cod_repro, costo, periodo FROM aca_registroestrepro WHERE periodo='$per' and materia='$mat' and codest='$codest' and parcial='$parcial'");
                                    $q_rep->execute();
                                    $_reproapagar=0;
                                    $r_per=0;
                                    while ($reg_rep = $q_rep->fetch(PDO::FETCH_ASSOC)){
                                        $_reproapagar = $_reproapagar + $reg_rep['costo'];
                                        $r_per = $reg_rep['periodo'];
                                    }

                                    $q_pagorepro = $bdcon->prepare("SELECT SUM(t10.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario where codest=$codest and semestre=$r_per and cuenta=75 and codmateria='$mat' and nro_cuota=$parcial UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco where codest=$codest and semestre=$r_per and cuenta=75 and codmateria='$mat' and nro_cuota=$parcial) t10");
                                    $q_pagorepro->execute();
                                    $pagos_rep = $q_pagorepro->fetch(PDO::FETCH_ASSOC);
                                    $pagado_rep = $pagos_rep['pagado'];

                                    // verificar pago repro
                                    if($q_rep->rowCount()>0){
                                        $texto="";
                                        if($parcial=="9"){
                                            $texto="<font color='#00aae4'>Investigación</font>";
                                        }

                                        if($ctrl_internado && ($pagado_rep >= $_reproapagar)){
                                        ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php }else{
                                            $texto="<font color='red'>Reprogramación no habilitada por falta de pago</font><br>";
                                        }
                                        echo $texto;
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
                                        $query_pagadoad= $bdcon->prepare("SELECT SUM(t4.bolivianos) bolivianos FROM (SELECT bolivianos FROM ca_diario WHERE semestre='$per' and codest='$codest' UNION ALL SELECT bolivianos FROM ca_diario_banco WHERE semestre='$per' and codest='$codest') t4");
                                        $query_pagadoad->execute();
                                        while ($row_admpaga= $query_pagadoad->fetch(PDO::FETCH_ASSOC)){       
                                            $pagado=$row_admpaga['bolivianos'];                         
                                        }
                                        if($pagado==0 || $pagado==""){
                                           echo "<font color='red'>No habilitado</font>";      
                                        }else{
                                            if ($monto==$pagado) {
                                                $texto="";
                                                if($parcial=="9"){
                                                    $texto="<font color='#00aae4'>Investigación</font>";
                                                }
                                                echo $texto;
                                                if($ctrl_internado){
                                                ?>
                                                    <div align="center" class="course-card-footer">
                                                        <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                                    </div>
                                                <?php }
                                            }else{
                                                 echo "<font color='red'>No habilitado</font>";
                                            }                                            
                                        }
                                    }else{
                                        $texto="";
                                        if($parcial=="9"){
                                            $texto="<font color='#00aae4'>Investigación</font>";
                                        }
                                        echo $texto;
                                        if($ctrl_internado){
                                        ?>
                                        <div align="center" class="course-card-footer">                                    
                                            <button class="btn btn-success btn-lg btn-block" onclick="terminos_condiciones('<?php echo $cod_act; ?>','<?php echo $id_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hr_fin; ?>')">INICIAR</button>
                                        </div>
                                        <?php
                                        }
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
        if(($car=="01MED" || $car=="03MED") && $codsemestre=="10"){
            $parcial=4;
        }else if($car=="03ENF" && $codsemestre=="7"){
            $parcial=4;
        }else if($car=="03ODT" && ($codsemestre=="7" || $codsemestre=="9")){
            $parcial=4;
        }else if($car=="03BYF" && $codsemestre=="3" && $nbgrupo=="TS"){
            $parcial=4;
        }else{
            $parcial=4;
        }
        ?>
        </div> 
    </div>
    </a>
    </div>  
    <?php
    }
    ?>

 </div>
 </div>