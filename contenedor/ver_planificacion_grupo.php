<?php 
include ("../includes/_actividades.php");
include ("../includes/_planificacion.php");
include ("../includes/_event_log.php");

$codest=$_POST['_codest'];
$id_grupo=$_POST['_idgrupo'];
$otro_grupo=$_POST['_idgruporaiz'];
$sub_grupo=$_POST['_subgrupo'];

$grupo = new grupo();
$e = new evento();
$grupo->getDatosGrupo($id_grupo);
$e->setIdGrupo($id_grupo);
$e->setIdSubGrupo($sub_grupo);
$e->e_log_inicio_evento($codest, 21);
$_idgrupoRaiz=0;

if($otro_grupo!=0){
	if($grupo->esNivelacion()){
		$_idgrupoRaiz=$otro_grupo;
	}else{
		$_idgrupoRaiz=$grupo->getIdramaRaiz();
		if($_idgrupoRaiz==0){
			$_idgrupoRaiz=$id_grupo;
		}
	}
}else{
	$_idgrupoRaiz=$id_grupo;
}

$plan=new planificacion();
$plan->getAllModalidades();
$plan->getAllEvaluaciones();
$plan->getUnidades($_idgrupoRaiz, $sub_grupo); 
$plan->getTemas($_idgrupoRaiz, $sub_grupo);// aqui inicializamos el grupo que tiene la planificacion
	function code2utf($num)
	{
	   if($num<128)return chr($num);
	   if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
	   if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
	   if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
	   return '';
	}
	?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
             <div class="blog-post-content">
             <div class="uk-overflow-auto">
	<table class="table table-bordered table-condensed table-hover table-striped">
		<tr class="success">
			<th>N°</th>
			<th>Nro Clase</th>
			<th>Día</th>
			<th>Unidad</th>
			<th>Tema</th>
			<th>Modalidad</th>
			<th>Evaluación</th>
			<th>Avanzado</th>
		</tr>
	<?php
	$hay=$plan->nro;
	$i=0;
	$num=$i+1;
	if ($hay>0) {
		while($i<$hay) {
			$nro_clase=$plan->_temas[$i]->getNro_clase();
			$fecha=$plan->_temas[$i]->getFecha();
			$unidad=$plan->_temas[$i]->getUnidad()->getNombre();
			$tema=$plan->_temas[$i]->getTema();
			$modalidad=$plan->_temas[$i]->getModalidad()->getNombre();
			$evaluacion=$plan->_temas[$i]->getEvaluacion()->getNombre();
			$avance=$plan->_temas[$i]->getAvanzado();
			?>
			<tr>
				<td><?php echo $num; ?></td>
				<td><?=$nro_clase; ?></td>
				<td><?=$fecha; ?></td>
				<td><?=$unidad; ?></td>
				<td><?=$tema;?></td>
				<td><?=$modalidad; ?></td>
				<td><?=$evaluacion; ?></td>
				<td><?=$avance; ?></td>
			</tr>
			<?php $num=$num+1;$i++;
		}
	}else{
		?>
		<tr>
			<td colspan="8"><?php echo "No tiene planificacion semestral registrada"; ?></td>
		</tr>
		<?php
	}
	
	?>
	</table>
	</div>
		</div>
	</div>
</div>
</div>
