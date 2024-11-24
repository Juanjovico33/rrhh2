<?php 
    include "../includes/_resultadoParcial.php";

    $id_banco = $_REQUEST['cod_banco'];	
    $codest = $_REQUEST['codest'];
    $ponderacion = $_REQUEST['ponde'];

    $resul = new examen();
    if($resul->cargarBanco($id_banco, $codest, $ponderacion)){
        // $_var=$resul->getItems();
        // echo json_encode($_var);
        // echo $resul->getError();
        // echo "Cargado!";
        // if($resul->puede_ver_enfecha()){
            if($resul->puede_ver()){
                // echo "tiempo transcurrido".$resul->getHora_transcurrida();
                $resul->mostrar_examen();
            }else{
                echo "<div class='uk-text-secondary uk-text-center'>Solo puedes ver los resultados del examen despues de 24 Hrs. <br>Tiempo transcurrido desde el examen : <b>".$resul->getHora_transcurrida()." Hrs.</b></div>";
            }
        // }else{
        //     echo "<div class='uk-text-secondary uk-text-center'>Los resultados anteriores a la fecha <b>2022-05-18</b>,<br>No podrán ser visualizados. </div>";
        // }
            
        
    }else{
        echo "Hubo un error al intentar extraer los resultados de este examen o este no existe. Intente más tardes por favor.";
    }
    // echo $id_banco."-".$codest;
?>