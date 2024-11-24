<?php 
$codest=$_REQUEST['_codest'];
$gestion=$_REQUEST['_gestion'];
$totin=0;
$toteg=0;
include "../includes/conexion.php";
$q_idges= $bdcon->prepare("SELECT id from gestion where opcion='$gestion'");
$q_idges->execute();
while ($fges = $q_idges->fetch(PDO::FETCH_ASSOC)) {                  
    $idgestion=$fges['id'];
}
?>                
        <table class="table table-responsive">
            <thead>
                <small>
                    <tr>
                        <th><strong>FECHA</strong></th>
                        <th><strong>NUMERO</strong></th>
                        <th><strong>CONCEPTO</strong></th>
                        <th><strong>HORA</strong></th>
                        <th><strong>IMPORTE Bs.</strong></th>
                    </tr>
                </small>
            </thead>
            <tbody>
            <?php            
                    $cantada=0;               
                    $contal= $bdcon->prepare("SELECT periodo FROM (SELECT periodo from ca_diario where codest='$codest' and gestion='$gestion' UNION ALL SELECT periodo from ca_diario_banco where codest='$codest' and gestion='$gestion') T1 group by periodo");
                    $contal->execute(); 
                    $num_carreras=$contal->rowCount();  

                        if ($num_carreras==0) {
                                echo "<div class='alert alert-warning'><strong>¡Atención! </strong>No tiene registros contables en este año</div>";
                                    exit();
                            }           
                    $cons= $bdcon->prepare("SELECT * FROM (SELECT * from ca_diario where codest='$codest' and gestion='$gestion' UNION ALL SELECT * from ca_diario_banco where codest='$codest' and gestion='$gestion') T2 order by fecha asc");
                    $cons->execute(); 
                    $periodo=0;
                    while ($fcons = $cons->fetch(PDO::FETCH_ASSOC)) {                  
                        $per=$fcons['periodo'];
                        $semes=$fcons['semestre'];
                        if ($per<>'01' || $per<>'02') {
                            $verificar=substr($semes, 4);
                            $per=$verificar;
                        }
                        ?>
                  
                        <?php
                    if($per>$periodo) {
                        $cantada=$cantada+1;
                        ?>  
                                 
                        <tr>
                            <td height="22" colspan="7"><div class="periodo"><?php
                            $totin=0;
                            $toteg=0;                  
                            $cperio= $bdcon->prepare("SELECT * from periodo where id LIKE '%$per' and relacion='$idgestion'");
                            $cperio->execute();
                            while ($fcperio = $cperio->fetch(PDO::FETCH_ASSOC)) {                   
                                $nbperio=$fcperio['opcion'];
                            }echo @$nbperio;?></div></td>
                        </tr>
                            <?php                         
                             $periodo=$fcons["periodo"];
                        }
                        $fecha=$fcons['fecha'];
                        $numero=$fcons['numero'];
                        $monto=$fcons['bolivianos'];
                        $concp=$fcons['concepto'];
                        $tcomp=$fcons['tipoComprobante'];
                        $cuenta=$fcons['cuenta'];
                        $hora=$fcons['hora'];
                        $user=$fcons['usuario'];
                        $sucursal=$fcons['sucursal'];
                        $hora=substr($hora,0,5);
                        $period=$fcons['periodo'];                       
                        $num_fac=$fcons['numfactura'];                                             
                        if($tcomp=='INGRESOS'){
                            $totin=$totin+$monto;
                        }else{
                            $toteg=$toteg+$monto;
                        }
                        if($tcomp=='INGRESOS'){
                            $valTipo="-I".$gestion;
                        }else{
                            $valTipo="-E".$gestion;
                        }
                        ?>                 
                  <tr>
                    <td style="color: #7F877D; "><small><i class="icon-material-outline-date-range"></i><?php echo $fecha;?></small></td>
                    <td><div align="center"><small><?php echo $numero.$valTipo;?></small></div></td>
                    <td style="color: #7F877D;"><small>
                        <?php 
                            echo $concp;                           
                        ?></small>
                    </td>
                    <td style="color: green;"><strong><div align="center"><small><i class="icon-feather-clock"></i><?php echo $hora;?></small></div></strong></td>
                    <td><div align="center"><?php echo number_format($monto,2);?></div></td>
                  </tr>
                  
               
              <?php
            }
                    ?>
                    </tbody>
                    </table>
