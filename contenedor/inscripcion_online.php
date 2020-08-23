<?php 
$codest=$_REQUEST['_codest'];
$semestre=$_REQUEST['_semestre'];
$carrera=$_REQUEST['_carrera'];
$intsemestre = (int)$semestre + 1;
$anio=date("Y");
include "../includes/conexion.php";
$per=$anio."02";
$perdo=$anio."08";
$q_resol= $bdcon->prepare("SELECT id_resolucion,tipo_pre FROM estudiante_codigo WHERE codigo_estudiante='$codest'");
$q_resol->execute();   
while ($r_resol = $q_resol->fetch(PDO::FETCH_ASSOC)) {       
    $idresol=$r_resol['id_resolucion'];
    $nop=$r_resol['tipo_pre'];
} 
if ($idresol==1) {
    if ($intsemestre==11) {
            echo "<div class='alert alert-danger'>No tiene materias para registrar materias</div>"; 
            exit();
   }    
}else{
     if ($intsemestre==10) {
            echo "<div class='alert alert-danger'>No tiene materias para registrar materias</div>"; 
            exit();
   }   
}

$q_gestion= $bdcon->prepare("SELECT id FROM gestion WHERE opcion='$anio'");
$q_gestion->execute();   
while ($r_gestion = $q_gestion->fetch(PDO::FETCH_ASSOC)) {       
    $id_gestion=$r_gestion['id'];
}    

$q_codcarrera= $bdcon->prepare("SELECT Codigo FROM carreras WHERE Titulo='$carrera' and Requisito='$anio' and id_resolucion='$idresol' and estado='1' ");
$q_codcarrera->execute();   
 while ($r_codcarrera = $q_codcarrera->fetch(PDO::FETCH_ASSOC)) {       
        $codcarreraa=$r_codcarrera['Codigo'];
        
}

/*----------aqui vamos a restringir los que se aplazaron mas de 4 materias*/
$q_registroesmat= $bdcon->prepare("SELECT codmateria, idgrupo FROM aca_registroestmat WHERE codest='$codest' and gestion='$id_gestion' and (periodo='202001' or periodo='202006')");
$q_registroesmat->execute();  
$materia_reprobada=0; 
 while ($r_regmat = $q_registroesmat->fetch(PDO::FETCH_ASSOC)) {       
        $codmat_ant=$r_regmat['codmateria'];
        $id_grupoant=$r_regmat['idgrupo'];
        $n_aproba =0;
        $q_notas= $bdcon->prepare("SELECT  SUM(reco) as notita from plat_doc_intentos_est where codest='$codest' and codgrupo='$id_grupoant' and estado='1'");
        $q_notas->execute();
        
        while ($r_notas = $q_notas->fetch(PDO::FETCH_ASSOC)) {  
                 $n_aproba=$r_notas['notita'];
                if ($n_aproba=="") {
                   // echo "0";
                }  
               // echo "<br>";              
                if ($n_aproba<51) {
                  $materia_reprobada ++;
                 }
        }
        
}
    //echo $materia_reprobada;
    if ($materia_reprobada>= 4) {
        echo "<div class='alert alert-danger'>Tiene más o igual de 4 materias reprobadas no puede pre registrar materias</div>";
    exit();
    }



$q_pensum= $bdcon->prepare("SELECT materia FROM aca_pensum WHERE gestion='$id_gestion' and carrera='$codcarreraa' and semestre='$intsemestre' and id_resolucion='$idresol'");
$q_pensum->execute();  
   
    $fecha=date("Y-m-d");
    $hora=date("H:i:s");    
?>

    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">

                <?php
                     $q_regesmat= $bdcon->prepare("SELECT * FROM aca_registroestmat WHERE codest='$codest' and periodo='$per' or periodo='$perdo'");
                      $q_regesmat->execute(); 
                      if ($q_regesmat->rowCount()>0) {
                             echo "<div class='alert alert-success'><strong>¡Registrado! </strong> Ya tiene materias registradas para este periodo</div>";
                             exit();
                         }   
                 ?>     
                    <div align="center">
                        <b>Materias Ofertadas Para el Periodo 2/2020</b>
                    </div>


                    <table class="table table-hover"> 
                            <tr align="center">
                                <td><strong>MATERIA</strong></td><td><strong>GRUPO</strong></td>
                            </tr>                      
                           <?php
                                 while ($r_pensum = $q_pensum->fetch(PDO::FETCH_ASSOC)) {  
                            ?>
                            <tr align="left"> 
                                <td>
                                    <?php
                                        $codmateria=$r_pensum['materia'];
                                        $q_nbmateria= $bdcon->prepare("SELECT Descripcion FROM materias WHERE Sigla='$codmateria' and CodCarrera='$codcarreraa'");
                                        $q_nbmateria->execute();   
                                        while ($r_nbmateria = $q_nbmateria->fetch(PDO::FETCH_ASSOC)) {       
                                            $nb_materia=$r_nbmateria['Descripcion'];
                                        }
                                        echo $codmateria." - ".$nb_materia;
                                    ?>
                                </td>
                                <td><!-- verificar si de proyecto o normal-->
                                    <?php
                                        if ($nop==3) {
                                           echo  $grupo="P";                                           
                                        }else{
                                            echo $grupo="A";
                                        }
                                    ?>
                                </td>
                            <?php
                                }
                            ?>
                            </tr>
                            <tr align="center">
                                <td colspan="2">
                                    <button class="btn btn-success" data-dismiss="modal" onclick="reg_inscripcion_online('<?php echo $grupo; ?>','<?php echo $id_gestion; ?>','<?php echo $codest; ?>','<?php echo $codcarreraa; ?>','<?php echo $intsemestre; ?>','<?php echo $idresol; ?>')">PRE-REGISTRO DE MATERIAS</button>
                                </td>
                            </tr>
                    </table>                 
                </div>                    
            </div>  
        </div> 
    </div>
