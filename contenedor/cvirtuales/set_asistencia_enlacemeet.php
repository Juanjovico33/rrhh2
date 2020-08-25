<?php
    include "../../includes/conexion.php";
    include "../../includes/_grupo.php";
    
    $id_grupo=$_POST['_idgrupo'];
    $estudiante=$_POST['_codest'];
    $grupo=new grupo();
    $grupo->getDatosGrupo($id_grupo);

    $carrera=$grupo->getIdcarrera();
    $docente=$grupo->getIddocente();
    $periodo=$grupo->getPeriodo();
    $materia=$grupo->getIdmateria();
    $g_descripcion=$grupo->getGrupo();

    // $fecha=date("Y-m-d");
    // $hora=date("H:i:s");
    
    $msj='';

    try{
        $q="INSERT INTO `aca_asistencia`(`id_asis`,`periodo`,`materia`,`grupo`,`codest`,`fecha`,`asistencia`,`hora`,`cod_doc`,`tipo_clase`,`turno`,`carrera`,`codgrupo`,`cod_subgru`)VALUES(0,'$periodo','$materia','$g_descripcion',$estudiante,DATE_FORMAT(now(),'%Y-%m-%d'),1,DATE_FORMAT(now(),'%H:%i:%s'),$docente,1,1,'$carrera',$id_grupo,0)";
        // echo $q;
        // exit;
        $resul = $bdcon->prepare($q);
        $resul->execute(); 
        if($resul){
            $msj='Se registró correctamente la asistencia!.';
        }else{
            $msj='Error: no se pudo registrar su asistencia. Intente ingresar nuevamente.';
        }
    }catch(Exception $e){
        $msj.= $e->getMessage();
    }
    echo $msj;
?>