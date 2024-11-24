<?php 
$grupo=$_REQUEST['_grupo'];
$gruporaiz=$_REQUEST['_gruporaiz'];
include "../includes/conexion.php"; 
$fecha=new DateTime();
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
if ($nbmes<=8) {
    $periodo=$nbges."01";   
}else{
    $periodo=$nbges."02";   
}
$q_grupo= $bdcon->prepare("SELECT title, url from files_practica where (codgrupo='$grupo' or codgrupo='$gruporaiz') and codgrupo<>'0'");
$q_grupo->execute();
$url="";
$titulo="";
while ($row = $q_grupo->fetch(PDO::FETCH_ASSOC)){
         $url=$row['url'];
         $titulo=$row['title'];
}
if ($url=='') {       
   ?>
        <div class="alert alert-danger" role="alert">No tiene registrado su guía consulte con su jefatura de carrera</div>
   <?php
}else{
    $dir="https://storage.googleapis.com/une_segmento-one/docentes/".$url."#zoom=100";
       ?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">               
                    <div align="center">
                        <object data="<?php echo $dir;?>" type="application/pdf" width="100%" height="750px">
                            <embed src="<?php echo $dir;?>" type="application/pdf">
                                <p>Este navegador no admite archivos PDF. Descargue el PDF para verlo: <a href="<?php echo $dir;?>">Descargar PDF</a>.</p>
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

 
