<?php
include "../includes/conexion.php";

$item=$_POST['_item'];
$id_grupo=$_POST['_idgrupo'];
$id_clase=$_POST['_idclase'];
$cod_est=$_POST['_codest'];
$grupo_aux=$_POST['_grupoAux'];

if($grupo_aux!=0){
    $id_grupo=$_POST['_grupoAux'];
    $grupo_aux=$_POST['_idgrupo'];
}else{
    $id_grupo=$_POST['_idgrupo'];
    $grupo_aux=$_POST['_idgrupo']; 
}
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$fec_act=$fecha->format('Y-m-d');
// $fec_act=date("Y-m-d");
$direccion_espejo="http://190.186.233.212/plataformaDocente/assets/docente/grupos/clases_virtuales/";

?><h2>CLASE <?=$item;?></h2>
    
<?php 
// echo $item ." - ". $id_grupo ." - ". $id_clase ." - ". $cod_est;

// -------------        MOMENTO 1 ------------
?>
<div uk-grid>
 <div class="uk-width-2-5@m">
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <h5 class="mb-0"> Momento 1 </h5>
        </div>
        <hr class="m-0">
        <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
<?php
    try{
        $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m1 where cod_grupo=$id_grupo and cod_clase=$id_clase");
        $class->execute();
        if($class->rowcount()>0){
            ?>
                <hr><h3>Enlaces</h3>
            <?php
            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                if($row['direccion']!=''){
                    $_url=substr($row['direccion'], 0, 4);
                    if($_url=='docs'){
                        ?>                   
                        URL: <a href='<?=$direccion_espejo.$row['direccion'];?>' target='_blank'><?=$row['direccion'];?></a><br>
                        <?php
                    }else{
                         ?>                   
                    URL: <a href='<?=$row['direccion'];?>' target='_blank'><?=$row['direccion'];?></a><br>
                    <?php
                    } 
                }
                    // $row['cod']
                    // $row['direccion']
                    // $row['tipo']
            }
        }else{
            echo 'No tiene registros';
        }       
    }
    catch(PDOException $e){
        echo 'Error al obtener el registro de momento 1 : ' . $e->getMessage();
    }
    ?>
     </div>
    </div>
    <?php

// -------------        MOMENTO 2 ------------
?>
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <h5 class="mb-0"> Momento 2 </h5>
        </div>
        <hr class="m-0">
        <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
