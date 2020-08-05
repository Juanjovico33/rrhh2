<?php
include "../../includes/conexion.php";
$cod_tarea=$_REQUEST['_codtarea'];
$resumen=$_REQUEST['_resumen'];
$cod_est=$_REQUEST['_codest'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");

try{
    if ($resumen=='') {
        ?>
        <div class="alert alert-error">
            <font color="black"><b>Error: </b>el resumen está vacío no se pudo registrar, intente ingresando una resumen.</font>
        </div>
        <?php
    }else{
        $q_resumen="SELECT * FROM aa_clases_virtuales_m4_respuestas WHERE cod_m4=$cod_tarea AND codest=$cod_est";
        $class = $bdcon->prepare($q_resumen);
        $class->execute();
        if($class->rowcount()>0){
            ?>
                <div class="alert alert-error">
                    <font color="black"><b>Error: </b>ya existe una respuesta.</font>
                </div>
            <?php
        }else{
            $q_insertar_resumen="INSERT INTO aa_clases_virtuales_m4_respuestas(`cod`,`cod_m4`,`respuesta`,`archivo`,`fec_resp`,`codest`) VALUES(0,$cod_tarea,'$resumen','','$fecha',$cod_est)";
            
            $i_resumen = $bdcon->prepare($q_insertar_resumen);
            $i_resumen->execute();

            if ($i_resumen) {
                ?>
                <div class="alert alert-success">
                    <font color="white"><b>Correcto: </b>Se guardó el resumen correctamente.</font>
                </div>
                <?php
            }else{
                ?>
                <div class="alert alert-error">
                    <font color="black"><b>Error: </b>No se pudo registrar el resumen, intente nuevamente mas tarde.</font>
                </div>
                <?php
            }

        }
        

    }

}catch(PDOException $e){
    echo 'Error al obtener el registro del momento 4 para el registro de la tarea : ' . $e->getMessage();
}
?>