<?php 
$codest=$_REQUEST['_codest'];
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$totin=0;
$toteg=0;
include "../includes/conexion.php";  
$query= $bdcon->prepare("SELECT codEstudiante,nombcompleto,carrera,codest FROM estudiante WHERE codest='$codest'");
$query->execute();   
    while ($festc = $query->fetch(PDO::FETCH_ASSOC)) {       
        $cod_ant=$festc['codEstudiante'];
        $nbestudiante=$festc['nombcompleto'];
        $carreraest=$festc['carrera'];
        $cod_new=$festc['codest'];                       
    } 
    $q_semestre= $bdcon->prepare("SELECT semestre FROM aca_estudiantesemestre WHERE codest='$codest' ORDER BY semestre");
    $q_semestre->execute(); 
    while ($fsemact = $q_semestre->fetch(PDO::FETCH_ASSOC)) {  
        $semac=$fsemact['semestre'];
    }
    $q_idges= $bdcon->prepare("SELECT id from gestion where opcion='$nbges'");
$q_idges->execute();
while ($fges = $q_idges->fetch(PDO::FETCH_ASSOC)) {                  
    $idgestion=$fges['id'];
}
?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center" style="font-size:18px; font-weight:bold;">HISTORICO DE PAGOS</div>
                        <table class="table table-responsive">
                            <tr>
                                <td align="right"><strong>SELECCIONA LA GESTION</strong></td>
                                <td>
                                    <select name="gestion" onChange="recargar(this.value,'<?php echo $codest?>')">
                                       <?php                                          
                                        $q_gestiones= $bdcon->prepare("SELECT opcion FROM gestion WHERE opcion>'2015'");
                                        $q_gestiones->execute();
                                        while ($fquery = $q_gestiones->fetch(PDO::FETCH_ASSOC)) {  
                                                $opcion=$fquery['opcion'];
                                            if ($opcion==$nbges) {
                                                echo "<option value='$opcion' selected='selected'>$opcion</option>";
                                            }else{
                                                echo "<option value='$opcion'>$opcion</option>";
                                            }
                                        }
                                        ?>
                                    </select> 
                                </td>
                            </tr>
                        </table>
                        <small>
                            <table class="table table-responsive">
                                <tr>
                                    <td><strong>&nbsp;NOMBRE:</strong></td>
                                    <td><strong><?php echo strtoupper($nbestudiante);?></strong></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"><strong>REGISTRO:&nbsp;</strong></div></td>
                                    <td>&nbsp;
                                        <strong>
                                            <?php 
                                            if ($cod_new=='0') {
                                                echo $cod_ant;
                                            }else{
                                                echo $cod_new;
                                            }
                                            ?>
                                            <input type="hidden" name="codest" id="codest" value="<?php echo $cod_ant; ?>">
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>&nbsp;CARRERA:</strong></td>
                                    <td><strong><?php if(is_numeric($carreraest)){ echo $nomb->carrera_post($carreraest);}else{ echo $carreraest;}?></strong></td>
                                    <td>&nbsp;</td>
                                    <td><div align="right"><strong>SEMESTRE ACTUAL: </strong></div></td>
                                    <td><strong>&nbsp;<?php echo $semac."° Semestre ";?></strong></td>
                                </tr>
                            </table>
                        </small>
                    <div id="recargo">
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
                    $contal= $bdcon->prepare("SELECT periodo FROM (SELECT periodo from ca_diario where codest='$codest' and gestion='$nbges' UNION ALL SELECT periodo from ca_diario_banco where codest='$codest' and gestion='$nbges') T1 group by periodo");
                    $contal->execute(); 
                    $num_carreras=$contal->rowCount();
                        if ($num_carreras==0) {
                                echo "<div class='alert alert-warning'><strong>¡Atención! </strong>No tiene registros contables en este año</div>";
                                    exit();
                            }           
                    $cons= $bdcon->prepare("SELECT * FROM (SELECT periodo, semestre, fecha, numero, bolivianos, concepto, tipoComprobante, cuenta, hora, usuario, sucursal, numfactura from ca_diario where codest='$codest' and gestion='$nbges' UNION ALL SELECT periodo, semestre, fecha, cod_diario as numero, bolivianos, concepto, tipoComprobante, cuenta, hora, usuario, sucursal, numfactura from ca_diario_banco where codest='$codest' and gestion='$nbges') T2 order by semestre,fecha asc");
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
                                <td height="22" colspan="7">
                                    <div class="periodo"><?php
                                        $totin=0;
                                        $toteg=0;                  
                                        $cperio= $bdcon->prepare("SELECT * from periodo where id LIKE '%$per' and relacion='$idgestion'");
                                        $cperio->execute();
                                        while ($fcperio = $cperio->fetch(PDO::FETCH_ASSOC)) {                   
                                            $nbperio=$fcperio['opcion'];
                                        }echo @$nbperio;?>
                                    </div>
                                </td>
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
                            $valTipo="-I".$nbges;
                        }else{
                            $valTipo="-E".$nbges;
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
                    </div>                    
                </div>                   
            </div>  
        </div> 
    </div> 
