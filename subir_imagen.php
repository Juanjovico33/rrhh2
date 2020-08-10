 <!-- Favicon -->
    <link href="img/favicon.png" rel="icon" type="image/png">
    <!-- CSS 
    ================================================== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/night-mode.css">
    <link rel="stylesheet" href="css/framework.css">
    <link rel="stylesheet" href="css/bootstrap.css">
   <div class="container-small blog-article-content-read">
        <br>
        <br>
        <br>
        <div class="alert alert-success"><strong>Espere por favor, subiendo la imágen del depósito adjuntado</strong></div>
        <img src="contenedor/js/carga.gif">               
	</div>
<?php
		$codest=$_REQUEST['codest'];		
		$fecha=date('Y-m-d');
		$time=time();
		$hora=date("H:i:s", $time);	
		include "includes/SimpleImage.class.php";
		include "includes/conexion.php";
		$var_name_img = $_FILES["file_img"]["name"]; 
		$var_img_dir = "archivos/"; 
		if (move_uploaded_file($_FILES["file_img"]["tmp_name"], $var_img_dir.$var_name_img)){ 
			$subida = true; 
		}
		if ($subida === true){ 
			$obj_simpleimage = new SimpleImage(); 
			$obj_simpleimage->load($var_img_dir.$var_name_img); 
			if ( ($_FILES["file_img"]["type"]) != 'image/gif'){
				$var_nuevo_archivo = time().rand().".jpg"; 
				//$obj_simpleimage->resize(150,160); 
			}else{ 
				$var_nuevo_archivo = time().rand().$var_name_img; 
			}
			$var_nuevo_archivo = strtolower(str_replace(' ', '_', $var_nuevo_archivo)); 
			$obj_simpleimage->save($var_img_dir.$var_nuevo_archivo);
			$dir="archivos/".$var_nuevo_archivo;
			$query_update= $bdcon->prepare("UPDATE aca_registro_online SET archivo='$dir', fecha='$fecha', hora='$hora' WHERE codest='$codest'");
    		$query_update->execute();
			//$cons->actualizar('estudiante',"foto='archivos/".$var_nuevo_archivo."'","codEstudiante='$codest'");
			//unlink($var_img_dir . $var_name_img);
		}
?>
<script language="JavaScript">
  setTimeout('history.back()',10000);
 </script>