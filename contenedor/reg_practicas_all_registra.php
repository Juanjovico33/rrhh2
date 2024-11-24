<?php 
$codest=$_REQUEST['codest'];
$semestre=$_REQUEST['sem'];
$carrera=$_REQUEST['codcar'];
$grupo=$_REQUEST['grupo'];
$fec_act=$_REQUEST['fecha'];
$hr_act=$_REQUEST['hora'];
$periodo=$_REQUEST['periodo'];
include "../includes/conexion.php";
$inscritos=0;
$nbsub="";
    $querycantidad= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$carrera' and sem='$semestre' and grupo='$grupo' and periodo='$periodo'");
    $querycantidad->execute();  
    if ($querycantidad->rowCount()==0) {        
        ///////INSERTAR/////////////
        //echo "grupo nuevo";
        $nbsub=$grupo."1";
        $inscritos=1;        
        $registrar= $bdcon->prepare("INSERT INTO grupos_sub_cloud VALUES ('0','$carrera','$semestre','$grupo','$inscritos','$nbsub','35','$periodo','','$fec_act','$hr_act')");
        $registrar->execute();
        echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
        $querycantidadnew= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$carrera' and sem='$semestre' and grupo='$grupo'");
        $querycantidadnew->execute();  
        while ($rowcan = $querycantidadnew->fetch(PDO::FETCH_ASSOC)) {
            $codsubgrupo=$rowcan['cod'];            
        } 
        $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodo','$fec_act','$hr_act')");
        $registrarest->execute();

    }else{
        //echo "ya existe grupo";
        while ($rowcan = $querycantidad->fetch(PDO::FETCH_ASSOC)) {
            $codsubgrupo=$rowcan['cod'];
            $inscritos=$rowcan['num_ins'];
            $nbsub=$rowcan['nb_sub']; 
        } 
        $inscritos++;
        if ($inscritos>25) {
            ////////////INSERTAR UN NUEVO SUBGRUPO/////////////
            //echo "grupo lleno ";
            $inscritos=1;
            $numero=substr($nbsub,1);           
            $numero++;
            $nbsub=$grupo.$numero;           
            $registrar= $bdcon->prepare("INSERT INTO grupos_sub_cloud VALUES ('0','$carrera','$semestre','$grupo','$inscritos','$nbsub','25','$periodo','','$fec_act','$hr_act')");
            $registrar->execute();
            echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
            $querycantidadnew= $bdcon->prepare("SELECT cod,num_ins,nb_sub FROM grupos_sub_cloud WHERE codcar='$carrera' and sem='$semestre' and grupo='$grupo'");
            $querycantidadnew->execute();  
            while ($rowcan = $querycantidadnew->fetch(PDO::FETCH_ASSOC)) {
                $codsubgrupo=$rowcan['cod'];            
            } 
            $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodo','$fec_act','$hr_act')");
            $registrarest->execute();
        }else{      
            //echo "hay cupo";      
            //////////MODIFICAR/////////////
            $modificar= $bdcon->prepare( "UPDATE grupos_sub_cloud SET num_ins='$inscritos' WHERE cod='$codsubgrupo'");
            $modificar->execute();
            echo "<div class='alert alert-success'><strong>¡Correcto!<br> ¡Datos registrados correctamente!</strong><br>Usted está inscrit@ al subgrupo <strong>$nbsub</strong></div>";
            $registrarest= $bdcon->prepare("INSERT INTO grupos_sub_listas_cloud VALUES ('0','$codsubgrupo','$codest','$periodo','$fec_act','$hr_act')");
            $registrarest->execute();
        }
    } 
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content"> 
                <div class="alert alert-warning">
                    <strong>Nota: </strong>Queda pendiente la asiganción de Docente y Horario, para los subgrupos de parte de jefatura de carrera.  
                </div> 
            </div>                   
        </div>  
    </div> 
</div>
 
