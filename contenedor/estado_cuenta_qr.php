<?php 
$codest=$_REQUEST['_codest'];    
$semestre=$_REQUEST['_semestre'];    
$carrera=$_REQUEST['_carrera'];
$cuota=$_REQUEST['_cuota'];
$tipo_cuota=$_REQUEST['_tipoCuota'];
$monto=$_REQUEST['_monto'];
$periodo=$_REQUEST['_periodo'];
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');

include "../includes/conexion.php"; 
include "../includes/api_bcp/BCPService.php";
include "../includes/_qr_ctrl_generate.php"; 

$q_idanio= $bdcon->prepare("SELECT anio from gestion where opcion=DATE_FORMAT(now(),'%Y')");
$q_idanio->execute();  
while ($row_anio = $q_idanio->fetch(PDO::FETCH_ASSOC)) { 
    $id_anio=$row_anio['anio']; 
}   

$bcp = new BCPServices();
$qr_ctrl_une = new qr_ctrl();

 $glosa="";
 $_sigla="";
 $_materia="";

 if($cuota==355){
    $tipo_tramite = $tipo_cuota;
    $q_tipotramite = $bdcon->prepare("SELECT cuenta, nb_cuenta, monto FROM sainc.plat_est_tramites_costos where cod_tramite=$tipo_tramite limit 1");
    $q_tipotramite->execute();  
    while ($row_tram = $q_tipotramite->fetch(PDO::FETCH_ASSOC)) { 
        $tipo_cuota = $row_tram['cuenta'];
        $glosa = $row_tram['nb_cuenta'];
        $monto = $row_tram['monto'];
    } 
    $cuota = 0;
 }else{
    if($tipo_cuota==77){
        $cuota=0;
        // $glosa="Pago de seguro contra accidentes"; //test
        $glosa="SEGURO CONTRA ACCIDENTES";
    }else if($tipo_cuota==38){
        // $glosa="Cuota $cuota del periodo $periodo"; // test
        $glosa="PAGO TOTAL DE LA CUOTA N° $cuota   P/estudiante";
    } else if($tipo_cuota==213){
        $glosa="CURSO DE NIVELACION CUOTA # $cuota   P/estudiante";
    } else if($tipo_cuota==75){
        $_sigla=$_REQUEST['_msigla'];    
        $_materia=$_REQUEST['_materia'];
        // $glosa="EX. REP. $_sigla $_materia ".$cuota."° PARCIAL";
        if($cuota==1 || $cuota==3){
            $cp = "er";
        }else if($cuota==2){
            $cp = "do";
        }else if($cuota==4){
            $cp = "to";
        }
        $_materia = substr($_materia, 0, 20);
        $glosa="EX REP $_sigla $_materia ".$cuota."".$cp." PARCIAL";

        $key_large = array("Á", "É", "Í", "Ó", "Ú");
        $key_short = array("A", "E", "I", "O", "U");
        $glosa = str_replace($key_large, $key_short, $glosa);
    } else if($tipo_cuota==355){
        $glosa = "SERVICIO DE PLATAFORMA";
    }
 }

    if ($tipo_cuota==75){
        $collector = array( 
            array( "Name" => "codest", "Parameter" =>  "int", "Value" => intval($codest) ),
            array( "Name" => "id_cuota", "Parameter" =>  "int", "Value" => intval($tipo_cuota) ),
            array( "Name" => "nro_cuota", "Parameter" =>  "int", "Value" => intval($cuota) ),
            array( "Name" => "periodo", "Parameter" =>  "int", "Value" => intval($periodo) ),
            array( "Name" => "carrera", "Parameter" =>  "string", "Value" => $carrera ),
            array( "Name" => "anio", "Parameter" =>  "int", "Value" => intval($id_anio) ),
            array( "Name" => "sigla", "Parameter" =>  "string", "Value" => $_sigla ),
            array( "Name" => "materia", "Parameter" =>  "string","Value" => $_materia )
        );
    }else{

        $collector = array( 
            array( "Name" => "codest", "Parameter" =>  "int", "Value" => intval($codest) ),
            array( "Name" => "id_cuota", "Parameter" =>  "int", "Value" => intval($tipo_cuota) ),
            array( "Name" => "nro_cuota",  "Parameter" =>  "int", "Value" => intval($cuota) ),
            array( "Name" => "periodo", "Parameter" =>  "int", "Value" => intval($periodo) ),
            array( "Name" => "carrera", "Parameter" =>  "string", "Value" => $carrera ),
            array( "Name" => "anio", "Parameter" =>  "int", "Value" => intval($id_anio) )
        );
    }
                                                   
