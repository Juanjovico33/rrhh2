<?php	
  include "../includes/conexion.php";
  include "../includes/_resultadoParcial.php";
  include "../includes/_event_log.php";
	$codest = $_GET['_codest'];	
 	$codmat = $_GET['_codmat'];
	$periodo = $_GET['_per'];	
  $idgrup=$_GET['_idgrupo'];
  @$subgrupo=$_GET['_subgrupo'];
  $mi_examen = new examen();
  $e = new evento();
  $e->setIdGrupo($idgrup);
  $e->e_log_inicio_evento($codest, 22);
  $subgrupopresencial="";
  if ($subgrupo=="0" || $subgrupo=="") {
    $subgrupo=$idgrup;
  }
  if ($periodo=='202002' || $periodo=='202001'){
    $q_verinbpresencial= $bdcon->prepare("SELECT cod from grupos_sub where (idgrupo_padre='$idgrup' or idgrupo_padre='$subgrupo') and (grupo='PRESENCIAL' or grupo='PRESENCIAL 1' or grupo='PRESENCIAL1' or grupo='PRESENCIAL 2' or grupo='PRESENCIAL 3' or grupo='Presencial' or grupo='PRESENCIAL Q' or grupo='PRESENCIAL FI' or grupo='PRESENCIAL A1') and idgrupo_padre<>'0'");
  }else{
    $q_verinbpresencial= $bdcon->prepare("SELECT cod from grupos_sub where (idgrupo_padre='$idgrup' or idgrupo_padre='$subgrupo') and (grupo='PRESENCIAL' or grupo='PRESENCIAL 1' or grupo='PRESENCIAL1' or grupo='Presencial' or grupo='PRESENCIAL Q' or grupo='PRESENCIAL FI' or grupo='PRESENCIAL A1') and idgrupo_padre<>'0'");
  }
  $q_verinbpresencial->execute();
  while ($rowcodsubgru = $q_verinbpresencial->fetch(PDO::FETCH_ASSOC)) {
    $subgrupopresencial=$rowcodsubgru['cod']; 
  }

  $q_semestre_carrera="SELECT estsem.semestre, estcod.carrera FROM aca_estudiantesemestre estsem left join estudiante_codigo estcod on estsem.codest=estcod.codigo_estudiante where estsem.codest=$codest and estsem.periodo=$periodo";
  $semestre=""; $carrera=""; $practicas_med=false;;
  $ob_semcar=$bdcon->prepare($q_semestre_carrera);
  $ob_semcar->execute();
  while ($row_semcar = $ob_semcar->fetch(PDO::FETCH_ASSOC)) {
    $semestre=$row_semcar['semestre'];
    $carrera=$row_semcar['carrera']; 
    if(($semestre==10 || $semestre==9 || $semestre==8 || $semestre==7 || $semestre==6) && ($carrera=="01MED" || $carrera=="03MED")){
        $practicas_med=true;
    }
  }

  $pp=0;
  $sp=0;
  $ef=0;
  $cp=0;
  $m2=0;
  $m4=0;
  $m5=0;
  $aapren=0;
  $ainver=0;
  $intsocial=0;
  $nprocesual=0;
  $ndefenza=0;
  $prominv=0;
  $pp_banco=0;
  $sp_banco=0;
  $ef_banco=0;
  $cp_banco=0;
  //verificar evaluacion de encuesta
  $cod_encu=5;
  $qev=$bdcon->prepare("SELECT cod FROM plat_est_encu_respuestas where codest='$codest' and codgrupo='$idgrup' and cod_encu='$cod_encu'");
  $qev->execute();
  $cod_er=0;
  while ($rqev = $qev->fetch(PDO::FETCH_ASSOC)) {
    $cod_er=$rqev['cod']; 
  }
  //$cod_er=1;
  if ($cod_er>0) {
    //ya tiene evaluacion
  }else{
    //no tiene evaluacion, obligarlo a hacer 
    ?>
    <div uk-grid>
      <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content" id="resultado">
              <h2>ENCUESTA DE SATISFACCIÓN ESTUDIANTIL DE CLASES TEÓRICAS - GESTIÓN II-2022</h2>
              <hr>
              <form name="encues" method="post" id="frm_encuesta" onsubmit="guardar_encuesta()">
              <input type="hidden" name="codest" value="<?php echo $codest; ?>">
              <input type="hidden" name="cod_encu" value="<?php echo $cod_encu; ?>">
              <input type="hidden" name="codgru" value="<?php echo $idgrup; ?>">
              <p class="uk-text-emphasis uk-text-lead uk-text-justify">
                Estimado estudiante:<br><br>
                Deseamos conocer tu opinión respecto al desarrollo de las clases virtuales y los servicios que te brinda la Plataforma estudiantil UNE.  Para este fin, requerimos que respondas con sinceridad la Encuesta que te presentamos. <br>La información que nos brindes será tratada con mucha confidencialidad y ayudará en la toma de decisiones buscando la mejora continua del proceso educativo de nuestra Universidad. <br>Debes responder eligiendo una de las opciones que se te plantean para cada pregunta. <br>También, puedes hacernos alguna (s) sugerencia (s) en el espacio que se encuentra al terminar la Encuesta (no es obligatorio hacerlo). Al finalizar, debes guardar el documento<br><br>Agradecemos tu participación.<br>
              </p>
              <table class="table table-sm">
                <!--tr>
                  <th colspan="3" class="uk-text-emphasis">RESPECTO LAS CLASES VIRTUALES:</th>
                </tr-->
              <?php
              $qpreg=$bdcon->prepare("SELECT cod, pregunta, tipo_respuesta, num_preg FROM plat_est_preguntas where cod_encu='$cod_encu' and estado='1'");
              $qpreg->execute();
              $resp=$bdcon->prepare("SELECT cod, cod_preg, respuesta FROM plat_est_preguntas_respuestas WHERE estado='1'");
              $resp->execute();
              while ($fresp=$resp->fetch(PDO::FETCH_ASSOC)) {
                $cod=$fresp['cod_preg'];
                $respu=$fresp['respuesta'];
                $cod_resp=$fresp['cod'];
                $respuestas[]=$cod."|".$respu."|".$cod_resp;
              }
              //print_r($respuestas);
              $nume=1;
              $part[]="";
              while ($fpreg=$qpreg->fetch(PDO::FETCH_ASSOC)) {
                $codpreg=$fpreg['cod'];
                $preg=$fpreg['pregunta'];
                $t_res=$fpreg['tipo_respuesta'];
                $num_preg=$fpreg['num_preg'];
                ?>
                <tr>
                  <th><?php echo $nume; ?></th>
                  <th colspan="2"><?php echo $preg; ?><input type="hidden" name="codpreg[]" value="<?php echo $codpreg; ?>"></th>
                </tr>
                <?php
                if ($t_res=='1') {
                  ?>
                  <tr>
                    <td></td>
                      <?php 
                      foreach ($respuestas as $key => $value) {
                        $part=explode("|", $value);
                        if ($part[0]==$codpreg) {
                          ?>
                          <td><?php //echo $part[2]; ?>
                            <div align="center">
                              <input type="radio" name="<?php echo $nume; ?>" value="<?php echo $part[1]; ?>"><?php echo $part[1]; ?>
                            </div>
                          </td>
                          <?php
                        }
                      }
                      ?>
                  </tr>
                  <?php
                }else{
                  ?>
                  <tr>
                    <td></td>
                    <td colspan="2"><input class="uk-input" type="text" name="<?php echo $nume; ?>" class="form-control" autocomplete="off"></td>
                  </tr>
                  <?php
                }
                /*if ($nume==12) {
                  ?>
                  <tr>
                    <th colspan="3">RESPECTO LA PLATAFORMA ESTUDIANTIL:</th>
                  </tr>
                  <?php
                }*/
                $nume++;
              }
              ?>
              </table>
              <button class="btn btn-success">GUARDAR ENCUESTA</button>
              </form>
          </div>
        </div>
      </div>
    </div>
    <?php
    exit();
  }

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
    $nq_carrera= $bdcon->prepare("SELECT CodCarrera from grupos where CodGrupo='$idgrup'");
    $nq_carrera->execute();
    while ($rowcar = $nq_carrera->fetch(PDO::FETCH_ASSOC)) {
       $codcar=$rowcar['CodCarrera']; 
    } 

    $n_materia= $bdcon->prepare("SELECT Descripcion from materias where Sigla='$codmat' and CodCarrera='$codcar'");
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
    $campos1=0;
    $campos2=0;
    $campos3=0;
    $campos4=0;
    $campos5=0;
    $campos6=0;
    $campos7=0;
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
                        <h6>Materia: <?php echo $codmat." - ". $n_mat; ?></h6>
                        <?php 
                          if($periodo=='202202') {
                        ?>
                            <h6>Periodo: Normal 2/2022</h6>
                        <?php
                          }elseif($periodo=='202208'){
                        ?>
                            <h6>Periodo: Nivelación 2/2022</h6>
                        <?php    
                          }
                        ?>                        
                    </div>
                     <table class="table table-hover">
                        <thead>
                          <tr align="center">    
                              <th class='text-muted small'><strong>1° Parcial</strong></th>
                              <th class='text-muted small'><strong>2° Parcial</strong></th>                              
                              <th class='text-muted small'><strong>3° Parcial</strong></th>
                              <th class='text-muted small'><strong>4° Parcial</strong></th> 
                              <?php
                                $percor=substr($periodo, 4,5);
                                if ($percor=="02" || $percor=="08") {                                  
                                }else{
                                 ?>
                                  <th class='text-muted small'><strong>M.2</strong></th>
                                  <th class='text-muted small'><strong>M.4</strong></th>
                                 <?php
                                }                                
                              ?> 
                              <th class='text-muted small'><strong>M.5</strong></th>
                              <?php 
                                $querycampo1= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='49'");
                                $querycampo1->execute();
                                while ($rowquery1 = $querycampo1->fetch(PDO::FETCH_ASSOC)) {
                                  $campos1=$rowquery1['campo'];
                                  if ($campos1=="" || $campos1==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Aulas<br>Invertidas</strong></th>
                                    <?php
                                  }
                                } 
                                $querycampo2= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='48'");
                                $querycampo2->execute();
                                while ($rowquery2 = $querycampo2->fetch(PDO::FETCH_ASSOC)) {
                                  $campos2=$rowquery2['campo'];
                                  if ($campos2=="" || $campos2==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Auto<br>Aprendizaje</strong></th>
                                    <?php
                                  }
                                }  
                                $querycampo3= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='22'");
                                $querycampo3->execute();
                                while ($rowquery3 = $querycampo3->fetch(PDO::FETCH_ASSOC)) {
                                  $campos3=$rowquery3['campo'];
                                  if ($campos3=="" || $campos3==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Nota Practica</strong></th>
                                    <?php
                                  }
                                } 
                                $querycampo4= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and (campo='6' or campo='28')");
                                $querycampo4->execute();
                                while ($rowquery4 = $querycampo4->fetch(PDO::FETCH_ASSOC)) {
                                  $campos4=$rowquery4['campo'];
                                  if ($campos4=="" || $campos4==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Investigación</strong></th>
                                    <?php
                                  }
                                } 
                                $querycampo5= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='26'");
                                $querycampo5->execute();
                                while ($rowquery5 = $querycampo5->fetch(PDO::FETCH_ASSOC)) {
                                  $campos5=$rowquery5['campo'];
                                  if ($campos5=="" || $campos5==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Int. Social</strong></th>
                                    <?php
                                  }
                                }  
                                $querycampo6= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='30'");
                                $querycampo6->execute();
                                while ($rowquery6 = $querycampo6->fetch(PDO::FETCH_ASSOC)) {
                                  $campos6=$rowquery6['campo'];
                                  if ($campos6=="" || $campos6==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Procesual</strong></th>
                                    <?php
                                  }
                                } 
                                $querycampo7= $bdcon->prepare("SELECT campo from aca_ponderacion where cod_grupo='$idgrup' and campo='47'");
                                $querycampo7->execute();
                                while ($rowquery7 = $querycampo7->fetch(PDO::FETCH_ASSOC)) {
                                  $campos7=$rowquery7['campo'];
                                  if ($campos7=="" || $campos7==0 ) {
                                  }else{
                                    ?>
                                      <th class='text-muted small'><strong>Defenza<br>Taller</strong></th>
                                    <?php
                                  }
                                }                                         
                              ?> 
                              <!--****************para mostra instancia *****************-->
                              <?php
                               /* $ins_numero=2;
                                for ($i=5; $i <=6 ; $i++) { 
                                    $notains= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='$i' and estado='1'");
                                    $notains->execute();
                                      if ($notains->rowCount()>0) { 
                                            ?>
                                          <!--<th class='text-muted small'><strong><?php echo $ins_numero;?>° Instancia</strong></th>-->
                                          <?php
                                     }                             
                                   $ins_numero ++;
                                } */
                              ?>
                              <th class='text-muted small'><strong>Nota Final</strong></th>
                          </tr>
                        </thead>
                    <tbody>
                      <tr align="center">      
                        <td class='text-muted small'>
                    <?php    
                          if ($pp=='0') {
                             $nota1= $bdcon->prepare("SELECT reco, cod_ban from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='1' and estado='1'");
                             $nota1->execute();
                             while ($row4 = $nota1->fetch(PDO::FETCH_ASSOC)) {
                                $pp=$row4['reco']; 
                                $pp_banco=$row4['cod_ban'];
                             }          
                          }
                          if ($pp=='') {
                            echo "0.00";
                            $pp=0;
                          }else{
                              $ponde1= $bdcon->prepare("SELECT porcentaje from aca_ponderacion where cod_grupo='$idgrup' and num_parcial='1' and estado='1' and (campo='2' or campo='14' or campo='17' or campo='20' or campo='29')");
                              $ponde1->execute();
                             while ($rowpon1 = $ponde1->fetch(PDO::FETCH_ASSOC)) {
                                $pondep1=$rowpon1['porcentaje']; 
                             }
                             if ($pp>$pondep1) {
                                echo "<font color='red'>A espera <br>de revisión</font>";
                             }else{
                               // PARCIAL 1
                               $title_parcial1="1° Parcial vale ".$pondep1." ptos.";
                               $_note1=number_format($pp,2); 
                               $mi_examen->setCodest($codest);
                               $mi_examen->setCodBanco($pp_banco);
                               if($mi_examen->puede_ver_enfecha()){
                                  echo "<a href='#' title='$title_parcial1' uk-toggle='target:#modal-detalle-eval' onclick='ver_mi_examen($pp_banco, $codest, $pondep1)'>".$_note1."</a>";
                               }else{
                                  echo $_note1;// SIN VER RESULTADOS
                               }
                             }                            
                          }
                    ?>
                        </td>
                        <td class='text-muted small'>
                            <?php    
                                if ($sp=='0') {
                                   $nota2= $bdcon->prepare("SELECT reco, cod_ban from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='2' and estado='1'");
                                   $nota2->execute();
                                   while ($row5 = $nota2->fetch(PDO::FETCH_ASSOC)) {
                                      $sp=$row5['reco']; 
                                      $sp_banco=$row5['cod_ban'];
                                   }          
                                }
                                if ($sp=='') {
                                  echo "0.00";
                                  $sp=0;
                                }else{
                                   $ponde2= $bdcon->prepare("SELECT porcentaje from aca_ponderacion where cod_grupo='$idgrup' and num_parcial='2' and estado='1' and (campo='2' or campo='14' or campo='17' or campo='20' or campo='29')");
                                    $ponde2->execute();
                                   while ($rowpon2 = $ponde2->fetch(PDO::FETCH_ASSOC)) {
                                      $pondep2=$rowpon2['porcentaje']; 
                                   }
                                   if ($sp>$pondep2) {
                                     echo "<font color='red'>A espera <br>de revisión</font>";
                                   }else{
                                     // PARCIAL 2
                                    $title_parcial2="2° Parcial vale ".$pondep2." ptos.";
                                    $_note2=number_format($sp,2); 
                                    $mi_examen->setCodest($codest);
                                    $mi_examen->setCodBanco($sp_banco);
                                    if($mi_examen->puede_ver_enfecha()){
                                      echo "<a href='#' title='$title_parcial2' uk-toggle='target:#modal-detalle-eval' onclick='ver_mi_examen($sp_banco, $codest, $pondep2)'>".$_note2."</a>";  
                                      // echo number_format($sp,2);  
                                    }else{
                                      echo $_note2; // SIN VER RESULTADOS
                                    }
                                   }                                  
                                }
                            ?>
                        </td>
                        <td class='text-muted small'>
                            <?php    
                                if ($ef=='0') {
                                   $nota3= $bdcon->prepare("SELECT reco, cod_ban from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='3' and estado='1'");
                                   $nota3->execute();
                                   while ($row6 = $nota3->fetch(PDO::FETCH_ASSOC)) {
                                      $ef=$row6['reco']; 
                                      $ef_banco=$row6['cod_ban'];
                                   }          
                                }
                                if ($ef=='') {
                                  echo "0.00";
                                  $ef=0;
                                }else{
                                  $ponde3= $bdcon->prepare("SELECT porcentaje from aca_ponderacion where cod_grupo='$idgrup' and num_parcial='3' and estado='1' and (campo='2' or campo='14' or campo='17' or campo='20' or campo='29' or campo='1')");
                                    $ponde3->execute();
                                   while ($rowpon3 = $ponde3->fetch(PDO::FETCH_ASSOC)) {
                                      $pondep3=$rowpon3['porcentaje']; 
                                   }
                                   if ($ef>$pondep3) {
                                     echo "<font color='red'>A espera <br>de revisión</font>";
                                   }else{
                                     // PARCIAL 3
                                     $title_parcial3="3° Parcial vale ".$pondep3." ptos.";
                                     $_note3=number_format($ef,2);
                                     $mi_examen->setCodest($codest);
                                     $mi_examen->setCodBanco($ef_banco);
                                     if($mi_examen->puede_ver_enfecha()){
                                       echo "<a href='#' title='$title_parcial3' uk-toggle='target:#modal-detalle-eval' onclick='ver_mi_examen($ef_banco, $codest, $pondep3)'>".$_note3."</a>";  
                                       // echo number_format($ef,2);
                                     }else{
                                       echo $_note3;   // SIN VER RESULTADOS
                                     }
                                   } 
                                }
                            ?>        
                        </td>

                        <?php
                      
                          ?>
                            <td class='text-muted small'>
                            <?php    
                                if ($cp=='0') {
                                   $nota4= $bdcon->prepare("SELECT reco, cod_ban from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='4' and estado='1'");
                                   $nota4->execute();
                                   while ($row7 = $nota4->fetch(PDO::FETCH_ASSOC)) {
                                      $cp=$row7['reco']; 
                                      $cp_banco=$row7['cod_ban'];
                                   }          
                                }
                                if ($cp=='') {
                                  echo "0.00";
                                  $cp=0;
                                }else{
                                   $ponde4= $bdcon->prepare("SELECT porcentaje from aca_ponderacion where cod_grupo='$idgrup' and num_parcial='4' and estado='1' and (campo='2' or campo='14' or campo='17' or campo='20' or campo='29')");
                                    $ponde4->execute();
                                   while ($rowpon4 = $ponde4->fetch(PDO::FETCH_ASSOC)) {
                                      $pondep4=$rowpon4['porcentaje']; 
                                   }
                                   if ($cp>$pondep4) {
                                     echo "<font color='red'>A espera <br>de revisión</font>";
                                   }else{
                                     // PARCIAL 4
                                     $title_parcial4="4° Parcial vale ".$pondep4." ptos.";
                                     $_note4=number_format($cp,2); 
                                     $mi_examen->setCodest($codest);
                                     $mi_examen->setCodBanco($cp_banco);
                                     if($mi_examen->puede_ver_enfecha()){
                                        echo "<a href='#' title='$title_parcial4' uk-toggle='target:#modal-detalle-eval' onclick='ver_mi_examen($cp_banco, $codest, $pondep4)'>".$_note4."</a>";
                                       // echo number_format($cp,2);
                                     }else{
                                       echo $_note4;
                                     }
                                   }  
                                }
                            ?>        
                            </td>                           
                          <?php                           
                            if ($percor=="02" || $percor=="08") {                                  
                            } else {
                             ?>
                              <!--Momento 2-->
                            <td class='text-muted small'>
                              <?php                                 
                                $nota11= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='11' and estado='1'");
                                  $nota11->execute();
                                while ($row8 = $nota11->fetch(PDO::FETCH_ASSOC)) {
                                      $m2=$row8['reco']; 
                                } 
                                if ($m2=='0' || $m2=='') {  
                                     $nota111= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='11' and estado='1'");
                                     $nota111->execute();
                                     while ($row81 = $nota111->fetch(PDO::FETCH_ASSOC)) {
                                        $m2=$row81['reco']; 
                                     } 
                                     if ($m2=='0' || $m2=='') {
                                       echo "0.00";
                                       $m2=0;
                                     }else{
                                         echo number_format($m2,2);
                                     } 
                                }else{
                                  echo number_format($m2,2);                               
                                }                             
                            ?>
                            </td>
                            <!-- Momento 4-->
                            <td class='text-muted small'>
                              <?php                                 
                                $nota12= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='12' and estado='1'");
                                $nota12->execute();
                                while ($row9 = $nota12->fetch(PDO::FETCH_ASSOC)) {
                                  $m4=$row9['reco']; 
                                } 
                                if ($m4=='0' || $m4=='') {
                                    $nota122= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='12' and estado='1'");
                                    $nota122->execute();
                                    while ($row92 = $nota122->fetch(PDO::FETCH_ASSOC)) {
                                      $m4=$row92['reco']; 
                                    } 
                                    if ($m4=='0' || $m4=='') {
                                        echo "0.00";
                                        $m4=0;
                                    }else{
                                         echo number_format($m4,2);
                                    }
                                }else{
                                  echo number_format($m4,2);
                                }                              
                              ?>
                            </td>
                             <?php
                            }                                
                          ?> 
                            <!--Momento 5-->
                            <td class='text-muted small'>
                            <?php
                                $nota13= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='13' and estado='1'");
                                $nota13->execute();
                                while ($row10 = $nota13->fetch(PDO::FETCH_ASSOC)) {
                                  $m5=$row10['reco']; 
                                } 
                                if ($m5=='0' || $m5=='') { 
                                    $nota133= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='13' and estado='1'");
                                    $nota133->execute();
                                    while ($row103 = $nota133->fetch(PDO::FETCH_ASSOC)) {
                                      $m5=$row103['reco']; 
                                    } 
                                    if ($m5=='0' || $m5=='') {
                                        echo "0.00";
                                        $m5=0;
                                    }else{
                                      echo number_format($m5,2);  
                                    }
                                }else{
                                  echo number_format($m5,2);  
                                }                                
                              ?>
                            </td>
                             <!--Aulas Invertidas-->
                             <?php
                              if ($campos1=="" || $campos1==0 ) {
                                  }else{
                                    ?>
                                     <td class='text-muted small'>
                                        <?php
                                            $nota14= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='14' and estado='1'");
                                            $nota14->execute();
                                            while ($row11 = $nota14->fetch(PDO::FETCH_ASSOC)) {
                                              $ainver=$row11['reco']; 
                                            } 
                                            if ($ainver=='0' || $ainver=='') { 
                                                $nota144= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='14' and estado='1'");
                                                $nota144->execute();
                                                while ($row104 = $nota144->fetch(PDO::FETCH_ASSOC)) {
                                                  $ainver=$row104['reco']; 
                                                } 
                                                if ($ainver=='0' || $ainver=='') {
                                                    echo "0.00";
                                                    $ainver=0;
                                                }else{
                                                  echo number_format($ainver,2);  
                                                }
                                            }else{
                                              echo number_format($ainver,2);  
                                            }                                
                                          ?>
                                        </td>
                                    <?php
                                  }
                              ?>
                              <!--Auto Aprendizaje-->
                              <?php
                              if ($campos2=="" || $campos2==0 ) {
                                  }else{
                                    ?>
                                      <td class='text-muted small'>
                                        <?php
                                            $nota6= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='6' and estado='1'");
                                            $nota6->execute();
                                            while ($row12 = $nota6->fetch(PDO::FETCH_ASSOC)) {
                                              $aapren=$row12['reco']; 
                                            } 
                                            if ($aapren=='0' || $aapren=='') { 
                                                $nota166= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='6' and estado='1'");
                                                $nota166->execute();
                                                while ($row122 = $nota166->fetch(PDO::FETCH_ASSOC)) {
                                                  $aapren=$row122['reco']; 
                                                } 
                                                if ($aapren=='0' || $aapren=='') {
                                                    echo "0.00";
                                                    $aapren=0;
                                                }else{
                                                  echo number_format($aapren,2);  
                                                }
                                            }else{
                                              echo number_format($aapren,2);  
                                            }                                
                                          ?>
                                        </td>
                                    <?php
                                  }
                              ?>                            
                         <!--//////////notas de practicas///////////-->
                         <?php
                         if ($campos3=="" || $campos3==0 ) {
                            }else{
                              ?>
                                <td class='text-muted small'>
                                    <?php                           
                                        $notapfinal=0;
                                        $sub_grupo=0;
                                        $query_sub= $bdcon->prepare(" SELECT grupos_sub.idgrupo_padre, grupos_sub.cod_doc, grupos_sub.cod , grupos_sub.grupo FROM (grupos_sub INNER JOIN grupos_sub_listas ON grupos_sub.cod = grupos_sub_listas.cod_subgru) where grupos_sub_listas.codest='$codest' and (grupos_sub.idgrupo_padre='$idgrup' OR idgrupo_padre='$subgrupo')");
                                        $query_sub->execute();
                                        $subexiste2="";
                                        while ($row = $query_sub->fetch(PDO::FETCH_ASSOC)) {
                                          $subexiste2=$row['idgrupo_padre'];
                                          $sub_grupo=$row['cod'];
                                          $doc_sub=$row['cod_doc'];
                                          $subgrup=$row['grupo'];
                                        }
                                        $hay=$query_sub->rowCount(); 
                                        $notapractica=0;
                                        $pondepracc= $bdcon->prepare("SELECT porcentaje from aca_ponderacion where cod_grupo='$idgrup' and estado='1' and (campo='3' or campo='19' or campo='22' or campo='24' or campo='27' or campo='31')");
                                        $pondepracc->execute();
                                        while ($rowponprac = $pondepracc->fetch(PDO::FETCH_ASSOC)) {
                                          $pondeprac=$rowponprac['porcentaje']; 
                                        }
                                        if ($hay=='0' || $hay=='') {                             

                                          if($practicas_med){
                                            goto seguir_aqui;
                                          }else{
                                            if($periodo>=202201){
                                              goto seguir_aqui;
                                            }else{
                                              echo "0.00";
                                            }
                                          }

                                        }else{    
                                          seguir_aqui:                               
                                          $query_verygru= $bdcon->prepare("SELECT nb from plat_est_nbsubgrupo where nb='$subgrup'");
                                          $query_verygru->execute();
                                          $existe=$query_verygru->rowCount();
                                          $query_sub= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and (codgrupo='$idgrup' or codgrupo='$subgrupo') and parcial='10' and estado='1'");
                                          $query_sub->execute();
                                              while ($rprac = $query_sub->fetch(PDO::FETCH_ASSOC)) {
                                                  $notapractica=$rprac['reco']; 
                                               }  
                                          if ($existe>0) {
                                               # PRESENCIAL 
                                                if ($notapractica=='') {
                                                    echo "0.00";
                                                    $notapfinal=0;
                                                }else{
                                                    $notapfinal=$notapractica;
                                                    if ($notapfinal>$pondeprac) {
                                                      echo "<font color='red'>A espera <br>de revisión</font>";
                                                    }else{
                                                      echo number_format($notapfinal,2);
                                                    }                                            
                                                }
                                          }else{
                                              # VIRTUAL                                      
                                              $query_sub1= $bdcon->prepare("SELECT fecha FROM plat_doc_int_est_consolidar where sub_grupo='$subgrupopresencial'");
                                              $query_sub1->execute();  
                                              $n_clases=$query_sub1->rowCount();
                                            
                                              $query_sub2= $bdcon->prepare("SELECT fecha FROM aca_asistencia_practica where codgrupo='$idgrup' and cod_subgru='$sub_grupo' and codest='$codest' GROUP BY fecha");
                                              $query_sub2->execute();   
                                              $n_clases_asistidas=$query_sub2->rowCount();                                          
                                              
                                              $puntos= 5;
                                              $resultadopuntos=0;
                                              if ($notapractica==0) {
                                                    echo "0.00";
                                                    $notapfinal=0;
                                              }else{
                                                  if ($n_clases==0) {
                                                  # code...
                                                  }else{
                                                    if ($n_clases<$n_clases_asistidas) {
                                                      $n_clases_asistidas=$n_clases;
                                                    }
                                                    $resultadopuntos= (int)$n_clases_asistidas * (int)$puntos;
                                                    $resultadopuntos=  (int)$resultadopuntos / (int)$n_clases;
                                                  }    
                                                  $notapfinal=$resultadopuntos+$notapractica;
                                                  if ($notapfinal>$pondeprac) {
                                                      echo "<font color='red'>A espera <br>de revisión</font>";
                                                  }else{
                                                      echo number_format($notapfinal,2);
                                                  } 
                                              }                                       
                                          }                                 
                                      }
                              
                                    ?>                            
                                </td>
                              <?php
                            }
                        ?>
                        <!--NOTA de Investigacion-->
                        <?php
                          if ($campos4=="" || $campos4==0 ) {
                             }else{
                          ?>
                            <td class='text-muted small'>
              
                            <?php
                                $notainv= $bdcon->prepare("SELECT codest, reco, fecha from plat_doc_intentos_est where  codgrupo='$idgrup' and parcial='9' and estado='1'");
                                $notainv->execute(); 
                                $auxfecha="";
                                $suminv=0;  
                                $continv=0;  
                                $prominv=0;                          
                               while ($rowinv = $notainv->fetch(PDO::FETCH_ASSOC)) {
                                  $notasinv=$rowinv['reco']; 
                                  $codestinv=$rowinv['codest']; 
                                  $fechainv=$rowinv['fecha'];

                                  if ($codest==$codestinv) {
                                    $suminv=$suminv+$notasinv;
                                  }

                                  if ($auxfecha<>$fechainv) {
                                      $auxfecha=$fechainv;
                                      $continv++;
                                  }                                
                                }

                                if ($suminv==0 || $suminv=='') {
                                    $prominv=0;
                                    echo number_format($prominv,2);
                                }else{
                                  if($periodo=='202101'){
                                    $prominv=$suminv/3;
                                    echo number_format($prominv,2);
                                  }else if($periodo=='202102' || $periodo=='202108'){
                                    $prominv=$suminv;
                                    echo number_format($prominv,2);
                                  }else if($periodo=='202201' || $periodo=='202206'){
                                    $prominv=$suminv;
                                    echo number_format($prominv,2);
                                  }else if($periodo=='202202' || $periodo=='202208'){
                                    $prominv=$suminv;
                                    echo number_format($prominv,2);
                                  }
                                }
                            ?>
                          </td>
                          <?php
                        }
                        ?> 
                         <!--Interaccion Social-->
                              <?php
                              if ($campos5=="" || $campos5==0 ) {
                                  }else{
                                    ?>
                                      <td class='text-muted small'>
                                        <?php
                                            $nota7= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='15' and estado='1'");
                                            $nota7->execute();
                                            while ($row13 = $nota7->fetch(PDO::FETCH_ASSOC)) {
                                              $intsocial=$row13['reco']; 
                                            } 
                                            if ($intsocial=='0' || $intsocial=='') { 
                                                $nota177= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='15' and estado='1'");
                                                $nota177->execute();
                                                while ($row133 = $nota177->fetch(PDO::FETCH_ASSOC)) {
                                                  $intsocial=$row133['reco']; 
                                                } 
                                                if ($intsocial=='0' || $intsocial=='') {
                                                    echo "0.00";
                                                    $intsocial=0;
                                                }else{
                                                  echo number_format($intsocial,2);  
                                                }
                                            }else{
                                              echo number_format($intsocial,2);  
                                            }                                
                                          ?>
                                        </td>
                                    <?php
                                  }
                              ?> 
                      <!--Procesual-->
                              <?php
                              if ($campos6=="" || $campos6==0 ) {
                                  }else{
                                    ?>
                                      <td class='text-muted small'>
                                        <?php
                                            $nota8= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='17' and estado='1'");
                                            $nota8->execute();
                                            while ($row14 = $nota8->fetch(PDO::FETCH_ASSOC)) {
                                              $nprocesual=$row14['reco']; 
                                            } 
                                            if ($nprocesual=='0' || $nprocesual=='') { 
                                                $nota178= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='17' and estado='1'");
                                                $nota178->execute();
                                                while ($row134 = $nota178->fetch(PDO::FETCH_ASSOC)) {
                                                  $nprocesual=$row134['reco']; 
                                                } 
                                                if ($nprocesual=='0' || $nprocesual=='') {
                                                    echo "0.00";
                                                    $nprocesual=0;
                                                }else{
                                                  echo number_format($nprocesual,2);  
                                                }
                                            }else{
                                              echo number_format($nprocesual,2);  
                                            }                                
                                          ?>
                                        </td>
                                    <?php
                                  }
                              ?> 
                               <!--Defenza de grado-->
                              <?php
                              if ($campos7=="" || $campos7==0 ) {
                                  }else{
                                    ?>
                                      <td class='text-muted small'>
                                        <?php
                                            $nota9= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$idgrup' and parcial='16' and estado='1'");
                                            $nota9->execute();
                                            while ($row15 = $nota9->fetch(PDO::FETCH_ASSOC)) {
                                              $ndefenza=$row15['reco']; 
                                            } 
                                            if ($ndefenza=='0' || $ndefenza=='') { 
                                                $nota179= $bdcon->prepare("SELECT reco from plat_doc_intentos_est where codest='$codest' and codgrupo='$subgrupo' and parcial='16' and estado='1'");
                                                $nota179->execute();
                                                while ($row135 = $nota179->fetch(PDO::FETCH_ASSOC)) {
                                                  $ndefenza=$row135['reco']; 
                                                } 
                                                if ($ndefenza=='0' || $ndefenza=='') {
                                                    echo "0.00";
                                                    $ndefenza=0;
                                                }else{
                                                  echo number_format($ndefenza,2);  
                                                }
                                            }else{
                                              echo number_format($ndefenza,2);  
                                            }                                
                                          ?>
                                        </td>
                                    <?php
                                  }
                              ?>                                         
                    <?php            
                        $einstancia=0;
                        /*for ($j=5; $j <=6 ; $j++) {
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
                        }*/                       
                        $nf=array($pp,$sp,$ef,$cp,$einstancia,$m2,$m4,$m5,$ainver,$aapren,$intsocial,$nprocesual,$ndefenza,$notapfinal,$prominv);
                        $nff=array_sum($nf);
                        $nfff=number_format($nff,2);
                        if ($einstancia==0) { 
                            if ($nfff>100) {
                            ?> 
                                <td class='text-muted small'><h6 style="color:red;">Nota en Verificación...</h6></td>
                            <?php 
                            }else{
                            ?> 
                                <td class='text-muted small'><?php  echo $nfff;?></td>
                            <?php 
                            }                                       
                        }else{
                            if ($einstancia>=51) {
                              ?>
                                  <td class='text-muted small'>51</td>
                              <?php
                            }else{
                              if ($einstancia<51) {
                                ?>
                                   <td class='text-muted small'><?php  echo $einstancia;?></td>
                                <?php
                                }else{
                                    if ($nfff>100) {
                                    ?> 
                                        <td class='text-muted small'><h6 style="color:red;">Nota en Verificación</h6></td>
                                    <?php 
                                    }else{
                                    ?> 
                                        <td class='text-muted small'><?php  echo $nfff;?></td>
                                    <?php 
                                    } 
                                }                            
                            }
                        }
                      ?>
                    </tr>    
                  </tbody>
                </table>
                <!-- <div class="uk-alert-success"><font color="green" size=1>*Para ver el detalle de tu examen, puedes hacer clic en la nota correspondiente al parcial.</font></div> -->
              </div>
          </div> 
      </div>     
  </div> 
  <div id="modal-detalle-eval" uk-modal>
      <div class="uk-modal-dialog uk-modal-body"> 
          <div id="mod_evaluacion">                                
          </div> 
      </div>
  </div>