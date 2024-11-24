<?php 
$codest=$_REQUEST['_codest'];
$semestre=$_REQUEST['_semestre'];
$carrera=$_REQUEST['_carrera'];
include "../includes/conexion.php"; 
include("clases_nombres.php");
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
$fec_act=$fecha->format('Y-m-d');
$hr_act=$fecha->format('H:i:s');    
$periodo=$nbges."02"; 
$nbperiodo="II/2022";
$registromat=1;
$registromatniv=1;
$nbsub="";
$querynormal= $bdcon->prepare("SELECT est.codreg, est.grupo, mat.semestre FROM aca_registroestmat est INNER JOIN materias mat ON(est.codmateria=mat.Sigla and mat.CodCarrera='$carrera') WHERE est.codest='$codest' and periodo='$periodo' limit 1");
$querynormal->execute();  
if($querynormal->rowCount()==0){
    $registromat=0;
}
$periodoniv=$nbges."08"; 
$querynivelacion= $bdcon->prepare("SELECT codreg, grupo, codmateria FROM aca_registroestmat WHERE codest='$codest' and periodo='$periodoniv'");
$querynivelacion->execute();  
if ($querynivelacion->rowCount()==0) {
    $registromatniv=0;
}  

?>
<div uk-grid>       
    <div class="uk-width-2-5@m">
        <div class="uk-card-default rounded p-3">
            <div align="center">
                <div class="alert alert-warning" role="alert">
                    <strong>INSCRIPCIÓN GENERAL A LOS SUBGRUPOS DE PRÁCTICAS </strong>
                </div>
            </div>
            <hr class="ms-1">
            <?php  
            if ($registromat==1) {
                ?>                                                  
                <?php
                while ($row = $querynormal->fetch(PDO::FETCH_ASSOC)) {
                    $grupo=$row['grupo']; 
                    $sem=$row['semestre'];                             
                }
                $querycantidad= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$carrera' and sem='$sem' and grupo='$grupo' and periodo='$periodo'");
                $querycantidad->execute(); 
                if ($querycantidad->rowCount()==0) {
                    $nb_sub="A1";
                }else{
                    $switch=0;
                    while ($rowcan = $querycantidad->fetch(PDO::FETCH_ASSOC)) {
                        $codsubgrupo=$rowcan['cod'];
                        $inscritos=$rowcan['num_ins'];
                        $nbsub=$rowcan['nb_sub'];
                        if ($switch==0) {
                            if ($inscritos<25) {
                                $nb_sub=$nbsub;
                                $switch=1;
                            }
                        }
                    } 
                } 
               // echo "SEMESTRE ".$sem;
                $querycantidad= $bdcon->prepare("SELECT b.nb_sub FROM grupos_sub_listas_cloud a INNER JOIN grupos_sub_cloud b on a.cod_subcloud = b.cod WHERE a.codest='$codest' and a.periodo='$periodo'"); 
                $querycantidad->execute();  
                if ($querycantidad->rowCount()>=1) {
                    while ($rows = $querycantidad->fetch(PDO::FETCH_ASSOC)) {
                        $grupo=$rows['nb_sub'];                               
                    }  
                    ?>
                    <div class="alert alert-success" role="alert">
                      <strong>¡YA REALIZÓ SU REGISTRO A LOS SUBGRUPOS DE PRÁCTICA!</strong><br> Usted está inscrit@ al subgrupo<strong> <?php echo $grupo;?></strong>
                    </div>
                <?php                    
                }else{
                    ?>
                    <form method="post" onsubmit="return false;" id="form_reg">
                        <input type="hidden" name="codcar" value="<?php echo $carrera; ?>">
                        <input type="hidden" name="sem" value="<?php echo $sem; ?>"> 
                        <input type="hidden" name="grupo" value="<?php echo $grupo; ?>">
                        <input type="hidden" name="codest" value="<?php echo $codest; ?>">
                        <input type="hidden" name="fecha" value="<?php echo $fec_act; ?>">
                        <input type="hidden" name="hora" value="<?php echo $hr_act; ?>"> 
                        <input type="hidden" name="periodo" value="<?php echo $periodo; ?>"> 
                        <div align="center">
                            <p>Usted se va a registrar al subgrupo <b><?php echo $nb_sub; ?></b> en todas las materias con prácticas</b></p>
                            <button class="btn btn-success" type="button" onclick="guardar_subgrupo_all()">INSCRIBIRSE AHORA</button>
                        </div>   
                    </form> 
                    <?php
                }
            }else{
                ?>
                <div align="center"><div class="alert alert-danger" role="alert">
                    NO TIENE MATERIAS REGISTRADAS PARA ESTE PERIODO NORMAL
                </div></div>
                <?php
            } 
            ?>                    
        </div>
    </div> 
    <div class="uk-width-2-5@m">
       <div class="uk-card-default rounded p-3">                            
            <div align="center">
                <div class="alert alert-warning" role="alert">
                    <strong>INSCRIPCIÓN GENERAL A LOS SUBGRUPOS DE PRÁCTICAS NIVELACIÓN</strong>
                </div>
            </div>
            <hr class="ms-1">      
            <?php
            if ($registromatniv==1) {
                $querycantidadniv= $bdcon->prepare("SELECT b.nb_sub FROM grupos_sub_listas_cloud a INNER JOIN grupos_sub_cloud b on a.cod_subcloud = b.cod WHERE a.codest='$codest' and a.periodo='$periodoniv'"); 
                $querycantidadniv->execute();  
                if ($querycantidadniv->rowCount()>=1) {
                    while ($rows = $querycantidadniv->fetch(PDO::FETCH_ASSOC)) {
                        $grupo=$rows['nb_sub'];                               
                    }  
                    ?>
                    <div align="center"><div class="alert alert-success" role="alert">
                      <strong>¡YA REALIZÓ SU REGISTRO A LOS SUBGRUPOS DE PRÁCTICA!</strong><br> Usted está inscrit@ al subgrupo<strong> <?php echo $grupo;?></strong>
                    </div></div>
                <?php                            
                }else{
                    ?>
                    <form method="post" onsubmit="return false;" id="form_reg_niv">
                        <input type="hidden" name="codest" value="<?php echo $codest; ?>"> 
                        <input type="hidden" name="codcar" value="<?php echo $carrera; ?>">
                        <input type="hidden" name="periodo" value="<?php echo $periodoniv; ?>"> 
                        <input type="hidden" name="fecha" value="<?php echo $fec_act; ?>">
                        <input type="hidden" name="hora" value="<?php echo $hr_act; ?>"> 
                        <div align="center">
                            <button class="btn btn-success" type="button" onclick="guardar_subgrupo_all_niv()">INSCRIBIRSE AHORA</button>
                        </div>   
                    </form> 
                    <?php
                }
            }else{
                ?>
                <div align="center">
                    <div class="alert alert-danger" role="alert">
                        <strong>NO TIENE MATERIAS REGISTRADAS PARA ESTE PERIODO DE NIVELACIÓN</strong>
                    </div>
                </div>
                <?php
            } 
                ?> 
        </div>
    </div>             
</div>