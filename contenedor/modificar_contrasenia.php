<?php 
    include "../includes/conexion.php";   
    $codest=$_REQUEST['codest'];
    $actual=$_REQUEST['passactual'];
    $nueva=$_REQUEST['passnueva'];
    $confirmado=$_REQUEST['passconfirmado'];
    $query= $bdcon->prepare("SELECT contrasena from userest where usuario='$codest'");
    $query->execute(); 
    while ($row= $query->fetch(PDO::FETCH_ASSOC)){
       $pass=$row['contrasena'];
    } 
    
    if ($actual==$pass) {
        
    }else{
        echo "<div class='alert alert-danger' align='center'><strong>Error no coincide la contraseña actual</strong><br></div>";
        exit();
    }

    if ($nueva==$confirmado) {
        $query_update= $bdcon->prepare("UPDATE userest SET contrasena='$nueva' WHERE usuario='$codest'");
        $query_update->execute();
        echo "<div class='alert alert-success' align='center'><strong>Contraseña modificada con exito! </strong><br></div>";
    }else{
        echo "<div class='alert alert-danger' align='center'><strong>¡No coincide la nueva contraseña con la confirmada</strong><br></div>";
    }
?>
<div align="center" >
    <button class="btn btn-danger uk-modal-close mr-2">Cerrar</button>
</div>