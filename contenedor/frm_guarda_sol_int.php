<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Opciones</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="window.print()">IMPRIMIR</a>
      </li>
      
 
    </ul>
   
  </div>
</nav>
<?php 
$rural="";
$per=$_REQUEST['periodo'];
$codest=$_REQUEST['codest'];
$carr=$_REQUEST['carrera'];
$especial=$_REQUEST['especial'];
$dpto=$_REQUEST['dpto'];
$prov=$_REQUEST['prov'];
if ($dpto!='0') {
	$selec=$dpto;
}else{
	if ($prov!='0') {
		$selec=$prov;
	}else{
		$selec=0;
	}
}
@$rural=$_REQUEST['rural'];
if ($rural=='') {
	$rural='no';
}
$op1=$_REQUEST['op1'];
$op2=$_REQUEST['op2'];
$op3=$_REQUEST['op3'];
include "../includes/conexion.php";
include "class_internado.php";
$inter=new internado_class();
$fech=date("Y-m-d");
$hora=date("H:i");
$reg=$inter->registrar_solicitud($codest,$per,$especial,$selec,$rural,$op1,$op2,$op3,$fech,$hora);
if ($reg) {
	$list_h=$inter->obt_hospitales();
    $part_h=explode("|", $list_h);
	$nbest=$inter->nb_estudiante($codest);
	?>
	<div align="right">Santa Cruz, <?php echo $inter->nb_mes(date("m")); ?> de <?php echo date("Y"); ?></div>
	<br><br>
	Señores:<br>
	UNIVERSIDAD NACIONAL ECOLÓGICA <br>	
	Presente.- <br><br><br>

	<div align="right">REF.: <b>Solicitud de habilitación al Internado Rotatorio</b></div>
	<br>
	<br>
	Yo, <?php echo $nbest; ?> con código <?php echo $codest; ?>, estudiante de la carrera de <?php echo $carr; ?>, informo que habiendo concluido y aprobado las asignaturas requeridas para optar al INTERNADO ROTATORIO y así finalizar mi malla curricular;  que tomo conocimiento de los requisitos internos, así como de los plazos establecidos para la entrega de los mismos, con el propósito de presentar mi postulación para la HABILITACIÓN AL INTERNADO ROTATORIO según cronograma interno. <br>
    De la misma manera asumo el compromiso de cumplir con el tiempo de cada rotación en total apego a la normativa interna del establecimiento de salud asignado y en plena observancia del Reglamento del Internado Rotatorio emitido por el CRIDAIIC. <br>
    El incumplimiento de alguna de las rotaciones o la solicitud de futuras modificaciones a lo establecido en el cronograma inicial, me compromete por consecuencia, a asumir las medidas administrativas que la Universidad Nacional Ecológica y el CRIDAIIC, definan como amonestación y punición. <br> <br>
    Por otro lado, de manera especial solicito: <br>
    <?php 
    echo $inter->nb_opciones($especial);
    ?><br> <br>
    Finalmente, por las características particulares de mi postulación solicito de manera especial: <br>
    <b><?php echo strtoupper($rural); ?></b>, Iniciar el internado rotatorio con la rotación del SERVICIO SOCIAL RURAL OBLIGATORIO (Provincia).<br>
    <br>
    Consciente de la necesidad de sujetarme a la disponibilidad de los cupos dispuestos por el CRIDAIIC en el sistema hospitalario de nuestra región para realizar el Internado Rotatorio, es que solicito por orden de preferencia, realizar mi internado rotatorio en los siguientes hospitales: <br>
    <br>
    Opcion 1: 
    <?php 
    foreach ($part_h as $key => $value) {
        $dat_h=explode(",", $value);
        $cod=$dat_h[0];
        $nom=$dat_h[1];
        $tip=$dat_h[2];
        if ($cod==$op1) {
        	echo $nom;
        }
    }
    ?>
    <br>
    Opcion 2:
    <?php 
    foreach ($part_h as $key => $value) {
        $dat_h=explode(",", $value);
        $cod=$dat_h[0];
        $nom=$dat_h[1];
        $tip=$dat_h[2];
        if ($cod==$op2) {
        	echo $nom;
        }
    }
    ?>
    <br>
    Opcion 3: 
    <?php 
    foreach ($part_h as $key => $value) {
        $dat_h=explode(",", $value);
        $cod=$dat_h[0];
        $nom=$dat_h[1];
        $tip=$dat_h[2];
        if ($cod==$op3) {
        	echo $nom;
        }
    }
    ?>
    <br>
    <br>
    <br>
    <br>
    ____________________________ <br>
    Nombre de estudiante y firma
	<?php
}else{
	echo "Error al registrar su solicitud, intente nuevamente mas tarde.";
}
?>