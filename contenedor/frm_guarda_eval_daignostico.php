<?php 
	include "../includes/conexion.php";
	$codest=$_REQUEST['codest'];
	$cod_ban=$_REQUEST['codban'];
	$carr=$_REQUEST['carrera'];
	$sem=$_REQUEST['semestre'];
	$cod_eval=$_REQUEST['cod_eval'];
	$sub_grupo='0';

	$preg=$_REQUEST['preg'];
	$fecha=new DateTime();
	$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
	$fec_act=$fecha->format('Y-m-d');
	$hr_act=$fecha->format('H:i:s');	                        

	/*$query_insert_m5 = $bdcon->prepare("INSERT INTO aa_clases_virtuales_m5_respuestas VALUES ('0','$cod_eval','$codest','$cod_ban','0',DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(now(),'%H:%i:%s'),'$idgrupom5', '$sub_grupo')");
	$query_insert_m5->execute();

	exit();*/
	    	
		    
	
	$query_exis = $bdcon->prepare("SELECT cod FROM plat_doc_intentos_est_diag where cod_act='$cod_eval' and cod_ban='$cod_ban' and codest='$codest'");
	$query_exis->execute();
	 while ($r_exi = $query_exis->fetch(PDO::FETCH_ASSOC)) {
	 	$exis=$r_exi['cod'];
	 }	
	if($query_exis->rowCount()>0){
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-error">		  
		  <strong>Error! </strong> Ya tiene registro de evaluacion diagnostica, no puede hacerlo dos veces.
		</div>
		<?php
		exit();
	}else{
		?>
		<table class="table table-condensed">
			<tr>
				<th colspan="3"> 
					<div class="alert alert-success" align="center">			 
			  			<strong>EVALUACION FINALIZADA</strong>
					</div>
				</th>
			</tr>
		<?php
			$sum_resp=0;
			$cont_preg=0;
			$cont_pregcorrectas=0;
			$n=1;
			$paux=0;
			foreach ($preg as $key => $value) {
				@$resp=$_REQUEST[$value];
				if (is_array($resp)) {
					foreach ($resp as $id => $val) {
						$part=explode("|", $val);
						if (count($part)>1) {
							$nota=$part[0];
							$idr=$part[2];
							$tipo=$part[1];
							$deta="";
							if ($nota==100) {
								$nota=1;
								$cont_pregcorrectas++;
							}elseif ($nota==50) {
								$nota=0.5;
								$cont_pregcorrectas=$cont_pregcorrectas+0.5;
							}
						}else{
							$nota=0;
							$idr=0;
							$tipo=0;
							$deta=$val;
						}
						?>
						<tr>
							<th>
								<?php
								if ($value!=$paux) {
									$query_pregunta= $bdcon->prepare("SELECT pregunta FROM plat_doc_banco_preg where id='$value'");
			       					$query_pregunta->execute();
			       					while ($row_preg = $query_pregunta->fetch(PDO::FETCH_ASSOC)) {
			       				 	echo $n.".- ".$nbpregunta=$row_preg['pregunta'];	
			       					}
			       					$paux=$value;
								}
								?>					
							</th>
							<th>
								NOTA
							</th>
							<th>
								OPCIÓN CORRECTA
							</th>
						</tr>
						<tr>
							<td>
							<?php 								
								$query_resp= $bdcon->prepare("SELECT eleccion FROM plat_doc_banco_resp where id='$idr'");
			       				$query_resp->execute();	       				
			       				while ($row_resp = $query_resp->fetch(PDO::FETCH_ASSOC)) {
			       				 	$nbresp=$row_resp['eleccion'];
			       				}
			       				if ($nota=="2.5" || $nota=="1.25") {
			       					?>
			       					<div style="color:green;"><?php echo "Resp.- ".$nbresp;?></div>
			       					<?php
			       				}else{
			       					?>
			       					<div style="color:red;"><?php echo "Resp.- ".$nbresp;?></div>
			       					<?php
			       				}		
								?>					
							</td>
							<td>
								<?php 						
								if ($nota=="2.5" || $nota=="1.25") {
									if($sub_grupo==0){
										?>
											<div style="color:green;"><?php echo $nota;?></div>
										<?php 
									}
			       				}else{
									if($sub_grupo==0){
										?>
											<div style="color:red;"><?php echo $nota;?></div>
										<?php
									}
			       				}
								?>	
							</td>
							<td>
								<?php 						
								if ($nota=="1" || $nota=="0.5") {
			       					?>
			       					<div align="center"><img src='img/iconos/bien2.png' class='img-fluid' alt='Responsive image'></div>
			       					<?php
			       				}else{
			       					?>
			       					<div style="color:green;">
			       						<?php
											$query_correcta= $bdcon->prepare("SELECT eleccion FROM plat_doc_banco_resp where id_preg='$value' and calif>0");
						       				$query_correcta->execute();
						       				while ($row_correcta = $query_correcta->fetch(PDO::FETCH_ASSOC)) {
						       				 	echo $nbcorrecta=$row_correcta['eleccion'];	
						       				 	echo ",";
						       				}								
			       						?>
			       					</div>
			       					<?php
			       				}
								?>
							</td>
						</tr>
						<?php
						$sum_resp=$sum_resp+$nota;
						$query_insert_m5d = $bdcon->prepare("INSERT INTO plat_doc_intentos_est_diag_deta VALUES ('0','$cod_eval','$codest','$cod_ban','$value','$idr','$nota','$deta')");
						$query_insert_m5d->execute(); 
					}				
				}else{
					?>
					<tr>
						<th>
							<?php
							if ($value!=$paux) {
								$query_pregunta= $bdcon->prepare("SELECT pregunta FROM plat_doc_banco_preg where id='$value'");
								$query_pregunta->execute();
								while ($row_preg = $query_pregunta->fetch(PDO::FETCH_ASSOC)) {
								echo $n.".- ".$nbpregunta=$row_preg['pregunta'];	
								}
								$paux=$value;
							}
							?>					
						</th>
						    <th>NOTA</th><th>OPCIÓN CORRECTA</th>
						</tr>
						<tr>
							<td>
								<div style="color:red;"><?php echo "Resp.- No tiene ninguna respuesta seleccionada";?></div>				
							</td>
							<td>
								<div style="color:red;"><?php echo "0.00";?></div>
							</td>
							<td>
								<div align="center"><img src='img/iconos/mal2.png' class='img-fluid' alt='Responsive image'></div>
							</td>
						</tr>
					<?php
					// Cuando no respondio el estudiante
					$query_insert_m5d = $bdcon->prepare("INSERT INTO plat_doc_intentos_est_diag_deta VALUES ('0','$cod_eval','$codest','$cod_ban','$value','0','0.00','Sin respuesta')");
						$query_insert_m5d->execute(); 
					$sum_resp=$sum_resp+0;
				}			
				$cont_preg++;
				$n++;
			}
		?>
		</table>	
		<?php
		//$ponde_act=$cont_preg*100;

		$notaf=$sum_resp;
		$query_insert_m5 = $bdcon->prepare("INSERT INTO plat_doc_intentos_est_diag VALUES ('0','$cod_eval','$cod_ban','$codest','$notaf',DATE_FORMAT(now(),'%Y-%m-%d'),DATE_FORMAT(now(),'%H:%i:%s'),'$carr','$sem','1')");
		$query_insert_m5->execute();
		if ($query_insert_m5) {
			?>
		    <div class="alert alert-success" align="center">			 
			   	<strong>Correcto! </strong> <font>se guardó correctamente la evaluación diagnóstica.</font>
			</div>
			<?php
		}else{
			?>
		    <div class="alert alert-error" align="center">			  
			   	<strong>Error! </strong> no se pudo guardar, Verifique su internet y vuelva a intentar.
			</div>
			<?php
		}
	}
?>
<div class="alert alert-success" align="center">
	<table class="table table-condensed" style="background: url(img/trebol_fons.png)">
		<tr>
			<th>Preguntas: </th>
			<td><?php echo $cont_preg;?></td>
		</tr>
		<tr>
			<th>Preguntas Correctas: </th>
			<td><?php echo number_format($cont_pregcorrectas,2);?></td>
		</tr>
		<tr>
			<th>Nota Final: </th>
			<td><?php				
					echo number_format($notaf,2)." pts.";				
			?></td>
		</tr>
	</table>
</div>