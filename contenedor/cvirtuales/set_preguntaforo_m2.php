<?php
    include "../../includes/conexion.php";
    include "../../includes/_horarios.php";
    
    $grupo=$_POST['_grupo'];
    $clase=$_POST['_clase'];
    $grupo_raiz=$_POST['_idraiz'];
    $estudiante=$_POST['_estudiante'];
    $foro=$_POST['_foro'];
    $pregunta=$_POST['_pregunta'];
    $nb_grupo=$_POST['_nbgrupo']; // TS

        $horario=new horarios();

        $msj='';
        $id_switch=$horario->enable_to_Moments($grupo_raiz, $estudiante, $grupo); //nivel-2/2021
        // si existe id de registro puede realizar los momentos
        if ($id_switch!=0){
            $num_respuesta=$horario->puede_to_M2($id_switch, $clase);
            // echo "id=".$id_switch."/respuesta=".$num_respuesta;
            // echo $id_switch."<br>";
            if ($num_respuesta==1){
                // echo "Registro simulado correcto :)";
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
            }else if($num_respuesta==2){
                $msj="<div class='alert alert-warning alert-danger'>No se puede registrar porque su asistencia está fuera del tiempo permitido. Para poder participar de los momentos debe de ingresar en los 10 minutos iniciales desde la hora de inicio de su clase.<br></div>";
            }else if($num_respuesta==3){
                $msj="<div class='alert alert-warning alert-danger'>No puede registrar porque esta fuera de la fecha permitida para participar.<br></div>";
            }else if($num_respuesta==0){
                $msj="<div class='alert alert-warning alert-danger'>No puede registrar porque no tiene registro de asistencia.<br></div>";
            }else{
                $msj="<div class='alert alert-warning alert-danger'>No se puede registrar porque esta fuera del tiempo permitido.<br></div>";
            }
        }else{
            $msj="<div class='alert alert-warning alert-danger'>No puede participar del momento 2 porque no tiene asistencia a clase.<br></div>";
        }
    echo $msj;
    // echo $grupo.'-'.$clase.'-'.$estudiante.'-'.$foro.'-'.$pregunta;
?>