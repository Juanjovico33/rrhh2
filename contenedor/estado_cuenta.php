<?php 
$codest=$_REQUEST['_codest'];
$semestre=$_REQUEST['_semestre'];
$carrera=$_REQUEST['_carrera'];
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
$periodo=$nbges."02";
$car="";

$pagocuota=4;
$pagocuotats=4;
$pagocuotapq=4;

$tienequepagar=0; $pagadocole=0; $n_cuotas=0;
include "../includes/conexion.php";  
include "../includes/_estudiante.php";
$estudiante=new estudiante($codest);
$estudiante->getdatosest(2022);

    $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest' and monto>0");
    $q_plan->execute(); 
    $q_plancontado=$bdcon->prepare("SELECT codest from all_stars where codest='$codest' and estado=1");
    $q_plancontado->execute();  
    $query_gru= $bdcon->prepare("SELECT grupo FROM aca_registroestmat WHERE codest='$codest' and periodo='$periodo'");
    $query_gru->execute();
    //echo "SELECT grupo FROM aca_registroestmat WHERE codest='$codest' and periodo='$periodo'"."<br>";exit;
    $nbgrupo="";
    while ($row25 = $query_gru->fetch(PDO::FETCH_ASSOC)) {                               
        $nbgrupo=$row25['grupo']; 
    }

    $query_cod= $bdcon->prepare("SELECT carrera, tipo_pre FROM estudiante_codigo where codigo_estudiante='$codest' order by codigo_estudiante");
    $query_cod->execute();
    while ($fde = $query_cod->fetch(PDO::FETCH_ASSOC)) {       
       $car=$fde['carrera'];      
       $tp=$fde['tipo_pre'];                        
    } 

    if ($nbgrupo=="TS") {
        $periodo=$nbges."02"; 
        $nbperiodo="II/2022";
        $periodoniv=$nbges."08";
    }else{
        $periodo=$nbges."02"; 
        $nbperiodo="II/2022";
        $periodoniv=$nbges."08";
    }         
