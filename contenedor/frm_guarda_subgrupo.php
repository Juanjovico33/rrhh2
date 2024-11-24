<?php 
include "../includes/conexion.php";	
$eligio="";	
function nb_dia($dia){
    switch ($dia) {
      case '1':
        $nb_dia="Lunes";
        break;
      case '2':
        $nb_dia="Martes";
        break;
      case '3':
        $nb_dia="Miercoles";
        break;
      case '4':
        $nb_dia="Jueves";
        break;
      case '5':
        $nb_dia="Viernes";
        break;
      case '6':
        $nb_dia="Sabado";
        break;
      case '7':
        $nb_dia="Domingo";
        break;
      default:
        $nb_dia="S/A";
        break;
    }
    return $nb_dia;
  }
?>
<div uk-grid>
    <div class="uk-width-6-7@m" >
        <div class="blog-post single-post">
            <div class="blog-post-content" >				
				<?php
					@$eligio=$_REQUEST['elegi'];	
					$codest=$_REQUEST['codest'];
					$grupo=$_REQUEST['idgrup'];
					$periodo=$_REQUEST['periodo'];
					$codmat=$_REQUEST['codmat'];	
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s'); 		


					if ($eligio=="0" || $eligio=="") {
						echo "<div class='alert alert-danger'><strong>Error!<br> Seleccione un subgrupo para realizar el registro</strong></div>";
					?>
						      <div align="center">
                      		<button class="btn btn-success" type="button" onclick="registrar_subgrupo('<?=$codest; ?>','<?=$periodo; ?>','<?=$grupo; ?>','<?=$codmat; ?>')">REGISTRAR SUBGRUPO</button>
                   		</div>
                   	<?php
					}else{
              $q_cuantos= $bdcon->prepare("SELECT count(cod_subgru) as total FROM grupos_sub_listas  where cod_subgru='$eligio'");
              $q_cuantos->execute();            

						$q_horario= $bdcon->prepare("SELECT grupos_sub.grupo, grupos_horario.dia, grupos_horario.hr_entrada, grupos_horario.hr_salida, grupos_horario.cod_subgru FROM (grupos_horario INNER JOIN grupos_sub ON grupos_horario.cod_subgru = grupos_sub.cod) where cod_subgru='$eligio'");
                        $q_horario->execute();
                        while ($wr = $q_horario->fetch(PDO::FETCH_ASSOC)) { 
                            $nb_grupo=trim($wr['grupo']);
                            $dia=$wr['dia'];
                            $hr_entrada=$wr['hr_entrada'];  
                            $hr_salida=$wr['hr_salida']; 
                            $subgrupo=$wr['cod_subgru'];                  
                        } 
                        while ($rw = $q_cuantos->fetch(PDO::FETCH_ASSOC)) { 
                             $total=$rw['total'];
                        }
                          
                         if ($nb_grupo=='VIRTUAL' || $nb_grupo=='virtual' || $nb_grupo=='VIRTUAL 2' || $nb_grupo=='virtual 2' || $nb_grupo=='VIRTUAL 3' || $nb_grupo=='virtual 3' || $nb_grupo=='VIRTUAL 1' || $nb_grupo=='virtual 1' || $nb_grupo=='virtual 4' || $nb_grupo=='VIRTUAL 4' || $nb_grupo=='virtual a1' || $nb_grupo=='virtual a2' || $nb_grupo=='VIRTUAL A1' || $nb_grupo=='VIRTUAL A2' || $nb_grupo=='virtual a3' || $nb_grupo=='virtual a4' || $nb_grupo=='VIRTUAL A3' || $nb_grupo=='VIRTUAL A4'){
              
                          }else{
                              if ($total>=25) {
                                echo "<div class='alert alert-danger'><strong>SUBGRUPO LLENO !!...Verifique por favor inscritos ".$total."</strong></div>";
                                ?>
                                <div align="center">
                                  <button class="btn btn-danger" type="button" onclick="registrar_subgrupo('<?=$codest; ?>','<?=$periodo; ?>','<?=$grupo; ?>','<?=$codmat; ?>')">VOLVER </button>
                                </div>
                                <?php
                                exit();
                              }else{
                                //echo "<font color='green'>Inscritos (".$total.")</font>";
                              }

                          } 
                        if($q_horario->rowCount()>1){
                        	$q_horto= $bdcon->prepare("SELECT  dia, hr_entrada, hr_salida FROM grupos_horario  where cod_subgru='$subgrupo'");
                            $q_horto->execute();
                            $dias="";
                            while ($rhora = $q_horto->fetch(PDO::FETCH_ASSOC)) { 
                               $dia=$rhora['dia'];
                               $hr_entrada=$rhora['hr_entrada'];  
                               $hr_salida=$rhora['hr_salida'];                                         
                               $diass= nb_dia($dia)." (".$hr_entrada." - ".$hr_salida .")" ;
                               $dias= $dias.", ".$diass;
                             }
                             echo "<div class='alert alert-warning'><strong>¿Estas segur@ de registrarte a este subgrupo ". $nb_grupo." en los dias y horarios de".$dias."?";
                        }else{
                        	echo "<div class='alert alert-warning'><strong>¿Estas segur@ de registrarte a este subgrupo ". $nb_grupo." del día ".nb_dia($dia)." a la hora de ".$hr_entrada." hasta ". $hr_salida."?</strong></div>";
                        }	
						
						?>
						          <div align="center">
                      		<button class="btn btn-success" type="button" onclick="registrar_estsubgrupo('<?=$codest; ?>','<?=$periodo; ?>','<?=$grupo; ?>','<?=$subgrupo; ?>','<?=$codmat; ?>')">SI</button>
                      		<button class="btn btn-danger" type="button" onclick="registrar_subgrupo('<?=$codest; ?>','<?=$periodo; ?>','<?=$grupo; ?>','<?=$codmat; ?>')">NO</button>
                   		</div>
                   		<?php 
					}					
				?>				
			</div>
		</div>
	</div>
</div>