<?php	
  include "../includes/conexion.php";
//include "../includes/_estudiante.php";
$codest = $_POST['_codest'];	
$codmat = $_POST['_codmat'];
$periodo = $_POST['_per'];	
$idgrup = $_POST['_idgrupo'];  

  $pp=0;
  $sp=0;
  $ef=0;
  $query_nota= $bdcon->prepare("SELECT nota,t_examen from aca_notas where id_grupo='$idgrup' and codest='$codest' ORDER BY t_examen");
  $query_nota->execute();
    while ($row2 = $query_nota->fetch(PDO::FETCH_ASSOC)) {
        //$idgrup=$row2['idgrupo']; 
        $nota=$row2['nota'];
				$parcial=$row2['t_examen'];
				if ($parcial=='1') {
					   $pp=$pp;
				}else{
					if ($parcial=='2') {
						    $sp=$sp;
					}else{
						    $ef=$ef;
					}
				}              
    }	    
    $n_materia= $bdcon->prepare("SELECT Descripcion from materias where Sigla='$codmat'");
    $n_materia->execute();
    while ($row3 = $n_materia->fetch(PDO::FETCH_ASSOC)) {
       $n_mat=$row3['Descripcion']; 
    } 
    
    $nota_prac=0;
    //nota para practica
    $ppr=0;
    $spr=0;
    $efr=0;
    $ipp=0;
    $isp=0;
    $ief=0;
    $c_pp=0;
    $c_sp=0;
    $c_ef=0;
    $inv_pp=0;
    $inv_sp=0;
    $inv_ef=0;
    $is_pp=0;
    $is_sp=0;
    $is_ef=0;
    $is_pp=0;
    $is_sp=0;
    $is_ef=0;
    $ispp=0;
    $issp=0;
    $isef=0;
    $pro_pp=0;
    $pro_sp=0;
    $pro_ef=0;
    $propp=0;
    $prosp=0;
    $proef=0;
    $invin_pp=0;
    $invin_sp=0;
    $invin_ef=0;
    $invinpp=0;
    $invinsp=0;
    $invinef=0;
    $nota_prac=0;
    $c_pra=0;
    $c_inv=0;
    $c_is=0;
    $c_pro=0;
    $c_invin=0;
    $aux_pra=0;
    $aux_inv=0;
    $aux_int=0;
    $aux_pro=0;
    $aux_invin=0;
