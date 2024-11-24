<?php 
$codest=$_REQUEST['_codest'];
$sem=$_REQUEST['_semestre'];
$carrera=$_REQUEST['_carrera'];
include "../includes/conexion.php"; 
include("clases_nombres.php");
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
$nlunes=0;
$nmartes=0;
$nmiercoles=0;
$njueves=0;
$nviernes=0;
$nsabado=0;
$ndomingo=0;
$per=$nbges."02"; 
$nbperiodo="II/2022";
$q_resolucion= $bdcon->prepare("SELECT id_resolucion, carrera from estudiante_codigo where codigo_estudiante='$codest'");
$q_resolucion->execute();  
while ($rowresolucion = $q_resolucion->fetch(PDO::FETCH_ASSOC)) { 
    $res=$rowresolucion['id_resolucion']; 
    $car=$rowresolucion['carrera']; 
}
if ($res=='1') {
     $nb_res='2002';
}else{
    if ($res=='2') {
        $nb_res='2017';
    }else{
        $nb_res='2002';
    }
}
function cal_tiempo($hr1,$hr2){
    $apertura = new DateTime($hr1);
    $cierre = new DateTime($hr2);
    $tiempo = $apertura->diff($cierre);
    $retorn=$tiempo->format('%H.%i');
    return $retorn;
} 
function hr_academicas($hrs){
    if ($hrs>='0.45' && $hrs<'1') {
        $hrs_acad=1;
    }else{
        if ($hrs>='1' && $hrs<'2') {
            $hrs_acad=2;
        }else{
            if ($hrs>='2' && $hrs<'3') {
                $hrs_acad=3;
            }else{
                if ($hrs>='3' && $hrs<'4') {
                    $hrs_acad=4;
                }else{
                    if ($hrs>='4' && $hrs<'5') {
                        $hrs_acad=5;
                    }else{
                        if ($hrs>='5' && $hrs<'5.45') {
                            $hrs_acad=6;
                        }else{
                            if ($hrs>='5.45' && $hrs<7) {
                                $hrs_acad=7;
                            }else{
                                $hrs_acad=0;    
                            }
                        }
                    }
                }
            }
        }
    }
    return $hrs_acad;
}

function semanas($desde, $hasta){
    $datetime1 = new DateTime($desde);
    $datetime2 = new DateTime($hasta);
    $interval = $datetime1->diff($datetime2);
    $semanas=floor(($interval->format('%a') / 7)) . '|'.($interval->format('%a') % 6);
    return $semanas;
}

function nb_dia($dia){
        switch ($dia) {
            case '1':
                $nb_dia="Lunes";
                break;
            case '2':
                $nb_dia="Martes";
                break;
            case '3':
                $nb_dia="Miercoles";
                break;
            case '4':
                $nb_dia="Jueves";
                break;
            case '5':
                $nb_dia="Viernes";
                break;
            case '6':
                $nb_dia="Sabado";
                break;
            case '7':
                $nb_dia="Domingo";
                break;
            default:
                $nb_dia="S/A";
                break;
        }
        return $nb_dia;
}

function formato_fecha($fecha){
        $part=explode("-", $fecha);
        $dia=$part[2];
        $mes=$part[1];
        $anio=$part[0];
        switch ($mes) {
            case '01':
                $nb_mes="enero";
                break;
            case '02':
                $nb_mes="febrero";
                break;
            case '03':
                $nb_mes="marzo";
                break;
            case '04':
                $nb_mes="abril";
                break;
            case '05':
                $nb_mes="mayo";
                break;
            case '06':
                $nb_mes="junio";
                break;
            case '07':
                $nb_mes="julio";
                break;
            case '08':
                $nb_mes="agosto";
                break;
            case '09':
                $nb_mes="septiembre";
                break;
            case '10':
                $nb_mes="octubre";
                break;
            case '11':
                $nb_mes="noviembre";
                break;
            case '12':
                $nb_mes="diciembre";
                break;
            default:
                $nb_mes="Error en el mes";
                break;
        }
        $fec_form=$dia." DE ".strtoupper($nb_mes);
        return $fec_form;
}

function saca_gest($fecha){
    $part=explode("-",$fecha);
    $anio=$part[0];
    return $anio;
}
?>
<style type="text/css">
    th,td{
        font-size: 11px;
        text-align: center;
    }
    h6{
        font-size: 12px;
    }
    h4{
        font-size: 13px;
    }
