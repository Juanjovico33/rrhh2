<?php
session_start(); 
include "../includes/conexion.php";
include "../includes/_validar.php";
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
				<?php
					$nombres=$_REQUEST['nom'];
					$apellidos=$_REQUEST['ap'];
				    $nombresm=mb_strtoupper($nombres);
				    $apellidosm=mb_strtoupper($apellidos);
					$fnac=$_REQUEST['fnac'];
					$sexo=$_REQUEST['sexo'];
					$ci=$_REQUEST['ci'];
					$estadocivil=$_REQUEST['es'];
					$extension=$_REQUEST['ext'];
					$extensionm=mb_strtoupper($extension);
					$usuario = $_REQUEST['usuario'];	
					$telf = $_REQUEST['telf'];	
					$mail = $_REQUEST['mail'];	
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s'); 
					$nombcompleto=$apellidos." ".$nombres;	
					$nombcompletom=mb_strtoupper($nombcompleto);
					$dept=$_REQUEST['dept'];
					$cargo= $_REQUEST['cargo'];
					$sucursal= $_REQUEST['sucursal'];
					$fingre= $_REQUEST['fing'];
					$q_pre= $bdcon->prepare("SELECT * FROM persona where ci='$ci'");
                    $q_pre->execute();
                 	if ($q_pre->rowCount()>0) {	
                 		?>
                 			<div class="alert alert-danger" role="alert">	
								Ya existe un personal con el mismo Carnet de Identidad
							</div>
                 		<?php
                 		exit();
                 	}	
					$query_insert= $bdcon->prepare("INSERT INTO persona VALUES ('0','$nombresm','$apellidosm','$telf','$mail','$nombcompletom','$ci','$extensionm','$fnac','$sexo','$estadocivil','','','$cargo','$dept','$sucursal','$fingre','$usuario','$fec_act','1')");
					$query_insert->execute();
				?>		
					<div class="alert alert-success" role="alert">	
						Registro Exitoso !...
					</div>
			</div>
		</div>
	</div>
</div>