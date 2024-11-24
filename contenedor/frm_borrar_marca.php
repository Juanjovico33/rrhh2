<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sis RRHH Reportes</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/night-mode.css">
<link rel="stylesheet" href="../css/framework.css">
<link rel="stylesheet" href="../css/bootstrap.css"> 
<?php
	include "../includes/conexion.php";
	include "../includes/_validar.php";
	include "../includes/user_session.php";
	$user=$_REQUEST['usuario'];
	$persona=$_REQUEST['per'];	
	$dia=$_REQUEST['dia'];
	$diacompl=$_REQUEST['diacompleto'];	
    $q_marca= $bdcon->prepare("UPDATE `marcadores` SET `estado`='0',`obs`='Eliminado por $user $diacompl' WHERE ci='$persona' and id='$dia'");
    $q_marca->execute();
?>			
</head>
<body>                  
	<div class="alert alert-success" role="alert">
		Eliminación correcta !...
	</div>
</body>
</html> 