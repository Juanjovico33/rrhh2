<?php
include "../includes/_event_log.php";
    include "../includes/user_session.php";
    $user = new UserSession();    
    include "../includes/conexion.php";
    $usuario=$user->getCurrentUser();

?>

<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
                <div align="center">
                    <h3>REGISTRAR PERSONAL</h3>
                </div>
                <br>
                <div class="container">
                  <div class="row">
                    <div class="icon-set-container">   
                    <form method="post" id="form_datos" onsubmit="return validarFormulario()">
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                    <table class="table">
                        <tr>
                            <td>Nombres:</td><td> <input type="text" name="nom" id="nom" autocomplete="off"></td>
                            <td>Apellidos:</td><td><input type="text" name="ap" id="ap" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Fecha Nacimiento:</td>
                            <td> <input type="date" name="fnac" id="fnac"></td>
                            <td>Sexo</td>
                            <td>  
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="M">
                                 <label class="form-check-label">
                                    Masculino
                                  </label>
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="F">
                                <label class="form-check-label">
                                    Femenino
                                </label> 
                            </td>                                   
                        </tr>
                        <tr>
                            <td>Cedula:</td><td><input type="number" name="ci" id="ci" autocomplete="off"></td>
                            <td>Extensión:</td><td><input type="text" name="ext" id="ext" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Estado civil:</td>
                            <td>                                
                                <input class="form-check-input" type="radio" name="es" id="es" value="S">
                                 <label class="form-check-label">
                                    Soltero/a
                                  </label>                                
                                    <input class="form-check-input" type="radio" name="es" id="es" value="C">
                                    <label class="form-check-label">
                                    Casado/a
                                  </label> 
                            </td>
                            <td>Telefono:</td><td><input type="text" name="telf" id="telf" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Correo:</td><td colspan="3"><input type="text" name="mail" id="mail" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Departamento:</td>
                            <td> 
                                <select name="dept" id="dept">
                                    <option value="0">Elegir..</option>
                                   <?php                                          
                                    $q_dep= $bdcon->prepare("SELECT id, nombre FROM departamento");
                                    $q_dep->execute();
                                    while ($depquery = $q_dep->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_dep=$depquery['id'];
                                            $nombredep=$depquery['nombre'];
                                       
                                            echo "<option value='$id_dep'>$nombredep</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                            <td>Cargo:</td>
                            <td>
                                 <select name="cargo" id="cargo">
                                    <option value="0">Elegir..</option>
                                   <?php                                          
                                    $q_car= $bdcon->prepare("SELECT id, nombre FROM cargo");
                                    $q_car->execute();
                                    while ($carquery = $q_car->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_car=$carquery['id'];
                                            $nombrecar=$carquery['nombre'];
                                       
                                            echo "<option value='$id_car'>$nombrecar</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                        </tr>
                        <tr>
                            <td>SUCURSAL:</td>
                            <td> 
                                <select name="sucursal" id="sucursal">
                                    <option value="0">Elegir..</option>
                                   <?php                                          
                                    $q_suc= $bdcon->prepare("SELECT id, nombre FROM sucursal");
                                    $q_suc->execute();
                                    while ($sucquery = $q_suc->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_suc=$sucquery['id'];
                                            $nombresuc=$sucquery['nombre'];
                                       
                                            echo "<option value='$id_suc'>$nombresuc</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                            <td>Fecha de Ingreso:</td>
                            <td> <input type="date" name="fing" id="fing"></td>
                        </tr>
                        <tr>
                            <td>Sueldo Basico:</td>
                            <td><input type="number" name="basico" id="basico" value="0" autocomplete="off"></td>
                            <td>Bono de Eficiencia:</td>
                            <td><input type="number" name="eficiencia" id="eficiencia" value="0" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Bono de Transporte:</td>
                            <td><input type="number" name="transporte" id="transporte" value="0" utocomplete="off"></td>  
                            <td>Tipo Planilla:</td>
                            <td><select name="tplanilla" id="tplanilla">
                                    <option value="0">Elegir..</option>
                                   <?php                                          
                                    $q_tipo= $bdcon->prepare("SELECT id, nombre FROM tipo_planilla");
                                    $q_tipo->execute();
                                    while ($tipoquery = $q_tipo->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_tipo=$tipoquery['id'];
                                            $nombretipo=$tipoquery['nombre'];
                                       
                                            echo "<option value='$id_tipo'>$nombretipo</option>";                                       
                                    }
                                    ?>
                                </select>   
                                </select> 
                            </td>                          
                        </tr>
                    </table>  
                        <div align="center">
                            <button class="btn btn-success" type="button" onclick="guardar_datos()">REGISTRAR</button>
                        </div>                      
                    </form>                                              
                    </div>
                  </div>
                </div> 
            </div>
        </div>
    </div>
</div>