?>
 
          <?php 
              $querynb= $bdcon->prepare("SELECT nombcompleto from estudiante where codest='$codest'");
              $querynb->execute();
              while ($ronb = $querynb->fetch(PDO::FETCH_ASSOC)) {
                 $nombcompleto=$ronb['nombcompleto']; 
              }          
         ?> 
  <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                      <h3>Boletin de notas</h3>
                      <h6>Estudiante: <?php echo $codest."-".$nombcompleto;?></h6>
                      <h6>Materia: <?php echo $n_mat; ?></h6>
                    </div>
                     <table class="table table-hover">
                      <thead>
                        <tr align="center">    
                          <th class='text-muted small'><trong>1° Parcial</trong></th>
                          <th class='text-muted small'><trong>2° Parcial</trong></th>
                          <th class='text-muted small'><trong>Examen Final</trong></th>
                          <th class='text-muted small'><trong>Nota Practica</trong></th>
                          <!--****************para mostra instancia *****************-->
                          <?php
                                $ins_numero=2;
                            for ($i=5; $i <=6 ; $i++) { 
                                     $notains= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='$i' and estado='1'");
                                     $notains->execute();
                                      if ($notains->rowCount()>0) { 
                                            ?>
                                          <th class='text-muted small'><trong><?php echo $ins_numero;?>° Instancia</trong></th>
                                          <?php
                                     }                             
                                $ins_numero ++;
                            } 
                          ?>
                          <th class='text-muted small'><trong>Nota Final</trong></th>
                        </tr>
                      </thead>
                    <tbody>
                      <tr align="center">      
                        <td class='text-muted small'>
            <?php    
                  if ($pp=='0') {
                     $nota1= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='1' and estado='1'");
                     $nota1->execute();
                     while ($row4 = $nota1->fetch(PDO::FETCH_ASSOC)) {
                        $pp=$row4['reco']; 
                     }          
                  }
                  if ($pp=='') {
                    echo "0.00";
                    $pp=0;
                  }else{
                    echo number_format($pp,2);  
                  }
            ?>
      </td>
      <td class='text-muted small'>
          <?php    
              if ($sp=='0') {
                 $nota2= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='2' and estado='1'");
                 $nota2->execute();
                 while ($row5 = $nota2->fetch(PDO::FETCH_ASSOC)) {
                    $sp=$row5['reco']; 
                 }          
              }
              if ($sp=='') {
                echo "0.00";
                $sp=0;
              }else{
                echo number_format($sp,2);  
              }
          ?>
      </td>
      <td class='text-muted small'>
          <?php    
              if ($ef=='0') {
                 $nota3= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='3' and estado='1'");
                 $nota3->execute();
                 while ($row6 = $nota3->fetch(PDO::FETCH_ASSOC)) {
                    $ef=$row6['reco']; 
                 }          
              }
              if ($ef=='') {
                echo "0.00";
                $ef=0;
              }else{
                echo number_format($ef,2);  
              }
          ?>        
      </td>
      <td class='text-muted small'>
          <?php 
               $nota_practica= $bdcon->prepare("SELECT SUM(grupos_sub_notas.nota)as noti,grupos_sub_notas.parcial,grupos_sub.descripcion,grupos_sub_notas.fecha, grupos_sub.prac_prog from grupos_sub INNER JOIN grupos_sub_notas ON (grupos_sub.cod=grupos_sub_notas.cod_subgrupo) WHERE grupos_sub.idgrupo_padre='$idgrup' and grupos_sub_notas.codest='$codest' GROUP BY grupos_sub_notas.fecha, grupos_sub_notas.codest, grupos_sub.descripcion ORDER BY grupos_sub.descripcion ASC");
                 $nota_practica->execute();
                 while ($rows = $nota_practica->fetch(PDO::FETCH_ASSOC)){                   
                    $notita=$rows['noti'];
                    $par=$rows['parcial'];
                    $tipo=$rows['descripcion'];
                    $prac_prog=$rows['prac_prog'];
                    if ($tipo=='1') {
                            #Practica
                            if ($par=='1') {
                              //primer parcial
                              $ppr=$ppr+$notita;
                              $c_pp++;
                            }else{
                              if ($par=='2') {
                                //segundo parcial
                                $spr=$spr+$notita;
                                $c_sp++;
                              }else{
                                if ($par=='3') {
                                  //examen final
                                  $efr=$efr+$notita;
                                  $c_ef++;
                                }
                              }
                            }
                            $c_pra=$rows['prac_prog'];
                          }else{
                            if ($tipo=='2') {
                              #Investigacion
                              if ($par=='1') {
                                $ipp=$ipp+$notita;
                                $inv_pp++;
                              }else{
                                if ($par=='2') {
                                  $isp=$isp+$notita;
                                  $inv_sp++;
                                }else{
                                  if ($par=='3') {
                                    $ief=$ief+$notita;
                                    $inv_ef++;
                                  }else{
                                    //nada
                                  }
                                }
                              }
                              $c_inv=$rows['prac_prog'];
                            }else{
                              if ($tipo=='3') {
                                #Interaccion Social
                                if ($par=='1') {
                                  $ispp=$ispp+$notita;
                                  $is_pp++;
                                }else{
                                  if ($par=='2') {
                                    $issp=$issp+$notita;
                                    $is_sp++;
                                  }else{
                                    if ($par=='3') {
                                      $isef=$isef+$notita;
                                      $is_ef++;
                                    }else{
                                      //nada
                                    }
                                  }
                                }
                                $c_is=$rows['prac_prog'];
                              }else{
                                if ($tipo=='4') {
                                  #procesual
                                  if ($par=='1') {
                                    $propp=$propp+$notita;
                                    $pro_pp++;
                                  }else{
                                    if ($par=='2') {
                                      $prosp=$prosp+$notita;
                                      $pro_sp++;
                                    }else{
                                      if ($par=='3') {
                                        $proef=$proef+$notita;
                                        $pro_ef++;
                                      }else{
                                        //nada
                                      }
                                    }
                                  }
                                  $c_pro=$rows['prac_prog'];
                                }else{
                                  if ($tipo=='5') {
                                    # investigacion - interaccion
                                    if ($par=='1') {
                                      $invinpp=$invinpp+$notita;
                                      $invin_pp++;
                                    }else{
                                      if ($par=='2') {
                                        $invinsp=$invinsp+$notita;
                                        $invin_sp++;
                                      }else{
                                        if ($par=='3') {
                                          $invinef=$invinef+$notita;
                                          $invin_ef++;
                                        }
                                      }
                                    }
                                    $c_invin=$rows['prac_prog'];
                                  }else{
                                    //otro tipo no hacer nada
                                  }
                                }
                              }
                            }
                          }
                          if ($periodo<'201701') {
                            //variables de practica
                            @$ppr=$ppr/$c_pp;
                            @$spr=$spr/$c_sp;
                            @$efr=$efr/$c_ef;
                            @$prac=$ppr+$spr+$efr;
                            //variables de investigacion
                            @$ipp=$ipp/$inv_pp;
                            @$isp=$isp/$inv_sp;
                            @$ief=$ief/$inv_ef;
                            @$inves=$ipp+$isp+$ief;
                            //variables de interaccion
                            @$ispp=$ispp/$is_pp;
                            @$issp=$issp/$is_sp;
                            @$isef=$isef/$is_ef;
                            @$inter=$ispp+$issp+$isef;
                            //variables de procesual
                            @$propp=$propp/$pro_pp;
                            @$prosp=$prosp/$pro_sp;
                            @$proef=$proef/$pro_ef;
                            @$proce=$propp+$prosp+$proef;
                            //variables investigacion - interaccion
                            @$invinpp=$invinpp/$invin_pp;
                            @$invinsp=$invinsp/$invin_sp;
                            @$invinef=$invinef/$invin_ef;
                            @$invinte=$invinpp+$invinsp+$invinef;
                          }else{
                            if ($periodo=='201701' && ($codmat=='ODT106' || $codmat=='ODT306' || $codmat=='ODT509' || $codmat=='ODT709' || $codmat=='ODT908')) {
                              //echo "Solo de odonto y solo en ingles ";
                              //variables de practica
                              @$ppr=$ppr/$c_pp;
                              @$spr=$spr/$c_sp;
                              @$efr=$efr/$c_ef;
                              @$prac=$ppr+$spr+$efr;
                              //variables de investigacion
                              @$ipp=$ipp/$inv_pp;
                              @$isp=$isp/$inv_sp;
                              @$ief=$ief/$inv_ef;
                              @$inves=$ipp+$isp+$ief;
                              //variables de interaccion
                              @$ispp=$ispp/$is_pp;
                              @$issp=$issp/$is_sp;
                              @$isef=$isef/$is_ef;
                              @$inter=$ispp+$issp+$isef;
                              //variables de procesual
                              @$propp=$propp/$pro_pp;
                              @$prosp=$prosp/$pro_sp;
                              @$proef=$proef/$pro_ef;
                              @$proce=$propp+$prosp+$proef;
                              //variables investigacion - interaccion
                              @$invinpp=$invinpp/$invin_pp;
                              @$invinsp=$invinsp/$invin_sp;
                              @$invinef=$invinef/$invin_ef;
                              @$invinte=$invinpp+$invinsp+$invinef;
                            }else{
                              $per_cor=substr($periodo,4,5);
                              if ($per_cor=='01' || $per_cor=='02' || $per_cor=='06' || $per_cor=='08') {
                                //variables de practica
                                $aux_pra=$c_pp+$c_sp+$c_ef;
                                if ($aux_pra>$c_pra) {
                                  $c_pra=$aux_pra;
                                }
                                @$prac=($ppr+$spr+$efr)/$c_pra;
                                //variables de investigacion
                                $aux_inv=$inv_pp+$inv_sp+$inv_ef;
                                if ($aux_inv>$c_inv) {
                                  $c_inv=$aux_inv;
                                }
                                @$inves=($ipp+$isp+$ief)/$c_inv;
                                //variables de interaccion
                                $aux_int=$is_pp+$is_sp+$is_ef;
                                if ($aux_int>$c_is) {
                                  $c_is=$aux_int;
                                }
                                @$inter=($ispp+$issp+$isef)/$c_is;
                                //variables de procesual
                                $aux_pro=$pro_pp+$pro_sp+$pro_ef;
                                if ($aux_pro>$c_pro) {
                                  $c_pro=$aux_pro;
                                }
                                if ($periodo>=201902) {
                                  @$proce=($propp+$prosp+$proef);
                                }else{
                                  @$proce=($propp+$prosp+$proef)/$c_pro;
                                }
                                //variables investigacion - interaccion
                                $aux_invin=$invin_pp+$invin_sp+$invin_ef;
                                if ($aux_invin>$c_invin) {
                                  $c_invin=$aux_invin;
                                }
                                @$invinte=($invinpp+$invinsp+$invinef)/$c_invin;
                              }else{
                                //periodo cortos se suman por parcial
                                //variables de practica
                                @$ppr=$ppr/$c_pp;
                                @$spr=$spr/$c_sp;
                                @$efr=$efr/$c_ef;
                                @$prac=$ppr+$spr+$efr;
                                //variables de investigacion
                                @$ipp=$ipp/$inv_pp;
                                @$isp=$isp/$inv_sp;
                                @$ief=$ief/$inv_ef;
                                @$inves=$ipp+$isp+$ief;
                                //variables de interaccion
                                @$ispp=$ispp/$is_pp;
                                @$issp=$issp/$is_sp;
                                @$isef=$isef/$is_ef;
                                @$inter=$ispp+$issp+$isef;
                                //variables de procesual
                                @$propp=$propp/$pro_pp;
                                @$prosp=$prosp/$pro_sp;
                                @$proef=$proef/$pro_ef;
                                @$proce=$propp+$prosp+$proef;
                                //variables investigacion - interaccion
                                @$invinpp=$invinpp/$invin_pp;
                                @$invinsp=$invinsp/$invin_sp;
                                @$invinef=$invinef/$invin_ef;
                                @$invinte=$invinpp+$invinsp+$invinef;
                              }
                            }
                          }
                          $nota_prac=$prac+$inves+$inter+$proce+$invinte;
                        }
                      echo number_format($nota_prac,2);
          ?>
      </td>
      <?php
            
            $einstancia=0;
            for ($j=5; $j <=6 ; $j++) {
              $notainsi= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='$j' and estado='1'");
              $notainsi->execute();
              if ($notainsi->rowCount()>0) { 
                  while ($row10 = $notainsi->fetch(PDO::FETCH_ASSOC)) {
                    $eins=$row10['reco']; 
                      ?>
                       <td class='text-muted small'><trong><?php echo $eins;?></trong></td>
                    <?php
                  } 
                   $einstancia=$einstancia + @$eins;          
               } 
            }             
                $nf=$pp+$sp+$ef+$einstancia; 
                $nff=number_format($nf,2);
            if ($einstancia==0) {                
                ?>
                  <td class='text-muted small'><?php  echo $nff;?></td>
                <?php              
            }else{
                if ($einstancia>=51) {
                  ?>
                  <td class='text-muted small'>51</td>
                  <?php
                }else{
                  ?>
                     <td class='text-muted small'><?php  echo $nff;?></td>
                  <?php  
                }
            }
          ?>
    </tr>    
  </tbody>
</table>

                    </div>
                
    </div> 
  </div>     
   </div>          
   
	