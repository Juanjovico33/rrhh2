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
        $ronb = $querynb->fetch(PDO::FETCH_ASSOC);
        $nombcompleto=$ronb['nombcompleto']; 

        $querynp= $bdcon->prepare("SELECT grupo from grupos_sub where cod='$subgrupo'");
        $querynp->execute();
        $ronbp = $querynp->fetch(PDO::FETCH_ASSOC);
        $nombresubgrupo=$ronbp['grupo']; 
    ?> 
  <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h3>Notas del Aula Invertida detalle SUB GRUPO (<?php echo $nombresubgrupo;?>)</h3>
                        <h6>Estudiante: <?php echo $codest."-".$nombcompleto;?></h6>
                        <h6>Materia: <?php echo $n_mat; ?></h6>                                             
                    </div>
                    <?php
                      ?>
                        <table class="table table-hover">
                          <thead>                        
                            <tr align="center"> 
                                <th class='text-muted small'><trong>FECHA</trong></th> 
                                <th class='text-muted small'><trong>NOTA</trong></th>
                            </tr>
                          </thead>
                          <tbody>
                             <?php                             
                            $query_aula_invertida= $bdcon->prepare("SELECT fecha, nota FROM sainc.plat_doc_intentos_est_prac where id_subgru=$subgrupo and codest=$codest");
                            $query_aula_invertida->execute(); 
                            $contador=0;
                            $sumatoria_notas=0;
                            while ($rowinvertida = $query_aula_invertida->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                               <tr align="center">
                                  <td class='text-muted small'><?=$rowinvertida['fecha']?></td>
                                  <td class='text-muted small'><?=$rowinvertida['nota']?></td>
                                </tr>
                               <?php
                              $sumatoria_notas=+$rowinvertida['nota'];
                              $contador++;
                             }
                              $promedio_total=$sumatoria_notas/$contador; ?>
                            <tr>
                              <td style="text-align:right" class='text-muted small'><b>PROMEDIO TOTAL</b></td>
                              <td style="text-align:center"  class='text-muted small'><b><?=number_format($promedio_total,2)?></b></td>
                            </tr>
                          </tbody>
                        </table>
              </div>
          </div> 
      </div>     
  </div> 