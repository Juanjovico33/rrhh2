<?php
include "../includes/conexion.php";
include "../includes/_horarios.php";
include "../includes/_event_log.php";

$item=$_POST['_item'];
$codgrupom5=$id_grupo=$_POST['_idgrupo'];
$id_clase=$_POST['_idclase'];
$cod_est=$_POST['_codest'];
$sub_grupo=$_POST['_subgrupo'];

$grupo_aux=$_POST['_grupoAux']; // grupo_raiz
$horario= new horarios();
$_switch=0;

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
$e->e_log_inicio_evento_conClase($cod_est, 23);

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

$tipo_sub=0;

// if($nbgrupo=="TS"){
//     $_switch=1;
// }else{
if($sub_grupo==0){
    $_switch=$horario->enable_to_Moments($id_grupo, $cod_est, $grupo_aux);
}else{
    //obtener tipo de práctica  SELECT cod,idgrupo_padre, descripcion FROM grupos_sub where
    $q_tiposub= $bdcon->prepare("SELECT descripcion FROM grupos_sub WHERE cod = $sub_grupo");
    $q_tiposub->execute();
    while ($row_sub = $q_tiposub->fetch(PDO::FETCH_ASSOC)) {
        $tipo_sub = $row_sub['descripcion'];
    }
    $_switch = $horario->enable_to_Moments_sub($id_grupo, $cod_est, $grupo_aux, $sub_grupo);
}
// }

?>
<style>
    strong{
        /* font-weight: bold; */
        font-size: medium;
        color: #36486b;
    }
</style>
<h2>CLASE <?=$item;?></h2>    
<?php 
// echo $item ." - ". $id_grupo ." - ". $id_clase ." - ". $cod_est;
// -------------        MOMENTO 1 ------------
?>
<div uk-grid>
 <div class="uk-width-2-5@m">
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <!-- <h5 class="mb-0"> Momento 1 </h5> -->
            <strong class="mb-0">Momento 1. <br>Planteamiento del problema</strong>
        </div>
        <hr class="m-0">
        <div class="uk-grid-small p-4" uk-grid>
<?php
    try{
        $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m1 where cod_grupo=$id_grupo and cod_clase=$id_clase");
        $class->execute();
        if($class->rowcount()>0){
            ?>
            <table width="100%">
                    <!-- <tr><td colspan=2><h5>Material de apoyo</h5></td></tr> -->
            <?php
            $e=0;
            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                if($row['direccion']!=''){
                    $nombre_url='';
                    $_url=substr($row['direccion'], 0, 4);
                    if($row['nb_maturl']==''||$row['nb_maturl']==' '){
                        $e=$e+1;
                        $nombre_url='Enlace '.$e;
                    }else{
                        $nombre_url=$row['nb_maturl'];
                    }
                    
                    if($_url=='docs'){
                        ?>                   
                        <tr>
                            <td width="15%">URL:</td> <td width="85%"><a href='<?=$direccion_espejo.$row['direccion'];?>' target='_blank'><?=$nombre_url;?></a></td>
                        </tr>
                        <?php
                    }else if($_url=='<ifr'){
                        ?>
                        <tr>
                            <?=$row['direccion'];?>
                        </tr>
                        <?php
                        //enlace enbebido <ifr
                    }else{
                         ?>                   
                        <tr>
                            <td>URL:</td> <td><a href='<?=$row['direccion'];?>' target='_blank'><?=$nombre_url;?></a></td>
                        </tr>
                    <?php
                    } 
                }                   
            }
        }else{
            echo 'No tiene registros';
        }       
    }
    catch(PDOException $e){
        echo 'Error al obtener el registro de momento 1 : ' . $e->getMessage();
    }
    ?></table>
     </div>
    </div>
    <?php

// -------------        MOMENTO 2 ------------
?>
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <strong class="mb-0"> Momento 2. <br>Análisis y reflexión sobre la temática</strong>
        </div>
        <hr class="m-0">
        <div class="uk-grid-small p-4" uk-grid>
