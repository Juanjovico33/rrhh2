<?php
include "../../includes/conexion.php";
include "../../includes/_horarios.php";

$horario=new horarios();
$cod_tarea=$_REQUEST['cod_ta'];
$grupo=$_REQUEST['_grupo'];
$grupo_raiz=$_REQUEST['_raiz'];
// $archivo=$_REQUEST['mate_apo'];
$cod_est=$_REQUEST['codest'];
$nb_grupo=$_REQUEST['_nbgrupo']; // TS

// if($nb_grupo=="TS"){
//     $id_switch=0;
//     $num_respuesta=1;
// }else{
//     $id_switch=$horario->enable_to_Moments($grupo_raiz, $cod_est, $grupo);
// }
$id_switch=$horario->enable_to_Moments($grupo_raiz, $cod_est, $grupo);
//  || $nb_grupo=="TS"
if ($id_switch!=0){
    // if($nb_grupo!="TS"){
    //     $num_respuesta=$horario->puede_to_M4($id_switch);
    // }
    $num_respuesta=$horario->puede_to_M4($id_switch);
    if ($num_respuesta==1){
        try {
            $nb_file="mate_apo".$cod_tarea;
            if($_FILES[$nb_file]["name"]) {
                $filename = $_FILES[$nb_file]["name"];
                $source = $_FILES[$nb_file]["tmp_name"]; 
            
                //guardar en google ?
                $directorio = 'estudiantes/files/'.$cod_tarea.'/'.$cod_est; 
                if(!file_exists($directorio)){
                    mkdir($directorio, 0777, true) or die("No se puede crear el directorio de extracci&oacute;n");    
                } 
                $dir=opendir($directorio); 
                $target_path = $directorio.'/'.$filename;
                if(move_uploaded_file($source, $target_path)) { 

                    $q_insertar_archivo="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`cod_gru`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,$grupo,'','$target_path',DATE_FORMAT(now(),'%Y-%m-%d'),$cod_est)";
                    $i_resumen = $bdcon->prepare($q_insertar_archivo);
                    $i_resumen->execute();

                    if ($i_resumen) {
                        echo "<div class='alert alert-success'><font color='black'><b>Correcto: </b>Archivo enviado correctamente. <?php echo $cod_tarea; ?></font></div>";
                    }else{
                        echo "<div class='alert alert-danger'><font color='black'><b>Error: </b>No se pudo registrar el archivo adjunto, intente nuevamente.</font></div>";
                    }
                } else {    
                    echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
                }
                closedir($dir); 
            }else{
                echo "<div class='alert alert-danger'>Debe de seleccionar un archivo antes de presionar <b>Enviar</b></div>";
            }
        } catch(PDOException $e){
            echo 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
        }
    }else if($num_respuesta==2){
        echo "<div class='alert alert-warning alert-danger'>No se puede registrar, su asistencia está fuera del tiempo permitido. Para poder participar de los momentos debe de ingresar en los 10 minutos iniciales desde la hora de inicio de su clase.<br></div>";
    }else if($num_respuesta==0){
        echo "<div class='alert alert-warning alert-danger'>No puede registrar porque no tiene registro de asistencia.<br></div>";
    }
}else{
    echo "<div class='alert alert-warning alert-danger'>No puede enviar el momento 4 porque no tiene asistencia a clase.<br></div>";
}
?>