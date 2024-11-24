<?php 
$cod_eval=$_REQUEST['cod_eval'];
$cod_ban=$_REQUEST['cod_ban'];
$codest=$_REQUEST['codest'];
$id_grupo=$_REQUEST['id_grupo'];
$idgrupom5=$_REQUEST['cod_grupom5'];
$id_clase=$_REQUEST['id_clase'];
$id_subgrupo=$_REQUEST['sub_grupo'];
$tiposeval=$_REQUEST['tipoeval'];
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$fec_act=$fecha->format('Y-m-d');
$hr_act=$fecha->format('H:i:s');
$fec_pub_eval_hasta="";
include "../includes/conexion.php";
$q_bpreguntas="SELECT cod, fec_pub, cod_banco, tipo_tiempo, fec_hasta FROM aa_clases_virtuales_m5 where cod_gru=$id_grupo and cod_clase=$id_clase";
$clases = $bdcon->prepare($q_bpreguntas);
$clases->execute();
/*$query_gru= $bdcon->prepare("SELECT max(codreg) as id FROM aca_registroestmat WHERE codest='$codest'");
$query_gru->execute();
while ($row24 = $query_gru->fetch(PDO::FETCH_ASSOC)) {       
    $idgggrupo=$row24['id'];                         
    $query_nbgru= $bdcon->prepare("SELECT grupo FROM aca_registroestmat WHERE codreg='$idgggrupo'");
    $query_nbgru->execute();
    while ($row25 = $query_nbgru->fetch(PDO::FETCH_ASSOC)) {
        $nbgrupo=$row25['grupo']; 
    }
}*/
// echo "ops";
while ($feval = $clases->fetch(PDO::FETCH_ASSOC)) {
    $cod_eval=$feval['cod'];
    $fec_pub_eval=$feval['fec_pub'];
    $cod_ban=$feval['cod_banco'];
    $fec_pub_eval_hasta=$feval['fec_hasta']; 
    $t_tiempo=$feval['tipo_tiempo'];
}
// if ($nbgrupo=='TS') {
// 	# no controla hora
// }else{
	if ($t_tiempo==1) {
	    $tiemponb="6 minutos";                                              
	    $hrfin = strtotime ( '+0 hour' , strtotime ($hr_act) ) ; 
	    $hrfin = strtotime ( '+6 minute' , $hrfin ) ; 
	    $hrfin = strtotime ( '+0 second' , $hrfin ) ; 
	    $hrfin = date ( 'H:i:s' , $hrfin);     
	}elseif ($t_tiempo==2) {
	    $tiemponb="10 minutos";
	    $hrfin = strtotime ( '+0 hour' , strtotime ($hr_act) ) ; 
	    $hrfin = strtotime ( '+10 minute' , $hrfin ) ; 
	    $hrfin = strtotime ( '+0 second' , $hrfin ) ; 
	    $hrfin = date ( 'H:i:s' , $hrfin);     
	}else{   
		$t_tiempo=2;
	    $tiemponb="10 minutos";
	    $hrfin = strtotime ( '+0 hour' , strtotime ($hr_act) ) ; 
	    $hrfin = strtotime ( '+10 minute' , $hrfin ) ; 
	    $hrfin = strtotime ( '+0 second' , $hrfin ) ; 
	    $hrfin = date ( 'H:i:s' , $hrfin);    
	}
