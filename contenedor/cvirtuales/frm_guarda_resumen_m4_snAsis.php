<?php
include "../../includes/conexion.php";

$grupo=$_REQUEST['_grupo'];
$grupo_raiz=$_REQUEST['_raiz'];
$cod_tarea=$_REQUEST['_codtarea'];
$resumen=$_REQUEST['_resumen'];
$cod_est=$_REQUEST['_codest'];
$nb_grupo=$_REQUEST['_nbgrupo']; // TS


try{
    if ($resumen=='') {
        echo "<div class='alert alert-error'><font color='black'><b>Error: </b>el resumen está vacío no se pudo registrar, intente ingresando una resumen.</font></div>";
    }else{
        $q_resumen="SELECT * FROM aa_clases_virtuales_m4_respuestas WHERE cod_m4=$cod_tarea AND codest=$cod_est";
        $class = $bdcon->prepare($q_resumen);
        $class->execute();
        if($class->rowcount()>0){
            echo "<div class='alert alert-error'><font color='black'><b>Error: </b>ya existe una respuesta.</font></div>";
        }else{

            $q_insertar_resumen="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`cod_gru`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,$grupo,'$resumen','',DATE_FORMAT(now(),'%Y-%m-%d'),$cod_est)";
            $i_resumen = $bdcon->prepare($q_insertar_resumen);
            $i_resumen->execute();

            if ($i_resumen) {
                echo "<div class='alert alert-success'><font color='black'><b>Correcto: </b>Se guardó el resumen correctamente.</font></div>";
            }else{
                echo "<div class='alert alert-error'><font color='black'><b>Error: </b>No se pudo registrar el resumen, intente nuevamente mas tarde.</font></div>";
            }
           
        }
    }

}catch(PDOException $e){
    echo 'Error al obtener el registro del momento 4 para el registro de la tarea : ' . $e->getMessage();
}

?>