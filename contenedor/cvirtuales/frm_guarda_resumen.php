<?php
    include "../../includes/conexion.php";
    $id_clase=$_POST['_clase'];
    $id_grupo=$_POST['_grupo'];
    $cod_est=$_POST['_estudiante'];
    $resumen=$_POST['_resumen'];
    $video=$_POST['_video'];
    $fecha=date("Y-m-d");
	$hora=date("H:i:s");
    try{
        $q="INSERT INTO `aa_clases_virtuales_resumenes`(`cod`,`cod_clase`,`cod_grupo`,`codest`,`resumen`,`nota`,`fec_reg`,
        `hr_reg`,`cod_vid`)VALUES(0,$id_clase,$id_grupo,$cod_est,'$resumen',0,'$fecha','$hora',$video);";
        // $q_estadoresumen="UPDATE `sainc`.`aa_clases_virtuales_m3` SET `resumen` = '1' WHERE (`cod` = '$video');";
        $resul = $bdcon->prepare($q);
        $resul->execute(); 
        if($resul->rowcount()){
            $q_update_state_resumen="UPDATE `sainc`.`aa_clases_virtuales_m3` SET `resumen` = '1' WHERE (`cod_cla` = '$id_clase' AND `cod_gru` = '$id_grupo')";
            $update = $bdcon->prepare($q_update_state_resumen);
            $update->execute();
            echo "Se registró correctamente el resumen!";
        }else{
            echo "No se pudo realizar el registro, intente nuevamente.";
        }
    }catch(Exception $e){
        echo "Error: ".$e->getMessage();
    }

?>