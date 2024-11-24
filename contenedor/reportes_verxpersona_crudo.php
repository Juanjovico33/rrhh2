<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CRUDO</title>
<link rel="stylesheet" type="text/css" href="../../estilos/general.css" />
<script type="text/javascript">
var ventana = false;
function abrir(ci,id){
	if (!ventana){
			ventana = window.open("frm_elimina_mar.php?_ci="+ci+"&_id="+id+"", "", "width=450,height=350,scrollbars=YES,resizable=YES");
	}else{
	    ventana.close();
	    ventana = false;
	}
}
</script>
<style type="text/css">
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
body {
	background-color: #FFF;
}
.num_chicos{
	font-size:11px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
}
</style>
<style media='print'>
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
    $personal=$_REQUEST['persona'];
	$anio=$_REQUEST['gestnomb'];
    $mes=$_REQUEST['mes'];
    $mesnb='';
    switch ($mes) {
    	case '1':
    		$mesnb='ENERO';
    		$mes="01";
    		break;
    	case '2':
    		$mesnb='FEBRERO';
    		$mes="02";
    		break;
    	case '3':
    		$mesnb='MARZO';
    		$mes="03";
    		break;
    	case '4':
    		$mesnb='ABRIL';
    		$mes="04";
    		break;
    	case '5':
    		$mesnb='MAYO';
    		$mes="05";
    		break;
    	case '6':
    		$mesnb='JUNIO';
    		$mes="06";
    		break;
    	case '7':
    		$mesnb='JULIO';
    		$mes="07";
    		break;
    	case '8':
    		$mesnb='AGOSTO';
    		$mes="08";
    		break;
    	case '9':
    		$mesnb='SEPTIEMBRE';
    		$mes="09";
    		break;
    	case '10':
    		$mesnb='OCTUBRE';
    		break;
    	case '11':
    		$mesnb='NOVIEMBRE';
    		break;
    	case '12':
    		$mesnb='DICIEMBRE';
    		break;
    	default:
    		$mesnb='ERROR EN SELECCIONAR MES';
    		break;
    }
    $q_persona= $bdcon->prepare("SELECT id, nombcompleto, ci, fechanaci, sexo, estado_civil, cargo, depar FROM persona where id='$personal'");
    $q_persona->execute();
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
    }
    ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		  <td>
		  	<img src="../img/iconos/logo-verde.png"  style="width: 30%;">
		  </td>
		   <td>
		   		<div class="noimpri">
		    	<input type="image" src="../img/iconos/imprimir.jpg" style="width: 5%; cursor: hand; cursor: pointer; border: 0px none; float:left;" value="Imprimir" onclick="window.print()" />		    
		    	</div>
		    </td>	
		    <td><div style="font-size:9px;">
        <div align="right"><strong>Fecha :</strong><?php echo date("d/m/Y");?><br />
        <strong>Usuario:</strong> <?php echo $usuario;?><br />
        <strong>Hora:</strong> <?php echo date("H:i");?></div>
        </div>
    </td>    
	  </tr>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">  
		    <tr>
		    	<td colspan="2"><div align="center" style="font-size:14px;"><b>CONTROL DE ASISTENCIA CRUDO SIN HACER REFERENCIA AL HORARIO ASIGNADO</b></div></td>
		    </tr>
		    <tr>
		    <td colspan="2">
		    	<table width="80%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          <td><div class="listas_nombre" style="font-size:11px;"><strong>PERSONAL:</strong></div></td>
		          <td colspan="3"><div class="listas_nombre" style="font-size:11px;">&nbsp;<?php echo $nombre;?></div></td>       	    
		        </tr>
		        <tr>
		          <td width="10%"><strong class="listas_nombre" style="font-size:11px;">N° CARNET:</strong></td>
		          <td width="39%">
		          	<div class="listas_nombre" style="font-size:11px;">&nbsp;<?php echo $ci;?>
		          	</div>
			          </td> 
			        </tr>
			        <tr>
			          <td><strong class="listas_nombre" style="font-size:11px;">SEXO: </strong></td>
			          <td colspan="3"><div class="listas_nombre" style="font-size:11px;">&nbsp;<?php echo $sexonom;?></div></td>				
					</tr>
			        <tr>
			          <td><strong class="listas_nombre" style="font-size:11px;">DEPARTAMENTO: </strong></td>
			          <td colspan="3"><div class="listas_nombre" style="font-size:11px;">&nbsp;<?php echo $nombrcargo;?></div></td>     
					</tr>
			        <tr>
			          <td><strong class="listas_nombre" style="font-size:11px;">CARGO: </strong></td>
			          <td colspan="3"><span class="listas_nombre" style="font-size:11px;">&nbsp;<?php echo $nombre_depar;?></span></td>
			          
			        </tr>
			    </table>      
		    </td>
		    </tr>
		    <tr>
		    	<td colspan="2"><div align="center" style="font-size:14px;"><b><?php echo $mesnb;?></b></div></td>
		    </tr>
	</table> 
	<br>
	<table width="100%" border="0" cellspacing="1" cellpadding="1" style="font-size:18px;"> 	 	
		<tr>
			<td style="border-bottom:1px dashed black; font-size:11px;"><strong><div align="center">FECHA</div></strong></td>	
			<td style="border-bottom:1px dashed black; font-size:11px;"><strong><div align="center">DIA</div></strong></td>						
		</tr>
		<?php
			$dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
			$diasmes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
			$fechafiltro=$anio."-".$mes."-";
	        for ($i=1; $i <= $diasmes ; $i++) {
	        	$hentrada="";
	        	$hsalida="";
	        	$dia=""	 ;       	
		       	if ($i<10) {
		       		$inb="0".$i;
		       	}else{
		       		$inb=$i;
		       	}
	       		$fechacompleta=$inb."-".$mes."-".$anio;	       		
	        	$ffecha=$anio."-".$mes."-".$inb;
	        	$fechacompletain=$anio."-".$mes."-".$inb;
	        	$dia = $dias[date("w", strtotime($fechacompletain))];
	        	$diam=mb_strtoupper($dia);
			?>
		     	<tr>
		     		<td style="border-bottom:1px dashed black;"><div align="center"><?php echo $fechacompleta;?></div></td>		     	
		     		<td style="border-bottom:1px dashed black;"><div align="center"><?php echo $diam;?></div></td>	
				    <!-- se visualizan las marcaciones entrada mañana-->				     		
     				<?php 
     					$q_marca= $bdcon->prepare("SELECT id, hora FROM marcadores where fecha = '$ffecha' and ci='$ci' and estado='1'");
					    $q_marca->execute();
					    $ndatos=$q_marca->rowCount();
					    while ($mquery = $q_marca->fetch(PDO::FETCH_ASSOC)){  				       
						    @$horamarca = $mquery['hora'];
						   	$idmarca = $mquery['id'];
 						?>
 						<td style="border-bottom:1px dashed black;">
 							<div align="center" onclick="abrir('<?php echo $ci;?>','<?php echo $idmarca;?>')">
 								<?php echo date("H:i", strtotime($horamarca));?>
 							</div>
						</td>
     				<?php
	     				}			     					
     				?>				     			     		
		     	</tr>
		<?php		     			
			}
		?>	
	</table>      
</body>
</html>