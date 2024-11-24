<?php
include "../../includes/conexion.php";

$codest=$_REQUEST['codest'];
$periodo=$_REQUEST['periodo'];
$tipodeuda=$_REQUEST['tipodeuda'];
$cuota=$_REQUEST['cuota'];
$nb_file=$_FILES['recibo']['name'];

try {
    if($_FILES['recibo']["name"]) {
        $filename = $_FILES['recibo']["name"];
        $source = $_FILES['recibo']["tmp_name"]; 
    
        $directorio='estudiantes/recibos/'.$codest;
        if(!file_exists($directorio)){
            mkdir($directorio, 0777, true) or die("No se puede crear el directorio de extracci&oacute;n");    
        } 
        $dir=opendir($directorio); 
        $target_path = $directorio.'/'.$nb_file;
        if(move_uploaded_file($source, $target_path)) { 
            $q_insertar_archivo="INSERT INTO plat_est_recibos(id,codest,periodo,tipo_deuda,nro_cuota,url_imagen,registro,estado) VALUES(0,$codest,$periodo,$tipodeuda,$cuota,'$target_path',now(),1)";
            $i_resumen = $bdcon->prepare($q_insertar_archivo);
            $i_resumen->execute();

            if ($i_resumen) {
                ?>
                <div class="alert alert-success">
                    <font color="black"><b>Correcto: </b>Archivo enviado correctamente.</font>
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
    echo 'Error al obtener el registros del recibo : ' . $e->getMessage();
}
?>