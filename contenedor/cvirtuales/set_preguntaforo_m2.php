<?php
    include "../../includes/conexion.php";
    
    $grupo=$_POST['_grupo'];
    $clase=$_POST['_clase'];
    $estudiante=$_POST['_estudiante'];
    $foro=$_POST['_foro'];
    $pregunta=$_POST['_pregunta'];

    $msj='';

    try{
        $q="INSERT INTO aa_clases_virtuales_m2_preguntas(`cod`,`cod_gru`,`cod_clase`,`cod_foro`,`pregunta`,`tipo`,`codest`) VALUES(0,$grupo, $clase, $foro, '$pregunta', 1,$estudiante)";
        $resul = $bdcon->prepare($q);
        $resul->execute(); 
        if($resul){
            $msj='Se registró correctamente la pregunta!.';
        }else{
            $msj='Error: no se pudo registrar su pregunta, intentelo de nuevo.';
        }
    }catch(Exception $e){
        $msj.= $e->getMessage();
    }
    echo $msj;
    // echo $grupo.'-'.$clase.'-'.$estudiante.'-'.$foro.'-'.$pregunta;
?>