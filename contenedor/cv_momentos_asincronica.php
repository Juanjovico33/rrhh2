<?php
include "../includes/conexion.php";
include "../includes/_event_log.php";

$item=$_POST['_item'];
$codgrupom5=$id_grupo=$_POST['_idgrupo'];
$id_clase=$_POST['_idclase'];
$cod_est=$_POST['_codest'];
$sub_grupo=$_POST['_subgrupo'];

$grupo_aux=$_POST['_grupoAux']; // grupo_raiz

if($grupo_aux!=0){
    $id_grupo=$_POST['_grupoAux']; // ahora la raiz es id_grupo
    $grupo_aux=$_POST['_idgrupo']; // ahora el grupo original es grupo_aux
}else{
    $id_grupo=$_POST['_idgrupo']; // grupo original es id_grupo
    $grupo_aux=$_POST['_idgrupo'];  // grupo raiz es el mismo que el original
}

$e = new evento();
$e->setIdGrupo($grupo_aux);
$e->setIdSubGrupo($sub_grupo);
$e->setIdClase($id_clase);
$e->e_log_inicio_evento_conClase($cod_est, 24);

$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$fec_act=$fecha->format('Y-m-d');
$hr_act=$fecha->format('H:i:s');
$direccion_espejo="http://190.186.233.211/plataformaDocente/assets/docente/grupos/clases_virtuales/";
      
                            
$query_gru= $bdcon->prepare("SELECT max(codreg) as id FROM aca_registroestmat WHERE codest='$cod_est'");
$query_gru->execute();

$nbgrupo="null";

while ($row24 = $query_gru->fetch(PDO::FETCH_ASSOC)) {       
    $idgggrupo=$row24['id'];                         
    $query_nbgru= $bdcon->prepare("SELECT grupo FROM aca_registroestmat WHERE codreg='$idgggrupo'");
    $query_nbgru->execute();
    while ($row25 = $query_nbgru->fetch(PDO::FETCH_ASSOC)) {
        $nbgrupo=$row25['grupo']; 
    }
} 

?>
<style>
    strong{
        /* font-weight: bold; */
        font-size: medium;
        color: #36486b;
    }
</style>
<h2>AUTOAPRENDIZAJE <?=$item?>  (Asincrónica) </h2>    
<?php 
// echo $item ." - ". $id_grupo ." - ". $id_clase ." - ". $cod_est;
?>
    <?php

    // -------------        MOMENTO 3 ------------
    ?>
