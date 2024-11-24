<?php 
function nb_materia($sigla, $car){
	include "../includes/conexion.php"; 
	$qnb=$bdcon->prepare("SELECT Descripcion FROM materias WHERE Sigla='$sigla' and CodCarrera='$car'");
	$qnb->execute();
	while ($fnb=$qnb->fetch(PDO::FETCH_ASSOC)) {
		$mate=$fnb['Descripcion'];
	}
	return $mate;
}
function nb_docente($coddoc){
	include "../includes/conexion.php"; 
	$qnb=$bdcon->prepare("SELECT nombres, apellidos FROM docentes WHERE id_docente='$coddoc'");
	$qnb->execute();
	while ($fnb=$qnb->fetch(PDO::FETCH_ASSOC)) {
		$nbd=$fnb['nombres'];
		$app=$fnb['apellidos'];
	}
	$nbcomp=$nbd." ".$app;
	return $nbcomp;
}
?>