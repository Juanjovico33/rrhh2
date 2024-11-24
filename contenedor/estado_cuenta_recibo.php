<?php
    include "../includes/conexion.php";

    $codest=$_REQUEST['_codest'];
    $periodo=$_REQUEST['periodo'];
    $tipodeuda=$_REQUEST['tipodeuda'];
    $cuota=$_REQUEST['cuota'];

    $q_recibos="SELECT id, url_imagen FROM plat_est_recibos WHERE codest=$codest AND periodo=$periodo AND nro_cuota=$cuota AND estado=1";

    $row_recibos= $bdcon->prepare($q_recibos);
    $row_recibos->execute();
    
?>

<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">

                <?php
                    if($row_recibos->rowCount()>0){
                        $_direccion="https://storage.googleapis.com/une_segmento-one/";
                        while ($row = $row_recibos->fetch(PDO::FETCH_ASSOC)) {       
                            $id=$row['id'];      
                            $url=$row['url_imagen'];                        
                         } 

                         $path = $url;
                         $file = new SplFileInfo($path);
                         $extension = $file->getExtension();
                         $name_file = $file->getFilename();

                         $_direccion=$_direccion.$url.$name_file;
                         ?><div align="center"><div id="content_recibo" name="content_recibo"><?php
                         if($extension=="jpg" || $extension=="png"){
                            ?>
                             <img src="<?php echo $_direccion;?>" width="65%">
                            <?php
                         }else if($extension=="pdf"){
                             ?>
                             <embed src="<?=$_direccion?>#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="100%" />
                             <?php
                         }else{
                             ?>
                             <a href="<?=$_direccion?>" target="_blank">Enlace de <b>Recibo</b></a>
                             <?php
                         }
                         ?> </div> <hr>
                          <p class="uk-text-right">
                            <button id="btn_quitar_recibo" name="btn_quitar_recibo" type="button" class="btn btn-danger" onclick="quitar_recibo(<?=$id?>)">Quitar Recibo</button>
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button> 
                          </p>
                          </div>
                         <?php
                    }else{
                ?>

                <h4>Subir mi recibo de la Cuota "<?=$cuota?>"</h4>
                <form id="envio_recibo" name="envio_recibo" method="post" enctype="multipart/form-data" onsubmit="return false;">
                        <input type="hidden" name="codest" id="codest" value="<?php echo $codest; ?>">
                        <input type="hidden" name="periodo" id="periodo" value="<?php echo $periodo; ?>">
                        <input type="hidden" name="tipodeuda" id="tipodeuda" value="<?php echo $tipodeuda; ?>">
                        <input type="hidden" name="cuota" id="cuota" value="<?php echo $cuota; ?>">
                        <input type="file" name="recibo" id="recibo">
                    <div id="msj">
                        <button class="btn btn-success" onclick="set_archivo_recibo_cloud()">ENVIAR</button>
                    </div>
                </form>

                <?php
                    }
                ?>

            </div>                   
        </div>  
    </div> 
</div>

