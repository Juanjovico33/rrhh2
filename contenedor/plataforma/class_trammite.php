<?php 
class tramites{
	function tramites(){

	}
	function nb_tramite($tram){
		include "../../includes/conexion.php";
		$nomb="";
		$q_reg=$bdcon->prepare("SELECT nombre FROM plat_est_tramites_nombres  WHERE cod='$tram'");
		$q_reg->execute();
		while ($freg=$q_reg->fetch(PDO::FETCH_ASSOC)) {
			$nomb=$freg['nombre'];
		}
		return $nomb;
	}
	function costos_tramites($tram){
		include "../../includes/conexion.php";
		$datos="";
		$qdat=$bdcon->prepare("SELECT cuenta, nb_cuenta, monto FROM plat_est_tramites_costos WHERE cod_tramite='$tram'");

		$qdat->execute();
		while($fdat=$qdat->fetch(PDO::FETCH_ASSOC)){
			$cuenta=$fdat['cuenta'];
			$nbcuen=$fdat['nb_cuenta'];
			$monto=$fdat['monto'];
			if ($datos=='') {
				$datos=$cuenta.",".$nbcuen.",".$monto;	
			}else{
				$datos=$daots."|".$cuenta.",".$nbcuen.",".$monto;
			}
			
		}
		return $datos;
	}
	function verificar_pagos($codest){
		include "../../includes/conexion.php";
		$datos="";
		$q_reg=$bdcon->prepare("SELECT cod_diario FROM ca_diario_banco  WHERE codest='$codest' and cuenta='355'");
		$q_reg->execute();
		while ($freg=$q_reg->fetch(PDO::FETCH_ASSOC)) {
			$nume=$freg['cod_diario'];
			if ($datos=='') {
				$datos=$nume;
			}else{
				$datos=$datos."|".$nume;
			}
		}
		return $datos;
	}
	function verifica_usados($codest, $cod_diario){
		include "../../includes/conexion.php";
		$code="";
		$q_reg=$bdcon->prepare("SELECT cod FROM plat_est_tramites_historial  WHERE codest='$codest' and cod_diario='$cod_diario'");
		$q_reg->execute();
		while ($freg=$q_reg->fetch(PDO::FETCH_ASSOC)) {
			$code=$freg['cod'];
		}
		return $code;
	}
	function registrar_historial($codest, $cod_diario, $tramite){
		include "../../includes/conexion.php";
		$fec=date("Y-m-d");
		$regis=$bdcon->prepare("INSERT INTO plat_est_tramites_historial VALUES('0','$codest','$cod_diario','$tramite','$fec','1')");
		$regis->execute();
	}
	function historial($codest){
		include "../../includes/conexion.php";
		$qdat=$bdcon->prepare("SELECT cod, tramite, fecha_reg, estado FROM plat_est_tramites_historial WHERE codest='$codest' ORDER BY cod");
		$qdat->execute();
		while ($freg=$qdat->fetch(PDO::FETCH_ASSOC)) {
			$code=$freg['cod'];
			$trami=$freg['tramite'];
			$fec=$freg['fecha_reg'];
			$est=$freg['estado'];
			echo "<small>".$this->nb_tramites($trami)." ".$this->nb_estado($est)."</small>";
			echo "<br>";
		}
	}
	function nb_tramites($trami){
		switch ($trami) {
			case '1':
				$nb_tram="Auditoría de pagos";
				break;
			case '2':
				$nb_tram="Certificado alumno regular detallado ";
				break;
			case '3':
				$nb_tram="Certificado alumno regular extranjero";
				break;
			case '4':
				$nb_tram="Copia documentación con Programas analíticos";
				break;
			case '5':
				$nb_tram="Examen Preinternado";
				break;
			case '6':
				$nb_tram="Examen Preinternado fuera de fecha";
				break;
			case '7':
				$nb_tram="Examen Preinternado fuera de fecha mas multa ";
				break;
			default:
				//llamar a otra funcion para otros tramites
				break;
		}
		return $nb_tram;
	}
	function nb_estado($esta){
		switch ($esta) {
			case '1':
				$nb_esta="<font color='blue'>(Activo)</font>";
				break;
			case '2':
				$nb_esta="<font color='red'>(En Proceso)</font>";
				break;
			case '3':
				$nb_esta="<font color='green'>(Concluido)";
				break;
			default:
				$nb_esta="<font color='blue'>(Activo)</font>";
				break;
		}
		return $nb_esta;
	}
}
?>