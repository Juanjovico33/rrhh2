<?php 
$cod_act=$_REQUEST['cod_act'];
$cod_ban=$_REQUEST['cod_ban'];
$codest=$_REQUEST['codest'];
$cod_gru_aux=$_REQUEST['cod_gru_aux'];
$hora_final=$_REQUEST['h_fin'];
$parcial=1;
$ponde="";
?>
<?php 
include "../includes/conexion.php";
	$query= $bdcon->prepare("SELECT cod FROM plat_doc_intentos_est WHERE cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'");
    $query->execute();      
//$exis=$cons->cons_simple('plat_doc_intentos_est',"cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'",'cod');
    $query_periodo=$bdcon->prepare("SELECT periodo, CodCarrera, CodMateria, Descripcion FROM grupos WHERE CodGrupo='$cod_gru_aux'");
    $query_periodo->execute();
	$grupo_nb="";
	    while ($rowperi = $query_periodo->fetch(PDO::FETCH_ASSOC)) { 
			 $grupo_peri=$rowperi['periodo'];
			 $grupo_car=$rowperi['CodCarrera'];
			 $grupo_mat=$rowperi['CodMateria'];
			 $grupo_nb=$rowperi['Descripcion'];
		}
	$car=substr($grupo_car, 2);
	$per=substr($grupo_peri, 4);
	$mat=$grupo_mat;
	
	if($query->rowCount()>0){
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-danger">
			<strong>Error! </strong> Ya tiene registro de evaluación, no puede hacerlo dos veces.
		</div>
		<?php
		exit();
	}
	$q_acti= $bdcon->prepare("SELECT idgrupo, hasta, hora_h, parcial, obser FROM plat_doc_actividades WHERE id='$cod_act'");
    $q_acti->execute();   
    //$q_acti=mysql_query("SELECT idgrupo, hasta, hora_h, parcial, obser FROM plat_doc_actividades WHERE id='$cod_act'");
    while ($rowact = $q_acti->fetch(PDO::FETCH_ASSOC)) {       
        //$exis=$rowact['cod'];  
		$f_has=$rowact['hasta'];
		$h_has=$rowact['hora_h'];
		$idgru=$rowact['idgrupo'];
		$parcial=$rowact['parcial'];
		$obser=$rowact['obser'];
	}
	$q_act= $bdcon->prepare("SELECT categoria,descr FROM plat_doc_banco_cat WHERE id='$cod_ban'");
    $q_act->execute(); 
	//$q_act=mysql_query("SELECT categoria,descr FROM plat_doc_banco_cat WHERE id='$cod_ban'");
	while ($fact = $q_act->fetch(PDO::FETCH_ASSOC)) {   
		$cate=$fact['categoria'];
		$descr=$fact['descr'];
	}
	$querycarr= $bdcon->prepare("SELECT carrera FROM estudiante WHERE codest='$codest'");
    $querycarr->execute(); 
    while ($rowcarr = $querycarr->fetch(PDO::FETCH_ASSOC)) {   
		$carre=$rowcarr['carrera'];
	}
	//$carre=$cons->cons_simple('estudiante',"codest='$codest'",'carrera');
	$n_preg=0;
	/*if ($carre=='LICENCIATURA EN DERECHO') {
		$parcial=3;
	}else{
		$parcial=3;
	}*/
	if ($parcial=='' || $parcial=='0') {
		$parcial=1;
	}
	$querypon= $bdcon->prepare("SELECT porcentaje FROM aca_ponderacion WHERE cod_grupo='$idgru' and num_parcial='$parcial' and (campo='1' or campo='2' or campo='20' or campo='29' or campo='14')");
	//$ponde=$cons->cons_simple('aca_ponderacion',"cod_grupo='$idgru' and num_parcial='$parcial' and (campo='2' or campo='20' or campo='29')",'porcentaje');
    $querypon->execute(); 
    while ($rowpon = $querypon->fetch(PDO::FETCH_ASSOC)) {   
		$ponde=$rowpon['porcentaje'];
	}
	if ($per=='11') {
		$n_preg=40;
		$ponde=100;
		$cond_cb="id_cat='$cod_ban'";
	}else{
		
		 // $car=="ENF" && $mat=="ENF705" else if($codest==43440 && $per==202204 && $mat=="ING307")
		 // if ($per=='07' && $car="DER") {
		 // $mat=="BYF507" && 
		if ($codest==46449 && $per=='08') { // 43197,42004
			$n_preg=50;
			$ponde=100;
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		} else if ($grupo_nb=="TS" && $per=='08') { // NIVELACIÓN II/2022 --6? TS
			$n_preg=50;
			$ponde=100;
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		}else if (($codest==47873 || $codest==42907 || $codest==46897) && $per=='04') { // -- 46627
			$n_preg=50;
			$ponde=100;
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		}
		else if ($codest==44528 && $per=='04') {
			$n_preg=50;
			$ponde=100;
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		}
		else if ($per=='06' && $ponde==100) {
			$n_preg=50;
			$ponde=100;
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		}
		// RECUPERATORIOS
		else if ($per=='05' || $per=='07') {
			$n_preg=50;
			$ponde=100;
			
			$cond_cb="id_cat='$cod_ban'";
			$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			$q_ob->execute(); 
			while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
				$codban=$fob['codbanco'];
				$cond_cb=$cond_cb." or id_cat=".$codban;
			}
		}else{
			if ($parcial=='5') {
				# 2da instancia
				$n_preg=50;
				$ponde=100;
				$cond_cb="id_cat='$cod_ban'";
				$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			    $q_ob->execute(); 
			    while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {   
					$codban=$fob['codbanco'];
					$cond_cb=$cond_cb." or id_cat=".$codban;
				}
				//$q_ob=$cons->cons_cond('plat_doc_actividades_segundas',"codgrupo='$idgru'",'cod');		
			}else{
				if ($parcial=='6') {
					# 3ra instancia
					$n_preg=50;
					$ponde=100;
					$cond_cb="id_cat='$cod_ban'";
					$q_ob= $bdcon->prepare("SELECT codbanco FROM plat_doc_actividades_segundas WHERE codgrupo='$idgru' ORDER BY cod");
			    	$q_ob->execute(); 
					//$q_ob=$cons->cons_cond('plat_doc_actividades_terceras',"codgrupo='$idgru'",'cod');
					while ($fob = $q_ob->fetch(PDO::FETCH_ASSOC)) {
						$codban=$fob['codbanco'];
						$cond_cb=$cond_cb." or id_cat=".$codban;
					}
				}else if($parcial=="10"){
					$n_preg=15;
					$ponde=15;
				}else{
					if ($ponde=='') {
						if($parcial=="9"){
							$n_preg=10;
							$ponde=10;
						}else{
							$n_preg=15;
							$ponde=15;
						}	
					}else{
						if ($ponde=='30') {
							$n_preg=15;
						}else{
							if ($ponde=='50') {
								$n_preg=20;
							}else{
								if ($ponde=='70') {
									$n_preg=35;
								}else{
									if ($ponde=='100') {
										$n_preg=50;
									}else{
										if($ponde=='60'){
											$n_preg=20;
										}else{
											if($ponde=='40'){
												$n_preg=20;
											}else{
												$n_preg=$ponde;	
											}	
										}
									}
								}
							}
						}
					}
				}		
				$cond_cb="id_cat='$cod_ban'";
			}
		}
			
	}
	
	$fec_ini="";
	$fec_fin="";
	$q_p= $bdcon->prepare("SELECT id, pregunta, puntuacion, tipo, uov, imagen FROM plat_doc_banco_preg WHERE ($cond_cb) and (pregunta!='' or imagen!='') and (tipo='1' or tipo='3') ORDER BY rand() LIMIT $n_preg");
	// $q_p= $bdcon->prepare("SELECT id, pregunta, puntuacion, tipo, uov, imagen FROM plat_doc_banco_preg WHERE ($cond_cb) and pregunta!='' and (tipo='1' or tipo='3') ORDER BY rand() LIMIT $n_preg");
	$q_p->execute(); 