<?php
try{
    $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m2 where cod_gru=$id_grupo and cod_clase=$id_clase and estado=1");
    $class->execute();
    ?>
        <table>
    <?php
    if($class->rowCount()>0){
        while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
            $tforo=''; $i=0;
            if($row['nb_foro']!=''){
                $tforo='Nombre Foro: '.$row['nb_foro'].' - '.$row['fec_pub'];
            }else{
                $tforo='Foro sin nombre - '.$row['fec_pub'];
            }
            ?>
                <tr><?=$tforo;?></tr>
            <?php
            $q_preguntas="SELECT * FROM aa_clases_virtuales_m2_preguntas where cod_foro=".$row['cod'];
            $q_respuesta='';
            $pre = $bdcon->prepare($q_preguntas);
            $pre->execute(); 
            if($pre->rowCount()>0){
                while ($rowpre = $pre->fetch(PDO::FETCH_ASSOC)) {
                    if($rowpre['pregunta']!=''||$rowpre['pregunta']!=' '){
                         $i=$i+1;
                        $pregunta=''; $respuestas=' R. ';
                        $pregunta= $i.'P. '. $rowpre['pregunta'];
                            // $rowpre['cod']
                            // $rowpre['pregunta']
                            // $rowpre['tipo']
                            $q_respuesta="SELECT * FROM aa_clases_virtuales_m2_respuestas where cod_pregunta=".$rowpre['cod'];
                        $res = $bdcon->prepare($q_respuesta);
                        $res->execute();
                            if($res->rowCount()>0){
                                
                                while ($rowres = $res->fetch(PDO::FETCH_ASSOC)) {
                                    $respuestas.=$rowres['respuesta'].' - ';
                                    // $rowres['cod']
                                    // $rowres['respuesta']
                                    // $rowres['codest']
                                }
                            }else{
                                $respuestas.="No tiene respuesta";
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
                    <tr><button class="btn btn-success" onclick="set_M2preguntaforo(<?=$grupo_aux;?>, <?=$id_clase;?>, <?=$cod_est;?>, <?=$row['cod'];?>)">PREGUNTAR</button></tr>
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
            <h5 class="mb-0"> Momento 3 </h5>
        </div>
        <hr class="m-0">
        <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
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
            while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                $nb_class='';
                if($row['nbcla']==''){
                    $nb_class='Sin nombre de clase'.' - '. $row['fec_pub'];
                }else{
                    $nb_class=$row['nbcla'].' - '. $row['fec_pub'];
                }
                ?>
                    <tr><td><?=$nb_class;?></td></tr>
                <?php
                if($row['embed']!=''){
                    $tiene_embed=strpos($row['embed'], 'embed');
                    if($tiene_embed>0){
                        ?><tr><td><?=$row['embed'];?></td></tr><?php
                    }else{
                        ?><tr><td><a href="<?=$row['embed'];?>" target="_blank" ><?=$row['embed'];?></a></td></tr><?php
                    }
                }else{
                    ?><tr><td>Sin enlace de video</td></tr><?php
                }
                if($row['resumen']=='1'){
                    $q_resumen_est="SELECT resumen FROM aa_clases_virtuales_resumenes WHERE codest=$cod_est AND cod_clase=$id_clase AND cod_grupo=$id_grupo";
 
                    $resumen = $bdcon->prepare($q_resumen_est);
                    $resumen->execute();
                    if($resumen->rowcount()>0){
                        // $resumen='';
                        while ($rowres = $resumen->fetch(PDO::FETCH_ASSOC)) {
                            if($rowres['resumen']!=''){
                               ?><tr><td>Resumen: Ya se realizó el resumen para este tema.</td></tr><?php                      
                            }
                        }
                    }
                }else{
                    ?>
                        <tr><th>Rellene su resumen en el cuadro:</th></tr>
                        <tr><td colspan="2">
                            <div id="msj<?=$id_clase; ?>">
                                <button class="btn btn-success" onclick="set_resumen_m3(<?=$id_clase;?>, <?=$grupo_aux;?>,<?=$cod_est;?>, <?=$row['cod'];?>)">GUARDAR RESUMEN</button>
                            </div>
                            <textarea name="textarea" id="resumen<?=$id_clase.$row['cod']; ?>" rows="6" cols="50"></textarea>
                        </td></tr>
                        <tr><td>
                            <blockquote><p>Al rellenar el resumen, no use los siguientes caracteres, por que le sale error: <strong>! " @ # $ % / & ( ) = ? \ | </strong></p></blockquote>
                        </td></tr>
                    <?php
                }
                // $row['cod']
                // $row['nbcla']
                // $row['embed']
                // $row['resumen']
                // $row['fec_pub']
            }
            ?>
                </table>
            <?php
        }else{
            echo 'No tiene registros';
        }
    }
    catch(PDOException $e){
        echo 'Error al obtener el registro de momento 3 : ' . $e->getMessage();
    }
    ?>
     </div>
    </div>
    <?php


    // -------------        MOMENTO 4 ------------
    ?>
    <div class="uk-card-default rounded mt-4">
        <div class="uk-flex uk-flex-between uk-flex-middle py-3 px-4">
            <h5 class="mb-0"> Momento 4 </h5>
        </div>
        <hr class="m-0">
        <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
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
               ?>
               <table class="table">
					<tr>
						<th><h3>Tarea: responder el tema con un <?php echo $nb_resp; ?><br><small>fecha de entrega desde <?php echo $fec_desde; ?> hasta <?php echo $fec_pub_res; ?> </small></h3> </th>
					</tr> 
					<?php 
					if ($fec_act>=$fec_desde && $fec_act<=$fec_pub_res) {
						if ($res_m4->rowcount()>0) {
							$desabil="disabled='disabled'";
							$msj_m4="<font color='red'>Ya se envió respuesta</font>";
						}else{
							$desabil="";
							$msj_m4="";
						}
					}else{
						$desabil="disabled='disabled'";
						$msj_m4="No esta en fecha";
					}
					if ($tipo_resp=='1') {
						?>
						<tr>
							<td><textarea class="form-control" id="tarea<?php echo $cod_ta; ?>" rows="4" <?php echo $desabil; ?>></textarea></td>
						</tr>
						<tr>
							<td><div id="msj_ta<?=$cod_ta; ?>"><button class="btn btn-success" onclick="set_resumen_m4('<?=$cod_ta;?>','<?=$cod_est;?>')" <?=$desabil;?>>GUARDAR RESUMEN</button> <?=$msj_m4;?></div></td>
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
										<input type="file" name="mate_apo<?=$cod_ta;?>" id="mate_apo<?=$cod_est;?>">
										<div id="msj_ta<?php echo $cod_ta; ?>"><button class="btn btn-success" onclick="set_archivo_m4_cloud('<?=$cod_ta;?>')" <?=$desabil;?>>ENVIAR</button> <?=$msj_m4;?></div>
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
                <h5 class="mb-0"> Momento 5 </h5>
            </div>
            <hr class="m-0">
            <div class="uk-child-width-1-2@s uk-grid-small p-4" uk-grid>
        <?php
        $fecha=new DateTime();
        $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
        $fec_act=$fecha->format('Y-m-d');
        // $fec_act=date("Y-m-d");
        try{
            $q_bpreguntas="SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$id_grupo and cod_clase=$id_clase";
            // echo $q_bpreguntas;
            $class = $bdcon->prepare($q_bpreguntas);
            $class->execute();
            if($class->rowcount()>0){
                $fec_pub_eval="";
                $cod_ban=0;
                $cod_eval=0;
                while ($feval = $class->fetch(PDO::FETCH_ASSOC)) {
                    $cod_eval=$feval['cod'];
                    $fec_pub_eval=$feval['fec_pub'];
                    $cod_ban=$feval['cod_banco']; 	
                }
                if ($fec_act==$fec_pub_eval) {
                    $disab="";
                    $func="iniciar_eval";
                }else{
                    $disab="disabled='disabled'";
                    $func="msj_error";
                }
            }else{
                echo 'No tiene registros';
            }
                
        }
        catch(PDOException $e){
            echo 'Error al obtener el registro de momento 5 : ' . $e->getMessage();
        }
        echo "</div>";
    ?>
 <table class="table">
        <tr>
            <th colspan="2"><h3>Evaluación del tema</h3></th>
        </tr>
        <tr>
            <th>Fecha publicacion: </th>
            <td><?php echo $fec_pub_eval; ?></td>
        </tr>
        <tr>
            <th>Nombre de Evaluación: </th>
            <td>
                <?php 
                if ($cod_ban==0) {
                    echo "No se seleccionó un banco de preguntas.";
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
        <tr>
        <td colspan="2"><a href="#" role="button" class="btn btn-success" <?php echo $disab; ?> onclick="<?php echo $func; ?>('<?php echo $cod_eval; ?>','<?php echo $cod_ban; ?>','<?php echo $cod_est; ?>')">INICIAR</a></td>
        </tr>
    </table>

   </div>
  </div>
 </div>  
</div>