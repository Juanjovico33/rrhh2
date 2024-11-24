<?php
    include "../../includes/conexion.php";

    $id_recibo=$_REQUEST['idrecibo'];

    $q_update_recibo="UPDATE plat_est_recibos SET estado=0 WHERE id=$id_recibo";

    $r_recibo = $bdcon->prepare($q_update_recibo);
    $r_recibo->execute();
    if($r_recibo){
        echo "Se quito el recibo de forma correcta!";
    }else{
        echo "ops, tuvimos inconvenientes al quitar tu recibo, intenta nuevamente!";
    }
    // echo $id_recibo;
?>