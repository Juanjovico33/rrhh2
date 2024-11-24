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
$user = new UserSession();    
$usuario=$user->getCurrentUser();
?>
	<?php
		$persona=$_REQUEST['_ci'];
		$dia=$_REQUEST['_id'];	
		$fecha=new DateTime();
	    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
	    $fec_act=$fecha->format('Y-m-d');
	    $hr_act=$fecha->format('H:i:s');
	    $dia_completo= $fec_act."-".$hr_act;
	    $q_marca= $bdcon->prepare("SELECT hora, fecha FROM marcadores where id = '$dia'");
	    $q_marca->execute();	   
	    while ($mquery = $q_marca->fetch(PDO::FETCH_ASSOC)){  				       
		    $horamarca = $mquery['hora'];
		    $fechamar = $mquery['fecha'];	   	
		}
		$q_per= $bdcon->prepare("SELECT nombcompleto FROM persona where ci = '$persona'");
	    $q_per->execute();	  
	    while ($perquery = $q_per->fetch(PDO::FETCH_ASSOC)){  				       
		    $pernomb = $perquery['nombcompleto'];		   
		}
	?>			
</head>
<body>	
	<div align="center">
	<br>
	<br>
		<form id="form1" name="form1" method="post" action="frm_borrar_marca.php">
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
			<input type="hidden" name="per" id="per" value="<?php echo $persona;?>">
			<input type="hidden" name="dia" id="dia" value="<?php echo $dia;?>">
			<input type="hidden" name="diacompleto" id="diacompleto" value="<?php echo $dia_completo;?>">
			<h1>¿ESTA SEGURO DE ELIMINAR LA MARCACIÓN?</h1>
			<p>de <?php echo  $pernomb;?></p>	
			<p>la hora: <?php echo  $horamarca." de la fecha: ". $fechamar;?></p>
			 <button class="btn btn-success" type="submit">SI</button>
	</form>		
	</div>			
</body>
</html>                   