<?php

    try{
        $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m2 WHERE (cod_gru=$id_grupo OR cod_gru=$grupo_aux) AND cod_clase=$id_clase and estado=1");
        $class->execute();
        ?>
            <table>
        <?php
        if($class->rowCount()>0){
            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                $tforo=''; $i=0;
                if($row['nb_foro']!=''){
                    $tforo='Participación asincrónica: '.$row['nb_foro'].'<br>';
                }else{
                    $tforo='Participación asincrónica - '.'<br>';
                }
                ?>
                    <tr><?=$tforo;?></tr>
                <?php
                $q_preguntas="SELECT * FROM aa_clases_virtuales_m2_preguntas WHERE cod_foro=".$row['cod']." AND codest=$cod_est AND (cod_gru=".$id_grupo." OR cod_gru=".$grupo_aux.")";
                $q_respuesta='';
                $pre = $bdcon->prepare($q_preguntas);
                $pre->execute(); 
                if($pre->rowCount()>0){
                    while ($rowpre = $pre->fetch(PDO::FETCH_ASSOC)) {
                        if($rowpre['pregunta']!=''||$rowpre['pregunta']!=' '){
                            $i=$i+1;
                            $pregunta=''; $respuestas='<br> R. ';
                            $pregunta= $i.'<br>P. '. $rowpre['pregunta'];
                                // $rowpre['cod']
                                // $rowpre['pregunta']
                                // $rowpre['tipo']
                            $q_respuesta="SELECT * FROM aa_clases_virtuales_m2_respuestas where cod_pregunta=".$rowpre['cod'];
                            $res = $bdcon->prepare($q_respuesta);
                            $res->execute();
                                if($res->rowCount()>0){
                                    
                                    while ($rowres = $res->fetch(PDO::FETCH_ASSOC)) {
                                        $respuestas.=$rowres['respuesta'].'<hr>';
                                        // $rowres['cod']
                                        // $rowres['respuesta']
                                        // $rowres['codest']
                                    }
                                }else{
                                    $respuestas.="No tiene respuesta <hr>";
                                }
                            $pregunta='<tr>'.$pregunta.$respuestas.'</tr><br>';
                            echo $pregunta;
                        }
                    }
                    // $row['cod']
                    // $row['nb_foro']
                    // $row['fec_pub']
                }else{
                    echo "No tiene preguntas registradas";
                }
                ?>
                <form action="#" id="form<?=$id_clase;?>">

                    <tr><input type="text" id="text_pregunta_<?=$id_clase;?>" /></tr>

                    <div id="msjm2<?=$id_clase; ?>">
                        <tr><button class="btn btn-success" onclick="set_M2preguntaforo_snAsis(<?=$grupo_aux?>, <?=$id_grupo?>, <?=$id_clase?>, <?=$cod_est?>, <?=$row['cod']?>, '<?=$nbgrupo?>')">PREGUNTAR</button></tr>
                    </div>
                    
                </form>
                <?php
            }
        }else{
            echo 'No tiene registros';
        }
        ?>
            </table>
        <?php
    }catch(PDOException $e){
        echo 'Error al obtener el registro de momento 2 : ' . $e->getMessage();
    }

?>
        </div>
    </div>
</div>
    <?php

    // -------------        MOMENTO 3 ------------
    ?>
