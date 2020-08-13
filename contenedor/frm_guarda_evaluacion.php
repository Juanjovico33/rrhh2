<?php 
include "../includes/conexion.php";
	$cod_act=$_REQUEST['cod_act'];
	$cod_ban=$_REQUEST['cod_ban'];
	$codest=$_REQUEST['codest'];
	$cod_gru_aux=$_REQUEST['cod_gru_aux'];
	$parcial=$_REQUEST['parcial'];
	$preg=$_REQUEST['preg'];
	echo $hr_fin=$_REQUEST['hr_fin'];
	$ponde=$_REQUEST['ponde'];
	$not_conv=0;
	@$num_preg=$_REQUEST['num_preg'];
	if ($num_preg=='') {
		$num_preg=20;
	}
	$fec_act=date("Y-m-d");
	$hr_act=date("H:i:s");
	if ($hr_fin=='') {
		echo "<h2>Fuera de hora, hora expirada.</h2>";
		$query_insert= $bdcon->prepare("INSERT INTO plat_est_fuera_hr VALUES ('0','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin')");
   		$query_insert->execute();
		//$ins_fue=$cons->insertar('plat_est_fuera_hr',"'','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin'");
		exit();
	}else{
		if ($hr_act>$hr_fin) {
			echo "<h2>Fuera de hora, ya no puede registrar respuestas.</h2>";
			$query_insert= $bdcon->prepare("INSERT INTO plat_est_fuera_hr VALUES ('0','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin')");
   			$query_insert->execute();
		//$ins_fue=$cons->insertar('plat_est_fuera_hr',"'','$codest','$cod_act','$cod_ban','$parcial','$fec_act','$hr_act','$hr_fin'");
			exit();
		}
	}

	$queryex= $bdcon->prepare("SELECT cod FROM plat_doc_intentos_est WHERE cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'");
	$queryex->execute(); 
		$exis=0;
	    while ($rowexi = $queryex->fetch(PDO::FETCH_ASSOC)) {   
			$exis=$rowexi['cod'];			
		}
	//$exis=$cons->cons_simple('plat_doc_intentos_est',"cod_act='$cod_act' and cod_ban='$cod_ban' and codest='$codest'",'cod');
	if ($exis>0) {
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-error">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Error! </strong> Ya tiene registro de evaluacion, no puede hacerlo dos veces.
		</div>
		<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ver_cronograma('<?php echo $codest; ?>','<?php echo $codest; ?>')">Cerrar</button>
		<?php
		exit();
	}else{
		$sum_resp=0;
		$cont_preg=0;
		foreach ($preg as $key => $value) {
			//echo $key." ".$value;
			//echo "<br>";
			@$resp=$_REQUEST[$value];
			if (is_array($resp)) {
				foreach ($resp as $id => $val) {
					//echo $id." -> ".$val;
					//echo "<br>";
					$part=explode("|", $val);
					if (count($part)>1) {
						$nota=$part[0];
						$idr=$part[2];
						$tipo=$part[1];
						$deta="";
					}else{
						$nota=0;
						$idr=0;
						$tipo=0;
						$deta=$val;
					}
					$sum_resp=$sum_resp+$nota;
					$query_insertplat= $bdcon->prepare("INSERT INTO plat_doc_intentos_est_deta VALUES ('0','$cod_act','$codest','$cod_ban','$value','$idr','$nota','$deta')");
   					$query_insertplat->execute();
					//$ins_deta=$cons->insertar('plat_doc_intentos_est_deta',"'','$cod_act','$codest','$cod_ban','$value','$idr','$nota','$deta'");
				}
			}else{
				$sum_resp=$sum_resp+0;
			}
			
			$cont_preg++;
		}
		$fec_act=date("Y-m-d");
		$hr_act=date("H:i:s");
		//$cont_preg=15;
		$q_rg= $bdcon->prepare("SELECT  SUM(nota)as notita FROM plat_doc_intentos_est_deta WHERE cod_act='$cod_act' and codest='$codest' and cod_banco='$cod_ban' GROUP BY cod_preg");
	    $q_rg->execute();  
		//$q_rg=mysql_query("SELECT SUM(nota)as notita FROM plat_doc_intentos_est_deta WHERE cod_act='$cod_act' and codest='$codest' and cod_banco='$cod_ban' GROUP BY cod_preg");
		$cont_preg=0;
		$sum_resp=0;
		//while ($frg=mysql_fetch_array($q_rg)) {
		while ($frg = $q_rg->fetch(PDO::FETCH_ASSOC)) {   
			$nota=$frg['notita'];
			$sum_resp=$sum_resp+$nota;
			$cont_preg++;
		}
		$ponde_act=$ponde*100;
		//echo "<br>";
		$nota=(($sum_resp*$ponde)/$ponde_act);
		//echo "<br>";
		$not_conv=($nota*$ponde)/$num_preg;
		//echo "<br>";
		$query_insertplatdoc= $bdcon->prepare("INSERT INTO plat_doc_intentos_est VALUES ('0','$cod_act','$cod_ban','$codest','$not_conv','$fec_act','$hr_act','$cod_gru_aux','$parcial','1')");
   		$query_insertplatdoc->execute();
		//$ins=$cons->insertar('plat_doc_intentos_est',"'','$cod_act','$cod_ban','$codest','$not_conv','$fec_act','$hr_act','$cod_gru_aux','$parcial','1'");
		if ($query_insertplatdoc) {
			?>
		    <div class="alert alert-success">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Correcto! </strong> <font color="black">se guardó correctamente la evaluación y nota.</font>
			</div>
			<?php
		}else{
			?>
		    <div class="alert alert-error">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Error! </strong> no se pudo guardar, intente nuevamente mas tarde.
			</div>
			<?php
		}
	}
//echo "<h3>Evaluación finalizada</h3>";
//echo "<br>";
?>
<table class="table">
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
		<th>Sigla Materia: </th>
		<td>
			<?php
				$nsigla="";
				$querysigla= $bdcon->prepare("SELECT CodMateria FROM grupos WHERE CodGrupo='$cod_gru_aux'");
				$querysigla->execute();
				while ($rowsigla = $querysigla->fetch(PDO::FETCH_ASSOC)) {   
						$nsigla=$rowsigla['CodMateria'];			
				}
			    //echo $cons->cons_simple('grupos',"CodGrupo='$cod_gru_aux'",'CodMateria'); 
			    echo $nsigla;
			?>
		</td>
	</tr>
	<tr>
		<th>Hora actual: </th>
		<td><?php echo date("H:i"); ?></td>
	</tr>
	<tr>
		<th>Preguntas: </th>
		<td><?php echo $cont_preg; ?></td>
	</tr>
	<!--tr>
		<th>Sumatoria puntaje: </th>
		<td><?php //echo $sum_resp; ?></td>
	</tr-->
	<tr>
		<th>Nota Final: </th>
		<td><?php echo "<h4><b>NOTA: </b>".number_format($not_conv,2)." pts. de ".$ponde." pts.</h4>"; ?></td>
	</tr>
</table>
<!--<div class="modal-footer">
	<?php 
	//if ($not_conv>0) {
		?>
		<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="ver_cronograma('<?php //echo $codest; ?>','<?php //echo $codest; ?>')">Cerrar</button>
		<?php
	//}else{
		?>
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		<?php
	//}
	?>
</div>-->