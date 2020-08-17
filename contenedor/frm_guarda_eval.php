<?php 
include "../includes/conexion.php";

	$cod_eval=$_REQUEST['cod_eval'];
	$cod_ban=$_REQUEST['cod_ban'];
	$codest=$_REQUEST['codest'];
	$preg=$_REQUEST['preg'];
	$query_exis = $bdcon->prepare("SELECT cod FROM aa_clases_virtuales_m5_respuestas where cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest'");
	$query_exis->execute(); 
	 while ($r_exi = $query_exis->fetch(PDO::FETCH_ASSOC)) {
	 		 $exis=$r_exi['cod'];
	 }
	//$exis=$cons->cons_simple('aa_clases_virtuales_m5_respuestas',"cod_reg='$cod_eval' and cod_banco='$cod_ban' and codest='$codest'",'cod');
	//if ($exis>0) {
	if($query_exis->rowCount()>0){
		//ya existe registro de evaluacion de esa estudiante
		?>
	    <div class="alert alert-error">		  
		  <strong>Error! </strong> Ya tiene registro de evaluacion, no puede hacerlo dos veces.
		</div>
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
					$query_insert_m5d = $bdcon->prepare("INSERT INTO aa_clases_virtuales_m5_respuesta_deta VALUES ('0','$cod_eval','$codest','$cod_ban','$value','$idr','$nota','$deta')");
					$query_insert_m5d->execute(); 
					//$ins_deta=$cons->insertar('aa_clases_virtuales_m5_respuesta_deta',"'','$cod_eval','$codest','$cod_ban','$value','$idr','$nota','$deta'");
				}
			}else{
				$sum_resp=$sum_resp+0;
			}
			
			$cont_preg++;
		}
		$fec_act=date("Y-m-d");
		$hr_act=date("H:i:s");
		$ponde_act=$cont_preg*100;
		$nota=(($sum_resp*100)/$ponde_act);
		$query_insert_m5 = $bdcon->prepare("INSERT INTO aa_clases_virtuales_m5_respuestas VALUES ('0','$cod_eval','$codest','$cod_ban','$nota','$fec_act','$hr_act')");
		$query_insert_m5->execute();
		//$ins=$cons->insertar('aa_clases_virtuales_m5_respuestas',"'','$cod_eval','$codest','$cod_ban','$nota','$fec_act','$hr_act'");
		if ($query_insert_m5) {
			?>
		    <div class="alert alert-success">			 
			  <strong style="color: white;">Correcto! </strong> <font color="white">se guardó correctamente la evaluación y nota.</font>
			</div>
			<?php
		}else{
			?>
		    <div class="alert alert-error">			  
			  <strong>Error! </strong> no se pudo guardar, intente nuevamente mas tarde.
			</div>
			<?php
		}
	}
	

?>
<table class="table">
	<tr>
		<th>Preguntas: </th>
		<td><?php echo $cont_preg; ?></td>
	</tr>
	<tr>
		<th>Sumatoria puntaje: </th>
		<td><?php echo $sum_resp; ?></td>
	</tr>
	<tr>
		<th>Nota Final: </th>
		<td><?php echo $nota." pts."; ?></td>
	</tr>
</table>