?>
<div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
<table class="table table-hover">
	<tr align="center">
		<td><strong>TE QUEDA !!!</strong></td>
		<td><h3><strong><div id="countdown"></div></strong></h3></td>
		<td style="color: green;"><strong>Tu Exámen Finaliza <?php echo $hora_final;?></strong></td>
	</tr>
</table>
<?php 
//$conver=convert_date($f_has, $h_has);
//$conver=date($f_has, $h_has);
//$conver=date_format($f_has, '$h_has');
/*echo $f_has;
echo "<br>";
echo $h_has;*/
?>
<form method="post" onsubmit="return false;" id="form_eval">
	<input type="hidden" name="cod_act" value="<?php echo $cod_act; ?>">
	<input type="hidden" name="cod_ban" value="<?php echo $cod_ban; ?>">
	<input type="hidden" name="codest" value="<?php echo $codest; ?>">
	<input type="hidden" name="cod_gru_aux" value="<?php echo $cod_gru_aux; ?>">
	<input type="hidden" name="parcial" value="<?php echo $parcial; ?>">
	<input type="hidden" id="resul" name="resul" value="">
<div class="table-responsive" class="panel-body" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;"unselectable="on" onselectstart="return false;"  onmousedown="return false;">
<table class="table table-sm">
	<tr>
		<th>Estudiante: </th>
		<td>
			<?php
				$querynb= $bdcon->prepare("SELECT nombcompleto FROM estudiante WHERE codest='$codest'");
			    $querynb->execute(); 
			    while ($rownb = $querynb->fetch(PDO::FETCH_ASSOC)) {   
					$estcompleto=$rownb['nombcompleto'];					
				}
				echo $codest." - ".$estcompleto;
		    ?>			
		</td>
		<th>Carrera: </th>
		<td><?php echo $carre; ?></td>
	</tr>
	<tr>
		<th>Nombre: </th>
		<td><?php echo $cate; ?></td>
		<th>Descripcion: </th>
		<td><?php echo $descr; ?></td>
	</tr>
	<tr>
		<th>Nro. Preguntas: </th>
		<td><?php echo $n_preg; ?></td>
		<th>Calificación sobre: </th>
		<td><?php echo $ponde; ?></td>
	</tr>