<div class="uk-width-expand@m">
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <strong class="mb-0"> Momento 3. <br>Videoclase y material de apoyo</strong>
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
        echo 'Error al obtener el registro de momento 3 : ' . $e->getMessage();
    }
    ?></table>
     </div>
    </div>
    <?php


    // -------------        MOMENTO 4 ------------
    ?>
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <strong class="mb-0"> Momento 4. <br>Aplicación: Resolviendo el problema planteado</strong>
        </div>
        <hr class="m-0">
        <div class="uk-grid-small p-4" uk-grid>
    <?php

    try{
        $q_tarea="SELECT * FROM aa_clases_virtuales_m4 where cod_gru=$id_grupo and cod_clase=$id_clase and estado=1";
        $class = $bdcon->prepare($q_tarea);
        // echo $q_tarea;
        $class->execute();
        if($class->rowcount()>0){

            $tipo_resp=0;
            $fec_pub_res="";
            $fec_desde="";
            $cod_ta=0;

            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                $tipo_resp=$row['tipo_resp'];
                $fec_pub_res=$row['fec_pub'];
                $fec_desde=$row['fec_desde'];
                $cod_ta=$row['cod'];

                switch ($tipo_resp) {
                    case '1':
                        $nb_resp="RESUMEN";
                        break;
                    case '2':
                        $nb_resp="ARCHIVO ADJUNTO";
                        break;
                    default:
                        $nb_resp="N/A";
                        break;
               }
               if ($fec_desde=='0000-00-00') {
                   $fec_desde=$fec_pub_res;
               }

               $q_respuesta="SELECT * FROM aa_clases_virtuales_m4_respuestas WHERE codest=$cod_est AND cod_m4=$cod_ta;";
            //    echo $q_respuesta;
               $res_m4 = $bdcon->prepare($q_respuesta);
               $res_m4->execute();
               $msj_m4='';
            //    $f_inicio=$fec_desde;
            //    $f_fin=$fec_pub_res;
               $f_inicio=date("d/m/Y", strtotime($fec_desde));
               $f_fin=date("d/m/Y", strtotime($fec_pub_res));
               ?>
               <table class="table">
					<tr>
                        <strong class="mb-0"><small><?=$nb_resp;?><br> Fecha de entrega: <?=$f_inicio;?> - <?=$f_fin;?></small></strong>
					</tr> 
					<?php 

                    $q_tarea="SELECT respuesta, archivo FROM aa_clases_virtuales_m4_respuestas WHERE cod_m4=$cod_ta AND codest=$cod_est";
                    $obj_tar = $bdcon->prepare($q_tarea);
                    $obj_tar->execute();
                    if($obj_tar->rowcount()>0){
                        echo "<tr><td><div class='alert alert-success'>Ya ha enviado este momento!</div></td></tr>";

                        $_resumen=""; $_archivo="";
                        while ($row_tar = $obj_tar->fetch(PDO::FETCH_ASSOC)) {
                            $_resumen=$row_tar['respuesta'];
                            $_archivo=$row_tar['archivo'];
                        }
                        if(trim($_resumen)!=""){
                            echo "<textarea readonly>Respuesta:\n$_resumen</textarea>";
                        }else{
                            $partes_ruta = pathinfo($_archivo);
                            $nb_file=$partes_ruta['basename'];
                            $nb_u=$partes_ruta['dirname'];
                            $nb_file=str_replace(' ','%20',$nb_file);
                            $_url="https://storage.googleapis.com/une_segmento-one/".$nb_u.'/'.$nb_file.$nb_file;
										
                            echo "<tr><td><a href='$_url'>Enlace para abrir <b>archivo</b></a></td></tr>";
                        }
                    }else{
                        if ($fec_act>=$fec_desde && $fec_act<=$fec_pub_res) {
                            if ($res_m4->rowcount()>0) {
                                $desabil="disabled='disabled'";
                                $msj_m4="<div class='alert alert-success'>Ya se envió respuesta</div>";//<font color='red'>
                            }else{
                                // preguntar si switch activo

                                $desabil="";
                                $msj_m4="";

                            }
                        }else{
                            $desabil="disabled='disabled'";
                            $msj_m4="<div class='alert alert-warning alert-danger'>No esta en fecha</div>";
                        }
                        if ($tipo_resp=='1') {
                            ?>
                            <tr>
                                <td><textarea class="form-control" id="tarea<?php echo $cod_ta; ?>" rows="4" <?php echo $desabil; ?>></textarea></td>
                            </tr>
                            <tr>
                                <td><div style="text-align:center;"id="msj_ta<?=$cod_ta; ?>"><button class="btn btn-success" onclick="set_resumen_m4_snAsis(<?=$grupo_aux?>, <?=$id_grupo?>, <?=$cod_ta?>,<?=$cod_est;?>,'<?=$nbgrupo;?>')" <?=$desabil;?>>GUARDAR RESUMEN</button> <?=$msj_m4;?> </div></td>
                            </tr>
                            <?php
                        }else{
                            if ($tipo_resp=='2') {
                                ?>
                                <tr>
                                    <td>
                                        <form id="envio_arch<?=$cod_ta;?>" method="post" enctype="multipart/form-data" onsubmit="return false;">
                                            <input type="hidden" name="cod_ta" id="cod_ta" value="<?php echo $cod_ta; ?>">
                                            <input type="hidden" name="codest" id="codest" value="<?php echo $cod_est; ?>">
                                            <input type="hidden" name="_grupo" id="_grupo" value="<?php echo $grupo_aux; ?>">
                                            <input type="hidden" name="_raiz" id="_raiz" value="<?php echo $id_grupo; ?>">
                                            <input type="hidden" name="_nbgrupo" id="_nbgrupo" value="<?php echo $nbgrupo; ?>">
                                            <input type="file" name="mate_apo<?=$cod_ta;?>" id="mate_apo<?=$cod_ta;?>">
                                            <div id="msj_ta<?php echo $cod_ta; ?>"><button class="btn btn-success" onclick="set_archivo_m4_cloud_snAsis('<?=$cod_ta;?>')" <?=$desabil;?>>ENVIAR</button> <?=$msj_m4;?></div>
                                        </form>
                                    </td>
                                </tr> 
                                <?php
                            }else{
                                ?>
                                <tr>
                                    <td>No asignado</td>
                                </tr>
                                <?php
                            }
                        }
                    }
					?>
				</table>
               <?php
                // $row['cod']
                // $row['tipo_resp']
                // $row['fec_desde']
                // $row['fec_pub']
            }
        }else{
            echo 'No tiene registros';
        }
            
    }
    catch(PDOException $e){
        echo 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
    }
    ?>
        </div>
    </div>
        <?php
        // -------------        MOMENTO 5 ------------

        ?>
        <div class="uk-card-default rounded mt-4">
            <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
                <strong class="mb-0"> Momento 5.<br>Evaluación Procesual Cognitiva</strong>
            </div>
            <hr class="m-0">
            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
        <?php
        $msj_m5="";
    if($tipo_sub==0 || $tipo_sub==2 || $tipo_sub==8 || $tipo_sub==9){
        try{
            $q_bpreguntas="SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$id_grupo and cod_clase=$id_clase";
            $class = $bdcon->prepare($q_bpreguntas);
            $class->execute();
            $cod_ban=0;
            $cod_eval=0;
            $fec_pub_eval="";
            if($class->rowcount()>0){                               
                while ($feval = $class->fetch(PDO::FETCH_ASSOC)) {
                    $cod_eval=$feval['cod'];
                    $fec_pub_eval=$feval['fec_pub'];
                    $cod_ban=$feval['cod_banco'];
                    $hora_ini=$feval['hr_inicio']; 
                    $t_tiempo=$feval['tipo_tiempo']; 
                }
                $q_evaluacion=$bdcon->prepare("SELECT cod from aa_clases_virtuales_m5_respuestas where codest='$cod_est' and cod_banco='$cod_ban' and cod_reg='$cod_eval'");
                $q_evaluacion->execute();
                if($q_evaluacion->rowCount()>0){
                    ?>
                    <div class="alert alert-success" align="center"><font>Evaluación Realizada</font></div>
                    <?php
                    exit();
                }else{
                    // No evaluado
                }
                if ($fec_act==$fec_pub_eval) {
                    
                    $disab="";
                    $func="iniciar_eval";
                    
                }else{
                    $disab="disabled='disabled'";
                    // $func="msj_error";
                    $func="alert('No está en fecha!')";
                    $msj_m5="<div class='alert alert-warning alert-danger'>No esta en fecha</div>";
                }
                if($cod_ban==0){
                    echo "<p align='center'>No tiene registrada la evaluación</p>";
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
                            <th colspan="2">Fecha de la evaluación:</th>
                            <th colspan="2"><div style="color:#960808"><?php echo $fec_pub_eval;?></div></th>
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
                                if ($fec_pub_eval==$fec_act) {
                                    ?>
                                        <div style="text-align:center;">
                                            <a href="#" role="button" class="btn btn-success" <?=$disab;?> onclick="<?=$func;?>('<?=$cod_eval;?>','<?=$cod_ban;?>','<?=$cod_est;?>','<?=$id_grupo;?>','<?=$id_clase;?>','<?=$codgrupom5;?>','<?=$sub_grupo;?>','1')">INICIAR</a><?=$msj_m5?>
                                        </div>
                                    <?php
                                }else{
                                ?>
                                    <div class="alert alert-danger" align="center">¡Fuera de Fecha! fue registrado para el <?=$fec_pub_eval?></div>
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
            echo 'Error al obtener el registro de momento 5 : ' . $e->getMessage();
        }
    }else{
        echo "<div class='alert alert-success'>Este <b>subgrupo</b> no requiere de esta participación!</div>";
    }
        echo "</div>";        
       ?>
   </div>
  </div>
 </div>  
</div>