</style>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">               
                <div align="center">
                    <?php
                        $q_mat = $bdcon->prepare("SELECT codreg, codmateria, grupo from aca_registroestmat where periodo='$per' and codest='$codest'");
                        $q_mat->execute();
                        if($q_mat->rowCount()==0){
                            echo "NO TIENE REGISTROS PARA ESTE PERIODO ".$nbperiodo; 
                            exit();                          
                        }else{
                            $materias="";         
                            while ($row = $q_mat->fetch(PDO::FETCH_ASSOC)){   
                                $codmat=$row['codmateria'];
                                $gru=$row['grupo'];                                                            
                            } 
                        }
                        $vec_txt="";
                        $txt_add= $bdcon->prepare("SELECT cod, txt, prioridad from grupos_horario_textos where per='$per' and car='$car' and sem='$sem' and gru='$gru' and estado='1'");
                        $txt_add->execute();  
                        while ($fil_txt = $txt_add->fetch(PDO::FETCH_ASSOC)) { 
                            $lbl_txt=$fil_txt['txt'];
                            $lbl_pos=$fil_txt['prioridad'];
                            if ($vec_txt=='') {
                                $vec_txt=$lbl_txt.",".$lbl_pos;
                            }else{
                                $vec_txt=$vec_txt."|".$lbl_txt.",".$lbl_pos;
                            }
                        }
                        //$qaulas=$cons->cons_cond('aulas',"cod>'0'",'cod');
                        $qaulas= $bdcon->prepare("SELECT cod, CodAula, Descripcion from aulas where cod>'0'");
                        $aulas[]="";
                        //while ($faulas=mysql_fetch_array($qaulas)) {
                        while ($faulas = $qaulas->fetch(PDO::FETCH_ASSOC)) { 
                            $codau=$faulas['CodAula'];
                            $nomau=$faulas['Descripcion'];
                            $aulas[$codau]=$nomau;
                        }
                        $fusionadas="";
        $q_mat=$bdcon->prepare("SELECT mat.CodCarrera, mat.Sigla, mat.Descripcion, fus.codcar_raiz, fus.mat_raiz, fus.gru_raiz FROM materias mat INNER JOIN grupos_fusionados fus ON(mat.CodCarrera=fus.codcar_rama and mat.Sigla=fus.mat_rama and fus.per_rama='$per' and fus.gru_rama='$gru') WHERE mat.CodCarrera='$car' and mat.semestre='$sem'");
         $q_mat->execute();
         while ($fmat = $q_mat->fetch(PDO::FETCH_ASSOC)){
            $m_carre=$fmat['CodCarrera'];
            $m_sigla=$fmat['Sigla'];           
            $m_nombr=$fmat['Descripcion'];
            $car_raiz=$fmat['codcar_raiz'];
            $mat_raiz=$fmat['mat_raiz'];
            $gru_raiz=$fmat['gru_raiz'];
            $q_math=$bdcon->prepare("SELECT CodGrupo from grupos where CodCarrera='$car_raiz' and periodo='$per' and CodMateria='$mat_raiz' and Descripcion='$gru_raiz'");            
            $q_math->execute();
            while ($fmatt = $q_math->fetch(PDO::FETCH_ASSOC)){
               $codgru_raiz=$fmatt['CodGrupo'];                 
            }
            if ($fusionadas=='') {
                $fusionadas=$car_raiz.",".$mat_raiz.",".$gru_raiz.",".$codgru_raiz;
            }else{
                $fusionadas=$fusionadas."|".$car_raiz.",".$mat_raiz.",".$gru_raiz.",".$codgru_raiz;
            }
            //podria obtener el codigo del grupo
        }
        $part_fus=explode("|",$fusionadas);        
            $vaciar=$bdcon->prepare("TRUNCATE TABLE `grupos_horario_tmp`");
            $vaciar->execute();
            $fec_ini_teo="";
            $fec_fin_teo="";
            $fec_ini_asi_teo="";
            $fec_fin_asi_teo="";
            $fec_ini_asi_pra="";
            $fec_fin_asi_pra="";
            $fec_prac_ini="";
            $fec_prac_fin="";
            $fec_inv_ini="";
            $fec_inv_fin="";
            if ($fusionadas!='') {
                $fus_car="";
                $fus_mat="";
                $fus_gru="";
                $condi_fus="";
                $cad_gru="";
                foreach ($part_fus as $key => $value) {
                    $val_fus=explode(",",$value);
                    $fcar=$val_fus[0];
                    $fmat=$val_fus[1];
                    $fgru=$val_fus[2];
                    $fcodgru=$val_fus[3];
                    if ($fus_car=='') {
                        $fus_car="carrera='$fcar'";
                        $fus_mat="materia='$fmat'";
                        $fus_gru="grupo='$fgru'";
                    }else{
                        $fus_car=$fus_car." or carrera='$fcar'";
                        $fus_mat=$fus_mat." or materia='$fmat'";
                        $fus_gru=$fus_gru." or grupo='$fgru'";
                    }
                    if ($condi_fus=='') {
                        $condi_fus="(carrera='$fcar' and materia='$fmat' and grupo='$fgru')";
                    }else{
                        $condi_fus=$condi_fus." and (carrera='$fcar' and materia='$fmat' and grupo='$fgru')";
                    }
                    if ($cad_gru=='') {
                        $cad_gru="cod_gru='$fcodgru'";
                    }else{
                        $cad_gru=$cad_gru." or cod_gru='$fcodgru'";
                    }
                }
               
                $q_h=$bdcon->prepare("SELECT dia, hr_entrada, hr_salida, aula, materia, cod_doc, cod_gru, cod_subgru, tipo_clase, ini, fin, carrera FROM grupos_horario WHERE (periodo='$per' and grupo='$gru' and semestre='$sem' and carrera='$car') ORDER BY hr_entrada, dia ASC");
                $q_h->execute();
                $q_f=$bdcon->prepare("SELECT dia, hr_entrada, hr_salida, aula, materia, cod_doc, cod_gru, cod_subgru, tipo_clase, ini, fin, carrera FROM grupos_horario WHERE ($cad_gru) and materia!='PRO001' ORDER BY hr_entrada, dia ASC");
                $q_f->execute();
               
                $hayfus=$q_f->rowCount();              
            }else{
                $q_h=$bdcon->prepare("SELECT dia, hr_entrada, hr_salida, aula, materia, cod_doc, cod_gru, cod_subgru, tipo_clase, ini, fin, carrera FROM grupos_horario WHERE periodo='$per' and grupo='$gru' and semestre='$sem' and carrera='$car' ORDER BY hr_entrada, dia ASC");
                $q_h->execute();
                $hayfus=0;
            }
            
            $materias=array();
            $nume=1;
            $hrs_aca=0;
            $mos_sem=0;
            $obser="";           
            while ($fh = $q_h->fetch(PDO::FETCH_ASSOC)){
                $dia=$fh['dia'];
                $hing=$fh['hr_entrada'];
                $hsal=$fh['hr_salida'];
                $hing=substr($hing, 0, 5);
                $hsal=substr($hsal, 0, 5);
                $aula=$fh['aula'];
                $sigmat=$fh['materia'];
                $cod_doc=$fh['cod_doc'];
                $cod_gru=$fh['cod_gru'];
                $cod_subgru=$fh['cod_subgru'];
                $tipoc=$fh['tipo_clase'];
                $f_ini=$fh['ini'];
                $f_fin=$fh['fin'];
                $n_car=$fh['carrera'];
                $desc_aula=0;                
                switch ($dia) {
                    case '1':
                        $lunes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '2':
                        $martes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '3':
                        $miercoles[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '4':
                        $jueves[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '5':
                        $viernes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '6':
                        $sabado[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    case '7':
                        $domingo[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                    default:
                        $error[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                        break;
                }
                switch ($tipoc) {
                    case '2':
                        $fec_ini_teo=$f_ini;
                        $fec_fin_teo=$f_fin;
                        break;
                    case '3':
                        $fec_prac_ini=$f_ini;
                        $fec_prac_fin=$f_fin;
                        break;
                    case '4':
                        $fec_inv_ini=$f_ini;
                        $fec_inv_fin=$f_fin;
                        break;
                    case '5':
                        $fec_ini_asi_teo=$f_ini;
                        $fec_fin_asi_teo=$f_fin;
                        break;
                    case '6':
                        $fec_ini_asi_pra=$f_ini;
                        $fec_fin_asi_pra=$f_fin;
                        break;
                    default:
                        break;
                }                
                $time2=cal_tiempo($hing,$hsal); 
                $time2=(double)$time2;
                $hr_acad=hr_academicas($time2);                
                $ins_tmp=$bdcon->prepare("INSERT INTO grupos_horario_tmp (`cod`, `periodo`, `carrera`, `semestre`, `materia`, `grupo`, `hrs_acade`, `tipo_clase`, `cod_gru`, `ini`, `fin`) values(0,'$per','$car','$sem','$sigmat','$gru','$hr_acad','$tipoc','$cod_gru','$f_ini','$f_fin')");
                $ins_tmp->execute();

                $nume++;
            }

            if ($hayfus>0) {
                //echo "Hay fusionadas";
                $materias=array();
                $nume=1;
                $hrs_aca=0;
                $mos_sem=0;
                $obser="";
               
                while ($fh = $q_f->fetch(PDO::FETCH_ASSOC)){
                    $dia=$fh['dia'];
                    $hing=$fh['hr_entrada'];
                    $hsal=$fh['hr_salida'];
                    $hing=substr($hing, 0, 5);
                    $hsal=substr($hsal, 0, 5);
                    $aula=$fh['aula'];
                    $sigmat=$fh['materia'];
                    $cod_doc=$fh['cod_doc'];
                    $cod_gru=$fh['cod_gru'];
                    $cod_subgru=$fh['cod_subgru'];
                    $tipoc=$fh['tipo_clase'];
                    $f_ini=$fh['ini'];
                    $f_fin=$fh['fin'];
                    $n_car=$fh['carrera'];
                    $obser=substr($n_car, 2,4);
                    foreach ($aulas as $key => $value) {
                        if ($key==$aula) {
                            $desc_aula=$value;
                            break;
                        }
                    }
                    switch ($dia) {
                        case '1':
                            $lunes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '2':
                            $martes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '3':
                            $miercoles[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '4':
                            $jueves[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '5':
                            $viernes[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '6':
                            $sabado[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        case '7':
                            $domingo[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                        default:
                            $error[]=$hing.",".$hsal.",".$aula.",".$sigmat.",".$cod_doc.",".$cod_subgru.",".$tipoc.",".$f_ini.",".$f_fin.",".$desc_aula.",".$n_car.",".$obser;
                            break;
                    }
                    switch ($tipoc) {
                        case '2':
                            $fec_ini_teo=$f_ini;
                            $fec_fin_teo=$f_fin;
                            break;
                        case '3':
                            $fec_prac_ini=$f_ini;
                            $fec_prac_fin=$f_fin;
                            break;
                        case '4':
                            $fec_inv_ini=$f_ini;
                            $fec_inv_fin=$f_fin;
                            break;
                        case '5':
                            $fec_ini_asi_teo=$f_ini;
                            $fec_fin_asi_teo=$f_fin;
                            break;
                        case '6':
                            $fec_ini_asi_pra=$f_ini;
                            $fec_fin_asi_pra=$f_fin;
                            break;
                        default:
                            break;
                    }                    
                    $time2=cal_tiempo($hing,$hsal); 
                    $time2=(double)$time2;
                    $hr_acad=hr_academicas($time2);                    
                    $ins_tmp=$bdcon->prepare("INSERT INTO grupos_horario_tmp (`cod`, `periodo`, `carrera`, `semestre`, `materia`, `grupo`, `hrs_acade`, `tipo_clase`, `cod_gru`, `ini`, `fin`) values(0,'$per','$car','$sem','$sigmat','$gru','$hr_acad','$tipoc','$cod_gru','$f_ini','$f_fin')");
                    $ins_tmp->execute();
                    $nume++;
                }
            }       
            
        ?>
        <table class="table table-sm">
            <tr>               
                <td><div align="center"><h4><?php echo $carrera; ?></h4></div></td>               
            </tr>           
        </table>
        <!-- inicio clases teoricas virtuales-->
        <div id="tbl_teorico">
        <table class="table table-bordered table-sm">
            <tr>
                <th colspan="7" class="table-success" style="background-image:url('fondo.png');"><div align="center"><h4>TURNO MAÑANA - CLASES TEÓRICAS VIRTUALES</h4></div></th>
            </tr>
            <tr class="table-success">
                <?php 
                for ($i=1; $i <=7 ; $i++) { 
                    ?>
                    <th width="14.28%" style="background-image:url('fondo.png');"><h6><?php echo strtoupper(nb_dia($i)); ?></h6></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php 
                $ap="";
                $nb="";
                $franja=1;
                $fondo="#FFF";
                for ($d=1; $d <=7 ; $d++) { 
                    //$dia=$nba->nb_dia($d);
                    ?>
                    <td>
                        <div id="cont_teo<?php echo $d; ?>">
                        <?php
                        if ($d=='1') {
                            if (count(@$lunes)>0) {
                                foreach (@$lunes as $key => $value) {
                                    $datos=explode(',', $value);
                                    $h_i=$datos[0];
                                    $h_s=$datos[1];
                                    $aula=$datos[2];
                                    $mate=$datos[3];
                                    $doc=$datos[4];
                                    $subgru=$datos[5];
                                    $t_cla=$datos[6];
                                    $f_car=$datos[10];
                                    $obser=$datos[11];
                                    if ($t_cla=='2' || $t_cla=='4') {
                                        if ($t_cla=='4') {
                                            $fondo="#f6eb55";
                                        }else{
                                            $fondo="EEE";
                                        }
                                        echo "<div style='background-color:".$fondo.";'>";
                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                        $q_nbmat->execute(); 
                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                          echo $matenb=$fhrsmt['Descripcion'];
                                        }    
                                        echo "<br>";
                                        echo $h_i." - ".$h_s;                                       
                                        echo "<br>";
                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                        $q_nbta->execute();
                                        if ($q_nbta->rowCount()>0) {
                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                $nb=$fhrdoc['nombres'];
                                                $ap=$fhrdoc['apellidos'];
                                            }
                                        $nb_comp=$ap." ".$nb;
                                        }else{
                                            $nb_comp="";
                                        }
                                        if ($nb_comp<>'') {
                                            echo "<b>DOC: ".$nb_comp."</b>";
                                            echo "<br>";
                                        }
                                        $nb_comp="";
                                        if ($subgru>0) {
                                            echo "<b>(PRACTICA)</b><br>";
                                        }
                                        if ($t_cla=='4') {
                                            echo "<b>(INVESTIGACIÓN)</b><br>";

                                        }
                                        $time2=cal_tiempo($h_i,$h_s); 
                                        $time2=(double)$time2;
                                        $hr_acad=hr_academicas($time2);
                                        echo "(".$hr_acad." hrs académicas)";
                                        echo "<br>";
                                        if ($obser!='') {
                                            echo "<font color='green'>".$obser."</font>";
                                        }
                                        echo "<div>";
                                        echo " <hr>";
                                    }
                                    
                                }
                            }
                        }else{
                            if ($d=='2') {
                                if (count(@$martes)>0) {
                                    foreach (@$martes as $key => $value) {
                                        $datos=explode(',', $value);
                                        $h_i=$datos[0];
                                        $h_s=$datos[1];
                                        $aula=$datos[2];
                                        $mate=$datos[3];
                                        $doc=$datos[4];
                                        $subgru=$datos[5];
                                        $t_cla=$datos[6];
                                        $f_car=$datos[10];
                                        $obser=$datos[11];
                                        if ($t_cla=='2' || $t_cla=='4') {
                                            if ($t_cla=='4') {
                                                $fondo="#f6eb55";
                                            }else{
                                                $fondo="EEE";
                                            }
                                            echo "<div style='background-color:".$fondo.";'>";
                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                            $q_nbmat->execute(); 
                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                              echo  $matenb=$fhrsmt['Descripcion'];
                                            }  
                                            echo "<br>";
                                            echo $h_i." - ".$h_s;
                                           
                                            echo "<br>";
                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                            $q_nbta->execute();
                                            if ($q_nbta->rowCount()>0) {
                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                    $nb=$fhrdoc['nombres'];
                                                    $ap=$fhrdoc['apellidos'];
                                                }
                                                $nb_comp=$ap." ".$nb;
                                            }else{
                                                $nb_comp="";
                                            }
                                            if ($nb_comp<>'') {
                                                echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                            }
                                            $nb_comp="";
                                            if ($subgru>0) {
                                                echo "<b>(PRACTICA)</b><br>";
                                            }
                                            if ($t_cla=='4') {
                                                echo "<b>(INVESTIGACIÓN)</b><br>";  
                                            }
                                            $time2=cal_tiempo($h_i,$h_s); 
                                            $time2=(double)$time2;
                                            $hr_acad=hr_academicas($time2);
                                            echo "(".$hr_acad." hrs académicas)";
                                            echo "<br>";
                                            if ($obser!='') {
                                                echo "<small><font color='green'>".$obser."</font></small>";
                                            }
                                            echo "</div>";
                                            echo " <hr>";
                                        }
                                    }
                                }
                            }else{
                                if ($d=='3') {
                                    if (count(@$miercoles)>0) {
                                        foreach (@$miercoles as $key => $value) {
                                            $datos=explode(',', $value);
                                            $h_i=$datos[0];
                                            $h_s=$datos[1];
                                            $aula=$datos[2];
                                            $mate=$datos[3];
                                            $doc=$datos[4];
                                            $subgru=$datos[5];
                                            $t_cla=$datos[6];
                                            $f_car=$datos[10];
                                            $obser=$datos[11];
                                            if ($t_cla=='2' || $t_cla=='4') {
                                                if ($t_cla=='4') {
                                                    $fondo="#f6eb55";
                                                }else{
                                                    $fondo="EEE";
                                                }
                                                echo "<div style='background-color:".$fondo.";'>";

                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                $q_nbmat->execute(); 
                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                } 
                                                echo "<br>";
                                                echo $h_i." - ".$h_s;
                                                echo "<br>";
                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                $q_nbta->execute();
                                                if ($q_nbta->rowCount()>0) {
                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                        $nb=$fhrdoc['nombres'];
                                                        $ap=$fhrdoc['apellidos'];
                                                    }
                                                    $nb_comp=$ap." ".$nb;
                                                }else{
                                                    $nb_comp="";
                                                }
                                                if ($nb_comp<>'') {
                                                    echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                }
                                                $nb_comp="";
                                                if ($subgru>0) {
                                                    echo "<b>(PRACTICA)</b><br>";
                                                }
                                                if ($t_cla=='4') {
                                                    echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                }
                                                $time2=cal_tiempo($h_i,$h_s); 
                                                $time2=(double)$time2;
                                                $hr_acad=hr_academicas($time2);
                                                echo "(".$hr_acad." hrs académicas)";
                                                echo "<br>";
                                                if ($obser!='') {
                                                    echo "<small><font color='green'>".$obser."</font></small>";
                                                }
                                                echo "</div>";
                                                echo " <hr>";
                                            }
                                        }
                                    }
                                }else{
                                    if ($d=='4') {
                                        if (count(@$jueves)>0) {
                                            foreach ($jueves as $key => $value) {
                                                $datos=explode(',', $value);
                                                $h_i=$datos[0];
                                                $h_s=$datos[1];
                                                $aula=$datos[2];
                                                $mate=$datos[3];
                                                $doc=$datos[4];
                                                $subgru=$datos[5];
                                                $t_cla=$datos[6];
                                                $f_car=$datos[10];
                                                $obser=$datos[11];
                                                if ($t_cla=='2' || $t_cla=='4') {
                                                    if ($t_cla=='4') {
                                                        $fondo="#f6eb55";
                                                    }else{
                                                        $fondo="EEE";
                                                    }
                                                    echo "<div style='background-color:".$fondo.";'>";

                                                    $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                    $q_nbmat->execute(); 
                                                    while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                      echo  $matenb=$fhrsmt['Descripcion'];
                                                    } 
                                                    echo "<br>";
                                                    echo $h_i." - ".$h_s;                                                    
                                                    echo "<br>";
                                                    $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                    $q_nbta->execute();
                                                    if ($q_nbta->rowCount()>0) {
                                                        while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                            $nb=$fhrdoc['nombres'];
                                                            $ap=$fhrdoc['apellidos'];
                                                        }
                                                        $nb_comp=$ap." ".$nb;
                                                    }else{
                                                        $nb_comp="";
                                                    }
                                                    if ($nb_comp<>'') {
                                                        echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                    }
                                                    $nb_comp="";
                                                    if ($subgru>0) {
                                                        echo "<b>(PRACTICA)</b><br>";
                                                    }
                                                    if ($t_cla=='4') {
                                                        echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                    }
                                                    $time2=cal_tiempo($h_i,$h_s); 
                                                    $time2=(double)$time2;
                                                    $hr_acad=hr_academicas($time2);
                                                    echo "(".$hr_acad." hrs académicas)";
                                                    echo "<br>";
                                                    if ($obser!='') {
                                                        echo "<small><font color='green'>".$obser."</font></small>";
                                                    }
                                                    echo "</div>";
                                                    echo " <hr>";
                                                }
                                            }
                                        }
                                    }else{
                                        if ($d=='5') {
                                            if (count(@$viernes)>0) {
                                                foreach (@$viernes as $key => $value) {
                                                    $datos=explode(',', $value);
                                                    $h_i=$datos[0];
                                                    $h_s=$datos[1];
                                                    $aula=$datos[2];
                                                    $mate=$datos[3];
                                                    $doc=$datos[4];
                                                    $subgru=$datos[5];
                                                    $t_cla=$datos[6];
                                                    $f_car=$datos[10];
                                                    $obser=$datos[11];
                                                    if ($t_cla=='2' || $t_cla=='4') {
                                                        if ($t_cla=='4') {
                                                            $fondo="#f6eb55";
                                                        }else{
                                                            $fondo="EEE";
                                                        }
                                                        echo "<div style='background-color:".$fondo.";'>";
                                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                        $q_nbmat->execute(); 
                                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                          echo  $matenb=$fhrsmt['Descripcion'];
                                                        } 
                                                        echo "<br>";
                                                        echo $h_i." - ".$h_s;                                                    
                                                        echo "<br>";
                                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                        $q_nbta->execute();
                                                        if ($q_nbta->rowCount()>0) {
                                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                $nb=$fhrdoc['nombres'];
                                                                $ap=$fhrdoc['apellidos'];
                                                            }
                                                            $nb_comp=$ap." ".$nb;
                                                        }else{
                                                            $nb_comp="";
                                                        }
                                                        if ($nb_comp<>'') {
                                                            echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                        }
                                                        $nb_comp="";
                                                        if ($subgru>0) {
                                                            echo "<b>(PRACTICA)</b><br>";
                                                        }
                                                        if ($t_cla=='4') {
                                                            echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                        }
                                                        $time2=cal_tiempo($h_i,$h_s); 
                                                        $time2=(double)$time2;
                                                        $hr_acad=hr_academicas($time2);
                                                        echo "(".$hr_acad." hrs académicas)";
                                                        echo "<br>";
                                                        if ($obser!='') {
                                                            echo "<small><font color='green'>".$obser."</font></small>";
                                                        }
                                                        echo "</div>";
                                                        echo " <hr>";
                                                    }
                                                }
                                            }
                                        }else{
                                            if ($d=='6') {
                                                if (count(@$sabado)>0) {
                                                    foreach (@$sabado as $key => $value) {
                                                        $datos=explode(',', $value);
                                                        $h_i=$datos[0];
                                                        $h_s=$datos[1];
                                                        $aula=$datos[2];
                                                        $mate=$datos[3];
                                                        $doc=$datos[4];
                                                        $subgru=$datos[5];
                                                        $t_cla=$datos[6];
                                                        $f_car=$datos[10];
                                                        $obser=$datos[11];
                                                        if ($t_cla=='2' || $t_cla=='4') {
                                                            if ($t_cla=='4') {
                                                                $fondo="#f6eb55";
                                                            }else{
                                                                $fondo="EEE";
                                                            }
                                                            echo "<div style='background-color:".$fondo.";'>";

                                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                            $q_nbmat->execute(); 
                                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                              echo  $matenb=$fhrsmt['Descripcion'];
                                                            }
                                                            echo "<br>";
                                                            echo $h_i." - ".$h_s;
                                                            echo "<br>";
                                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                            $q_nbta->execute();
                                                            if ($q_nbta->rowCount()>0) {
                                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                    $nb=$fhrdoc['nombres'];
                                                                    $ap=$fhrdoc['apellidos'];
                                                                }
                                                                $nb_comp=$ap." ".$nb;
                                                            }else{
                                                                $nb_comp="";
                                                            }
                                                            if ($nb_comp<>'') {
                                                                echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                            }
                                                            $nb_comp="";
                                                            if ($subgru>0) {
                                                                echo "<b>(PRACTICA)</b><br>";
                                                            }
                                                            if ($t_cla=='4') {
                                                                echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                            }
                                                            $time2=cal_tiempo($h_i,$h_s); 
                                                            $time2=(double)$time2;
                                                            $hr_acad=hr_academicas($time2);
                                                            echo "(".$hr_acad." hrs académicas)";
                                                            echo "<br>";
                                                            if ($obser!='') {
                                                                echo "<small><font color='green'>".$obser."</font></small>";
                                                            }
                                                            echo "</div>";
                                                            echo " <hr>";
                                                        }
                                                    }
                                                }
                                            }else{
                                                if ($d=='7') {
                                                    if (count(@$domingo)>0) {
                                                        foreach (@$domingo as $key => $value) {
                                                            $datos=explode(',', $value);
                                                            $h_i=$datos[0];
                                                            $h_s=$datos[1];
                                                            $aula=$datos[2];
                                                            $mate=$datos[3];
                                                            $doc=$datos[4];
                                                            $subgru=$datos[5];
                                                            $t_cla=$datos[6];
                                                            $f_car=$datos[10];
                                                            $obser=$datos[11];
                                                            if ($t_cla=='2' || $t_cla=='4') {
                                                                if ($t_cla=='4') {
                                                                    $fondo="#f6eb55";
                                                                }else{
                                                                    $fondo="EEE";
                                                                }
                                                                echo "<div style='background-color:".$fondo.";'>";
                                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                                $q_nbmat->execute(); 
                                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                                }
                                                                echo "<br>";
                                                                echo $h_i." - ".$h_s;
                                                                echo "<br>";
                                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                                $q_nbta->execute();
                                                                if ($q_nbta->rowCount()>0) {
                                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                        $nb=$fhrdoc['nombres'];
                                                                        $ap=$fhrdoc['apellidos'];
                                                                    }
                                                                    $nb_comp=$ap." ".$nb;
                                                                }else{
                                                                    $nb_comp="";
                                                                }
                                                                if ($nb_comp<>'') {
                                                                    echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                                }
                                                                $nb_comp="";
                                                                if ($subgru>0) {
                                                                    echo "<b>(PRACTICA)</b><br>";
                                                                }
                                                                if ($t_cla=='4') {
                                                                    echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                                }
                                                                $time2=cal_tiempo($h_i,$h_s); 
                                                                $time2=(double)$time2;
                                                                $hr_acad=hr_academicas($time2);
                                                                echo "(".$hr_acad." hrs académicas)";
                                                                echo "<br>";
                                                                if ($obser!='') {
                                                                    echo "<small><font color='green'>".$obser."</font></small>";
                                                                }
                                                                echo "</div>";
                                                                echo " <hr>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } 
                        ?>
                        </div>
                    </td>
                    <?php
                    $doc=0;
                }
                ?>
            </tr>
        </table>
        </div>
        <!-- FIN clases teoricas virtuales-->

        <!-- ESTATICO PARA SEMESTRE 7 Y 10 MEDICINA-->
        <?php
        if (($sem==7 or $sem==8 or $sem==9 or $sem==10) and $carrera=="MEDICINA") {
            # code...               
        ?>
        <table class="table table-bordered table-sm">
            <tr>
                <th colspan="7" class="table-success"><div align="center"><h4>PRÁCTICAS HOSPITALARIAS INTEGRALES</h4></div></th>
            </tr>
            <tr class="table-success">
                 <th width="14.28%"><h6>Lunes</h6></th>
                 <th width="14.28%"><h6>Martes</h6></th>
                 <th width="14.28%"><h6>Miercoles</h6></th>
                 <th width="14.28%"><h6>Jueves</h6></th>
                 <th width="14.28%"><h6>Viernes</h6></th>
                 <th width="14.28%"><h6>Sábado</h6></th>
                 <th width="14.28%"><h6>Domingo</h6></th>
            </tr>
            <tr>
                <td><div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                     08:00 - 9:30<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    09:45 - 11:15<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    11:30 - 12:15
                </td>
                <td><div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                     08:00 - 9:30<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    09:45 - 11:15<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    11:30 - 12:15
                </td>
                <td><div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                     08:00 - 9:30<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    09:45 - 11:15<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    11:30 - 12:15
                </td>
                <td><div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                     08:00 - 9:30<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    09:45 - 11:15<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    11:30 - 12:15
                </td>
                <td><div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                     08:00 - 9:30<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    09:45 - 11:15<br>
                    <hr>
                    <div align="center">PRÁCTICAS HOSPITALARIAS INTEGRALES</div>
                    11:30 - 12:15
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>         
        <?php
         }
        ?>
        <!-- FIN ESTATICO PARA SEMESTRE 7 Y 10 MEDICINA-->
        <!-- INICIO clases practicas presenciales-->
       <!-- <div id="tbl_practico">
        <table class="table table-bordered table-sm">
            <tr>
                <th colspan="7" class="table-success" style="background-image:url('fondo.png');"><div align="center"><h4>TURNO TARDE - CLASES PRÁCTICAS PRESENCIALES</h4></div></th>
            </tr>
            <tr class="table-success">
                <?php 
                for ($i=1; $i <=7 ; $i++) { 
                    ?>
                    <th width="14.28%" style="background-image:url('fondo.png');"><h6><?php echo strtoupper(nb_dia($i)); ?></h6></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php 
                $franja=2;
                $doc=0;
                for ($d=1; $d <=7 ; $d++) { 
                    //$dia=$nba->nb_dia($d);
                    ?>
                    <td data-toggle="modal">
                        <div id="cont_prac<?php echo $d; ?>">
                        <?php
                        if ($d=='1') {
                            if (count(@$lunes)>0) {
                                foreach (@$lunes as $key => $value) {
                                    $datos=explode(',', $value);
                                    $h_i=$datos[0];
                                    $h_s=$datos[1];
                                    $aula=$datos[2];
                                    $mate=$datos[3];
                                    $doc=$datos[4];
                                    $subgru=$datos[5];
                                    $t_cla=$datos[6];
                                    $f_car=$datos[10];
                                    if ($t_cla=='3') {
                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                        $q_nbmat->execute(); 
                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                          echo  $matenb=$fhrsmt['Descripcion'];
                                        }  
                                        echo "<br>";
                                        echo $h_i." - ".$h_s;
                                        echo "<br>";
                                        echo "Aula:".$datos[9];
                                        echo "<br>";
                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                        $q_nbta->execute();
                                        if ($q_nbta->rowCount()>0) {
                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                $nb=$fhrdoc['nombres'];
                                                $ap=$fhrdoc['apellidos'];
                                            }
                                            $nb_comp=$ap." ".$nb;
                                        }else{
                                            $nb_comp="";
                                        }
                                        if ($nb_comp<>'') {
                                            echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                        }
                                        $nb_comp="";
                                        if ($subgru>0) {
                                            $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                            $nb_sgq->execute(); 
                                            while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                               $nb_sg=$fhd['Descripcion'];
                                            }
                                            //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                            echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                        }
                                        $time2=cal_tiempo($h_i,$h_s); 
                                        $time2=(double)$time2;
                                        $hr_acad=hr_academicas($time2);
                                        echo "(".$hr_acad." hrs académicas)";
                                        echo "<br>";
                                        echo " <hr>";
                                    }                                    
                                }
                            }
                        }else{
                            if ($d=='2') {
                                if (count(@$martes)>0) {
                                    foreach (@$martes as $key => $value) {
                                        $datos=explode(',', $value);
                                        $h_i=$datos[0];
                                        $h_s=$datos[1];
                                        $aula=$datos[2];
                                        $mate=$datos[3];
                                        $doc=$datos[4];
                                        $subgru=$datos[5];
                                        $t_cla=$datos[6];
                                        $f_car=$datos[10];
                                        if ($t_cla=='3') {
                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                            $q_nbmat->execute(); 
                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                              echo  $matenb=$fhrsmt['Descripcion'];
                                            }
                                            echo "<br>";
                                            echo $h_i." - ".$h_s;
                                            echo "<br>";
                                            echo "Aula:".$datos[9];
                                            echo "<br>";                                            
                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                            $q_nbta->execute();
                                            if ($q_nbta->rowCount()>0) {
                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                    $nb=$fhrdoc['nombres'];
                                                    $ap=$fhrdoc['apellidos'];
                                                }
                                                $nb_comp=$ap." ".$nb;
                                            }else{
                                                $nb_comp="";
                                            }
                                            if ($nb_comp<>'') {
                                                echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                            }
                                            $nb_comp="";
                                            if ($subgru>0) {
                                                $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                $nb_sgq->execute(); 
                                                while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                   $nb_sg=$fhd['Descripcion'];
                                                }
                                                //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                echo "<b>(PRACTICA ".$nb_sg.")</b><br>";

                                            }
                                            $time2=cal_tiempo($h_i,$h_s); 
                                            $time2=(double)$time2;
                                            $hr_acad=hr_academicas($time2);
                                            echo "(".$hr_acad." hrs académicas)";
                                            echo "<br>";
                                            echo " <hr>";
                                        }
                                    }
                                }
                            }else{
                                if ($d=='3') {
                                    if (count(@$miercoles)>0) {
                                        foreach (@$miercoles as $key => $value) {
                                            $datos=explode(',', $value);
                                            $h_i=$datos[0];
                                            $h_s=$datos[1];
                                            $aula=$datos[2];
                                            $mate=$datos[3];
                                            $doc=$datos[4];
                                            $subgru=$datos[5];
                                            $t_cla=$datos[6];
                                            $f_car=$datos[10];
                                            if ($t_cla=='3') {
                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                $q_nbmat->execute(); 
                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                }
                                                echo "<br>";
                                                echo $h_i." - ".$h_s;
                                                echo "<br>";
                                                echo "Aula:".$datos[9];
                                                echo "<br>";
                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                $q_nbta->execute();
                                                if ($q_nbta->rowCount()>0) {
                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                        $nb=$fhrdoc['nombres'];
                                                        $ap=$fhrdoc['apellidos'];
                                                    }
                                                    $nb_comp=$ap." ".$nb;
                                                }else{
                                                    $nb_comp="";
                                                }
                                                if ($nb_comp<>'') {
                                                    echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                }
                                                $nb_comp="";
                                                if ($subgru>0) {
                                                    $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                    $nb_sgq->execute(); 
                                                    while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                       $nb_sg=$fhd['grupo'];
                                                    }
                                                //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                                }
                                                $time2=cal_tiempo($h_i,$h_s); 
                                                $time2=(double)$time2;
                                                $hr_acad=hr_academicas($time2);
                                                echo "(".$hr_acad." hrs académicas)";
                                                echo "<br>";
                                                echo " <hr>";
                                            }
                                        }
                                    }
                                }else{
                                    if ($d=='4') {
                                        if (count(@$jueves)>0) {
                                            foreach ($jueves as $key => $value) {
                                                $datos=explode(',', $value);
                                                $h_i=$datos[0];
                                                $h_s=$datos[1];
                                                $aula=$datos[2];
                                                $mate=$datos[3];
                                                $doc=$datos[4];
                                                $subgru=$datos[5];
                                                $t_cla=$datos[6];
                                                $f_car=$datos[10];
                                                if ($t_cla=='3') {
                                                    $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                    $q_nbmat->execute(); 
                                                    while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                      echo  $matenb=$fhrsmt['Descripcion'];
                                                    }
                                                    echo "<br>";
                                                    echo $h_i." - ".$h_s;
                                                    echo "<br>";
                                                    echo "Aula:".$datos[9];
                                                    echo "<br>";
                                                    $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                    $q_nbta->execute();
                                                    if ($q_nbta->rowCount()>0) {
                                                        while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                            $nb=$fhrdoc['nombres'];
                                                            $ap=$fhrdoc['apellidos'];
                                                        }
                                                        $nb_comp=$ap." ".$nb;
                                                    }else{
                                                        $nb_comp="";
                                                    }
                                                    if ($nb_comp<>'') {
                                                        echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                    }
                                                    $nb_comp="";
                                                    if ($subgru>0) {
                                                        $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                        $nb_sgq->execute(); 
                                                        while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                           $nb_sg=$fhd['grupo'];
                                                        }
                                                        //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                        echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                                    }
                                                    $time2=cal_tiempo($h_i,$h_s); 
                                                    $time2=(double)$time2;
                                                    $hr_acad=hr_academicas($time2);
                                                    echo "(".$hr_acad." hrs académicas)";
                                                    echo "<br>";
                                                    echo " <hr>";
                                                }
                                            }
                                        }
                                    }else{
                                        if ($d=='5') {
                                            if (count(@$viernes)>0) {
                                                foreach (@$viernes as $key => $value) {
                                                    $datos=explode(',', $value);
                                                    $h_i=$datos[0];
                                                    $h_s=$datos[1];
                                                    $aula=$datos[2];
                                                    $mate=$datos[3];
                                                    $doc=$datos[4];
                                                    $subgru=$datos[5];
                                                    $t_cla=$datos[6];
                                                    $f_car=$datos[10];
                                                    if ($t_cla=='3') {
                                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                        $q_nbmat->execute(); 
                                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                          echo  $matenb=$fhrsmt['Descripcion'];
                                                        }
                                                        echo "<br>";
                                                        echo $h_i." - ".$h_s;
                                                        echo "<br>";
                                                        echo "Aula:".$datos[9];
                                                        echo "<br>";
                                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                        $q_nbta->execute();
                                                        if ($q_nbta->rowCount()>0) {
                                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                $nb=$fhrdoc['nombres'];
                                                                $ap=$fhrdoc['apellidos'];
                                                            }
                                                            $nb_comp=$ap." ".$nb;
                                                        }else{
                                                            $nb_comp="";
                                                        }
                                                        
                                                        if ($nb_comp<>'') {
                                                            echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                        }
                                                        $nb_comp="";
                                                        if ($subgru>0) {
                                                            $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                            $nb_sgq->execute(); 
                                                            while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                               $nb_sg=$fhd['grupo'];
                                                            }
                                                            //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                            echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                                        }
                                                        $time2=cal_tiempo($h_i,$h_s); 
                                                        $time2=(double)$time2;
                                                        $hr_acad=hr_academicas($time2);
                                                        echo "(".$hr_acad." hrs académicas)";
                                                        echo "<br>";
                                                        echo " <hr>";
                                                    }
                                                }
                                            }
                                        }else{
                                            if ($d=='6') {
                                                if (count(@$sabado)>0) {
                                                    foreach (@$sabado as $key => $value) {
                                                        $datos=explode(',', $value);
                                                        $h_i=$datos[0];
                                                        $h_s=$datos[1];
                                                        $aula=$datos[2];
                                                        $mate=$datos[3];
                                                        $doc=$datos[4];
                                                        $subgru=$datos[5];
                                                        $t_cla=$datos[6];
                                                        $f_car=$datos[10];
                                                        if ($t_cla=='3') {
                                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                            $q_nbmat->execute(); 
                                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                              echo  $matenb=$fhrsmt['Descripcion'];
                                                            }
                                                            echo "<br>";
                                                            echo $h_i." - ".$h_s;
                                                            echo "<br>";
                                                            echo "Aula:".$datos[9];
                                                            echo "<br>";
                                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                            $q_nbta->execute();
                                                            if ($q_nbta->rowCount()>0) {
                                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                    $nb=$fhrdoc['nombres'];
                                                                    $ap=$fhrdoc['apellidos'];
                                                                }
                                                                $nb_comp=$ap." ".$nb;
                                                            }else{
                                                                $nb_comp="";
                                                            }
                                                            if ($nb_comp<>'') {
                                                                echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                            }
                                                            $nb_comp="";
                                                            if ($subgru>0) {
                                                                $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                                $nb_sgq->execute(); 
                                                                while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                                   $nb_sg=$fhd['grupo'];
                                                                }
                                                                //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                                echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                                            }
                                                            $time2=cal_tiempo($h_i,$h_s); 
                                                            $time2=(double)$time2;
                                                            $hr_acad=hr_academicas($time2);
                                                            echo "(".$hr_acad." hrs académicas)";
                                                            echo "<br>";
                                                            echo " <hr>";
                                                        }
                                                    }
                                                }
                                            }else{
                                                if ($d=='7') {
                                                    if (count(@$domingo)>0) {
                                                        foreach (@$domingo as $key => $value) {
                                                            $datos=explode(',', $value);
                                                            $h_i=$datos[0];
                                                            $h_s=$datos[1];
                                                            $aula=$datos[2];
                                                            $mate=$datos[3];
                                                            $doc=$datos[4];
                                                            $subgru=$datos[5];
                                                            $t_cla=$datos[6];
                                                            $f_car=$datos[10];
                                                            if ($t_cla=='3') {
                                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                                $q_nbmat->execute(); 
                                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                                }
                                                                echo "<br>";
                                                                echo $h_i." - ".$h_s;
                                                                echo "<br>";
                                                                echo "Aula:".$datos[9];
                                                                echo "<br>";
                                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                                $q_nbta->execute();
                                                                if ($q_nbta->rowCount()>0) {
                                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                        $nb=$fhrdoc['nombres'];
                                                                        $ap=$fhrdoc['apellidos'];
                                                                    }
                                                                    $nb_comp=$ap." ".$nb;
                                                                }else{
                                                                    $nb_comp="";
                                                                }
                                                                if ($nb_comp<>'') {
                                                                    echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                                }
                                                                $nb_comp="";
                                                                if ($subgru>0) {
                                                                    $nb_sgq=$bdcon->prepare("SELECT grupo FROM grupos_sub where cod='$subgru'");
                                                                    $nb_sgq->execute(); 
                                                                    while ($fhd = $nb_sgq->fetch(PDO::FETCH_ASSOC)){    
                                                                       $nb_sg=$fhd['grupo'];
                                                                    }
                                                                    //$nb_sg=$cons->cons_simple('grupos_sub',"cod='$subgru'",'grupo');
                                                                    echo "<b>(PRACTICA ".$nb_sg.")</b><br>";
                                                                }
                                                                $time2=cal_tiempo($h_i,$h_s); 
                                                                $time2=(double)$time2;
                                                                $hr_acad=hr_academicas($time2);
                                                                echo "(".$hr_acad." hrs académicas)";
                                                                echo "<br>";
                                                                echo " <hr>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } 
                        ?>
                        </div>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        </div>-->
        <!-- FIN clases practicas presenciales-->
        <!-- INICIO clases asincronicas-->
        <div id="tbl_asincronico">
        <table class="table table-bordered table-sm">
            <tr>
                <th colspan="7" class="table-success" style="background-image:url('fondo.png');"><div align="center"><h4>ACTIVIDADES DE AUTOAPRENDIZAJE - ASINCRÓNICAS</h4></div></th>
            </tr>
            <tr class="table-success">
                <?php 
                for ($i=1; $i <=7 ; $i++) { 
                    ?>
                    <th width="14.28%" style="background-image:url('fondo.png');"><h6><?php echo strtoupper(nb_dia($i)); ?></h6></th>
                    <?php
                }
                ?>
            </tr>
            <tr style="background-color: #F1F1D9;">
                <?php 
                $franja=3;
                for ($d=1; $d <=7 ; $d++) { 
                    //$dia=$nba->nb_dia($d);
                    ?>
                    <td>
                        <div id="cont_asinc<?php echo $d; ?>">
                        <?php
                        if ($d=='1') {
                            if (count(@$lunes)>0) {
                                foreach (@$lunes as $key => $value) {
                                    $datos=explode(',', $value);
                                    $h_i=$datos[0];
                                    $h_s=$datos[1];
                                    $aula=$datos[2];
                                    $mate=$datos[3];
                                    $doc=$datos[4];
                                    $subgru=$datos[5];
                                    $t_cla=$datos[6];
                                    $f_car=$datos[10];
                                    $obser=$datos[11];
                                    if ($t_cla=='5' || $t_cla=='6') {
                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                        $q_nbmat->execute(); 
                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                          echo  $matenb=$fhrsmt['Descripcion'];
                                        }
                                       // echo $h_i." - ".$h_s;
                                        //echo "<br>";
                                        //echo "Aula:".$datos[9];
                                        echo "<br>";
                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                        $q_nbta->execute();
                                        if ($q_nbta->rowCount()>0) {
                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                $nb=$fhrdoc['nombres'];
                                                $ap=$fhrdoc['apellidos'];
                                            }
                                            $nb_comp=$ap." ".$nb;
                                        }else{
                                            $nb_comp="";
                                        }
                                        if ($nb_comp<>'') {
                                           // echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                        }
                                        $nb_comp="";
                                        if ($subgru>0) {
                                            echo "<b>(PRACTICA)</b><br>";
                                        }
                                        if ($t_cla=='4') {
                                            echo "<b>(INVESTIGACIÓN)</b><br>";  
                                        }else{
                                            if ($t_cla=='5') {
                                                echo "<b>(Asincrónicas Teoría)</b><br>";
                                            }else{
                                                if ($t_cla=='6') {
                                                    echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                }
                                            }
                                        }
                                        $time2=cal_tiempo($h_i,$h_s); 
                                        $time2=(double)$time2;
                                        $hr_acad=hr_academicas($time2);
                                       // echo "(".$hr_acad." hrs académicas)";
                                       // echo "<br>";
                                        if ($obser!='') {
                                            echo "<small><font color='green'>".$obser."</font></small>";
                                        }
                                        echo " <hr>";
                                    }
                                    
                                }
                            }
                        }else{
                            if ($d=='2') {
                                if (count(@$martes)>0) {
                                    foreach (@$martes as $key => $value) {
                                        $datos=explode(',', $value);
                                        $h_i=$datos[0];
                                        $h_s=$datos[1];
                                        $aula=$datos[2];
                                        $mate=$datos[3];
                                        $doc=$datos[4];
                                        $subgru=$datos[5];
                                        $t_cla=$datos[6];
                                        $f_car=$datos[10];
                                        $obser=$datos[11];
                                        if ($t_cla=='5' || $t_cla=='6') {
                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                            $q_nbmat->execute(); 
                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                              echo  $matenb=$fhrsmt['Descripcion'];
                                            }
                                           // echo $h_i." - ".$h_s;
                                            //echo "<br>";
                                            //echo "Aula:".$datos[9];
                                            echo "<br>";
                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                            $q_nbta->execute();
                                            if ($q_nbta->rowCount()>0) {
                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                    $nb=$fhrdoc['nombres'];
                                                    $ap=$fhrdoc['apellidos'];
                                                }
                                                $nb_comp=$ap." ".$nb;
                                            }else{
                                                $nb_comp="";
                                            }
                                            if ($nb_comp<>'') {
                                               // echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                            }
                                            $nb_comp="";
                                            if ($subgru>0) {
                                                echo "<b>(PRACTICA)</b><br>";
                                            }
                                            if ($t_cla=='4') {
                                                echo "<b>(INVESTIGACIÓN)</b><br>";  
                                            }else{
                                                if ($t_cla=='5') {
                                                    echo "<b>(Asincrónicas Teoría)</b><br>";
                                                }else{
                                                    if ($t_cla=='6') {
                                                        echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                    }
                                                }
                                            }
                                            $time2=cal_tiempo($h_i,$h_s); 
                                            $time2=(double)$time2;
                                            $hr_acad=hr_academicas($time2);
                                          //  echo "(".$hr_acad." hrs académicas)";
                                           // echo "<br>";
                                            if ($obser!='') {
                                                echo "<small><font color='green'>".$obser."</font></small>";
                                            }
                                            echo " <hr>";
                                        }
                                    }
                                }
                            }else{
                                if ($d=='3') {
                                    if (count(@$miercoles)>0) {
                                        foreach (@$miercoles as $key => $value) {
                                            $datos=explode(',', $value);
                                            $h_i=$datos[0];
                                            $h_s=$datos[1];
                                            $aula=$datos[2];
                                            $mate=$datos[3];
                                            $doc=$datos[4];
                                            $subgru=$datos[5];
                                            $t_cla=$datos[6];
                                            $f_car=$datos[10];
                                            $obser=$datos[11];
                                            if ($t_cla=='5' || $t_cla=='6') {
                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                $q_nbmat->execute(); 
                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                }
                                                //echo $h_i." - ".$h_s;
                                                //echo "<br>";
                                                //echo "Aula:".$datos[9];
                                                echo "<br>";
                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                $q_nbta->execute();
                                                if ($q_nbta->rowCount()>0) {
                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                        $nb=$fhrdoc['nombres'];
                                                        $ap=$fhrdoc['apellidos'];
                                                    }
                                                    $nb_comp=$ap." ".$nb;
                                                }else{
                                                    $nb_comp="";
                                                }
                                                if ($nb_comp<>'') {
                                                    //echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                }
                                                $nb_comp="";
                                                if ($subgru>0) {
                                                    echo "<b>(PRACTICA)</b><br>";
                                                }
                                                if ($t_cla=='4') {
                                                    echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                }else{
                                                    if ($t_cla=='5') {
                                                        echo "<b>(Asincrónicas Teoría)</b><br>";
                                                    }else{
                                                        if ($t_cla=='6') {
                                                            echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                        }
                                                    }
                                                }
                                                $time2=cal_tiempo($h_i,$h_s); 
                                                $time2=(double)$time2;
                                                $hr_acad=hr_academicas($time2);
                                               // echo "(".$hr_acad." hrs académicas)";
                                                //echo "<br>";
                                                if ($obser!='') {
                                                    echo "<small><font color='green'>".$obser."</font></small>";
                                                }
                                                echo " <hr>";
                                            }
                                        }
                                    }
                                }else{
                                    if ($d=='4') {
                                        if (count(@$jueves)>0) {
                                            foreach ($jueves as $key => $value) {
                                                $datos=explode(',', $value);
                                                $h_i=$datos[0];
                                                $h_s=$datos[1];
                                                $aula=$datos[2];
                                                $mate=$datos[3];
                                                $doc=$datos[4];
                                                $subgru=$datos[5];
                                                $t_cla=$datos[6];
                                                $f_car=$datos[10];
                                                $obser=$datos[11];
                                                if ($t_cla=='5' || $t_cla=='6') {
                                                    $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                    $q_nbmat->execute(); 
                                                    while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                      echo  $matenb=$fhrsmt['Descripcion'];
                                                    }
                                                    //echo $h_i." - ".$h_s;
                                                    //echo "<br>";
                                                    //echo "Aula:".$datos[9];
                                                    echo "<br>";
                                                    $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                    $q_nbta->execute();
                                                    if ($q_nbta->rowCount()>0) {
                                                        while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                            $nb=$fhrdoc['nombres'];
                                                            $ap=$fhrdoc['apellidos'];
                                                        }
                                                        $nb_comp=$ap." ".$nb;
                                                    }else{
                                                        $nb_comp="";
                                                    }
                                                    if ($nb_comp<>'') {
                                                       // echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                    }
                                                    $nb_comp="";
                                                    if ($subgru>0) {
                                                        echo "<b>(PRACTICA)</b><br>";
                                                    }
                                                    if ($t_cla=='4') {
                                                        echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                    }else{
                                                        if ($t_cla=='5') {
                                                            echo "<b>(Asincrónicas Teoría)</b><br>";
                                                        }else{
                                                            if ($t_cla=='6') {
                                                                echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                            }
                                                        }
                                                    }
                                                    $time2=cal_tiempo($h_i,$h_s); 
                                                    $time2=(double)$time2;
                                                    $hr_acad=hr_academicas($time2);
                                                    //echo "(".$hr_acad." hrs académicas)";
                                                    //echo "<br>";
                                                    if ($obser!='') {
                                                        echo "<small><font color='green'>".$obser."</font></small>";
                                                    }
                                                    echo " <hr>";
                                                }
                                            }
                                        }
                                    }else{
                                        if ($d=='5') {
                                            if (count(@$viernes)>0) {
                                                foreach (@$viernes as $key => $value) {
                                                    $datos=explode(',', $value);
                                                    $h_i=$datos[0];
                                                    $h_s=$datos[1];
                                                    $aula=$datos[2];
                                                    $mate=$datos[3];
                                                    $doc=$datos[4];
                                                    $subgru=$datos[5];
                                                    $t_cla=$datos[6];
                                                    $f_car=$datos[10];
                                                    $obser=$datos[11];
                                                    if ($t_cla=='5' || $t_cla=='6') {
                                                        $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                        $q_nbmat->execute(); 
                                                        while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                          echo  $matenb=$fhrsmt['Descripcion'];
                                                        }
                                                        //echo $h_i." - ".$h_s;
                                                        //echo "<br>";
                                                        //echo "Aula:".$datos[9];
                                                        echo "<br>";
                                                        $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                        $q_nbta->execute();
                                                        if ($q_nbta->rowCount()>0) {
                                                            while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                $nb=$fhrdoc['nombres'];
                                                                $ap=$fhrdoc['apellidos'];
                                                            }
                                                            $nb_comp=$ap." ".$nb;
                                                        }else{
                                                            $nb_comp="";
                                                        }
                                                        if ($nb_comp<>'') {
                                                           // echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                        }
                                                        $nb_comp="";
                                                        if ($subgru>0) {
                                                            echo "<b>(PRACTICA)</b><br>";
                                                        }
                                                        if ($t_cla=='4') {
                                                            echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                        }else{
                                                            if ($t_cla=='5') {
                                                                echo "<b>(Asincrónicas Teoría)</b><br>";
                                                            }else{
                                                                if ($t_cla=='6') {
                                                                    echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                                }
                                                            }
                                                        }
                                                        $time2=cal_tiempo($h_i,$h_s); 
                                                        $time2=(double)$time2;
                                                        $hr_acad=hr_academicas($time2);
                                                        //echo "(".$hr_acad." hrs académicas)";
                                                        //echo "<br>";
                                                        if ($obser!='') {
                                                            echo "<small><font color='green'>".$obser."</font></small>";
                                                        }
                                                        echo " <hr>";
                                                    }
                                                }
                                            }
                                        }else{
                                            if ($d=='6') {
                                                if (count(@$sabado)>0) {
                                                    foreach (@$sabado as $key => $value) {
                                                        $datos=explode(',', $value);
                                                        $h_i=$datos[0];
                                                        $h_s=$datos[1];
                                                        $aula=$datos[2];
                                                        $mate=$datos[3];
                                                        $doc=$datos[4];
                                                        $subgru=$datos[5];
                                                        $t_cla=$datos[6];
                                                        $f_car=$datos[10];
                                                        $obser=$datos[11];
                                                        if ($t_cla=='5' || $t_cla=='6') {
                                                            $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                            $q_nbmat->execute(); 
                                                            while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                              echo  $matenb=$fhrsmt['Descripcion'];
                                                            }
                                                            //echo $h_i." - ".$h_s;
                                                            //echo "<br>";
                                                            //echo "Aula:".$datos[9];
                                                            echo "<br>";
                                                            $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                            $q_nbta->execute();
                                                            if ($q_nbta->rowCount()>0) {
                                                                while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                    $nb=$fhrdoc['nombres'];
                                                                    $ap=$fhrdoc['apellidos'];
                                                                }
                                                                $nb_comp=$ap." ".$nb;
                                                            }else{
                                                                $nb_comp="";
                                                            }
                                                            if ($nb_comp<>'') {
                                                               // echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                            }
                                                            $nb_comp="";
                                                            if ($subgru>0) {
                                                                echo "<b>(PRACTICA)</b><br>";
                                                            }
                                                            if ($t_cla=='4') {
                                                                echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                            }else{
                                                                if ($t_cla=='5') {
                                                                    echo "<b>(Asincrónicas Teoría)</b><br>";
                                                                }else{
                                                                    if ($t_cla=='6') {
                                                                        echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                                    }
                                                                }
                                                            }
                                                            $time2=cal_tiempo($h_i,$h_s); 
                                                            $time2=(double)$time2;
                                                            $hr_acad=hr_academicas($time2);
                                                           // echo "(".$hr_acad." hrs académicas)";
                                                           // echo "<br>";
                                                            if ($obser!='') {
                                                                echo "<small><font color='green'>".$obser."</font></small>";
                                                            }
                                                            echo " <hr>";
                                                        }
                                                    }
                                                }
                                            }else{
                                                if ($d=='7') {
                                                    if (count(@$domingo)>0) {
                                                        foreach (@$domingo as $key => $value) {
                                                            $datos=explode(',', $value);
                                                            $h_i=$datos[0];
                                                            $h_s=$datos[1];
                                                            $aula=$datos[2];
                                                            $mate=$datos[3];
                                                            $doc=$datos[4];
                                                            $subgru=$datos[5];
                                                            $t_cla=$datos[6];
                                                            $f_car=$datos[10];
                                                            $obser=$datos[11];
                                                            if ($t_cla=='5' || $t_cla=='6') {
                                                                $q_nbmat=$bdcon->prepare("SELECT Descripcion FROM materias where CodCarrera='$f_car' and Sigla='$mate' and sigla_pensum<>''");
                                                                $q_nbmat->execute(); 
                                                                while ($fhrsmt = $q_nbmat->fetch(PDO::FETCH_ASSOC)){    
                                                                  echo  $matenb=$fhrsmt['Descripcion'];
                                                                }
                                                                //echo $h_i." - ".$h_s;
                                                                //echo "<br>";
                                                                //echo "Aula:".$datos[9];
                                                                echo "<br>";
                                                                $q_nbta=$bdcon->prepare("SELECT nombres,apellidos FROM docentes where id_docente='$doc'");
                                                                $q_nbta->execute();
                                                                if ($q_nbta->rowCount()>0) {
                                                                    while ($fhrdoc = $q_nbta->fetch(PDO::FETCH_ASSOC)){    
                                                                        $nb=$fhrdoc['nombres'];
                                                                        $ap=$fhrdoc['apellidos'];
                                                                    }
                                                                    $nb_comp=$ap." ".$nb;
                                                                }else{
                                                                    $nb_comp="";
                                                                }
                                                                if ($nb_comp<>'') {
                                                                  //  echo "<div class='chicas'><b>DOC: ".$nb_comp."</b></div>";
                                                                }
                                                                $nb_comp="";
                                                                if ($subgru>0) {
                                                                    echo "<b>(PRACTICA)</b><br>";
                                                                }
                                                                if ($t_cla=='4') {
                                                                    echo "<b>(INVESTIGACIÓN)</b><br>";  
                                                                }else{
                                                                    if ($t_cla=='5') {
                                                                        echo "<b>(Asincrónicas Teoría)</b><br>";
                                                                    }else{
                                                                        if ($t_cla=='6') {
                                                                            echo "<b>(Asincrónicas Prácticas)</b><br>";
                                                                        }
                                                                    }
                                                                }
                                                                $time2=cal_tiempo($h_i,$h_s); 
                                                                $time2=(double)$time2;
                                                                $hr_acad=hr_academicas($time2);
                                                               // echo "(".$hr_acad." hrs académicas)";
                                                                //echo "<br>";
                                                                if ($obser!='') {
                                                                    echo "<small><font color='green'>".$obser."</font></small>";
                                                                }
                                                                echo " <hr>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } 
                        ?>
                        </div>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        </div>
        <div id="tbl_texto">
            <table class="table">
                <tr>
                    <th class="table-success" style="background-image:url('fondo.png');">
                        <?php 
                        if ($vec_txt!='') {
                            $partir=explode("|", $vec_txt);
                            foreach ($partir as $key => $value) {
                                $part_peq=explode(",", $value);
                                $txt_mos=$part_peq[0];
                                $pos_mos=$part_peq[1];
                                if ($pos_mos=='1') {
                                    echo strtoupper($txt_mos);
                                    echo "<br>";
                                }
                            }
                        }
                        ?>
                        <div align="left">
                        CLASES TEÓRICAS VIRTUALES: DEL <?php echo formato_fecha($fec_ini_teo); ?> AL <?php echo formato_fecha($fec_fin_teo); ?> DEL <?php echo saca_gest($fec_fin_teo); ?><br>
                        ACTIVIDADES DE AUTOAPRENDIZAJE ASINCRÓNICAS TEÓRICAS: DEL <?php echo formato_fecha($fec_ini_asi_teo); ?> AL <?php echo formato_fecha($fec_fin_asi_teo); ?> DEL <?php echo saca_gest($fec_fin_asi_teo); ?><br>

                        <?php 
                            if ($fec_prac_ini=="") {
                                //echo "No tiene horario practicas";
                            }else{
                        ?>
                            PRACTICAS PRESENCIALES: DEL <?php echo formato_fecha($fec_prac_ini); ?> AL <?php echo formato_fecha($fec_prac_fin); ?> DEL <?php echo saca_gest($fec_prac_fin); ?><br>

                            ACTIVIDADES DE AUTOAPRENDIZAJE ASINCRÓNICAS PRÁCTICAS: DEL <?php echo formato_fecha($fec_prac_ini); ?> AL <?php echo formato_fecha($fec_fin_asi_teo); ?> DEL <?php echo saca_gest($fec_prac_fin); ?><br>
                        <?php
                            }
                        ?>
                        

                        INVESTIGACIÓN: DEL <?php echo formato_fecha($fec_inv_ini); ?> AL <?php echo formato_fecha($fec_inv_fin); ?> DEL <?php echo saca_gest($fec_inv_fin); ?>

                        </div><br>
                        <?php 
                        if ($vec_txt!='') {
                            $partir=explode("|", $vec_txt);
                            foreach ($partir as $key => $value) {
                                $part_peq=explode(",", $value);
                                $txt_mos=$part_peq[0];
                                $pos_mos=$part_peq[1];
                                if ($pos_mos=='2') {
                                    echo strtoupper($txt_mos);
                                    echo "<br>";
                                }
                            }   
                        }

                        ?>
                        <div id="mostrar_txt_add"></div>
                    </th>
                </tr>
            </table>
            <table class="table">
                <tr>
                    <th width="35%">ACTIVIDADES EVALUADAS EN LÍNEA</th>
                    <th width="30%">CALENDARIO AJUSTADO DE EVALUACIONES</th>
                    <th width="35%">ACTIVIDADES DE AUTOAPRENDIZAJE - ASINCRÓNICAS</th>
                </tr>
                <tr>
                    <td> <div align="left">
                        Evaluación continua y sumativa en la Plataforma Educativa SAINCO, de acuerdo al MODELO DEBANC, por cada sesión teórica y/o práctica, utilizando instrumentos establecidos y escalas de calificación definidas.</div>
                    </td>
                   <?php                   
                    if ($sem==3 and $gru=='TS') {
                    ?>
                    <td>
                        <div align="left">
                            <b>PRIMERA EVALUACIÓN: </b> 05 de septiembre al 11 de septiembre<br>
                        <b>SEGUNDA EVALUACIÓN: </b> 03 de octubre al 09 de octubre<br>
                        <b>TERCERA EVALUACIÓN: </b> 31 de octubre al 06 de noviembre<br>
                        <b>CUARTA EVALUACIÓN:  </b> 28 de noviembre al 04 de diciembre<br>
                        </div>
                    </td>
                    <?php
                    }else{
                   ?>
                    <td> <div align="left">
                        <b>PRIMERA EVALUACIÓN: </b> 05 de septiembre al 11 de septiembre<br>
                        <b>SEGUNDA EVALUACIÓN: </b> 03 de octubre al 09 de octubre<br>
                        <b>TERCERA EVALUACIÓN: </b> 31 de octubre al 06 de noviembre<br>
                        <b>CUARTA EVALUACIÓN:  </b> 28 de noviembre al 04 de diciembre<br>
                        </div>
                    </td>
                    <?php
                        }
                    ?>
                    <td> <div align="left">
                        Actividades de trabajo autónomo realizadas por el estudiante en la Plataforma SAINCO como complemento a las clases teóricas y prácticas, para el reforzamiento teórico y fijación de contenido procedimental, según planificación establecida para el semestre.<br>
                        La Plataforma estará disponible de 00:01 a 23:59 del día programado en cada asignatura, para que el estudiante realice las actividades de AUTOAPRENDIZAJE.</div>
                    </td>
                </tr>
            </table>
        </div>
        <!-- FIN clases asincronicas-->                             
                </div>       
            </div>                   
        </div>  
    </div> 
</div>
 