?>   
<div style="text-align:center;">
   <?php 
    
        $qr_vigencia="0/00:00";

        if ($tipo_cuota==75){
            $id_correlation="$codest-$periodo-".$fecha->format('Ymd')."-$cuota"."-$tipo_cuota-".substr($_sigla, 3); // 48522-202102-20210908-1-75-400
        } else if ($tipo_cuota==355){
            // $id_correlation=$fecha->format('Y-m-d h:m:s');
            $id_correlation="$codest-$periodo-".$fecha->format('Ymd')."-$tipo_cuota-".$cuota; // 48522-202102-20210908-355-0
            $qr_vigencia="0/00:00";
        } else if ($tipo_cuota==38 || $tipo_cuota==213){
            $id_correlation="$codest-$periodo-".$fecha->format('Ymd')."-$cuota"; // 48522-202102-20210908-1
        } else{
            $id_correlation="$codest-$periodo-".$fecha->format('Ymd')."-$tipo_cuota-".$cuota; // 48522-202102-20210908-355-0
        }
        
        $qr_generado=false;
        // echo $id_correlation; exit;

        if($qr_ctrl_une->existe_qr($id_correlation)){
            $consult = $bcp->ConsultQr($qr_ctrl_une->getId_qr_bcp(), $id_correlation);
            $qr_mostrar = $consult;
            $qr_generado=false;
            // echo "QR EXISTENTE!<BR>";
        }else{
            $qr = $bcp->GeneratedQr($monto, "BOB", $glosa, $collector, $qr_vigencia, $id_correlation);
            $qr_ctrl_une->insert_qrGenerated($id_correlation, $qr->data->id, $qr_vigencia, $codest, $periodo, $cuota, $monto, "1"); // 1 = Datos generado por la produccion
            $qr_mostrar = $qr;
            $qr_generado=true;
            // echo "QR GENERADO!<BR>";
        }

        if($qr_mostrar->state=="00"){
            if(!$qr_generado){
                if($qr_mostrar->data->description!="P"){
                    echo '<div><img src="data:image/png;base64,'.$qr_mostrar->data->qrImage.'"/></div>';
                    echo '<div>Estado de la transacción : <b>'.$qr_mostrar->data->description.'</b></div>';
                }else{
                    echo '<div>Está transacción ya se ha procesado, vuelva a actualizar está pagina para verificar el registro de su pago.</div>';
                }
                
            }else{
                echo '<div><img src="data:image/png;base64,'.$qr_mostrar->data->qrImage.'"/></div>';
            }
        }else{
            echo "Se produjo el siguiente error : <br>".$qr_mostrar->message;
        }
   ?>
</div>
<div align="center">       
        <!-- <img src="<?php // echo $url;?>" width="65%">   -->
      
        <!-- <p class="uk-text-right">
            <a href="<?php //echo $url;?>" download="Qr-UNE-CUOTA-<?php //echo $cuota."-".$periodo;?>.jpeg"><button type="button" class="btn btn-success">Descargar QR - UNE </button></a> 
            
        </p>   -->
        <h4>¿Como funciona el pago QR?</h4>        
        <ul class="list-group">
          <li class="list-group-item"> <span class="badge badge-success badge-pill">1</span><br>Debes ingresar a la app de tu banco. Si aún no la tienes, ingresa al App Store o Play Store para descargarla.</li>
          <li class="list-group-item"><span class="badge badge-success badge-pill">2</span> <br>Ve a la opción Pago o Tranferancias</li>
          <li class="list-group-item"><span class="badge badge-success badge-pill">3</span> <br>Elige la opción Pagar o Transferencia con QR Simple</li>
          <li class="list-group-item"><span class="badge badge-success badge-pill">4</span> <br>Para pagar la app te pedirá escanear o seleccionar de tu galeria el código QR generado</li>
          <li class="list-group-item"> <span class="badge badge-success badge-pill">5</span> <br>Confirma el pago y realiza una captura de la transacción para luego subir la imagen en el boton Azul de Recibo</li>
        </ul>
        <button class="uk-button uk-button-danger uk-modal-close" type="button">Cerrar</button> 
</div>  
           
<!--<img src="img/qr/1.jpeg">-->     