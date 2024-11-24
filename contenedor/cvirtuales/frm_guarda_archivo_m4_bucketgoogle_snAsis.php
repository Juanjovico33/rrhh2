<?php
include "../../includes/_storage.php";
include "../../includes/conexion.php";

$cod_tarea=0;
$cod_est=0;
$cod_tarea=$_POST['cod_ta'];
$grupo=$_REQUEST['_grupo'];
$grupo_raiz=$_REQUEST['_raiz'];
$cod_est=$_POST['codest'];
$nb_file="mate_apo".$cod_tarea;
$nb_grupo=$_REQUEST['_nbgrupo']; // TS

$storage = new storage();

// $directorio_main='estudiantes/files/';
$directorio_main='estudiantes/files/'.$cod_tarea.'/'.$cod_est.'/'.$_FILES[$nb_file]['name'];


// || $nb_grupo=="TS"

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
    }else{
        echo "<div class='alert alert-danger'>Debe de seleccionar un archivo antes de presionar <b>Enviar</b></div>";
    }
} catch(PDOException $e){
    echo 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
}
?>