?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h4 style="color: #496245;">ESTADO DE CUENTA</h4>
                        <?php
                            
                                $q_monto= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc");
                                $q_monto->execute(); 

                                $q_montoniv= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodoniv' and tipodeuda<> '75' order by cod_reg Desc");
                                $q_montoniv->execute();   
    
                               /* if(($car=="01MED" || $car=="03MED") && $semestre=='10'){
                                    $pagocuota=4;?>
                                    <div class="alert alert-warning"><strong>¡Atención! </strong><br>Para habilitar el cuarto parcial, deberás pagar hasta la cuarta cuota, de acuerdo a la nueva planificación de pagos.<br>Si te muestra numeros en rojo, es por que no estas habilitado para el parcial.</div> 
                                <?php }else if(($nbgrupo=="TS" && $semestre=='3') || ($car=="03ODT" && ($semestre=="7" || $semestre=="9"))){
                                    $pagocuotats=4;?>
                                    <div class="alert alert-warning"><strong>¡Atención! </strong><br>Para habilitar el cuarto parcial, deberás pagar hasta la cuarta cuota, de acuerdo a la nueva planificación de pagos.<br>Si te muestra numeros en rojo, es por que no estas habilitado para el parcial.</div> 
                                <?php }else*/
                                if(($car=="01MED" || $car=="03MED") && $semestre=='10'){
                                    $pagocuota=4;?>
                                    <div class="alert alert-warning"><strong>¡Atención! </strong><br>Para habilitar al cuarto parcial, deberás pagar hasta la cuarta cuota, de acuerdo a la nueva planificación de pagos.<br>Si te muestra numeros en rojo, es por que no estas habilitado para el parcial.</div> 
                                <?php }else{
                                    //$pagocuota=?;?>
                                    <div class="alert alert-warning"><strong>¡Atención! </strong><br>Para habilitar al cuarto parcial, deberás pagar hasta la cuarta cuota, de acuerdo a la nueva planificación de pagos.<br>Si te muestra numeros en rojo, es por que no estas habilitado para el parcial.</div> 
                                <?php }
                            
                        ?> 
                    </div>                   
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-sm table-hover">
                                <thead style="color:white; background-color: #496245;">
                                    <tr align="center" >                                       
                                       <th scope="col">Fecha Limite</th>
                                       <th scope="col">N° de Cuota</th>                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($nbgrupo=='TS' && $semestre=='3') {
                                         $q_fechaspag= $bdcon->prepare("SELECT fec_limit,parcial,cuota from con_fechas_pagos_ts where periodo='$periodo'");
                                        $q_fechaspag->execute();
                                    }else{
                                         $q_fechaspag= $bdcon->prepare("SELECT fec_limit,parcial,cuota from con_fechas_pagos where periodo='$periodo'");
                                        $q_fechaspag->execute();
                                    }                                

                                    while ($rowpag = $q_fechaspag->fetch(PDO::FETCH_ASSOC)) { 
                                    ?>
                                        <tr align="center">
                                            <td><?php echo $fecha_limite=$rowpag['fec_limit'];?></td>
                                            <td><?php echo $n_cuotas=$rowpag['cuota'];?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div style="width:auto;">

                                <div style="border-style:groove;">
                                    Para deposito o transferencia bancaria cuenta oficial
                                    <table  class="table table-sm table-hover">
                                        <thead style="color:white; background-color: #496245;">
                                            <tr align="center"><th colspan=2 scope="col">BANCO FASSIL<th></tr>
                                        </thead> 
                                        <tbody>
                                            <tr>
                                                <td style="width:100px;">CTA CTE #</td><td>975510</td>
                                            </tr>
                                            <tr>
                                                <td style="width:100px;">TITULAR</td><td>DAVID ERNESTO JUSTINIANO ATALA</td>
                                            </tr>
                                            <tr>
                                                <td style="width:100px;">C.I.</td><td>1548076 SC</td>
                                            </tr>
                                            <tr>
                                                <td style="width:100px;"></td>
                                                <td style="align-items:center;">Enviar el voucher al número </td>
                                            </tr>
                                            <tr>
                                                <td style="width:100px;font-size:12px;">Has clic aqui-></td>
                                                    <td style="align-items:center;">
                                                        <a href="https://wa.link/wzux3v" target="_blank">
                                                            <p style="color:green;"><img src="img/iconos/WhatsApp_icon.png" width="32px" height="32px">77303627</p>
                                                        </a>
                                                    </td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8">                           
                            <!--*****************************imprimimos periodo normal **************-->  
                            <!--****************vamos imprimir mensaje de ok si tiene mas de una cuota**********-->
                            <?php                           
                            if($q_plan->rowCount()>0 || $q_plancontado->rowCount()>0){
                            ?> 
                                <div class="alert alert-success"><strong>¡Plan al Contado! </strong> habilitado para todos los parciales</div>
                            <?php
                            }else{
                                if($q_monto->rowCount()>0){ 
                                    ?>
                                    <table class="table table-sm table-hover">
                                        <thead style="color:white; background-color: #496245;">
                                            <tr align="center">
                                                <th colspan="5">PERIODO NORMAL - PAGOS REALIZADOS</th>
                                            </tr>
                                            <tr align="center">  
                                               <th scope="col">Detalle</th>
                                               <th scope="col">Costo</th> 
                                               <th scope="col">A Cuenta</th>  
                                               <th scope="col">Saldo</th> 
                                               <th scope="col">Observacion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php                                       
                                            $colegiatura=0;
                                            $beca=0;
                                            $seguro=0;
                                            $descuento=0;
                                            $tienetallerdegrado=0;
                                            while ($rowmonto = $q_monto->fetch(PDO::FETCH_ASSOC)){  
                                                $montos=$rowmonto['monto'];
                                                $tipodeuda=$rowmonto['tipodeuda'];                                              
                                                if ($carrera=='BIOQUIMICA Y FARMACIA' || $carrera=='LIC. EN ENFERMERIA') {
                                                    if ($tipodeuda==146) {
                                                        # taller de grado
                                                        $tienetallerdegrado=1;
                                                    } 
                                                }
                                                if ($tipodeuda==1) {
                                                    # colegiatura
                                                    $colegiatura=$montos;
                                                } 
                                                if ($tipodeuda==77) {
                                                    # seguro
                                                    $q_seguro= $bdcon->prepare("SELECT SUM(t1.bolivianos) bolivianos FROM (SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='77' UNION ALL SELECT bolivianos FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='77') t1");
                                                    $q_seguro->execute();  
                                                    $pagado_s=0; 
                                                    while ($rowseguro = $q_seguro->fetch(PDO::FETCH_ASSOC)){  
                                                        $pagado=$rowseguro['bolivianos'];
                                                        $pagado_s=$pagado_s+$pagado;
                                                    }
                                                    $seguro=$montos;
                                                    ?>    
                                                    <tr align="center">
                                                        <td>Seguro</td>
                                                        <td><?php echo $seguro;?></td>
                                                        <td><?php echo $pagado_s;?></td>
                                                        <td><?php 
                                                            $saldo=$seguro-$pagado_s;
                                                            if ($saldo==0) {
                                                                echo "<p style='color:green'>".$saldo."</p>";
                                                            }else{
                                                                echo "<p style='color:red'>".$saldo."</p>";
                                                            }
                                                                ?>
                                                        </td>
                                                        <td><?php 
                                                            if ($saldo==0) {
                                                            ?>
                                                            <img src="img/iconos/bien2.png">
                                                            <?php
                                                            }else{
                                                            ?>
                                                            <div align="center">
                                                            
                                                                <button class="btn btn-success" type="button" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp('<?php echo $codest;?>','<?php echo $semestre;?>','<?php echo $carrera;?>','<?=77?>', '<?=$saldo?>', '<?=$tipodeuda?>', '<?=$periodo?>')">PAGAR</button>

                                                                <button class="btn btn-info" type="button" uk-toggle="target: #modal-example2" onclick="subir_recibo('<?=$codest?>','<?=$periodo?>','<?=$tipodeuda?>','<?=$n_cuotas?>')" >Recibo</button>
                                                            </div>
                                                            <?php
                                                            }
                                                            ?>    
                                                        </td>
                                                    </tr>
                                                        <?php
                                                }                                             
                                                if ($tipodeuda==656) {
                                                     # descuento
                                                    $descuento=$montos;
                                                    ?>    
                                                        <tr align="center">
                                                            <td>Descuento Especial</td>
                                                            <td><?php echo $descuento;?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php
                                                }   
                                                if ($tipodeuda>=20 and $tipodeuda<=34) {
                                                     # code...
                                                     $beca=$montos;
                                                    ?>    
                                                        <tr align="center">
                                                            <td>Beca</td>
                                                            <td><?php echo $beca;?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php
                                                }                                          
                                            }
                                                if ($colegiatura==0) { 
                                                	 ?>
		                                                <div class="alert alert-danger"><div align="center"><strong>¡ No tiene asignado deuda para este semestre!</strong></div></div>
		                                            <?php               
                                                }else{

                                                    $colegiaturapagar=$colegiatura - $descuento - $beca;
                                                    $beca=$beca/3;
                                                    $pagar=$colegiaturapagar/4;
                                                    $cuo_mos=($colegiatura-$descuento)/4;
                                                    if ($nbgrupo=="TS" && $semestre=='3') {
                                                         $tienequepagar=$pagar*$pagocuotats;
                                                    }else{
                                                         $tienequepagar=$pagar*$pagocuota;   
                                                    }
                                                    // else if($nbgrupo=="P" || $nbgrupo=="Q"){
                                                    //     $tienequepagar=$pagar*$pagocuotapq;   
                                                    // }                                   
                                                    $deudapapu=0;
                                                    $saldo_ant=0;
                                                    for ($i=1; $i <= 4; $i++) { 
                                                        $q_cuotas= $bdcon->prepare("SELECT SUM(t2.bolivianos) bolivianos FROM (SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38' and nro_cuota='$i' UNION ALL SELECT bolivianos FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='38' and nro_cuota='$i') t2");
                                                        $q_cuotas->execute();  
                                                        $pagado_c=0; 
                                                        $pagadocole=0;
                                                        while ($rowcuotas = $q_cuotas->fetch(PDO::FETCH_ASSOC)){  
                                                            $pagadocuota=$rowcuotas['bolivianos'];
                                                            $pagado_c=$pagado_c+$pagadocuota;
                                                        }
                                                        
                                                        ?>    
                                                        <tr align="center">
                                                            <td>CUOTAS <?php echo $i;?></td>
                                                            <td>
                                                                <?php 
                                                                if ($beca>0) {
                                                                    if ($i>='2') {
                                                                        $desc_beca=$beca;
                                                                        echo number_format(($cuo_mos-$desc_beca),2);
                                                                    }else{
                                                                        $desc_beca=0;
                                                                        echo number_format($cuo_mos,2);
                                                                    }
                                                                }else{
                                                                    $desc_beca=0;
                                                                    echo number_format($cuo_mos,2);
                                                                }
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                if ($saldo_ant>0) {
                                                                    $pagado_c=$pagado_c+$saldo_ant;
                                                                }
                                                                echo number_format($pagado_c,2);
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                            <?php $_saldo=$pagado_cuotas=(($cuo_mos-$desc_beca)-$pagado_c);
                                                                if ($_saldo<0) {
                                                                    //saldo negativo a favor de la siguiente cuota
                                                                    $saldo_ant=$_saldo*(-1);
                                                                }else{
                                                                    $saldo_ant=0;
                                                                }
                                                                if ($pagado_cuotas<=0){
                                                                    echo "<p style='color:green'>".number_format( $pagado_cuotas,2)."</p>";
                                                                    $q_colepagado= $bdcon->prepare("SELECT SUM(t3.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='38') t3");
                                                                    $q_colepagado->execute();
                                                                    while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                                                                        $pagadocole=$rcole['pagado'];
                                                                    }     
                                                                }else{
                                                                    $q_colepagado= $bdcon->prepare("SELECT SUM(t4.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='38') t4");
                                                                    $q_colepagado->execute();
                                                                    while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                                                                        $pagadocole=$rcole['pagado'];
                                                                    }                                                 
                                                                        if ($pagadocole>=$tienequepagar) {
                                                                            echo "<p style='color:green'>".number_format( $pagado_cuotas,2)."</p>";
                                                                        }else{
                                                                            echo "<p style='color:red'>".number_format( $pagado_cuotas,2)."</p>"; 
                                                                            $deudapapu=$deudapapu+$pagado_cuotas;
                                                                        }                
                                                                }
                                                            ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($pagado_cuotas<1) {               
                                                                    ?>
                                                                        <img src="img/iconos/bien2.png">
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <div align="center">

                                                                        <button class="btn btn-success" type="button" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp('<?php echo $codest;?>','<?php echo $semestre;?>','<?php echo $carrera;?>','<?php echo $i;?>', '<?=$_saldo?>', '<?=38?>', '<?=$periodo?>')">PAGAR</button>

                                                                        <button class="btn btn-info" type="button" uk-toggle="target: #modal-example2" onclick="subir_recibo('<?=$codest?>','<?=$periodo?>','<?=$tipodeuda?>','<?=$i?>')" >Recibo</button>
                                                                    </div>
                                                                    <?php
                                                                } 
                                                                ?>
                                                            </td>
                                                        </tr> 
                                                        <?php
                                                        $cuo_mos=($colegiatura-$descuento)/4;
                                                    }                                                     
                                                }                                           
                                            $deudataller=0; 
                                            $pagadotaller=0;  
                                            if ($tienetallerdegrado==1) { 
                                                $deudataller=3300;                                             
                                            ?> 
                                                <tr>                                                            
                                                    <td align="center">TALLER DE GRADO</td>
                                                    <td align="center">3300</td>
                                                    <td align="center">
                                                    <?php
                                                        $q_taller= $bdcon->prepare("SELECT SUM(t5.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='146' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodo' and cuenta='146') t5");
                                                        $q_taller->execute();
                                                        while ($rtaller= $q_taller->fetch(PDO::FETCH_ASSOC)) {
                                                                echo $pagadotaller=$rtaller['pagado'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($pagocuota==4) {
                                                            $pagotaller=$deudataller - $pagadotaller; 
                                                            if ($pagotaller<=0) {
                                                                  echo "<p style='color:green'>0</p>";                  
                                                            }else{
                                                                echo "<p style='color:red'>".number_format( $pagotaller,2)."</p>";
                                                            } 
                                                        }         
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($pagocuota==4) {
                                                            if ($pagotaller<=0) {
                                                            ?>
                                                                <div class="alert alert-success"><div align="center"><strong>¡ HABILITAD@ ! </strong></div></div>
                                                            <?php                                                          
                                                            }else{
                                                            ?>
                                                                <div class="alert alert-danger"><div align="center"><strong>¡NO HABILITAD@!</strong></div></div>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            } 
                                            ?>
                                            <tr>
                                            <td colspan="4" align="right"><strong>TOTAL DEUDA A PAGAR</strong></td>
                                            <td>
                                                <?php    
                                                if ($pagocuota==4) {
                                                    $sumapagar=$tienequepagar+$deudataller;
                                                    $sumapagado=$pagadocole + $pagadotaller;
                                                    $totaldeuda= $sumapagar - $sumapagado   ;           
                                                }else{
                                                     $totaldeuda= $tienequepagar - $pagadocole;
                                                }                                           
                                               
                                                if ($totaldeuda>0) {
                                                     echo "<p style='color:red'><strong>".number_format($totaldeuda,2)."</strong></p>";
                                                     $habil=0;
                                                }else{
                                                    if ($totaldeuda<=0) {
                                                        echo "<p style='color:green'><strong>0.00</strong></p>";
                                                        $habil=1;
                                                    }else{ 
                                                        echo "<p style='color:green'><strong>".
                                                        number_format($totaldeuda,2)."</strong></p>";
                                                        $habil=1;
                                                    }
                                                }  
                                                ?>
                                            
                                            </td>
                                        </tr>  
                                    </tbody>
                                </table>
                                    <?php
                                        if ($habil==0) {
                                            ?>
                                            <div class="alert alert-danger"><div align="center"><strong>¡ NO HABILITAD@!</strong></div></div>
                                            <?php
                                        }else{
                                            ?>
                                            <div class="alert alert-success"><div align="center"><strong>¡ HABILITAD@ ! </strong></div></div>
                                            <?php
                                        }
                                                                               
                                    ?>  
                                <?php
                                }
                            }  
                            ?> 
                            <!--******************************hasta aqui el periodo normal**********************-->

                            <!-- INICIO REPROS -->
                            <?php
                                $q_list_repros="SELECT rep.gestion, rep.periodo, rep.materia, m.Descripcion, rep.grupo, rep.parcial, SUM(rep.costo) costo FROM sainc.aca_registroestrepro rep LEFT JOIN sainc.materias m ON m.Sigla=rep.materia and m.CodCarrera='$car' where rep.codest=$codest and (rep.periodo=$periodo or rep.periodo=$periodoniv or rep.periodo=202204  or rep.periodo=202203) GROUP BY rep.gestion, rep.periodo, rep.materia, m.Descripcion, rep.grupo, rep.parcial";
                               $q_repros = $bdcon->prepare($q_list_repros);
                               // "SELECT rep.cod_repro, rep.gestion, rep.periodo, rep.materia, m.Descripcion, rep.grupo, rep.parcial, SUM(rep.costo) costo FROM sainc.aca_registroestrepro rep LEFT JOIN sainc.materias m ON m.Sigla=rep.materia and m.CodCarrera='$car' where (rep.codest=$codest and rep.periodo=$periodo and rep.costo<>0) GROUP BY rep.periodo, rep.materia, m.Descripcion, rep.grupo, rep.parcial"
                               $q_repros->execute();
                            ?>

                            <table class="table table-sm table-hover">
                                <thead style="color:white; background-color: #496245;">
                                    <tr align="center">
                                        <th colspan="5">Reprogramaciones</th>
                                    </tr>
                                    <tr align="center">  
                                        <th scope="col">Nro</th>
                                        <th scope="col">Materia</th> 
                                        <th scope="col">Parcial</th>  
                                        <th scope="col">Monto</th> 
                                        <th scope="col">Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $r_nro=0;
                                        while ($rep = $q_repros->fetch(PDO::FETCH_ASSOC)) {
                                            $r_nro++;
                                            $r_periodo = $rep['periodo'];
                                            $r_smateria = $rep['materia'];
                                            $r_materia = $rep['Descripcion'];
                                            $r_grupo = $rep['grupo'];
                                            $r_parcial = $rep['parcial'];
                                            $r_costo = $rep['costo'];

                                            $q_pagorepro = $bdcon->prepare("SELECT SUM(t10.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario where codest=$codest and semestre=$r_periodo and cuenta=75 and codmateria='$r_smateria' and nro_cuota=$r_parcial UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco where codest=$codest and semestre=$r_periodo and cuenta=75 and codmateria='$r_smateria' and nro_cuota=$r_parcial) t10");
                                            $q_pagorepro->execute();
                                            $pagos_rep = $q_pagorepro->fetch(PDO::FETCH_ASSOC);
                                            $pagado_rep = $pagos_rep['pagado'];

                                            $repro_apagar=0;
                                            if($pagado_rep>=$r_costo || $r_costo==0){
                                                $btn_pagar="<img src='img/iconos/bien2.png'>";
                                            }else{
                                                $repro_apagar=$r_costo - $pagado_rep;
                                                $btn_pagar='<button class="btn btn-success" type="button" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp_repro('.$codest.', '.$semestre.', \''.$carrera.'\' , '.$r_parcial.', '.$repro_apagar.', 75, \''.$r_smateria.'\', \''.$r_materia.'\', '.$r_periodo.')">PAGAR</button>';
                                            }
                                            echo "<tr align='center'> <td>$r_nro</td> <td>$r_materia</td> <td>$r_parcial º</td> <td>$r_costo </td> <td>$btn_pagar</td> </tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <!-- FIN REPROS -->

                            <?php
                            if($q_montoniv->rowCount()>0){                               
                            ?>
                            <!--******************************imprimimos periodo nivelacion********************************-->
                            <table class="table table-sm">
                                <thead style="color:white; background-color: #496245;">
                                    <tr align="center">
                                        <th colspan="5">PERIODO NIVELACION - PAGOS REALIZADOS</th>
                                    </tr>
                                    <tr align="center">                                                          
                                       <th scope="col">Detalle</th>
                                       <th scope="col">Costo</th> 
                                       <th scope="col">A Cuenta</th>  
                                       <th scope="col">Saldo</th>                                        
                                       <th scope="col">Observacion</th>                             
                                    </tr>
                                </thead>  
                                <tbody>
                                    <?php                                       
                                        $colegiaturaniv=0;
                                        while ($rowmontoniv = $q_montoniv->fetch(PDO::FETCH_ASSOC)){
                                            $montosniv=$rowmontoniv['monto'];
                                            $tipodeudaniv=$rowmontoniv['tipodeuda'];
                                            $colegiaturaniv=$colegiaturaniv+$montosniv;
                                        }  
                                        // echo $colegiaturaniv."<br>";

                                         $q_colepagados= $bdcon->prepare("SELECT SUM(t8.pagado) pagado FROM (SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213' UNION ALL SELECT sum(bolivianos) as pagado FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213') t8");
                                         $q_colepagados->execute();
                                         $rcole2 = $q_colepagados->fetch(PDO::FETCH_ASSOC);
                                         $pagadocoles = $rcole2['pagado'];
                                         $pagoniv_todo=false;
                                         if($pagadocoles>=$colegiaturaniv){
                                            $pagoniv_todo=true;
                                         }else{
                                            $pagoniv_todo=false;
                                         }

                                        $cuotasniv=$colegiaturaniv/4;  // Monto de cuota a pagar => colegiaturaniv=pagarniv
                                        $pagarniv=$cuotasniv*$pagocuota; 
                                        $deudapapuniv=0;
                                        for ($j=1; $j <= 4; $j++) { 
                                            $q_cuotasniv= $bdcon->prepare("SELECT SUM(t6.bolivianos) bolivianos FROM (SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213' and nro_cuota='$j' UNION ALL SELECT bolivianos FROM ca_diario_banco WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213' and nro_cuota='$j') t6");
                                            $q_cuotasniv->execute();  
                                            $pagado_cniv=0; 
                                            while ($rowcuotas = $q_cuotasniv->fetch(PDO::FETCH_ASSOC)){  
                                                $pagadocuotaniv=$rowcuotas['bolivianos']; // Monto total pagado => pagadocutoaniv=pagado_cniv
                                                $pagado_cniv=$pagado_cniv+$pagadocuotaniv; // ?
                                            }
                                        ?>    
                                            <tr align="center">
                                                <td>CUOTAS <?php echo $j;?></td>
                                                <td><?php echo $cuotasniv;?></td> <!-- A PAGAR SOLO CUOTA -->
                                                <td><?php echo $pagado_cniv;?></td> <!-- TOTAL PAGADO -->
                                                <td>
                                                <?php $pagado_cniv=$cuotasniv - $pagado_cniv;
                                                    if ($pagado_cniv==0 || $pagado_cniv<0) {
                                                        if($pagado_cniv==0){
                                                            echo "<p style='color:green'>".$pagado_cniv."</p>";
                                                        }else{
                                                            echo "<p style='color:green'>".($pagado_cniv*-1)."</p>";
                                                        }
                                                    }else{
                                                        if($pagoniv_todo){
                                                            echo "<p style='color:green'>".($pagado_cniv*-1)."</p>";
                                                        }else{
                                                            echo "<p style='color:red'>".($pagado_cniv*-1)."</p>";
                                                        }
                                                    }
                                                ?>
                                                </td>
                                                <td>    
                                                    <?php 
                                                        if ($pagadocuotaniv>=$cuotasniv) {               
                                                            echo '<img src="img/iconos/bien2.png">';
                                                        }else{
                                                            if($pagoniv_todo){
                                                                echo '<img src="img/iconos/bien2.png">';
                                                            }else{
                                                            ?>
                                                            <div align="center">
                                                                <button class="btn btn-success" type="button" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp('<?=$codest?>','<?=$semestre?>','<?=$carrera?>','<?=$j?>', '<?=$pagado_cniv?>', '<?=213?>', '<?=$periodoniv?>')">PAGAR</button>
                                                            </div>
                                                            <?php
                                                            }
                                                        } 
                                                        // if ($j==$pagocuota) {
                                                            //echo "vigente";
                                                        // }
                                                        // echo $cuotasniv.">=".$pagadocuotaniv;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }                                    
                                    ?>

                                    <tr>
                                        <td colspan=5>
                                            <?php
                                                // consultando ...
                                                // echo $pagadocoles."-".$pagarniv;

                                                if ($pagadocoles>=$pagarniv) {
                                                    echo "<div class='alert alert-success' align='center'><strong>¡Habilitad@! </strong><br></div>";
                                                }else{
                                                        echo "<div class='alert alert-danger' align='center'><strong>¡NO estas Habilitad@!</strong><br></div>";    
                                                }           
                                               
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"><strong>TOTAL DEUDA A PAGAR</strong></td>
                                        <td>
                                        <?php 
                                       
                                        if ($deudapapuniv>0) {
                                             echo "<p style='color:red'><strong>".number_format($deudapapuniv,2)."</strong></p>";
                                        }else{
                                            if ($deudapapuniv<0) {
                                                 echo "<p style='color:green'><strong>0.00</strong></p>";
                                            }else{
                                                echo "<p style='color:green'><strong>".number_format($deudapapuniv,2)."</strong></p>";
                                            }
                                        }                                                     
                                        ?>
                                        </td>
                                    </tr> 
                                </tbody>    
                            </table>
                            <?php
                            }
                            ?> 
                            <!--******************************hasta aqui periodo nivelacion********************************-->
                        </div> 
                    </div> 
                   
                </div>                   
            </div>  
        </div> 
    </div>
    <div id="modal-example2" uk-modal>
        <div class="uk-modal-dialog uk-modal-body"> 
            <div id="mos_qr">                                
            </div> 
        </div>
    </div>
