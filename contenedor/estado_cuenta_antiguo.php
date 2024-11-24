<?php 
$codest=$_REQUEST['_codest'];
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
if ($nbmes<=8) {
    $periodo=$nbges."01";
    $periodoniv=$nbges."06";
}else{
    $periodo=$nbges."02";
    $periodoniv=$nbges."08";
}
include "../includes/conexion.php";   
    $q_monto= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc");
    $q_monto->execute(); 

    $q_montoniv= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodoniv' and tipodeuda<> '75' order by cod_reg Desc");
    $q_montoniv->execute();

    $q_fechaspag= $bdcon->prepare("SELECT fec_limit,parcial,cuota from con_fechas_pagos where periodo='$periodo'");
    $q_fechaspag->execute(); 

    $q_plan= $bdcon->prepare("SELECT anios FROM con_planespagados WHERE codest='$codest'");
    $q_plan->execute(); 

    $q_plancontado=$bdcon->prepare("SELECT codest from all_stars where codest='$codest'");
    $q_plancontado->execute();                
?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h4 style="color: #496245;">ESTADO DE CUENTA</h4>
                        <!--<div class="alert alert-warning"><strong>¡Atención! </strong><br>Para habilitar el cuarto parcial, deberás pagar hasta la cuarta cuota, de acuerdo a la nueva planificación de pagos.<br>Si te muestra numeros en rojo, es por que no estas habilitado para el parcial.</div>-->                        
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
                        </div>
                        <div class="col-md-8">                           
                            <!--*****************************imprimimos periodo normal **************-->  
                            <!--****************vamos imprimir mensaje de ok si tiene mas de una cuota**********-->
                            <?php                           
                            if($q_plan->rowCount()>0 || $q_plancontado->rowCount()>0){
                            ?> 
                                <div class="alert alert-success"><strong>¡Plan al Contado! </strong> habilitado para el 1° parcial</div>
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
                                                    while ($rowmonto = $q_monto->fetch(PDO::FETCH_ASSOC)){  
                                                        $montos=$rowmonto['monto'];
                                                        $tipodeuda=$rowmonto['tipodeuda'];
                                                            if ($tipodeuda==1) {
                                                                # colegiatura
                                                                $colegiatura=$montos;
                                                            } 
                                                            if ($tipodeuda==77) {
                                                                # seguro
                                                                $q_seguro= $bdcon->prepare("SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='77'");
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
                                                                        <td>
                                                                        <?php $saldo=$seguro-$pagado_s;
                                                                            if ($saldo==0) {
                                                                                echo "<p style='color:green'>".$saldo."</p>";
                                                                            }else{
                                                                                echo "<p style='color:red'>".$saldo."</p>";
                                                                            }
                                                                        ?>
                                                                        </td>
                                                                        <td>
                                                                             <?php 
                                                                            if ($saldo==0) {
                                                                                ?>
                                                                                <img src="img/iconos/bien2.png">
                                                                                <?php
                                                                            }else{
                                                                            ?>
                                                                               <div align="center">
                                                                                    <button class="btn btn-success" type="button" uk-toggle="target: #modal-example" onclick="verificar_qr('<?php echo $codest;?>')">PAGAR</button>
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
                                                    }else{
                                                        $colegiaturapagar=$colegiatura - $descuento - $beca;
                                                        $pagar=$colegiaturapagar/4;
                                                        $tienequepagar=$pagar*2;
                                                        for ($i=1; $i <= 4; $i++) { 
                                                            $q_cuotas= $bdcon->prepare("SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38' and nro_cuota='$i'");
                                                            $q_cuotas->execute();  
                                                            $pagado_c=0; 
                                                            while ($rowcuotas = $q_cuotas->fetch(PDO::FETCH_ASSOC)){  
                                                                $pagadocuota=$rowcuotas['bolivianos'];
                                                                $pagado_c=$pagado_c+$pagadocuota;
                                                            }
                                                        ?>    
                                                            <tr align="center">
                                                                <td>CUOTAS <?php echo $i;?></td>
                                                                <td><?php echo $pagar;?></td>
                                                                <td><?php echo $pagado_c;?></td>
                                                                <td>
                                                                <?php $pagado_cuotas=$pagar-$pagado_c;
                                                                    if ($pagado_cuotas<=0){
                                                                        echo "<p style='color:green'>".$pagado_cuotas."</p>";
                                                                    }else{
                                                                         $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38'");
                                                                            $q_colepagado->execute();
                                                                            while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                                                                                    $pagadocole=$rcole['pagado'];
                                                                                }
                                                                        if ($i==2) { 
                                                                            if ($pagadocole>=$tienequepagar) {
                                                                                echo "<p style='color:green'>".$pagado_cuotas."</p>";
                                                                            }else{
                                                                                echo "<p style='color:red'>".$pagado_cuotas."</p>"; 
                                                                            }                     
                                                                        }else{ 
                                                                             if ($pagadocole>=$tienequepagar) {
                                                                                echo "<p style='color:green'>".$pagado_cuotas."</p>";
                                                                            }else{
                                                                                echo "<p style='color:red'>".$pagado_cuotas."</p>"; 
                                                                            } 
                                                                        }     
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        if ($i==2) {
                                                                            $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38'");
                                                                            $q_colepagado->execute();
                                                                            while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                                                                                    $pagadocole=$rcole['pagado'];
                                                                                }
                                                                            if ($pagadocole>=$tienequepagar) {
                                                                                echo "<div class='alert alert-success' align='center'><strong>¡Habilitad@! </strong><br></div>";
                                                                            }else{                           
                                                                                ?>
                                                                                  <div align="center">
                                                                                    <button class="btn btn-success" type="button" uk-toggle="target: #modal-example" onclick="verificar_qr('<?php echo $codest;?>')">PAGAR</button>
                                                                                </div>
                                                                                <?php
                                                                                  /*echo "<div class='alert alert-danger' align='center'><strong>¡NO estas Habilitad@!</strong><br></div>"; */   
                                                                            }           
                                                                      }else{
                                                                            ?>

                                                                              <div align="center">
                                                                                <button class="btn btn-success" type="button" uk-toggle="target: #modal-example" onclick="verificar_qr('<?php echo $codest;?>')">PAGAR</button>
                                                                            </div>
                                                                            <?php
                                                                      }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }                                                     
                                                    }                                                
                                                ?> 

                                                <tr>
                                                    <td colspan="4" align="right"><strong>TOTAL DEUDA A PAGAR</strong></td>
                                                    <td>
                                                    <?php 
                                                    $ultimacuota= $tienequepagar -$pagadocole;
                                                    if ($ultimacuota>0) {
                                                         echo "<p style='color:red'><strong>".number_format($ultimacuota,2)."</strong></p>";
                                                    }else{
                                                        if ($ultimacuota<0) {
                                                            echo "<p style='color:green'><strong>0.00</strong></p>";
                                                        }else{                                                            
                                                            echo "<p style='color:green'><strong>".number_format($ultimacuota,2)."</strong></p>";
                                                        }
                                                    }                                                     
                                                    ?>
                                                    </td>
                                                </tr>        
                                            </tbody>
                                    </table>
                                <?php
                                }
                            }  
                            ?> 
                            <!--******************************hasta aqui el periodo normal**********************-->
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
                                        $cuotasniv=$colegiaturaniv/4;
                                        $pagarniv=$cuotasniv*2;
                                        for ($j=1; $j <= 4; $j++) { 
                                            $q_cuotasniv= $bdcon->prepare("SELECT bolivianos FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213' and nro_cuota='$j'");
                                            $q_cuotasniv->execute();  
                                            $pagado_cniv=0; 
                                            while ($rowcuotas = $q_cuotasniv->fetch(PDO::FETCH_ASSOC)){  
                                                $pagadocuotaniv=$rowcuotas['bolivianos'];
                                                $pagado_cniv=$pagado_cniv+$pagadocuotaniv;
                                            }
                                        ?>    
                                            <tr align="center">
                                                <td>CUOTAS <?php echo $j;?></td>
                                                <td><?php echo $cuotasniv;?></td>
                                                <td><?php echo $pagado_cniv;?></td>
                                                <td>
                                                <?php $pagado_cniv=$cuotasniv - $pagado_cniv;
                                                    if ($pagado_cniv==0) {
                                                            echo "<p style='color:green'>".$pagado_cniv."</p>";
                                                    }else{
                                                        if ($j==2) {
                                                                $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213'");
                                                                $q_colepagado->execute();
                                                                while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
                                                                        $pagadocole=$rcole['pagado'];
                                                                    }
                                                                if ($pagadocole>=$pagarniv) {
                                                                    echo "<p style='color:green'>".$pagado_cniv."</p>";
                                                                }else{
                                                                    echo "<p style='color:red'>".$pagado_cniv."</p>"; 
                                                                }                     
                                                        }                                                           
                                                    }
                                                ?>
                                                </td>
                                                <td>    
                                                    <?php
                                                        if ($j==2) {  
                                                            $q_colepagados= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodoniv' and cuenta='213'");
                                                            $q_colepagados->execute();
                                                            while ($rcole= $q_colepagados->fetch(PDO::FETCH_ASSOC)) {
                                                                        $pagadocoles=$rcole['pagado'];
                                                            }                                                        
                                                            if ($pagadocoles>=$pagarniv) {
                                                                echo "<div class='alert alert-success' align='center'><strong>¡Habilitad@! </strong><br></div>";
                                                            }else{
                                                                  echo "<div class='alert alert-danger' align='center'><strong>¡NO estas Habilitad@!</strong><br></div>";    
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
                                        $ultimacuotaniv= $pagarniv-$pagadocoles;
                                        if ($ultimacuotaniv>0) {
                                             echo "<p style='color:red'><strong>".number_format($ultimacuotaniv,2)."</strong></p>";
                                        }else{
                                            if ($ultimacuotaniv<0) {
                                                 echo "<p style='color:green'><strong>0.00</strong></p>";
                                            }else{
                                             echo "<p style='color:green'><strong>".number_format($ultimacuotaniv,2)."</strong></p>";
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
                    <div id="modal-example" uk-modal>
                        <div class="uk-modal-dialog uk-modal-body">        
                            <div class="modal-body" id="mos_qr">
                                
                            </div>        
                        </div>
                    </div>
                </div>                   
            </div>  
        </div> 
    </div>
    
