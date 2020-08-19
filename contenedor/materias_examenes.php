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
    $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $fec_act=$fecha->format('Y-m-d');
    $hr_act=$fecha->format('H:i');   
?>      
        <div class="panel-body">
         <div class="section-small"> 
          <div align="center" class="col-md-12">
            <div class="alert alert-warning">
                <strong>¡Atención!</strong><br>
                Para los exámenes de verano, invierno, recuperatorio e intensivo ingresar por el siguiente enlace<br>               
                <a href="http://190.186.233.212/sainc/admin-est/" target="_blank" ><button type="button" class="btn btn-success btn-lg">INGRESAR</button></a>
            </div>                
           </div> 
      </div>
   </div>
        