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
					$personal=$_REQUEST['persona'];
					$fingre=$_REQUEST['fing'];
					/*$q_persona= $bdcon->prepare("SELECT * FROM persona where id='$personal'");
                    $q_persona->execute();
                    while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)) {  
                            $id=$fquery['id'];
                            $nombre=$fquery['nombcompleto'];
                                                             
                    }
					$query_insert= $bdcon->prepare("INSERT INTO `persona_update`(`id`, `id_persona`,`nombres`, `apellidos`, `telefono`, `correo`, `nombcompleto`, `ci`, `extencion`, `fechanaci`, `sexo`, `estado_civil`, `foto`, `cargo`, `depar`, `sucursal`, `usuario`, `freg-mod`) VALUES (0,[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16],[value-17])");
					$query_insert->execute();*/

					$query_update= $bdcon->prepare("UPDATE `persona` SET `nombres`='$nombresm',`apellidos`='$apellidosm',`telefono`='$telf',`correo`='$mail',`nombcompleto`='$nombcompletom',`ci`='$ci',`extencion`='$extensionm',`fechanaci`='$fnac',`sexo`='$sexo',`estado_civil`='$estadocivil',`cargo`='$cargo',`depar`='$dept',`sucursal`='$sucursal',`fechaingre`='$fingre'WHERE id='$personal'");
					$query_update->execute();
				?>		
					<div class="alert alert-success" role="alert">	
						Modificación Exitosa !...
					</div>
			</div>
		</div>
	</div>
</div>