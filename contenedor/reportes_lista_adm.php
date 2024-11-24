<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sis RRHH Reportes</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/night-mode.css">
<link rel="stylesheet" href="../css/framework.css">
<link rel="stylesheet" href="../css/bootstrap.css"> 
<style media='print'>
td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
input{display:none;} /* esto oculta los input cuando imprimes */
.noimpri{
	color:#FFF;
	display:none;
}
</style>
<style media='screen'>
input{
	display:block;
} /* muestra los input en la pantalla */
.noimpri{
	cursor:hand;
	font-size:9px;
	display:block;
	align: right;	
}
</style>
</head>
<body>
<?php
 include "../includes/user_session.php";
 include "../includes/conexion.php";
    $user = new UserSession();    
    $usuario=$user->getCurrentUser();	    
    $sucursal=$_REQUEST['sucursal'];	
    $q_persona= $bdcon->prepare("SELECT id, nombcompleto, ci, fechanaci, sexo, estado_civil, cargo, depar, sucursal, correo FROM persona where sucursal='$sucursal' order by nombcompleto asc");
    $q_persona->execute();
   
    $q_sucu= $bdcon->prepare("SELECT id, nombre FROM sucursal where id='$sucursal'");
    $q_sucu->execute();
    while ($suquery = $q_sucu->fetch(PDO::FETCH_ASSOC)){ 
    	$id_suc= $suquery['id'];
        $nombre_suc = $suquery['nombre'];
    }
    ?>
  <table class="table">
	  <tr>
		  <td>
		  	<img src="../img/iconos/logo-verde.png"  style="width: 30%;">
		  </td>
		   <td>
		   		<!--<div class="noimpri">-->
		    	<input type="image" src="../img/iconos/imprimir.jpg" style="width: 30%; cursor: hand; cursor: pointer; border: 0px none; float:left;" value="Imprimir" onclick="window.print()" />		    
		    	<!--</div>-->
		    	<!--<button type="button" class="btn btn-light"><img src="../img/iconos/imprimir.jpg" onclick="window.print()"></button>-->
		    </td>	
		    <td><div style="font-size:9px;">
        <div align="right"><strong>Fecha :</strong><?php echo date("d/m/Y");?><br />
        <strong>Usuario:</strong> <?php echo $usuario;?><br />
        <strong>Hora:</strong> <?php echo date("H:i");?></div>
        </div>
    </td>    
	  </tr>	
	</table> 
	<br>
	 <table class="table"> 
	 	<thead>
		 	<tr>
			   	<th colspan="10"><div align="center">LISTA ADMINISTRATIVA SUCURSAL - <?php echo $nombre_suc;?></div></th>
			</tr>
		 	<tr>
		 		<th><div align="center">N°</div></th>
		 		<th><div align="center">Nombre Completo</div></th>
		 		<th><div align="center">Carnet de Identidad</div></th>
		 		<th><div align="center">Fecha de Nacimiento</div></th>
		 		<th><div align="center">Género</div></th>
		 		<th><div align="center">Estado Civil</div></th>
		 		<th><div align="center">Departamento</div></th>
		 		<th><div align="center">Cargo</div></th>
		 		<th><div align="center">Correo</div></th>
		 	</tr>
	 	</thead>
	 	<?php
	 	$contador=1;
	 	 while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)){  
        $id = $fquery['id'];
        $nombre = $fquery['nombcompleto'];
        $ci = $fquery['ci'];       
        $fechanci = $fquery['fechanaci'];
        $sexo = $fquery['sexo'];
        if ($sexo=="M") {
        	$sexonom="Masculino";
        }else{
        	$sexonom="Femenino";
        }
        $estado_civil = $fquery['estado_civil'];  
        if ($estado_civil=="C") {
        	$estado_civilnom="Casado";
        }else{
        	$estado_civilnom="Soltero";
        }
        $cargo = $fquery['cargo'];
        $q_cargo= $bdcon->prepare("SELECT nombre FROM cargo where id='$cargo'");
	    $q_cargo->execute();
	    while ($carquery = $q_cargo->fetch(PDO::FETCH_ASSOC)){
	        $nombrcargo = $carquery['nombre'];
	    }
        $depar = $fquery['depar'];
        $q_depar= $bdcon->prepare("SELECT nombre FROM departamento where id='$depar'");
	    $q_depar->execute();
	    while ($dequery = $q_depar->fetch(PDO::FETCH_ASSOC)){ 
	    	
	        $nombre_depar = $dequery['nombre'];
	    }
	    $email = $fquery['correo'];
        ?>
        <tr>
        	<td><div align="center"><?php echo $contador;?></div></td>
        	<td><div align="center"><?php echo $nombre;?></div></td>
        	<td><div align="center"><?php echo $ci;?></div></td>
        	<td><div align="center"><?php echo $fechanci;?></div></td>
        	<td><div align="center"><?php echo $sexonom;?></div></td>
        	<td><div align="center"><?php echo $estado_civilnom;?></div></td>
        	<td><div align="center"><?php echo $nombre_depar;?></div></td>
        	<td><div align="center"><?php echo $nombrcargo;?></div></td>
        	<td><div align="center"><?php echo $email;?></div></td>
        </tr>
        <?php
        $contador++;
    }
	 	?>

	</table>      
</body>
</html>