// }
	if ($tiposeval==1) {
		if ($fec_pub_eval==$fec_act) {
       
		}else{
		?>
		    <div class="alert alert-danger" align="center">¡Fuera de Fecha! fue registrado para el <?=$fec_pub_eval?></div>
		<?php
			exit();
		}
	}else{
		if ($fec_act>=$fec_pub_eval && $fec_act<=$fec_pub_eval_hasta) {
       
		}else{
		?>
		    <div class="alert alert-danger" align="center">¡Fuera de Fecha! fue registrado para el <?=$fec_pub_eval?></div>
		<?php
			exit();
		}	
	}

	$query_exis = $bdcon->prepare("SELECT cod FROM aa_clases_virtuales_m5_respuestas where cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest' limit 1");
	$query_exis->execute(); 
	 while ($r_exi = $query_exis->fetch(PDO::FETCH_ASSOC)) {
	 		 $exis=$r_exi['cod'];
	 }
	//$exis=$cons->cons_simple('aa_clases_virtuales_m5_respuestas',"cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest'",'cod');	 
	if($query_exis->rowCount()>0){
	//if ($exis>0) {
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-danger">		  
		    <strong>Error! </strong> Ya tiene registro de evaluación, no puede hacerlo dos veces.
		</div>
		<?php
		exit();
	}
	$query_cat = $bdcon->prepare("SELECT categoria,descr FROM plat_doc_banco_cat WHERE id='$cod_ban'");
	$query_cat->execute(); 	
	while ($fact = $query_cat->fetch(PDO::FETCH_ASSOC)) {	
		$cate=$fact['categoria'];
		$descr=$fact['descr'];
	}
	$fec_ini="";
	$fec_fin="";
	$q_p = $bdcon->prepare("SELECT id, pregunta, puntuacion, tipo, uov, imagen FROM plat_doc_banco_preg WHERE id_cat='$cod_ban' and pregunta!='' and (tipo='1' or tipo='3') ORDER BY rand() LIMIT 4");
	$q_p->execute(); 
