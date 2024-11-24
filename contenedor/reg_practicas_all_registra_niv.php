<?php 
$codest=$_REQUEST['codest'];
$carrera=$_REQUEST['codcar'];
$fec_act=$_REQUEST['fecha'];
$hr_act=$_REQUEST['hora'];
$periodoniv=$_REQUEST['periodo'];
include "../includes/conexion.php";
$inscritos=0;
$nbsub="";
$querynivelacion= $bdcon->prepare("SELECT a.grupo, a.codmateria, b.semestre FROM aca_registroestmat a INNER JOIN materias b on a.codmateria=b.Sigla WHERE codest='$codest' and periodo='$periodoniv'");
$querynivelacion->execute();  
while ($rowmat = $querynivelacion->fetch(PDO::FETCH_ASSOC)) {
    $grupo=$rowmat['grupo']; 
    $mat=$rowmat['codmateria']; 
    $seme=$rowmat['semestre'];  
    $queryfusionado= $bdcon->prepare("SELECT codcar_raiz, per_raiz, mat_raiz, gru_raiz FROM grupos_fusionados WHERE codcar_rama='$carrera' and per_rama='$periodoniv' and mat_rama='$mat' and gru_rama='$grupo'");
    $queryfusionado->execute();  
    while ($rowfusion = $queryfusionado->fetch(PDO::FETCH_ASSOC)) {
        $codcarraiz=$rowfusion['codcar_raiz']; 
        $perraiz=$rowfusion['per_raiz']; 
        $matraiz=$rowfusion['mat_raiz']; 
        $gruraiz=$rowfusion['gru_raiz'];        
        $querycantidad= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$codcarraiz' and sem='$seme' and grupo='$gruraiz' and periodo='$perraiz'");
        $querycantidad->execute();  
        if ($querycantidad->rowCount()==0) {        
            ///////INSERTAR/////////////
            $nbsub=$grupo."1";
            $inscritos=1;        
            $registrar= $bdcon->prepare("INSERT INTO grupos_sub_cloud VALUES ('0','$codcarraiz','$seme','$gruraiz','$inscritos','$nbsub','25','$perraiz','','$fec_act','$hr_act')");
            $registrar->execute();
            //echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
            $querycantidadnew= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$codcarraiz' and sem='$seme' and grupo='$gruraiz'");
            $querycantidadnew->execute();  
            while ($rowcan = $querycantidadnew->fetch(PDO::FETCH_ASSOC)) {
                $codsubgrupo=$rowcan['cod'];            
            } 
            $queryver= $bdcon->prepare("SELECT cod FROM grupos_sub_listas_cloud WHERE cod_subcloud='$codsubgrupo' and codest='$codest'");
            $queryver->execute();  
            if ($queryver->rowCount()==0) {
                $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodoniv','$fec_act','$hr_act')");
                $registrarest->execute(); 
            } 
        }else{
            while ($rowcan = $querycantidad->fetch(PDO::FETCH_ASSOC)) {
                $codsubgrupo=$rowcan['cod'];
                $inscritos=$rowcan['num_ins'];
                $nbsub=$rowcan['nb_sub']; 
            } 
            $inscritos++;
            if ($inscritos>25) {
                ////////////INSERTAR UN NUEVO SUBGRUPO/////////////
                $inscritos=1;
                $numero=substr($nbsub,1);           
                $numero++;
                $nbsub=$grupo.$numero;           
                $registrar= $bdcon->prepare("INSERT INTO grupos_sub_cloud VALUES ('0','$codcarraiz','$seme','$gruraiz','$inscritos','$nbsub','25','$perraiz','','$fec_act','$hr_act')");
                $registrar->execute();
                //echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
                $querycantidadnew= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$codcarraiz' and sem='$seme' and grupo='$gruraiz'");
                $querycantidadnew->execute();  
                while ($rowcan = $querycantidadnew->fetch(PDO::FETCH_ASSOC)) {
                    $codsubgrupo=$rowcan['cod'];            
                } 

                $queryver= $bdcon->prepare("SELECT cod FROM grupos_sub_listas_cloud WHERE cod_subcloud='$codsubgrupo' and codest='$codest'");
                $queryver->execute();  
                if ($queryver->rowCount()==0) {
                   $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodoniv','$fec_act','$hr_act')");
                $registrarest->execute(); 
                }
            }else{            
                
               //echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
                $queryver= $bdcon->prepare("SELECT cod FROM grupos_sub_listas_cloud WHERE cod_subcloud='$codsubgrupo' and codest='$codest'");
                $queryver->execute();  
                if ($queryver->rowCount()==0) {
                    //////////MODIFICAR/////////////
                    $modificar= $bdcon->prepare( "UPDATE grupos_sub_cloud SET num_ins='$inscritos' WHERE cod='$codsubgrupo'");
                    $modificar->execute();

                    $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodoniv','$fec_act','$hr_act')");
                    $registrarest->execute(); 
                }
            }
        }
    }
} 
echo "<div align='center'><div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ a todos los subgrupos de nivelación</div></div>";    
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">               
                <div align="center">
                    <div align="center">
                        <div class="alert alert-warning">
                            <strong>Nota: </strong>Queda pendiente la asiganción de Docente y Horario, para los subgrupos de parte de jefatura de carrera.  
                        </div>
                    </div>
                </div>       
            </div>                   
        </div>  
    </div> 
</div>
