<?php 
/**
 * 
 */
class internado_class
{
	
	function internado_class()
	{
		// code...
	}
	function verificar_registro($codest){
		include "../includes/conexion.php";
		$cod="";
		$q_reg=$bdcon->prepare("SELECT cod FROM plat_est_sol_hosp  WHERE codest='$codest'");
		$q_reg->execute();
		while ($freg=$q_reg->fetch(PDO::FETCH_ASSOC)) {
			$cod=$freg['cod'];
		}
		return $cod;
	}
	function obt_hospitales(){
		include "../includes/conexion.php";
		$datos="";
		$q_hosp=$bdcon->prepare("SELECT cod, hospital, tipo FROM plat_est_sol_hosp_nombres WHERE estado='1'");
		$q_hosp->execute();
		while ($fhosp=$q_hosp->fetch(PDO::FETCH_ASSOC)) {
			$cod=$fhosp['cod'];
			$nbh=$fhosp['hospital'];
			$tip=$fhosp['tipo'];
			if ($datos=='') {
				$datos=$cod.",".$nbh.",".$tip;
			}else{
				$datos=$datos."|".$cod.",".$nbh.",".$tip;
			}
		}
		return $datos;
	}
	function nb_estudiante($codest){
		include "../includes/conexion.php";
		$nbest="";
		$qnbest=$bdcon->prepare("SELECT nombcompleto FROM estudiante WHERE codest='$codest'");
		$qnbest->execute();
		while ($fnbest=$qnbest->fetch(PDO::FETCH_ASSOC)) {
			$nbest=$fnbest['nombcompleto'];
		}
		return $nbest;
	}
	function lista_dpto(){
		include "../includes/conexion.php";
		$qlist=$bdcon->prepare("SELECT id, opcion FROM lista_estados WHERE relacion='2' ORDER BY opcion");
		$qlist->execute();
		$var[]="";
		while ($flist=$qlist->fetch(PDO::FETCH_ASSOC)) {
			$id=$flist['id'];
			$op=$flist['opcion'];
			$var[$id]=$op;
		}
		return $var;
	}
	function lista_provincias(){
		include "../includes/conexion.php";
		$qlist=$bdcon->prepare("SELECT cod, opcion FROM lista_provincias WHERE relacion='32' ORDER BY cod");
		$qlist->execute();
		$var[]="";
		while ($flist=$qlist->fetch(PDO::FETCH_ASSOC)) {
			$id=$flist['cod'];
			$op=$flist['opcion'];
			$var[$id]=$op;
		}
		return $var;
	}
	function registrar_solicitud($codest,$per,$especial,$selec,$rural,$op1,$op2,$op3,$fech,$hora){
		include "../includes/conexion.php";
		$ins=$bdcon->prepare("INSERT INTO plat_est_sol_hosp VALUES('0','$codest','$per','$especial','$selec','$rural','$op1','$op2','$op3','$fech','$hora')");
		$ins->execute();
		if ($ins) {
			return true;
		}else{
			return false;
		}
	}
	function nb_opciones($opc){
		switch ($opc) {
			case '1':
				$txt="Realizar mi internado rotatorio en otro departamento en Bolivia.";
				break;
			case '2':
				$txt="Realizar la totalidad de mi internado rotatorio en el municipio de Santa Cruz de la Sierra.";
				break;
			case '3':
				$txt="Realizar mi internado rotatorio en una provincia de la ciudad de Santa Cruz. ";
				break;
			case '4':
				$txt="Realizar mi internado rotatorio en el extranjero. ";
				break;
			default:
				$txt="Error de seleccion.";
				break;
		}
		return $txt;
	}
	function nb_mes($mes){
		switch ($mes) {
			case '01':
				$nb_mes="enero";
				break;
			case '02':
				$nb_mes="febrero";
				break;
			case '03':
				$nb_mes="marzo";
				break;
			case '04':
				$nb_mes="abril";
				break;
			case '05':
				$nb_mes="mayo";
				break;
			case '06':
				$nb_mes="junio";
				break;
			case '07':
				$nb_mes="julio";
				break;
			case '08':
				$nb_mes="agosto";
				break;
			case '09':
				$nb_mes="septiembre";
				break;
			case '10':
				$nb_mes="octubre";
				break;
			case '11':
				$nb_mes="noviembre";
				break;
			case '12':
				$nb_mes="diciembre";
				break;
			default:
				$nb_mes="Error en el mes";
				break;
		}
		return $nb_mes;
	}
}
?>