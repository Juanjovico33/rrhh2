<?php
session_start(); 
include "../includes/conexion.php";
include "../includes/_validar.php";
	$cod_act=$_REQUEST['cod_act'];
	$cod_ban=$_REQUEST['cod_ban'];
	$codest=$_REQUEST['codest'];
	$cod_gru_aux=$_REQUEST['cod_gru_aux'];
	$parcial=$_REQUEST['parcial'];
	$preg=$_REQUEST['preg'];
	$respuestas = $_REQUEST['resul'];
	$pr_array = json_decode($respuestas);
	$hr_fin=$_REQUEST['hr_fin'];
	$ponde=$_REQUEST['ponde'];
	$not_conv=0;
	@$num_preg=$_REQUEST['num_preg'];
	if ($num_preg=='') {
		$num_preg=20;
	}
	$fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $fec_act=$fecha->format('Y-m-d');
    $hr_act=$fecha->format('H:i:s');  
	//$fec_act=date("Y-m-d");
	//$hr_act=date("H:i:s");

	// $ver = new validar();
	// if (!isset($_SESSION['user']) || $_SESSION['user']!=$codest){
	// 	echo "No se puede guardar este examen, sesión nula!"; exit;
	// } else if ($ver->validarEstudiante($codest) && $ver->validarGrupo($cod_gru_aux, $codest) && $ver->validarActividad($cod_act, $cod_gru_aux)){
		if ($hr_fin=='') {
			echo "<h2>Fuera de hora, hora expirada.</h2>";
			$query_insert= $bdcon->prepare("INSERT INTO plat_est_fuera_hr VALUES ('0','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin')");
			$query_insert->execute();
			//$ins_fue=$cons->insertar('plat_est_fuera_hr',"'','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin'");
			exit();
		}else{
			if ($hr_act>$hr_fin) {
				?>
				<div class="alert alert-danger"><strong>Fuera de hora</strong><br> ya no puede registrar respuestas.</div>
				<div class='modal-footer'>
					<button class="uk-button uk-button-default uk-modal-close" type="button" onclick="ver_materias_examenes('<?php echo $codest; ?>')">Cerrar</button></div>
				<?php
				$query_insert= $bdcon->prepare("INSERT INTO plat_est_fuera_hr VALUES ('0','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin')");
				$query_insert->execute();
				//$ins_fue=$cons->insertar('plat_est_fuera_hr',"'','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin'");
				exit();
			}
		}
		$queryex= $bdcon->prepare("SELECT cod FROM plat_doc_intentos_est WHERE cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'");
		$queryex->execute(); 
		//$exis=$cons->cons_simple('plat_doc_intentos_est',"cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'",'cod');
		if($queryex->rowCount()>0){
			//ya existe registro de evaluacion de esa estudiante
			?>
			<div class="alert alert-danger">
				<strong>Error! </strong> Ya tiene registro de evaluacion, no puede hacerlo dos veces.
			</div>
			<div class="modal-footer">
				<button class="uk-button uk-button-default uk-modal-close" type="button" onclick="ver_materias_examenes('<?php echo $codest; ?>')">Cerrar</button>
			</div>	
			<?php
			exit();
		}else{
			$sum_resp=0;
			$cont_preg=0;
			foreach ($preg as $key => $idPreg) {
			// foreach ($pr_array as $key => $idPreg) {

				@$_respuestas=$_REQUEST[$idPreg];
				// echo $_preg[0];
				// exit;
				if (is_array($_respuestas)) {
					// SI TIENE UNO O MAS RESPUESTAS SELECCIONADAS
					foreach ($_respuestas as $key => $resp_conten) {
						$part=explode("|", $resp_conten);
						if (count($part)>1) {
							$id_nota=$part[0];

							$q_calif= $bdcon->prepare("SELECT calif FROM plat_doc_banco_resp WHERE id='$id_nota'");
							$q_calif->execute(); 
							while ($row_calif = $q_calif->fetch(PDO::FETCH_ASSOC)) {
								$nota=$row_calif['calif'];
								if($nota==''){
									$nota='0.00';
								}
							}

							$idResp=$part[2];
							$tipo=$part[1];
							$deta="";
						}else{
							$nota=0;
							$idResp=0;
							$tipo=0;
							$deta=$resp_conten;
						}

						// $q_calif= $bdcon->prepare("SELECT calif FROM plat_doc_banco_resp WHERE id='$idResp'");
						// $q_calif->execute(); 
						// while ($row_calif = $q_calif->fetch(PDO::FETCH_ASSOC)) {
						// 	$nota=$row_calif['calif'];
						// 	if($nota==''){
						// 		$nota='0.00';
						// 	}
						// }

						$sum_resp=$sum_resp+$nota;
						$query_insertplat= $bdcon->prepare("INSERT INTO plat_doc_intentos_est_deta VALUES ('0','$cod_act','$codest','$cod_ban','$idPreg','$idResp','$nota','')");
						$query_insertplat->execute();
					}
				}else{
					// NO RESPONDIO LA PREGUNTA, LA DEJO VACIA O NO TENIA OPCIONES, UPS A ESTO ULTIMO
					$query_insertplat= $bdcon->prepare("INSERT INTO plat_doc_intentos_est_deta VALUES ('0','$cod_act','$codest','$cod_ban','$idPreg','0','0','Sin responder')");
					$query_insertplat->execute();
					$sum_resp=$sum_resp+0;
				}
				$cont_preg++;
			}
			$fecha=new DateTime();
			$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
			$fec_act=$fecha->format('Y-m-d');
			$hr_act=$fecha->format('H:i:s');  
			//$fec_act=date("Y-m-d");
			//$hr_act=date("H:i:s");
			//$cont_preg=15;
			$q_rg= $bdcon->prepare("SELECT  SUM(nota)as notita FROM plat_doc_intentos_est_deta WHERE cod_act='$cod_act' and codest='$codest' and cod_banco='$cod_ban' GROUP BY cod_preg");
			$q_rg->execute(); 
			//$q_rg=mysql_query("SELECT SUM(nota)as notita FROM plat_doc_intentos_est_deta WHERE cod_act='$cod_act' and codest='$codest' and cod_banco='$cod_ban' GROUP BY cod_preg");
			$cont_preg=0;
			$sum_resp=0;
			while ($frg = $q_rg->fetch(PDO::FETCH_ASSOC)) {   
			//while ($frg=mysql_fetch_array($q_rg)) {
				$nota=$frg['notita'];			
				$sum_resp=$sum_resp+$nota;
				$cont_preg++;
			}
			$ponde_act=$ponde*100;	
			$nota=(($sum_resp*$ponde)/$ponde_act);
			$not_conv=($nota*$ponde)/$num_preg;
			$query_insertplatdoc= $bdcon->prepare("INSERT INTO plat_doc_intentos_est VALUES ('0','$cod_act','$cod_ban','$codest','$not_conv','$fec_act','$hr_act','$cod_gru_aux','$parcial','1')");
			$query_insertplatdoc->execute();
			//$ins=$cons->insertar('plat_doc_intentos_est',"'','$cod_act','$cod_ban','$codest','$not_conv','$fec_act','$hr_act','$cod_gru_aux','$parcial','1'");
			if ($query_insertplatdoc) {
				?>
				<div class="alert alert-success">
				<strong>Correcto! </strong> <font color="black">se guardó correctamente la evaluación y nota.</font>
				</div>
				<?php
			}else{
				?>
				<div class="alert alert-danger">
					<strong>Error! </strong> no se pudo guardar, intente nuevamente.
				</div>
				<div class="modal-footer">
					<button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>
				</div>
				<?php
			}
		}

		echo "<h3>Evaluación finalizada</h3>";
		echo "<br>";
		?>
		<table class="table" style="background: url(img/trebol_fons.png)">
			<tr>
				<th>Codigo: </th>
				<td><?php echo $codest; ?></td>
			</tr>
			<tr>
				<th>Nombre: </th>
				<td><?php 
						$nomb="";
						$querynomb= $bdcon->prepare("SELECT nombcompleto FROM estudiante WHERE codest='$codest'");
						$querynomb->execute();
						while ($rownom = $querynomb->fetch(PDO::FETCH_ASSOC)) {   
								$nomb=$rownom['nombcompleto'];			
						}
						//echo $cons->cons_simple('estudiante',"codest='$codest'",'nombcompleto'); 
						echo $nomb;
					?>
				</td>
			</tr>
			<tr>
				<th>Materia: </th>
				<td><?php
						$nsigla="";
						$querysigla= $bdcon->prepare("SELECT CodMateria,periodo,Descripcion FROM grupos WHERE CodGrupo='$cod_gru_aux'");
						$querysigla->execute();
						while ($rowsigla = $querysigla->fetch(PDO::FETCH_ASSOC)) {   
								$nsigla=$rowsigla['CodMateria'];	
								$per=$rowsigla['periodo'];	
								$desc=$rowsigla['Descripcion'];			
						}
						//echo $cons->cons_simple('grupos',"CodGrupo='$cod_gru_aux'",'CodMateria'); 
						echo $per." ".$nsigla." ".$desc;
					?>
				</td>
			</tr>
			<tr>
				<th>Fecha y hora actual: </th>
				<td><?php echo $fec_act."  ".$hr_act; ?></td>
			</tr>
			<tr>
				<th>Preguntas respondidas: </th>
				<td><?php echo $cont_preg; ?></td>
			</tr>
			<!--tr>
				<th>Sumatoria puntaje: </th>
				<td><?php //echo $sum_resp; ?></td>
			</tr-->
			<tr>
				<th>Nota Final: </th>
				<?php
				$notaexmane=number_format($not_conv,2);			
					if ($notaexmane>$ponde) {
						?>
						<td>
							<div class="alert alert-danger">
								<strong>Contáctese con su Jefatura de Carrera para Validar tu nota</strong>
							</div>
						</td>
						<?php
					}else{
						?>
						<td style="background: url(img/trebol_fonss.png)"><?php echo "<h4><b>NOTA: </b>".$notaexmane." pts. de ".$ponde." pts.</h4>"; ?></td>
						<?php
					}
				?>
				
			</tr>
		</table>
		<div class="modal-footer">	
			<button class="uk-button uk-button-default uk-modal-close" type="button" onclick="ver_materias_examenes('<?php echo $codest; ?>')">Cerrar</button>
		</div>
	<?php 
	// } else{
	// 	session_unset();
    //     session_destroy();
	// 	echo "ops!";
	// } ?>