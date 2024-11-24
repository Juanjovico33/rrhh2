<?php 
$codest=$_REQUEST['_codest'];
$buscador=$_REQUEST['_buscador'];
$carrera=$_REQUEST['_carrera'];
include "../includes/conexion.php";
$q_libros= $bdcon->prepare("SELECT titulo, descripcion, direccion, id_tipo_archivo, id_clasificacion_doc, nroedi, anopubli, conoci, asigna from aa_material_grupo WHERE  fecha>'2020-12-12' and direccion <>'' and (titulo LIKE '%$buscador%' OR asigna LIKE '%$buscador%' OR conoci LIKE '%$buscador%' OR descripcion LIKE '%$buscador%')");
$q_libros->execute(); 
$entra=0; 
while ($f_libros = $q_libros->fetch(PDO::FETCH_ASSOC)) { 
    $titulo=$f_libros['titulo'];
    $autor=$f_libros['descripcion'];
    $file=$f_libros['direccion'];
    $tipo=$f_libros['id_tipo_archivo'];
    $clasifica=$f_libros['id_clasificacion_doc'];
    $nedicion=$f_libros['nroedi'];
    $aniopu=$f_libros['anopubli'];
    $conoci=$f_libros['conoci'];
    $asigna=$f_libros['asigna'];
    $ext = pathinfo( $file,PATHINFO_EXTENSION );
    if ($tipo==6 and $file<>' ') {
        $entra=1;
        ?>
        <div align="center">
            <span class="mls"><strong>Titulo - </strong><small><?php echo $titulo;?></small></span><br>
            <small><strong>Autor - </strong><small><?php echo $autor;?></small> - <strong>Año publicado - </strong><small><?php echo $aniopu ;?></small></small>
            <small> - <strong>Edicíon</strong> <?php echo $nedicion ;?></small>           
            <div class="glyph fs1">
                <a target="_blank\" href="<?=$file;?>">
                    <div class="clearfix bshadow0 pbs">
                        <span class="icon-line-awesome-server"></span>
                        <span class="mls"> Visualizar</span>
                    </div>
                </a>
            </div>
            <hr>
        </div>
        <?php
    }else{
        if ($ext=="pptx" || $ext=="ppt" || $ext=="doc" || $ext=="docx" || $ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="pdf" || $ext=="xls" || $ext=="xlsx") {
           $entra=1; 
           $direccion_espejo="http://190.186.233.211/plataformaDocente/assets/docente/grupos/";
           $enlace=$direccion_espejo.$file;
           ?>
           <div align="center">
                <span class="mls"><strong>Titulo - </strong><small><?php echo $titulo;?></small></span><br>
                <small><strong>Autor - </strong><small><?php echo $autor;?></small> - <strong>Año publicado - </strong><small><?php echo $aniopu ;?></small></small>
                <small> - <strong>Edicíon </strong><?php echo $nedicion ;?></small>
                <div class="glyph fs1">
                <a href="#" onclick="libro_server('<?=$enlace;?>')">
                    <div class="clearfix bshadow0 pbs">
                        <span class="icon-line-awesome-server"></span>
                        <span class="mls"> Visualizar </span>
                    </div>
                </a>
                </div>
                <hr>
            </div>
            <?php
        }
    }       
}
if ($entra==1) {   
}else{
    echo "<div class='alert alert-danger'>No se encontraron resultados</div>";
}
?>                