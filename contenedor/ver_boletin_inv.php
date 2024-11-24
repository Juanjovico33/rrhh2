<?php	
  include "../includes/conexion.php";
  include "../includes/_event_log.php";
	$codest = $_GET['_codest'];	
	$codmat = $_GET['_codmat'];
	$periodo = $_GET['_per'];	
  $idgrup=$_GET['_idgrupo'];
  $subgrupo=$_GET['_subgrupo'];
  $e = new evento();
  $e->setIdGrupo($idgrup);
  $e->setIdSubGrupo($subgrupo);
  $e->e_log_inicio_evento($codest, 22);      
  $n_materia= $bdcon->prepare("SELECT Descripcion from materias where Sigla='$codmat'");
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

        $querynp= $bdcon->prepare("SELECT grupo from grupos_sub where cod='$subgrupo'");
        $querynp->execute();
        while ($ronbp = $querynp->fetch(PDO::FETCH_ASSOC)) {
           $nombresubgrupo=$ronbp['grupo']; 
        }   

    ?> 
  <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h3>Boletin de notas de INVESTIGACIÓN</h3>
                        <h6>Estudiante: <?php echo $codest."-".$nombcompleto;?></h6>
                        <h6>Materia: <?php echo $n_mat; ?></h6>                                             
                    </div>
                   
                        <table class="table table-hover">
                          <thead>                        
                            <tr align="center"> 
                                <th class='text-muted small'><trong>FECHA</trong></th>
                                <th class='text-muted small'><trong>NOTA</trong></th>
                            </tr>
                          </thead>
                          <tbody>
                             <?php                             
                            $query_pract_virtual= $bdcon->prepare("SELECT fecha, reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='9'");
                            $query_pract_virtual->execute(); 
                            while ($rowvirtual = $query_pract_virtual->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                               <tr align="center">
                                  <td class='text-muted small'><?php echo $fecha_virtual=$rowvirtual['fecha'];?></td>
                                  <!--<td class='text-muted small'><?php /*                                      
                                        $query_sub1= $bdcon->prepare("SELECT fecha FROM aca_asistencia_practica where codgrupo='$idgrup' and cod_subgru='$subgrupo' GROUP BY fecha");
                                        $query_sub1->execute();  
                                        $n_clases=$query_sub1->rowCount();                                            
                                        $query_sub= $bdcon->prepare("SELECT fecha FROM aca_asistencia_practica where codgrupo='$idgrup' and cod_subgru='$subgrupo' and codest='$codest' GROUP BY fecha");
                                        $query_sub->execute();   
                                        $n_clases_asistidas=$query_sub->rowCount(); 
                                        $puntos= 5;
                                        $resultadopuntos=0;
                                        if ($n_clases==0) {
                                          echo "0";
                                        }else{
                                          $resultadopuntos= (int)$n_clases_asistidas * (int)$puntos;
                                          $resultadopuntos=  (int)$resultadopuntos / (int)$n_clases; 
                                          //echo number_format($resultadopuntos,2); 
                                        } */
                                      ?>
                                  </td>-->
                                  <td class='text-muted small'><?php echo $nota_virtual=$rowvirtual['reco'];?></td>
                                  <!--<td class='text-muted small'>
                                      <?php
                                         $notafinal= /*$resultadopuntos+*/$nota_virtual;
                                         //echo number_format($notafinal,2)
                                      ?>
                                  </td>-->
                               </tr>
                               <?php
                                }
                               ?>
                          </tbody>
                        </table>
              </div>
          </div> 
      </div>     
  </div> 