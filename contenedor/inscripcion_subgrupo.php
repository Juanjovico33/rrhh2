<?php	
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
  include "../includes/conexion.php";
	$codest = $_GET['_codest'];	
	$periodo = $_GET['_periodo'];	
  $idgrup=$_GET['_idgrupo']; 
  $codmat=$_GET['_codmat'];
  $n_carrera= $bdcon->prepare("SELECT CodCarrera from grupos where CodGrupo='$idgrup'");
  $n_carrera->execute();
  while ($row4 = $n_carrera->fetch(PDO::FETCH_ASSOC)) {
     $n_Sigla=$row4['CodCarrera']; 
  } 	    
  $n_materia= $bdcon->prepare("SELECT Descripcion from materias where Sigla='$codmat' and CodCarrera='$n_Sigla'");
  $n_materia->execute();
  while ($row3 = $n_materia->fetch(PDO::FETCH_ASSOC)) {
     $n_mat=$row3['Descripcion']; 
  }     
?> 
  <?php 
      $querynb= $bdcon->prepare("SELECT nombcompleto from estudiante where codest='$codest'");
      $querynb->execute();
      while ($ronb = $querynb->fetch(PDO::FETCH_ASSOC)) {
         $nombcompleto=$ronb['nombcompleto']; 
      }          
  ?> 
  <div uk-grid>
        <div class="uk-width-6-7@m" >
            <div class="blog-post single-post">
                <div class="blog-post-content" >
                    <form method="post" onsubmit="return false;" id="form_reg">
                      <div align="center" style="background-image: url('img/iconos/inscrip.jpg');">
                          <h3>INSCRIPCIÓN DE SUBGRUPO</h3>
                          <h6>ESTUDIANTE: <?php echo $codest."-".$nombcompleto;?></h6>
                          <h6>MATERIA: <?php echo $n_mat; ?></h6>
                          <input type="hidden" name="codest" value="<?php echo $codest; ?>">
                          <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
                          <input type="hidden" name="idgrup" value="<?php echo $idgrup; ?>">
                          <input type="hidden" name="codmat" value="<?php echo $codmat; ?>">
                      </div>
                      <div class="table-responsive" class="panel-body">
                      <table class="table table-hover">
                          <thead>
                            <tr align="center">    
                                <th class='text-muted small'><trong>NOMBRE SUBGRUPO</trong></th>
                                <th class='text-muted small'><trong>HORARIO</trong></th>  
                                <th class='text-muted small'><trong>INSCRITOS</trong></th>                          
                                <th class='text-muted small'><trong>ELEGIR</trong></th> 
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if ($idgrup==0) {
                               echo "<font color='red'>No tiene Subgrupo</font>";
                              exit();
                            }
                            $q_subgrupos= $bdcon->prepare("SELECT grupo, cod FROM grupos_sub  where idgrupo_padre='$idgrup' AND descripcion='1'");
                            $q_subgrupos->execute();
                            while ($wr = $q_subgrupos->fetch(PDO::FETCH_ASSOC)) { 
                                $nb_grupo=trim($wr['grupo']);                               
                                $subgrupo=$wr['cod'];                  
                            ?>
                                <tr align="center">      
                                  <td class='text-muted small'>
                                    <?php echo $nb_grupo;?>
                                  </td>
                                  <td class='text-muted small'>
                                    <?php 
                                      $q_horario= $bdcon->prepare("SELECT  dia, hr_entrada, hr_salida FROM grupos_horario  where cod_subgru='$subgrupo'");
                                      $q_horario->execute();
                                      if($q_horario->rowCount()==0){
                                          echo "<font color='red'>No tiene Horario<br> Verifique con su jefe de carrera</font>";
                                      }else{
                                        while ($rhora = $q_horario->fetch(PDO::FETCH_ASSOC)) { 
                                          $dia=$rhora['dia'];
                                          $hr_entrada=$rhora['hr_entrada'];  
                                          $hr_salida=$rhora['hr_salida'];                                         
                                          echo nb_dia($dia)." (".$hr_entrada." - ".$hr_salida .") <br>" ;
                                        }
                                      }                                      
                                    ?>
                                  </td>
                                  <td class='text-muted small'>
                                    <?php
                                      $q_cuantos= $bdcon->prepare("SELECT count(cod_subgru) as total FROM grupos_sub_listas  where cod_subgru='$subgrupo'");
                                      $q_cuantos->execute();
                                        while ($rw = $q_cuantos->fetch(PDO::FETCH_ASSOC)) { 
                                              $total=$rw['total'];
                                        }
                                        if ($nb_grupo=='VIRTUAL' || $nb_grupo=='virtual' || $nb_grupo=='VIRTUAL 2' || $nb_grupo=='virtual 2' || $nb_grupo=='VIRTUAL 3' || $nb_grupo=='virtual 3' || $nb_grupo=='VIRTUAL 1' || $nb_grupo=='virtual 1' || $nb_grupo=='virtual 4' || $nb_grupo=='VIRTUAL 4' || $nb_grupo=='virtual a1' || $nb_grupo=='virtual a2' || $nb_grupo=='VIRTUAL A1' || $nb_grupo=='VIRTUAL A2' || $nb_grupo=='virtual a3' || $nb_grupo=='virtual a4' || $nb_grupo=='VIRTUAL A3' || $nb_grupo=='VIRTUAL A4') {
                                          echo "<font color='green'>Inscritos (".$total.")</font>";
                                        }else{
                                          if ($total>=25) {
                                            echo "<font color='red'>Inscritos (".$total.")</font>";
                                          }else{
                                            echo "<font color='green'>Inscritos (".$total.")</font>";
                                          }
                                        }                                        
                                    ?>
                                  </td>
                                  <td class='text-muted small'>
                                    <?php
                                     if($q_horario->rowCount()==0){

                                     }else{
                                        if ($nb_grupo=='VIRTUAL' || $nb_grupo=='virtual' || $nb_grupo=='VIRTUAL 2' || $nb_grupo=='virtual 2' || $nb_grupo=='VIRTUAL 3' || $nb_grupo=='virtual 3' || $nb_grupo=='VIRTUAL 1' || $nb_grupo=='virtual 1' || $nb_grupo=='virtual 4' || $nb_grupo=='VIRTUAL 4' || $nb_grupo=='virtual a1' || $nb_grupo=='virtual a2' || $nb_grupo=='VIRTUAL A1' || $nb_grupo=='VIRTUAL A2' || $nb_grupo=='virtual a3' || $nb_grupo=='virtual a4' || $nb_grupo=='VIRTUAL A3' || $nb_grupo=='VIRTUAL A4') {
                                          ?>
                                            <input class="checkbox-success" type="radio" id="elegi" name="elegi" value="<?php echo $subgrupo; ?>"/>
                                          <?php
                                        }else{
                                          if ($total>=25) {
                                            echo "<font color='red'>Subgrupo lleno</font>";
                                          }else{
                                            ?>
                                            <input class="checkbox-success" type="radio" id="elegi" name="elegi" value="<?php echo $subgrupo; ?>"/>
                                          <?php
                                          }
                                        }
                                     }                                      
                                    ?>                                     
                                  </td>
                                </tr> 
                            <?php
                            }
                            ?>
                          </tbody>                          
                    </table>
                    </div>
                    <div class="alert alert-warning" align="center"><strong>¡Atención!</strong><br>Cupo máximo para los subgrupos PRESENCIALES es de 25 inscritos</div>
                    <div align="center">
                      <button class="btn btn-success" type="button" onclick="guardar_subgrupo()">GUARDAR REGISTRO</button>
                    </div>
                  </form>
              </div>
          </div> 
      </div>     
  </div> 