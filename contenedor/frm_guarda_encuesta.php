<?php 
include "../includes/conexion.php";
$codgru=$_REQUEST['codgru'];
$codest=$_REQUEST['codest'];
$cod_encu=$_REQUEST['cod_encu'];
$v_preg=$_REQUEST['codpreg'];
$fecha=date("Y-m-d");
$hora=date("H:i");
for ($i=1; $i <=20 ; $i++) { 
	//echo "pregunta ".$i;
	//echo " - ";
	//echo "respuesta: ";
	@$resp=$_REQUEST[$i];
	$pr=$i-1;
	//echo " - ";
	//echo "codpreg: ";
	$codpreg=$v_preg[$pr];
	//echo "<br>";
	if ($resp=='SI') {
		$res_si=1;
		$res_no=0;
	}else{
		if ($resp=='NO') {
			$res_si=0;
			$res_no=1;
		}else{
			$res_si=0;
			$res_no=0;
		}
	}
	$ins_resp=$bdcon->prepare("INSERT INTO plat_est_encu_respuestas VALUES ('0','$cod_encu','$codest','$codpreg','$resp','$fecha','$hora','$codgru','1','0','$res_si','$res_no')");
	$ins_resp->execute();
}
?>
<div class="bg-gradient-success uk-light" uk-alert> <a class="uk-alert-close" uk-close></a> <p><b>Correcto!. </b>La encuesta se registró, puede volver a ingresar a materias registradas y seleccionar la materia para ver sus notas. </p> </div> </div>

