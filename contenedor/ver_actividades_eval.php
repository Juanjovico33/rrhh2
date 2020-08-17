<?php 
$cod_eval=$_REQUEST['cod_eval'];
$cod_ban=$_REQUEST['cod_ban'];
$codest=$_REQUEST['codest'];
include "../includes/conexion.php";
	$query_exis = $bdcon->prepare("SELECT cod FROM aa_clases_virtuales_m5_respuestas where cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest' ");
	$query_exis->execute(); 
	 while ($r_exi = $query_exis->fetch(PDO::FETCH_ASSOC)) {
	 		 $exis=$r_exi['cod'];
	 }
	//$exis=$cons->cons_simple('aa_clases_virtuales_m5_respuestas',"cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest'",'cod');	 
	if($query_exis->rowCount()>0){
	//if ($exis>0) {
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-error">		  
		  <strong>Error! </strong> Ya tiene registro de evaluación, no puede hacerlo dos veces.
		</div>
		<?php
		exit();
	}
	$query_cat = $bdcon->prepare("SELECT categoria,descr FROM plat_doc_banco_cat WHERE id='$cod_ban'");
	$query_cat->execute(); 
	//$q_act=mysql_query("SELECT categoria,descr FROM plat_doc_banco_cat WHERE id='$cod_ban'");
	//while ($fact=mysql_fetch_array($q_act)) {
	while ($fact = $query_cat->fetch(PDO::FETCH_ASSOC)) {	
		$cate=$fact['categoria'];
		$descr=$fact['descr'];
	}
	$fec_ini="";
	$fec_fin="";
	$q_p = $bdcon->prepare("SELECT id, pregunta, puntuacion, tipo, uov FROM plat_doc_banco_preg WHERE id_cat='$cod_ban'");
	$q_p->execute(); 
	//$q_p=mysql_query("SELECT id, pregunta, puntuacion, tipo, uov FROM plat_doc_banco_preg WHERE id_cat='$cod_ban'");

?>
<!--div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="#" onclick="window.print()">Imprimir</a></li>
    </ul>
  </div>
</div-->
<form method="post" onsubmit="return false;" id="form_eval">
	<input type="hidden" name="cod_eval" value="<?php echo $cod_eval; ?>">
	<input type="hidden" name="cod_ban" value="<?php echo $cod_ban; ?>">
	<input type="hidden" name="codest" value="<?php echo $codest; ?>">
<table class="table">
	<tr>
		<th>Nombre: </th>
		<td><?php echo $cate; ?></td>
		<th>Descripcion: </th>
		<td><?php echo $descr; ?></td>
	</tr>
	<!--tr>
		<th>Fecha Inicio: </th>
		<td><?php echo $fec_ini; ?></td>
		<th>Fecha Conclusión: </th>
		<td><?php echo $fec_fin; ?></td>
	</tr-->
</table>
<table class="table table-condensed">
	<tr>
		<th>N°</th>
		<th>PREGUNTA</th>
		<th>TIPO</th>
	</tr>
	<?php 
	$cont=1;
	$nb_uov="";
	//while ($f_p=mysql_fetch_array($q_p)) {
	while ($f_p = $q_p->fetch(PDO::FETCH_ASSOC)) {
		$idp=$f_p['id'];
		$tipo=$f_p['tipo'];
		$uov=$f_p['uov'];
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
			<th><?php echo $f_p['pregunta']; ?></th>
			<th><?php echo $nb_tipo."<br>".$nb_uov; ?></th>
		</tr>
		<?php
		$q_r = $bdcon->prepare("SELECT id, eleccion, calif FROM plat_doc_banco_resp WHERE id_preg='$idp'");
		$q_r->execute(); 
		//$q_r=mysql_query("SELECT id, eleccion, calif FROM plat_doc_banco_resp WHERE id_preg='$idp'");
		$fil=$q_r->rowCount();
		//$fil=mysql_num_rows($q_r);
		$fil=$fil*2;
		?>
		<tr>
			<td colspan="3">
				<?php 
				if ($tipo=='1') {
					//opcion multiple
					if ($uov=='1') {
						//solo una respuesta radio
						$type="radio";
					}else{
						//varias respuestas check
						$type="checkbox";
					}
					?>
					<table class="table table-condensed">
						<tr>
							<?php
							//while ($fr=mysql_fetch_array($q_r)) {
							while ($fr = $q_r->fetch(PDO::FETCH_ASSOC)) {
								$idr=$fr['id'];
								$cal=$fr['calif'];
								$conten=$cal."|".$tipo."|".$idr;
								?>
								<td>
									<input type="<?php echo $type; ?>" name="<?php echo $idp; ?>[]" value="<?php echo $conten; ?>"></td>
								<td><?php echo $fr['eleccion']; ?></td>
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
										?>
										<td><input type="radio" name="<?php echo $idp; ?>[]" value="<?php echo $conten; ?>"></td>
										<td><?php echo $fr['eleccion']; ?></td>
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
<button class="btn btn-success" onclick="guardar_eval()">GUARDAR EVALUACION</button>
</form>