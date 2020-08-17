<?php
include "../../includes/_storage.php";
include "../../includes/conexion.php";
$cod_tarea=0;
$cod_est=0;
$cod_tarea=$_REQUEST['cod_ta'];
$cod_est=$_REQUEST['codest'];
$nb_file="mate_apo".$cod_tarea;

$storage = new storage();
$_fecha=new DateTime();
$_fecha->setTimezone(new DateTimeZone('America/La_Paz'));

// $directorio_main='estudiantes/files/';
$directorio_main='estudiantes/files/'.$cod_tarea.'/'.$cod_est.'/'.$_FILES[$nb_file]['name'];

$fecha=$_fecha->format('Y-m-d');
$hora=$_fecha->format('H:i:s');

try {
    if(file_exists($_FILES[$nb_file]['tmp_name'])){
        $storage->uploadObject($_FILES[$nb_file]['name'], $_FILES[$nb_file]['tmp_name'], $directorio_main);
        $q_insertar_archivo="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,'','$directorio_main','$fecha',$cod_est)";

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
        // echo '<br>'.$q_insertar_archivo;
        // echo $directorio_main;
    }else{
        echo "Sin archivo<br>";
    }
} catch(PDOException $e){
    echo 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
}
?>