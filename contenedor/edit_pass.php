<?php
    $codest = $_GET['_codest'];   
?>
<div class="p-3">
    <h5 class="mb-0"> Editar Contraseña</h5>
</div>
<hr class="m-0">
<form method="post" id="pass" class="uk-child-width-1-1@s uk-grid-small p-4" uk-grid>
    <label>Contraseña Actual</label>
    <div class="input-group">
        <input id="passactual" name="passactual" type="Password" Class="form-control">
        <div class="input-group-append">
            <button class="btn btn-success" type="button" onclick="mostrarPassword('passactual')"><span class="icon-feather-eye"></span></button>
        </div>
    </div> 
    <label>Nueva Contraseña</label>
    <div class="input-group">
        <input id="passnueva" name="passnueva" type="Password" Class="form-control">
        <div class="input-group-append">
            <button class="btn btn-success" type="button" onclick="mostrarPassword('passnueva')"><span class="icon-feather-eye"></span></button>
        </div>
    </div>                         
    <label>Confirmar Contraseña</label>
    <div class="input-group">
         <input id="passconfirmado" name="passconfirmado" type="Password" Class="form-control">
        <div class="input-group-append">
            <button class="btn btn-success" type="button" onclick="mostrarPassword('passconfirmado')"><span class="icon-feather-eye"></span></button>
        </div>
    </div>   
    <input id="codest" name="codest" type="hidden" value="<?php echo $codest;?>">                        
</form>
<div align="center" >
    <button class="btn btn-danger uk-modal-close mr-2">Cerrar</button>
    <button class="btn btn-success grey" onclick="editar_contrass()">Modificar</button>
</div>