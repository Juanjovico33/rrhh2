<?php
include "../../includes/conexion.php";

$cod_tarea=$_REQUEST['cod_ta'];
// $archivo=$_REQUEST['mate_apo'];
$cod_est=$_REQUEST['codest'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");

try {
    $nb_file="mate_apo".$cod_tarea;
    if($_FILES[$nb_file]["name"]) {
        $filename = $_FILES[$nb_file]["name"];
        $source = $_FILES[$nb_file]["tmp_name"]; 
    
        //guardar en google ?
        $directorio = 'docs/'.$cod_tarea; 
        if(!file_exists($directorio)){
            mkdir($directorio, 0777, true) or die("No se puede crear el directorio de extracci&oacute;n");    
        } 
        $dir=opendir($directorio); 
        $target_path = $directorio.'/'.$filename;
        if(move_uploaded_file($source, $target_path)) { 

            $q_insertar_archivo="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,'','$target_path','$fecha',$cod_est)";
            $i_resumen = $bdcon->prepare($q_insertar_archivo);
            $i_resumen->execute();

            if ($i_resumen) {
                ?>
                <div class="alert alert-success">
                    <font color="white"><b>Correcto: </b>Archivo enviado correctamente. <?php echo $cod_tarea; ?></font>
                </div>
                <?php
            }else{
                ?>
                <div class="alert alert-error">
                    <font color="white"><b>Error: </b>No se pudo registrar el archivo adjunto, intente nuevamente.</font>
                </div>
                <?php
            }
        } else {    
            echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
        }
        closedir($dir); 
    }
} catch(PDOException $e){
    echo 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
}
?>