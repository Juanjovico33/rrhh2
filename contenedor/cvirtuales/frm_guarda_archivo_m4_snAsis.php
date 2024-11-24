<?php
include "../../includes/conexion.php";

$cod_tarea=$_REQUEST['cod_ta'];
$grupo=$_REQUEST['_grupo'];
$grupo_raiz=$_REQUEST['_raiz'];
// $archivo=$_REQUEST['mate_apo'];
$cod_est=$_REQUEST['codest'];
$nb_grupo=$_REQUEST['_nbgrupo']; // TS

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
?>