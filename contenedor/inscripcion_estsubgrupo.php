<?php 
include "../includes/conexion.php";	
?>
<div uk-grid>
    <div class="uk-width-6-7@m" >
        <div class="blog-post single-post">
            <div class="blog-post-content" >				
				<?php					
					$codest=$_REQUEST['_codest'];
					$grupo=$_REQUEST['_idgrupo'];
					$periodo=$_REQUEST['_periodo'];
					$subgrupo=$_REQUEST['_subgrupo'];	
					$codmat=$_REQUEST['_codmat'];				
					$fecha=new DateTime();
				    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
				    $fec_act=$fecha->format('Y-m-d');
				    $hr_act=$fecha->format('H:i:s');
				    $q_nbgru= $bdcon->prepare("SELECT grupo FROM grupos_sub  where cod='$subgrupo'");
                    $q_nbgru->execute();
                        while ($wr = $q_nbgru->fetch(PDO::FETCH_ASSOC)) { 
                            $nb_grupo=trim($wr['grupo']);                                       
                        } 
                     	if ($nb_grupo=='VIRTUAL' || $nb_grupo=='virtual' || $nb_grupo=='VIRTUAL 2' || $nb_grupo=='virtual 2' || $nb_grupo=='VIRTUAL 3' || $nb_grupo=='virtual 3' || $nb_grupo=='VIRTUAL 1' || $nb_grupo=='virtual 1' || $nb_grupo=='virtual 4' || $nb_grupo=='VIRTUAL 4' || $nb_grupo=='virtual a1' || $nb_grupo=='virtual a2' || $nb_grupo=='VIRTUAL A1' || $nb_grupo=='VIRTUAL A2' || $nb_grupo=='virtual a3' || $nb_grupo=='virtual a4' || $nb_grupo=='VIRTUAL A3' || $nb_grupo=='VIRTUAL A4'){
              			}else{
              				$q_cuantos= $bdcon->prepare("SELECT count(cod_subgru) as total FROM grupos_sub_listas  where cod_subgru='$subgrupo'");
		             		$q_cuantos->execute();
		         		   	while ($rw = $q_cuantos->fetch(PDO::FETCH_ASSOC)) { 
		                         $total=$rw['total'];
		                    }    
		                    	      
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
					$q_ver= $bdcon->prepare("SELECT cod_subgru, cod_subgru FROM grupos_sub_listas  where codest='$codest' and cod_subgru='$subgrupo'");	
			        $q_ver->execute();
			          if($q_ver->rowCount()>1){
			            echo "<div class='alert alert-danger'><strong>Error!<br> Ya esta inscrito en este subgrupo verifica bien tus datos</strong></div>";
			            exit();
			          }else{
			            $registrar= $bdcon->prepare("INSERT INTO grupos_sub_listas VALUES ('0','$subgrupo','$codest')");
			            $registrar->execute();
			            echo "<div class='alert alert-success'><strong>Correcto!<br> Datos registrados correctamente</strong></div>";
			           
			          }  
					?>					               		
			</div>
		</div>
	</div>
</div>