<div class="uk-width-expand@m">
    <div class="uk-card-default rounded mt-4">
        <div class="alert alert-info">
            <strong class="mb-0"> Estimado estudiante:<br>En esta sección encontrarás material complementario a la clase correspondiente, que el docente ha seleccionado para que lo revises, leas, y posteriormente, siguiendo las instrucciones, desarrolles, completes, resuelvas o rellenes para alcanzar el afianzamiento de los contenidos desarrollados en este contenido temático.
            </strong>
        </div>
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <strong class="mb-0"> Material Complementario</strong>
        </div>
        <hr class="m-0">
        <div class="uk-grid-small p-4" uk-grid>
    <?php
    try{
        $q_resumen="SELECT * FROM aa_clases_virtuales_m3 where cod_gru=$id_grupo and cod_cla=$id_clase  and embed!=''";
        // echo $q_resumen;
        $class = $bdcon->prepare($q_resumen);
        $class->execute();
        if($class->rowcount()>0){
            ?>
                <table>
            <?php
            $nbc=0;
            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                $nb_class='';
                if($row['nbcla']=='' || $row['nbcla']==' '){
                    $nbc=$nbc+1;
                    $nb_class='Enlace de clase '. $nbc;
                }else{
                    $nb_class=$row['nbcla'];
                }
                ?>
                <?php
                if($row['embed']!=''){
                    $fecha_registro=$row['registro'];
                    if(is_null($fecha_registro)){
                        $fecha_registro="Sin fecha";
                    }
                    $tiene_embed=strpos($row['embed'], 'embed');
                    if($tiene_embed>0){
                        ?><tr><td><strong>Enlace: </strong><?=$row['embed'];?></td></tr><?php
                    }else{
                        ?><tr><td><strong>Enlace: </strong><a href="<?=$row['embed'];?>" target="_blank" title="<?=$fecha_registro?>"><?=$nb_class;?></a></td></tr><?php
                    }
                }else{
                    ?><tr><td>Sin enlace de video</td></tr><?php
                }
                // if($row['resumen']=='1'){
                //     $q_resumen_est="SELECT resumen FROM aa_clases_virtuales_resumenes WHERE codest=$cod_est AND cod_clase=$id_clase AND cod_grupo=$id_grupo";
 
                //     $resumen = $bdcon->prepare($q_resumen_est);
                //     $resumen->execute();
                //     if($resumen->rowcount()>0){
                //         // $resumen='';
                //         while ($rowres = $resumen->fetch(PDO::FETCH_ASSOC)) {
                //             if($rowres['resumen']!=''){
                               ?><tr>
                               <!-- <td>Resumen: Ya se realizó el resumen para este tema.</td> -->
                               </tr><?php                      
                    //         }
                    //     }
                    // }
                // }else{
                    ?>
                        <!-- <tr><th>Rellene su resumen en el cuadro:</th></tr>
                        <tr><td colspan="2"> -->
                            <!-- <div id="msj<?php// echo $id_clase; ?>"> -->
                                <!-- <button class="btn btn-success" onclick="set_resumen_m3(<?php// echo $id_clase;?>, <?php// echo $grupo_aux;?>,<?php// echo $cod_est;?>, <?php// echo $row['cod'];?>)">GUARDAR RESUMEN</button> -->
                            <!-- </div> -->
                            <!-- <textarea name="textarea" id="resumen<?php// echo $id_clase.$row['cod']; ?>" rows="6" cols="50"></textarea> -->
                        <!-- </td></tr> -->
                        <!-- <tr><td>
                            <blockquote><p>Al rellenar el resumen, no use los siguientes caracteres, por que le sale error: <strong>! " @ # $ % / & ( ) = ? \ | </strong></p></blockquote>
                        </td></tr> -->
                    <?php
                // }
                // $row['cod']
                // $row['nbcla']
                // $row['embed']
                // $row['resumen']
                // $row['fec_pub']
            }
        }else{
            echo 'No tiene registros';
        }
    }
    catch(PDOException $e){
        echo 'Error al obtener el registro del material complementario : ' . $e->getMessage();
    }
    ?></table>
     </div>
    </div>
    
    <?php
    // -------------        MOMENTO 5 ------------

    ?>
        <div class="uk-card-default rounded mt-4">

            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                <strong class="mb-0"> Autoevaluación</strong>
            </div>
            <div class="alert alert-info">
                <strong class="mb-0"> La Autoevaluación son 4 preguntas que aleatoriamente te ofrece el sistema para resolver en línea. Al obtener la calificación de manera instantánea, podrás saber el nivel de comprensión que alcanzaste con los contenidos desarrollados en la actividad de autoaprendizaje.</strong>
            </div>
            <hr class="m-0">
            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
        <?php
        $msj_m5="";

    try{
        $q_bpreguntas="SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$id_grupo and cod_clase=$id_clase";
        $class = $bdcon->prepare($q_bpreguntas);
        $class->execute();
        $cod_ban=0;
        $cod_eval=0;
        $fec_pub_eval="";
        $fec_hasta="";
        if($class->rowcount()>0){                               
            while ($feval = $class->fetch(PDO::FETCH_ASSOC)) {
                $cod_eval=$feval['cod'];
                $fec_pub_eval=$feval['fec_pub'];
                $cod_ban=$feval['cod_banco'];
                $hora_ini=$feval['hr_inicio']; 
                $t_tiempo=$feval['tipo_tiempo']; 
                $fec_hasta=$feval['fec_hasta'];
                if($fec_hasta==""){
                    $fec_hasta=$fec_pub_eval;
                }
            }
            $q_evaluacion=$bdcon->prepare("SELECT cod from aa_clases_virtuales_m5_respuestas where codest='$cod_est' and cod_banco='$cod_ban' and cod_reg='$cod_eval'");
            $q_evaluacion->execute();
            if($q_evaluacion->rowCount()>0){
                ?>
                <div class="alert alert-success" align="center"><font>Autoevaluación Realizada</font></div>
                <?php
                exit();
            }else{
                // No evaluado
            }
            $hoy= $fec_act;
            $ini= $fec_pub_eval;
            $fin= $fec_hasta;
            if ($fec_act>=$fec_pub_eval && $fec_act<=$fec_hasta) {
                $disab="";
                $func="iniciar_eval";                    
            }else{
                $disab="disabled='disabled'";
                // $func="msj_error";
                $func="alert('No está en fecha!')";
                $msj_m5="<div class='alert alert-warning alert-danger'>No esta en fecha</div>";
            }
            if($cod_ban==0){
                echo "<p align='center'>No tiene registrada la autoevaluación</p>";
            }else{
                $tiemponb="";
            ?>
            <table class="table">                                                 
                <tr>
                    <th colspan="2">Nombre de Evaluación: </th>
                    <td colspan="2">
                        <?php 
                        if ($cod_ban==0) {
                            ?>
                                <div class="alert alert-success" align="center"><font>El Docente no seleccionó un banco de preguntas</font></div>
                            <?php
                            exit();
                        }else{
                            //echo "<br>".$cod_ban;
                            $catbanco='';
                            $query_cat = $bdcon->prepare("SELECT categoria FROM plat_doc_banco_cat WHERE id='$cod_ban'");
                            $query_cat->execute(); 
                            while ($fact = $query_cat->fetch(PDO::FETCH_ASSOC)) {   
                                echo $cate=$fact['categoria'];
                            }
                        }
                        ?>
                    </td>
                </tr>
                
                    <!--aqui empieza a contrololar la hora y fecha para el normal-->
                    <tr>
                        <th colspan="2">Fecha de la autoevaluación:</th>
                        <th colspan="2"> DESDE <a style="color:#960808"><?php echo $fec_pub_eval;?></a> HASTA <a style="color:#960808"><?php echo $fec_hasta;?></a></th>
                    </tr>
                    <!-- <tr>
                        <th>Hora Inicio:</th>
                        <td><?php //echo $hora_ini;?></td>
                        <th>Hora Fin:</th>
                        <td> -->
                            <?php 
                            if ($t_tiempo==1) {
                                $tiemponb="6 minutos";                                              
                                $hrfin = strtotime ( '+0 hour' , strtotime ($hora_ini) ) ; 
                                $hrfin = strtotime ( '+6 minute' , $hrfin ) ; 
                                $hrfin = strtotime ( '+0 second' , $hrfin ) ; 
                                $hrfin = date ( 'H:i:s' , $hrfin); 
                                // echo $hrfin;
                            }elseif ($t_tiempo==2) {
                                $tiemponb="10 minutos";
                                $hrfin = strtotime ( '+0 hour' , strtotime ($hora_ini) ) ; 
                                $hrfin = strtotime ( '+10 minute' , $hrfin ) ; 
                                $hrfin = strtotime ( '+0 second' , $hrfin ) ; 
                                $hrfin = date ( 'H:i:s' , $hrfin); 
                                // echo $hrfin;
                            }else{
                                $tiemponb="No tiene tiempo error de datos...";
                            }
                            ?>
                        <!-- </td>
                    </tr>                          -->
                    <tr>
                        <th colspan="2">Duracion de la evaluación: </th>
                        <td colspan="2"><?php echo $tiemponb;?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php
                            // verificamos si esta en fecha para dar la evaluacion 
                            if ($fec_act>=$fec_pub_eval && $fec_act<=$fec_hasta) {
                                ?>
                                    <div style="text-align:center;">
                                        <a href="#" role="button" class="btn btn-success" <?=$disab;?> onclick="<?=$func;?>('<?=$cod_eval;?>','<?=$cod_ban;?>','<?=$cod_est;?>','<?=$id_grupo;?>','<?=$id_clase;?>','<?=$codgrupom5;?>','<?=$sub_grupo;?>','2')">INICIAR</a><?=$msj_m5?>
                                    </div>
                                <?php
                            }else{
                            ?>
                                <div class="alert alert-danger" align="center">¡Fuera de Fecha! fue registrado para el <?=$fec_pub_eval?> hasta el <?=$fec_hasta?></div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
            </table>
            <?php 
            }
        }else{
            echo 'No tiene registros';
        }                           
    }catch(PDOException $e){
        echo 'Error al obtener el registro de la autoevaluación: ' . $e->getMessage();
    } 
    ?>
   </div>
  </div>