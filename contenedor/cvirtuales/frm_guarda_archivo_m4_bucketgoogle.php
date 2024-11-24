<?php
include "../../includes/_storage.php";
include "../../includes/conexion.php";
include "../../includes/_horarios.php";

$cod_tarea=0;
$cod_est=0;
$cod_tarea=$_POST['cod_ta'];
$grupo=$_REQUEST['_grupo'];
$grupo_raiz=$_REQUEST['_raiz'];
$cod_est=$_POST['codest'];
$nb_file="mate_apo".$cod_tarea;
$nb_grupo=$_REQUEST['_nbgrupo']; // TS

$horario=new horarios();
$storage = new storage();
// $_fecha=new DateTime();
// $_fecha->setTimezone(new DateTimeZone('America/La_Paz'));

// $directorio_main='estudiantes/files/';
$directorio_main='estudiantes/files/'.$cod_tarea.'/'.$cod_est.'/'.$_FILES[$nb_file]['name'];

// if($nb_grupo=="TS"){
//     $id_switch=0;
//     $num_respuesta=1;
// }else{
//     $id_switch=$horario->enable_to_Moments($grupo_raiz, $cod_est, $grupo);
// }

$id_switch=$horario->enable_to_Moments($grupo_raiz, $cod_est, $grupo);
// || $nb_grupo=="TS"
if ($id_switch!=0){
    // if($nb_grupo!="TS"){
    //     $num_respuesta=$horario->puede_to_M4($id_switch);
    // }
    $num_respuesta=$horario->puede_to_M4($id_switch);
    if ($num_respuesta==1){
        try {
            if(file_exists($_FILES[$nb_file]['tmp_name'])){
                $storage->uploadObject($_FILES[$nb_file]['name'], $_FILES[$nb_file]['tmp_name'], $directorio_main);
                $q_insertar_archivo="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`cod_gru`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,$grupo,'','$directorio_main',DATE_FORMAT(now(),'%Y-%m-%d'),$cod_est)";
                // DATE_FORMAT(now(),'%Y-%m-%d'),1,DATE_FORMAT(now(),'%H:%i:%s')
                $i_resumen = $bdcon->prepare($q_insertar_archivo);
                $i_resumen->execute();

                if ($i_resumen) {
                    echo "<div class='alert alert-success'><font color='black'><b>Correcto: </b>Archivo enviado correctamente. <?php echo $cod_tarea; ?></font></div>";
                }else{
                    echo "<div class='alert alert-danger'><font color='black'><b>Error: </b>No se pudo registrar el archivo adjunto, intente nuevamente.</font></div>";
                }
                // echo '<br>'.$q_insertar_archivo;
                // echo $directorio_main;
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