</table>
</div>
<div class="table-responsive"  class="table-responsive" class="panel-body" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;"unselectable="on" onselectstart="return false;"  onmousedown="return false;">
<table class="table table-sm">
	<tr>
		<th>N°</th>
		<th>PREGUNTA</th>
		<th>TIPO</th>
	</tr>
	<?php 
	$cont=1;
	$nb_uov="";

	// CICLO DE PREGUNTAS
	while ($f_p = $q_p->fetch(PDO::FETCH_ASSOC)) {
		$idp=$f_p['id'];
		$tipo=$f_p['tipo'];
		$uov=$f_p['uov'];
		$img=$f_p['imagen'];

		// Nombre para el tipo de pregunta
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

		// Opciones a seleccionar respuestas
		if ($uov=='1') {
			//solo una respuesta radio
			$nb_uov="(Una válida)";
		}else if ($uov=='2') {
			//varias respuestas check
			$nb_uov="(2 válidas)";	
		}else{
			$nb_uov="";
		}

		?>
		<tr class="active">
			<!-- Se carga array con ids de las preguntas en la variable PREG -->
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
			<th><small><?php echo $nb_tipo."<br>".$nb_uov; ?></small></th>
		</tr>
		<?php

		// OBTENER OPCIONES DEL ID PREGUNTA
		$q_r= $bdcon->prepare("SELECT id, eleccion, calif, imagen FROM plat_doc_banco_resp WHERE  id_preg='$idp'");
		$q_r->execute();
		$fil=$q_r->rowCount();

		$fil=$fil*2;
		?>
		<tr>
			<td></td>
			<td>
				<?php 
				if ($tipo=='1') {

					// OPCION MULTIPLE
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

							// OBTENER OPCIONES DE LA PREGUNTA
							while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {  

								$idr=$fr['id'];
								$cal=$fr['calif'];
								$imgr=$fr['imagen'];
								$conten=$idr."|".$tipo."|".$idr;
								
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
									<label class="form-check-label" for="<?=$conten?>"><?=$fr['eleccion']?><?php 
										//echo $fr['eleccion']; 
										if ($imgr!='') { ?>
											<img src="https://storage.googleapis.com/une_segmento-one/docentes/<?php echo $imgr; ?>"><?php
										} ?>
									</label>
								</td>
								<?php
							} ?>
						</tr>
					</table>
					<?php
					$nb_uov="";
				}else if ($tipo=='2') {
					// RESPUESTA CORTA --sin usar
					?>
						<table class="table table-condensed">
							<tr>
								<td><input type="text" name="<?=$idp?>[]" class="form-control" value=""></td>
							</tr>
						</table>
					<?php
				}else if ($tipo=='3') {
					// RESPUESTA VERDADERO/FALSO
					?>
					<table class="table table-condensed">
						<tr>
							<?php
							while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {

								$idre=$fr['id'];
								$val=$fr['calif'];
								$imgr=$fr['imagen'];
								$conten=$idre."|".$tipo."|".$idre;
								?>
								<td><input type="radio"  id="<?=$idre?>" name="<?=$idp?>[]" value="<?=$conten?>" onchange="set_respuesta(<?=$idp?>, <?=$cont?>, '<?=$idp?>[]')"/></td>
								<td>
									<?php 
									echo $fr['eleccion']; 
									if ($imgr!=''){
										echo '<img src="https://storage.googleapis.com/une_segmento-one/docentes/'.$imgr.'"/>';
									}
									?>
								</td>
								<?php

							}
							?>
						</tr>
					</table>
					<?php
				}else if ($tipo=='4') {
					// RESPUESTAS DESCRIPCION -- sin usar
					?>
					<table class="table table-condensed">
						<tr> <th colspan="<?=$fil?>">RESPUESTA</th> </tr>
						<tr> 
							<td><textarea name="<?=$idp?>[]" class="form-control" rows="3"></textarea></td>
						</tr>
					</table>
					<?php
				}else{
					//no mostrar respuestas
				}
				?>
			</td>
		</tr>
		<?php
		$cont++;
		$nb_uov="";
		$uov="";
	} // NEXT PREGUNTA
	?>
</table>
</div>
	<input type="hidden" name="num_preg" value="<?php echo $n_preg; ?>">
	<input type="hidden" name="hr_fin" id="hr_fin" value="<?php echo $h_has; ?>">
	<input type="hidden" name="ponde" id="ponde" value="<?php echo $ponde; ?>">
	<!--<button class="btn btn-success" onclick="guardar_evaluacion()">GUARDAR EVALUACION</button>-->
	<div align="center">
		<button class="btn btn-success" type="button" uk-toggle="target: #modal-example" onclick="guardar_evaluacion2()">GUARDAR EVALUACION</button>
	</div>
	<div id="modal-example" uk-modal>
	    <div class="uk-modal-dialog uk-modal-body">        
	        <div class="modal-body" id="mos_Eval">
	        	
	     	</div>        
	    </div>
	</div>
</form>
</div>
</div>
</div>
</div>
<script>
	iniciar_preguntas();
</script>