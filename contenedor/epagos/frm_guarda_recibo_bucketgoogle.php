<?php
include "../../includes/_storage.php";
include "../../includes/conexion.php";

$codest=$_REQUEST['codest'];
$periodo=$_REQUEST['periodo'];
$tipodeuda=$_REQUEST['tipodeuda'];
$cuota=$_REQUEST['cuota'];
$nb_file=$_FILES['recibo']['name'];

$storage = new storage();
// $_fecha=new DateTime();
// $_fecha->setTimezone(new DateTimeZone('America/La_Paz'));

// $directorio_main='estudiantes/recibos/';
$directorio_main='estudiantes/recibos/'.$codest.'/'.$nb_file;
// $direccion_google='https://storage.googleapis.com/une_segmento-one/';
// $fecha=$_fecha->format('Y-m-d');
// $hora=$_fecha->format('H:i:s');

$q_recibos="SELECT id FROM plat_est_recibos WHERE codest=$codest AND periodo=$periodo AND nro_cuota=$cuota AND estado=1";
// echo $q_recibos;
// exit;

$row_recibos= $bdcon->prepare($q_recibos);
$row_recibos->execute();
if($row_recibos->rowCount()>0){
    // YA TIENE REGISTRADO UN RECIBO PARA ESTA CUOTA, NO SE PUEDE SUBIR 2 VECES EL RECIBO
    echo "Ya se ha subido un recibo para esta cuota, no se puede subir dos veces!.";
}else{

    try {
        if(file_exists($_FILES['recibo']['tmp_name'])){
            $storage->uploadObject($_FILES['recibo']['name'], $_FILES['recibo']['tmp_name'], $directorio_main);
            $q_insertar_archivo="INSERT INTO plat_est_recibos(id,codest,periodo,tipo_deuda,nro_cuota,url_imagen,registro,estado) VALUES(0,$codest,$periodo,$tipodeuda,$cuota,'$directorio_main',now(),1)";
            // DATE_FORMAT(now(),'%Y-%m-%d'),1,DATE_FORMAT(now(),'%H:%i:%s')
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
                    <font color="black"><b>Error: </b>No se pudo registrar el archivo adjunto, intente nuevamente.</font>
                </div>
                <?php
            }
            // echo '<br>'.$q_insertar_archivo;
            // echo $directorio_main;
        }else{
            echo "Archivo seleccionado no existe!<br>";
        }
    } catch(PDOException $e){
        echo 'Error al obtener el registros del recibo : ' . $e->getMessage();
    }

}
?>