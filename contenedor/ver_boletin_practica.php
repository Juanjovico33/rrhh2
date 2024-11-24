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

        $query_verygru= $bdcon->prepare("SELECT nb from plat_est_nbsubgrupo where nb='$nombresubgrupo'");
        $query_verygru->execute();
        $existe=$query_verygru->rowCount();
        if ($periodo=='202002' || $periodo=='202001'){
          $q_verinbpresencial= $bdcon->prepare("SELECT cod from grupos_sub where idgrupo_padre='$idgrup' and (grupo='PRESENCIAL' or grupo='PRESENCIAL 1' or grupo='PRESENCIAL1' or grupo='PRESENCIAL 2' or grupo='PRESENCIAL 3' or grupo='Presencial' or grupo='PRESENCIAL Q' or grupo='PRESENCIAL FI' or grupo='PRESENCIAL A1') and idgrupo_padre<>'0'");
        }else{
          $q_verinbpresencial= $bdcon->prepare("SELECT cod from grupos_sub where idgrupo_padre='$idgrup' and (grupo='PRESENCIAL' or grupo='PRESENCIAL 1' or grupo='PRESENCIAL1' or grupo='Presencial' or grupo='PRESENCIAL Q' or grupo='PRESENCIAL FI' or grupo='PRESENCIAL A1') and idgrupo_padre<>'0'");
        }
        $q_verinbpresencial->execute();
        while ($rowcodsubgru = $q_verinbpresencial->fetch(PDO::FETCH_ASSOC)) {
           $subgrupopresencial=$rowcodsubgru['cod']; 
        }
    ?> 
  <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h3>Boletin de notas de Practicas a detalle SUB GRUPO (<?php echo $nombresubgrupo;?>)</h3>
                        <h6>Estudiante: <?php echo $codest."-".$nombcompleto;?></h6>
                        <h6>Materia: <?php echo $n_mat; ?></h6>                                             
                    </div>
                    <?php
                    if ($existe>0) {
                        # PRESENCIAL                 
                    ?>
                    <table class="table table-hover">
                        <thead>                        
                          <tr align="center"> 
                              <th class='text-muted small'><trong>FECHA</trong></th>     
                              <th class='text-muted small'><trong>R. Mat. P<br>(4 pts)</trong></th>
                              <th class='text-muted small'><trong>R. Proc. P<br>(6 pts)</trong></th>
                              <th class='text-muted small'><trong>P. Conc. Int T.<br>(10 pts)</trong></th>
                              <th class='text-muted small'><trong>NOTA FINAL</trong></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php                            
                            $query_pract= $bdcon->prepare("SELECT cod, nota, fecha from plat_doc_intentos_est_prac where codest='$codest' and id_subgru='$subgrupo' ORDER BY fecha");
                            $query_pract->execute(); 
                            while ($row2 = $query_pract->fetch(PDO::FETCH_ASSOC)) {
                              $idnota=$row2['cod']; 
                              ?>
                              <tr align="center">      
                                <td class='text-muted small'><?php echo $fecha=$row2['fecha'];?>                       
                                </td>                               
                                <?php 
                                    $notas= $bdcon->prepare("SELECT nota as notita from plat_doc_intentos_est_prac_deta where codest='$codest' and id_subgru='$subgrupo' and cod_prac='$idnota' Order By tipo Asc");
                                    $notas->execute();
                                    while ($row = $notas->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <td class='text-muted small'>
                                      <?php 
                                        echo $notita=$row['notita'];
                                      ?>
                                    </td>                                  
                                      <?php 
                                    }                                      
                                  ?> 
                                <td class='text-muted small'><?php echo $notafinal=$row2['nota'];?>                       
                                </td>
                            </tr> 
                          <?php
                            } 
                        ?> 
                        </tbody>
                    </table>
                    <?php
                    }else{
                      #virtual
                      ?>
                        <table class="table table-hover">
                          <thead>                        
                            <tr align="center"> 
                                <th class='text-muted small'><trong>FECHA</trong></th> 
                                <th class='text-muted small'><trong>ASISTENCIA</trong></th>
                                <th class='text-muted small'><trong>NOTA</trong></th>
                                <th class='text-muted small'><trong>NOTA PRACTICA</trong></th>
                            </tr>
                          </thead>
                          <tbody>
                             <?php                             
                            $query_pract_virtual= $bdcon->prepare("SELECT fecha, reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='10'");
                            $query_pract_virtual->execute(); 
                            while ($rowvirtual = $query_pract_virtual->fetch(PDO::FETCH_ASSOC)) {
                              ?>
                               <tr align="center">
                                  <td class='text-muted small'><?php echo $fecha_virtual=$rowvirtual['fecha'];?></td>
                                  <td class='text-muted small'><?php 
                                         # VIRTUAL                                      
                                        $query_sub1= $bdcon->prepare("SELECT fecha FROM plat_doc_int_est_consolidar where sub_grupo='$subgrupopresencial'");
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
                                          if ($n_clases<$n_clases_asistidas) {
                                              $n_clases_asistidas=$n_clases;
                                          }
                                          $resultadopuntos= (int)$n_clases_asistidas * (int)$puntos;
                                          $resultadopuntos=  (int)$resultadopuntos / (int)$n_clases; 
                                          echo number_format($resultadopuntos,2); 
                                        } 
                                      ?>
                                  </td>
                                  <td class='text-muted small'><?php echo $nota_virtual=$rowvirtual['reco'];?></td>
                                  <td class='text-muted small'>
                                      <?php
                                         $notafinal= $resultadopuntos+$nota_virtual;
                                         echo number_format($notafinal,2)
                                      ?>
                                  </td>
                               </tr>
                               <?php
                             }
                               ?>
                          </tbody>
                        </table>
                      <?php
                    }
                    ?>
              </div>
          </div> 
      </div>     
  </div> 