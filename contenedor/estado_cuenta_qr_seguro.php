<?php 
    $seguro=$_REQUEST['_seguro'];
    $fecha=new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
    $nbges=$fecha->format('Y');
    $nbmes=$fecha->format('m');

    if ($nbmes<=8){
        $periodo=$nbges."01";        
    }else{
        $periodo=$nbges."02";       
    }

    include "../includes/conexion.php"; 
    $q_seguro= $bdcon->prepare("SELECT url from files_qr where periodo='$periodo' and cuota='$seguro'");
    $q_seguro->execute();  
    while ($rowseguro = $q_seguro->fetch(PDO::FETCH_ASSOC)) { 
        $url=$rowseguro['url']; 
    }                               
                     
?>
    <div align="center"> 
        <img src="<?php echo $url;?>" width="65%"> 
        <p class="uk-text-right">
           <a href="<?php echo $url;?>" download="Qr-UNE-SEGURO-<?php echo $periodo;?>.jpeg"><button type="button" class="btn btn-success">Descargar QR - UNE </button></a>
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button> 
        </p>                                         
    </div>              
    <!--<img src="img/qr/1.jpeg">-->     