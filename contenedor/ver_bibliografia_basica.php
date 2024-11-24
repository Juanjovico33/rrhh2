<?php 
include ("../includes/_actividades.php");
include ("../includes/_planificacion.php");
include ("../includes/conexion.php");
include ("../includes/_event_log.php");
$codest=$_POST['_codest'];
$id_grupo=$_POST['_idgrupo'];
$otro_grupo=$_POST['_idgruporaiz'];
$grupo = new grupo();
$e = new evento();
$e->setIdGrupo($id_grupo);
$e->e_log_inicio_evento($codest, 16);
$grupo->getDatosGrupo($id_grupo);
$_idgrupoRaiz=0;
if($otro_grupo!=0){
	if($grupo->esNivelacion()){
		$_idgrupoRaiz=$otro_grupo;
	}else{
		$_idgrupoRaiz=$grupo->getIdramaRaiz();
		if($_idgrupoRaiz==0){
			$_idgrupoRaiz=$id_grupo;
		}
	}
}else{
	$_idgrupoRaiz=$id_grupo;
}
$dirimg="https://storage.googleapis.com/une_segmento-one/estudiantes/img/iconos/";
$direccion_espejo="http://190.186.233.211/plataformaDocente/assets/docente/grupos/";
$q_libros= $bdcon->prepare("SELECT titulo, descripcion, direccion, id_clasificacion_doc, id_tipo_archivo, nroedi, anopubli, conoci, asigna from aa_material_grupo WHERE  fecha>'2020-12-12' and direccion <>'' and id_grupo='$_idgrupoRaiz'");
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
        		<?php
        		$q_libros->execute();
				$num_libros=$q_libros->rowCount();
				if ($num_libros==0) {
					echo "<div class='alert alert-danger' align='center'>Docente no registro bibliografía básica para esta materia !!!</div>";
					exit();
				}
        		?>	            			           
	            	<h4 align="center"><strong>BIBLIOGRAFÍA BÁSICA</strong></h4>
		            <?php	
		            $nu=1;						 
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
					    	?>							           
				            <div class="glyph fs1" style="background-image:url('https://storage.googleapis.com/une_segmento-one/estudiantes/img/biblioteca/fondo-cabecera.jpg');  background-repeat: no-repeat;">
				                <a target="_blank\" href="<?=$file;?>">
				                    <div class="clearfix bshadow0 pbs">
										<span class="mls"> <img src="<?=$dirimg.$nu;?>.png" class="rounded"></span>
				                        <span class="mls"><strong>Titulo - </strong><small><?php echo $titulo;?></small><br>
				            		    <strong>Autor -</strong><small><?php echo $autor;?></small> <br><strong>Año publicado -</strong><small><?php echo $aniopu ;?></small><br>
				            		    <strong>Edicíon</strong> <small><?php echo $nedicion ;?></small>
				                        </span>
				                    </div>
				                </a>
						    </div>
				            <hr>							        
							<?php
							$nu++;
					    }else{
					    	if ($ext=="pptx" || $ext=="ppt" || $ext=="doc" || $ext=="docx" || $ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="pdf" || $ext=="xls" || $ext=="xlsx") {
					    	
							    ?>									      
					                <div class="glyph fs1" style="background-image:url('https://storage.googleapis.com/une_segmento-one/estudiantes/img/biblioteca/fondo-cabecera.jpg');  background-repeat: no-repeat;">
						                <a target="_blank\" href="<?=$direccion_espejo.$file;?>">
						                    <div class="clearfix bshadow0 pbs">
												<span class="mls"> <img src="<?=$dirimg.$nu;?>.png" class="rounded"></span>
						                        <span class="mls"><strong>Titulo - </strong><small><?php echo $titulo;?></small><br>
						            		    <strong>Autor -</strong><small><?php echo $autor;?></small> <br><strong>Año publicado -</strong><small><?php echo $aniopu ;?></small><br>
						            		    <strong>Edicíon</strong> <small><?php echo $nedicion ;?></small>
						                        </span>
						                    </div>
						                </a>
						            </div>
					                <hr>
							    <?php
							    $nu++;
					    	}		
					    }    
					}						
					?>				
			</div>
		</div>
	</div>
</div>