?>
<div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
<form method="post" onsubmit="return false;" id="form_eval">
	<input type="hidden" name="cod_eval" value="<?php echo $cod_eval; ?>">
	<input type="hidden" name="cod_ban" value="<?php echo $cod_ban; ?>">
	<input type="hidden" name="codest" value="<?php echo $codest; ?>">
	<input type="hidden" name="cod_grupo" value="<?php echo $id_grupo; ?>">
	<input type="hidden" name="cod_grupom5" value="<?php echo $idgrupom5; ?>">
	<input type="hidden" name="cod_clase" value="<?php echo $id_clase; ?>">
	<input type="hidden" name="hora_ini" value="<?php echo $hr_act; ?>">
	<input type="hidden" name="hora_fin" value="<?php echo $hrfin; ?>">
	<input type="hidden" name="tipo_tiempo" value="<?php echo $t_tiempo; ?>">
	<input type="hidden" name="sub_grupo" value="<?php echo $id_subgrupo; ?>">
	<input type="hidden" id="resul" name="resul" value="">	
	<table class="table">
		<tr>
			<th>Nombre: </th>
			<td><?php echo $cate; ?></td>
			<th>Descripcion: </th>
			<td><?php echo $descr; ?></td>
		</tr>
		<?php
		// if ($nbgrupo=='TS') {
		// 	# code...
		// }else{
		?>
			<tr style="background-color: #A4E5A6;">			
				<th>Hora Inicio:</th>
				<td><?php echo $hr_act; ?></td>
				<th>Hora finalización:</th>
				<td><?php echo $hrfin; ?></td>			
			</tr>
		<?php
		// }
		?>		
	</table>
	<div class="table-responsive" class="panel-body" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none; font-size: 12px; " unselectable="on" onselectstart="return false;"  onmousedown="return false;">
	<table class="table table-condensed">
	<tr>
		<th>N°</th>
		<th>PREGUNTA</th>
		<th>TIPO</th>
	</tr>
	<?php 
	$cont=1;
	$nb_uov="";
	while ($f_p = $q_p->fetch(PDO::FETCH_ASSOC)) {
		$idp=$f_p['id'];
		$tipo=$f_p['tipo'];
		$uov=$f_p['uov'];
		$img=$f_p['imagen'];
		switch ($tipo) {
			case '1':
				$nb_tipo="Opción Multiple";
				break;
			case '2':
				$nb_tipo="Respuesta Corta";
				break;
			case '3':
				$nb_tipo="Verdadero / Falso";
				break;
			case '4':
				$nb_tipo="Descripción";
				break;
			default:
				$nb_tipo="N/A";
				break;
		}
		if ($uov=='1') {
			//solo una respuesta radio
			$nb_uov="(Una sola válida)";
		}else{
			if ($uov=='2') {
				//varias respuestas check
				$nb_uov="(Varias válidas)";	
			}else{
				$nb_uov="";
			}
		}
		?>
		<tr class="active">
			<th><?php echo $cont.".-"; ?><input type="hidden" name="preg[]" value="<?php echo $idp; ?>"></th>
			<th>
				<?php 
				echo $f_p['pregunta']; 
				if ($img!='') {
					?>
					<img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $img; ?>">
					<?php
				}
				?>				
			</th>			
			<th><?php echo $nb_tipo."<br>".$nb_uov; ?></th>
		</tr>
		<?php
		$q_r = $bdcon->prepare("SELECT id, eleccion, calif, imagen FROM plat_doc_banco_resp WHERE id_preg='$idp'");
		$q_r->execute();
		$fil=$q_r->rowCount();
		$fil=$fil*2;
		?>
		<tr>
			<td></td>
			<td>
				<?php 
				if ($tipo=='1') {
					//opcion multiple
					if ($uov=='1') {
						//solo una respuesta radio
						$type="radio";
						$func="";
					}else{
						//varias respuestas check
						$type="checkbox";
						$func="onclick='verificar_uno(".$idp.")'";
					}
					?>
					<table class="table table-condensed">
						<tr>
							<?php
							while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
								$idr=$fr['id'];
								$cal=$fr['calif'];
								$imgr=$fr['imagen'];
								$conten=$cal."|".$tipo."|".$idr;
								if ($uov=='1') {
									//solo una respuesta radio
									$type="radio";
									$func="";
								}else{
									//varias respuestas check
									$type="checkbox";
									$func="onclick='verificar_uno(".$idp.",".$idr.")'";
								}
								?>
								<td>
									<input class="checkbox-success" type="<?=$type?>" id="<?=$idr?>" name="<?=$idp?>[]" value="<?=$conten?>" <?=$func?> onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/>
									<label class="form-check-label" for="<?php echo $conten;?>"><?php echo $fr['eleccion'];?><?php 
									//echo $fr['eleccion']; 
									if ($imgr!='') {
										?>
										<img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $imgr; ?>">
										<?php
									}
									?></label>
								</td>												
								<?php
									}						
								?>
						</tr>							
					</table>
					<?php
					$nb_uov="";
				}else{
					if ($tipo=='2') {
						//respuesta corta
						?>
						<table class="table table-condensed">
							<tr>
								<td><input type="text" name="<?php echo $idp; ?>[]" class="form-control" value=""></td>
							</tr>
						</table>
						<?php
					}else{
						if ($tipo=='3') {
							//Verdadero Falso
							?>
							<table class="table table-condensed">
								<tr>
									<?php
									while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
									//while ($fr=mysql_fetch_array($q_r)) {
										$idre=$fr['id'];
										$val=$fr['calif'];
										$conten=$val."|".$tipo."|".$idre;
										$imgr=$fr['imagen'];
										?>
										<td>
											<input type="radio" name="<?=$idp?>[]" value="<?=$conten?>" onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/>
										</td>
										<td><?php echo $fr['eleccion']; ?>
											<?php
											if ($imgr!='') {
												?><br>
												<img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $imgr; ?>">
												<?php
											}
											?></td>
										<?php
									}
									?>
								</tr>
							</table>
							<?php
						}else{
							if ($tipo=='4') {
								//descripcion
								?>
								<table class="table table-condensed">
									<tr>
										<th colspan="<?php echo $fil; ?>">RESPUESTA</th>
									</tr>
									<tr>
										<td><textarea name="<?php echo $idp; ?>[]" class="form-control" rows="3"></textarea></td>
									</tr>
								</table>
								<?php
							}else{
								//no mostrar respuestas
							}
						}
					}
				}
				?>
			</td>
		</tr>
		<?php
		$cont++;
		$nb_uov="";
		$uov="";
	}
	?>
	</table>
	</div>
		<div align="center"><button id="btn_guardar_eval" class="btn btn-success">GUARDAR EVALUACION</button></div>
</form>
</div>
</div>
</div>
</div>

<script>
	iniciar_preguntas();

	$('#btn_guardar_eval').click(function() {
		str_pendientes = validar_respuestas();

		if(str_pendientes==""){
			guardar_eval();
			// alert("guardar");
		}else{
			alert("Debe de seleccionar al menos 1 opcion de respuesta por cada pregunta para poder continuar, las siguientes preguntas estan sin respuesta:\n \t\t\t"+str_pendientes);
		}
	});
</script>