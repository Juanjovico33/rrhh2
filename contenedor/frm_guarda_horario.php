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
					$persona=$_REQUEST['persona'];
					$dia=$_REQUEST['dia'];				 
					$entrada=$_REQUEST['hentrada'];
					$salida=$_REQUEST['hsalida'];
					$turno=$_REQUEST['turno'];					
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s');
					$query_insert= $bdcon->prepare("INSERT INTO horario_personal VALUES ('0','$persona','$dia','$entrada','$salida','$turno','',1,'$fec_act','$usuario',1)");
					$query_insert->execute();
				?>		
					<div class="alert alert-success" role="alert">	
						Registro Exitoso !...
					</div>
				<?php
			 		$consulhorario= $bdcon->prepare("SELECT * FROM horario_personal WHERE persona='$persona'");
                    $consulhorario->execute(); 
                  ?>
                    <table class="table">
                        <thead style="background-color:#208018; color: white; ">
                            <tr>
                                <th>DIA</th>
                                <th>ENTRADA</th>
                                <th>SALIDA</th>
                                <th>TURNO</th>
                            </tr>
                        </thead>
	                   <?php
	                    while ($fcons = $consulhorario->fetch(PDO::FETCH_ASSOC)) {   
	                        $id_tipo=$fcons['id'];               
	                        $h_dia=$fcons['dia'];  
	                        $hentrada=$fcons['h_entrada'];  
	                        $hsalida=$fcons['h_salida'];
	                        $hturno=$fcons['turno'];
	                    ?>
	                    <tr>
	                        <td><?php echo $h_dia;?></td>
	                        <td><?php echo $hentrada;?></td>
	                        <td><?php echo $hsalida;?></td>
	                        <td><?php echo $hturno;?></td>
	                    </tr>
	                    <?php
	                    }
	               		?> 
               		</table>
			</div>
		</div>
	</div>
</div>