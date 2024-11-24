<?php
include "../includes/conexion.php";
include "../includes/_validar.php";
include "../includes/user_session.php";
$user = new UserSession();    
$usuario=$user->getCurrentUser();
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
				<?php
                    $q_gest= $bdcon->prepare("SELECT id, nombre FROM gestion where estado='1'");
                    $q_gest->execute();
                    while ($gquery = $q_gest->fetch(PDO::FETCH_ASSOC)) {  
                        $id_gest = $gquery['id'];
                        $nombre_gest = $gquery['nombre'];                                         
                    }
					$idhora=$_REQUEST['_idhora'];                 
					$idper=$_REQUEST['_persona'];                    
                    $mes_actualn=$_REQUEST['_mes'];
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s');
                    $observa="Eliminado por:".$usuario." ". $fec_act." ".$hr_act;
					$querydelete= $bdcon->prepare("UPDATE `horario_personal_cambio` SET `estado`=0,`obser`='$observa' WHERE id='$idhora' and id_persona='$idper'");
					$querydelete->execute();
				?>		
					<div class="alert alert-danger" role="alert">	
						Datos Eliminados !...
					</div>
				<?php                                          
                    $aniomes=$nombre_gest."-".$mes_actualn;  
			 		$consulhorario= $bdcon->prepare("SELECT * FROM horario_personal_cambio WHERE id_persona='$idper' and fecha like '$aniomes%' and estado='1'");
                    $consulhorario->execute(); 
                ?>
                <table class="table" style="font-size: 10px;">
                    <thead style="background-color:#208018; color: white; ">
                        <tr>
                            <th width="90px">FECHA</th>
                            <th>ENTRADA</th>
                            <th>SALIDA</th>
                            <th>TURNO</th>
                            <th>MOTIVO</th>
                        </tr>
                    </thead>
                   <?php
                    while ($fcons = $consulhorario->fetch(PDO::FETCH_ASSOC)) { 
                        $idhora=$fcons['id'];  
                        $fechac=$fcons['fecha'];
                        $hentrada=$fcons['h_entrada'];  
                        $hsalida=$fcons['h_salida'];
                        $hturno=$fcons['turno'];
                        $hmotivo=$fcons['motivo'];
                    ?>
                    <tr>
                        <td><?php echo $fechac;?></td>
                        <td><?php echo $hentrada;?></td>
                        <td><?php echo $hsalida;?></td>
                        <td><?php echo $hturno;?></td>
                        <td><?php echo $hmotivo;?></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_cambiohorario(<?php echo $idhora;?>,<?php echo $idper;?>,<?php echo $mes_actualn;?>)"><span class="icon-material-outline-delete"></span></button>
                    </tr>
                    <?php
                    }
               		?> 
           		</table>
			</div>
		</div>
	</div>
</div>