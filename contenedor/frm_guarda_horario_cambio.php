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
					$persona=$_REQUEST['persona'];
					$fechacambio=$_REQUEST['fechacambio'];				 
					$entrada=$_REQUEST['hentrada'];
					$salida=$_REQUEST['hsalida'];
					$motivo=$_REQUEST['motivo'];	
					$turno=$_REQUEST['turno'];	
                    $qverifica= $bdcon->prepare("SELECT * FROM horario_personal_cambio WHERE id_persona='$persona' and fecha='$fechacambio' and turno='$turno' and estado='1'");
                    $qverifica->execute();
                    if ($qverifica->rowCount()>0) {  
                     ?>
                        <div class="alert alert-danger" role="alert">  
                        No se registro los datos duplicados Verifique !
                        </div>
                     <?php
                    exit();    
                    }
                    if ($motivo=="") {
                     ?>
                        <div class="alert alert-danger" role="alert">  
                         Escriba el motivo del cambio de horario!..
                        </div>
                     <?php
                    exit();
                    }elseif($entrada==""){
                    ?>
                       <div class="alert alert-danger" role="alert">  
                        Error en la hora de entrada verifique!
                        </div>
                    <?php
                        exit();      
                    }elseif($salida==""){
                    ?>
                       <div class="alert alert-danger" role="alert">  
                        Error en la hora de salida verifique!
                        </div>
                    <?php
                     exit();
                    }elseif($turno==""){
                        ?>
                       <div class="alert alert-danger" role="alert">  
                            Error en Turno verifique!
                        </div>
                    <?php
                         exit();
                    }		
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s');

					$query_insert= $bdcon->prepare("INSERT INTO horario_personal_cambio VALUES ('0','$persona','$fechacambio','$entrada','$salida','$turno','$motivo','$usuario','$fec_act',1,'')");
					$query_insert->execute();
				?>		
					<div class="alert alert-success" role="alert">	
						Registro Exitoso !...
					</div>
				<?php
                     $mes_actualn = date('m');
                     switch ($mes_actualn) {
                                case '1':                                    
                                    $mes_actualn="01";
                                    break;
                                case '2':                                    
                                    $mes_actualn="02";
                                    break;
                                case '3':                                    
                                    $mes_actualn="03";
                                    break;
                                case '4':                                    
                                    $mes_actualn="04";
                                    break;
                                case '5':                                  
                                    $mes_actualn="05";
                                    break;
                                case '6':                                   
                                    $mes_actualn="06";
                                    break;
                                case '7':                                   
                                    $mes_actualn="07";
                                    break;
                                case '8':                                   
                                    $mes_actualn="08";
                                    break;
                                case '9':                                    
                                    $mes_actualn="09";
                                    break;
                                case '10':                                   
                                    break;
                                case '11':                                    
                                    break;
                                case '12':                                   
                                    break;
                                default:
                                    $mesnb='ERROR EN SELECCIONAR MES';
                                    break;
                            }                       
                    $aniomes=$nombre_gest."-".$mes_actualn; 
			 		$consulhorario= $bdcon->prepare("SELECT * FROM horario_personal_cambio WHERE id_persona='$persona'  and fecha like '$aniomes%' and estado='1'");
                    $consulhorario->execute(); 
                ?>
                <table class="table" style="font-size: 10px;">
                    <thead style="background-color:#208018; color: white; ">
                        <tr>
                            <th width="90px">FECHA</th>
                            <th>DIA</th>
                            <th>ENTRADA</th>
                            <th>SALIDA</th>
                            <th>TURNO</th>
                            <th>MOTIVO</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                   <?php
                    $dias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
                    while ($fcons = $consulhorario->fetch(PDO::FETCH_ASSOC)) { 
                        $idhora=$fcons['id'];  
                        $fechac=$fcons['fecha'];
                        $hentrada=$fcons['h_entrada'];  
                        $hsalida=$fcons['h_salida'];
                        $hturno=$fcons['turno'];
                        $hmotivo=$fcons['motivo'];
                        $dia = $dias[date("w", strtotime($fechac))];
                        $diam=mb_strtoupper($dia);
                    ?>
                    <tr>
                        <td><?php echo $fechac;?></td>
                        <td><?php echo $diam;?></td>
                        <td><?php echo $hentrada;?></td>
                        <td><?php echo $hsalida;?></td>
                        <td><?php echo $hturno;?></td>
                        <td><?php echo $hmotivo;?></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_cambiohorario(<?php echo $idhora;?>,<?php echo $persona;?>,<?php echo $mes_actualn;?>)"><span class="icon-material-outline-delete"></span></button>
                    </tr>
                    <?php
                    }
               		?> 
           		</table>
			</div>
		</div>
	</div>
</div>