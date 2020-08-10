<?php 
$cod_act=$_REQUEST['cod_act'];
$cod_ban=$_REQUEST['cod_ban'];
$codest=$_REQUEST['codest'];
$cod_gru_aux=$_REQUEST['cod_gru_aux'];
$hora_fin=$_REQUEST['hora_fin'];
include "../includes/conexion.php";
//include("convert.php");
	$query= $bdcon->prepare("SELECT reco FROM plat_doc_intentos_est WHERE cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'");
    $query->execute();
    $nota_exis=0;
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {       
        $nota_exis=$row['reco'];                         
    }
	//$nota_exis=$cons->cons_simple('plat_doc_intentos_est',"cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'",'reco');
    if ($nota_exis>0) {
        echo "<font color='green'>La evaluación ya fué realizada</font>";
        exit();
    }
    $fecha=date("Y-m-d");
    $hora=date("H:i:s");
    $query_insert= $bdcon->prepare("INSERT INTO plat_est_inicio VALUES ('0','$codest','$cod_act','$cod_ban','$fecha','$hora')");
    $query_insert->execute();
    //$inser=$cons->insertar('plat_est_inicio',"'','$codest','$cod_act','$cod_ban','$fecha','$hora'");
?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                <div align="center">
                    <b>Primera Evaluación Online es de calificación inmediata por computadora</b>
                </div>
                <p>
                    Estimado estudiante lee con atención:<br>
                    A partir del siguiente click, se presentará en tu pantalla una serie de preguntas que podrán ser de una sola alternativa, de dos alternativas o de Verdadero y Falso.<br>
                    El puntaje total de esta prueba está distribuido de manera equitativa entre las preguntas definidas para esta Primera Evaluación Online.<br>
                    <div align="center">
                        <b>Administre su tiempo con responsabilidad</b><br>
                    </div>
                    El tiempo estimado para la realización de esta Primera Evaluación Online es de una (1) hora cronológica, el mismo se irá descontando en su pantalla y que debe consultar de manera permanente mientras responde cada pregunta.<br>
                    Las preguntas contestadas, no podrán revisarse o corregirse una vez que ha pasado a la siguiente pregunta.<br>
                    La calificación final obtenida por la totalidad de las respuestas enviadas, será visualizada inmediatamente de concluida su Evaluación, la misma se visualizará en pantalla para su conocimiento y control.
                </p>
                <?php 
                ////hacemos la tranformacion de la fecha y hora para mandar al java script////////////////
                    $fecha2=date("m/d/Y");
                    $newDateTime = date('h:i A', strtotime($hora_fin));
                    $hora_completa=$fecha2." ".$newDateTime;
                ?>
                <div align="center">
                    <button class="btn btn-success" data-dismiss="modal" onclick="iniciar_evaluacion('<?php echo $cod_act; ?>','<?php echo $cod_ban; ?>','<?php echo $codest; ?>','<?php echo $cod_gru_aux; ?>','<?php echo $hora_completa; ?>','<?php echo $hora_fin; ?>')">Estoy de acuerdo</button>
                </div>
                </div>
                    <!--<div class="section-header-right">
                        <a href="#" class="see-all"> See all</a>
                    </div>-->  
            </div>  
        </div> 
    </div>

<!--button class="btn btn-success" data-dismiss="modal" onclick="iniciar_evaluacion_dos('<?php //echo $cod_act; ?>','<?php //echo $cod_ban; ?>','<?php //echo $codest; ?>','<?php //echo $cod_gru_aux; ?>')">Estoy de acuerdo</button-->

