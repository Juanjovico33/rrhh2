<?php 
include "../includes/conexion.php";
include "../includes/_files.php";
include "../includes/_event_log.php";

$_archivo=new file();
$e = new evento();
$idgrupo=$_REQUEST['id_grupo'];
$_archivo->getDatosFile($idgrupo);

$url="";
$url=$_archivo->getUrl();
$e->setIdGrupo($idgrupo);
$e->setEnlace($url);
$e->e_log_inicio_evento($cod_est, 20);
    if ( $url=='') {       
       ?>
            <div class="alert alert-danger" role="alert">No tiene registrado su guía consulte con su jefatura de carrera</div>
       <?php
    }else{
        $dir="https://storage.googleapis.com/une_segmento-one/docentes/".$url."#zoom=100";
        // $dir=$url."#zoom=100";
           ?>
        <div uk-grid>
            <div class="uk-width-6-7@m">
                <div class="blog-post single-post">
                    <div class="blog-post-content">
                        <div align="center">
                            <object data="<?php echo $dir;?>" type="application/pdf" width="100%" height="750px">
                                <embed src="<?php echo $dir;?>" type="application/pdf">
                                    <p>Este navegador no admite archivos PDF. Descargue el PDF para verlo: <a href="<?php echo $dir;?> " target="_blank">Descargar PDF</a>.</p>
                                </embed>
                            </object>
                            <!--<embed src="<?php //echo $dir;?>?embedded=true" width="100%" height="720">-->
                        </div>       
                    </div>                   
                </div>  
            </div> 
        </div>
        <?php
    }
?>

 
