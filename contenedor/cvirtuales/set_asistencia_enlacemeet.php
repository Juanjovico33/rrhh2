<?php
    include "../../includes/conexion.php";
    include "../../includes/_grupo.php";
    include "../../includes/_horarios.php";
    
    $id_grupo=$_POST['_idgrupo'];
    $estudiante=$_POST['_codest'];
    $_idgrupoRaiz=$_POST['_idgruporaiz'];
    $docente=0;

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

    if($docente===''){
        $docente=0;
    }
    $carrera=$grupo->getIdcarrera();
    $periodo=$grupo->getPeriodo();
    $materia=$grupo->getIdmateria();
    $g_descripcion=$grupo->getGrupo();

    // $horario->enHora_to_Asistencia($id_grupo);
    // echo $horario->getError();
    // exit;

    $msj='';
    if ($horario->enHora_to_Asistencia($grupito)){
        if($horario->tiene_asistencia_est($grupito, $estudiante, $id_grupo)){
            $habilitado_momentos="ops!";
            $horario->enable_to_Moments($grupito, $estudiante, $id_grupo);
            if($horario->getIdoMoments()==1){
                $habilitado_momentos="<font color='green'>SI</font>";
            }else{
                $habilitado_momentos="NO";
            }
            $msj="<div class='alert alert-warning alert-danger'><b>Ya registró su asistencia!!!</b>. No se puede registrar dos veces la asistencia para esta clase.</br> Hora registrada de asistencia : <b>".$horario->getHoraRegistrada()."</b>";
            //<br>Habilitado para participar de los momentos : <b>$habilitado_momentos</b>"
        }else{
            try{
                $q="INSERT INTO `aca_asistencia`(`id_asis`,`periodo`,`materia`,`grupo`,`codest`,`fecha`,`asistencia`,`hora`,`cod_doc`,`tipo_clase`,`turno`,`carrera`,`codgrupo`,`cod_subgru`)VALUES(0,'$periodo','$materia','$g_descripcion',$estudiante,DATE_FORMAT(now(),'%Y-%m-%d'),1,DATE_FORMAT(now(),'%H:%i:%s'),$docente,1,1,'$carrera',$id_grupo,0)";
                // exit;
                $resul = $bdcon->prepare($q);
                $resul->execute(); 
                $horario->registrar_emomentos_est($grupito, $estudiante, $id_grupo);
                // echo $horario->getError();
                if($resul){
                    $msj="<div class='alert alert-success'>Se registró correctamente la asistencia!.</div>";
                }else{
                    $msj="<div class='alert alert-warning alert-danger'>No se pudo registrar su asistencia. Intente ingresar nuevamente.</br>";
                }
            }catch(PDOException $e){
                $msj.= $e->getMessage();
            }
        }
    }else{
            //     $msj="No esta en hora para registrar la asistencia de la clase, deberá ingresar nuevamente en el horario de la clase para que se registre su asistencia.";
        $msj="<div class='alert alert-warning alert-danger'>No esta en hora para registrar la asistencia de esta clase, deberá ingresar nuevamente dentro del horario de la clase para que se pueda registrar su asistencia.<br></div>";
    }
    echo $msj.='<br>';
?>