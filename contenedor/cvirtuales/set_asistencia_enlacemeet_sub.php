<?php
    include "../../includes/conexion.php";
    include "../../includes/_grupo.php";
    include "../../includes/_horarios.php";
    
    $id_grupo=$_POST['_idgrupo'];
    $_idgrupoRaiz=$_POST['_idgruporaiz'];
    $estudiante=$_POST['_codest'];
    $subgrupo=$_POST['_subgrupo'];

    $grupo=new grupo();
    $_otrogrupo=new grupo();
    $horario=new horarios();
    $grupo->getDatosGrupo($id_grupo);

    $grupito=0;

    if($_idgrupoRaiz!=0){
        $grupito=$_idgrupoRaiz;
        $_otrogrupo->getDatosGrupo($_idgrupoRaiz); // Para obtener al docente de la raiz
        $docente=$_otrogrupo->getIddocente();
    }else{
        $grupito=$id_grupo;
        $docente=$grupo->getIddocente();
    }

    $carrera=$grupo->getIdcarrera();
    $docente=$grupo->getIddocente();
    $periodo=$grupo->getPeriodo();
    $materia=$grupo->getIdmateria();
    $g_descripcion=$grupo->getGrupo();
    // $fecha=date("Y-m-d");
    // $hora=date("H:i:s");    
    $msj='';
    if($horario->tiene_asistencia_est_prac($grupito, $estudiante, $id_grupo, $subgrupo)){
        $msj="<div class='alert alert-warning alert-danger'><b>Ya registró su asistencia!!!</b>. No se puede registrar dos veces la asistencia para esta clase.</br>";
    }else{
        try{
            if($horario->enHora_to_Asistencia_subGrupos($subgrupo)){
                $q="INSERT INTO sainc.aca_asistencia_practica(`id_asis`,`periodo`,`materia`,`grupo`,`codest`,`fecha`,`asistencia`,`hora`,`cod_doc`,`tipo_clase`,`turno`,`carrera`,`codgrupo`,`cod_subgru`)VALUES(0,'$periodo','$materia','$g_descripcion','$estudiante',DATE_FORMAT(now(),'%Y-%m-%d'),'1',DATE_FORMAT(now(),'%H:%i:%s'),'$docente','1','1','$carrera','$id_grupo','$subgrupo')";

                $resul = $bdcon->prepare($q);
                $resul->execute(); 

                $horario->registrar_emomentos_est_inv($grupito, $estudiante, $id_grupo, $subgrupo);
                if($resul){
                    $msj="<div class='alert alert-success'><strong>Se registró correctamente la asistencia!.</strong></div>";
                }else{
                    $msj="<div class='alert alert-danger'><strong>Error! no se pudo registrar su asistencia. Intente ingresar nuevamente.</strong></div>";
                }
            }else{
                $msj="<div class='alert alert-warning alert-danger'>No esta en hora para registrar la asistencia, deberá ingresar nuevamente dentro del horario de la clase o 10 minutos antes para poder registrar su asistencia.<br></div>";
            }
            
        }catch(Exception $e){
            $msj.= $e->getMessage();
        }
    }
    echo $msj;
?>