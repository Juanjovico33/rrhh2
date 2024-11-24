<?php    
    include "../includes/user_session.php";
    $user = new UserSession();    
    include "../includes/conexion.php";
    $usuario=$user->getCurrentUser();
    $personal=$_REQUEST['_persona'];
    $q_pers= $bdcon->prepare("SELECT * FROM persona where id='$personal'");    
    $q_pers->execute();
    while ($fquerys = $q_pers->fetch(PDO::FETCH_ASSOC)) {  
            $id_per=$fquerys['id'];
            $nombre=$fquerys['nombres'];
            $apellido=$fquerys['apellidos'];
            $telf=$fquerys['telefono'];
            $correo=$fquerys['correo'];
            $ci=$fquerys['ci'];
            $ext=$fquerys['extencion'];
            $fechana=$fquerys['fechanaci'];
            $sexo=$fquerys['sexo'];
            $estcivil=$fquerys['estado_civil'];    
            $cargo=$fquerys['cargo'];
            $depar=$fquerys['depar'];
            $sucu=$fquerys['sucursal'];
            $fechaingre=$fquerys['fechaingre'];
            $estado=$fquerys['estado'];                            
    }
?>
                <div align="center">
                    <h3>DATOS A MODIFICAR</h3>
                </div>
                <br>               
                    <form method="post" id="form_datos">
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                        <input type="hidden" name="persona" id="persona" value="<?php echo $personal;?>">
                    <table class="table">
                        <tr>
                            <td>Nombres:</td><td> <input type="text" name="nom" id="nom" autocomplete="off" value="<?php echo $nombre;?>"></td>
                            <td>Apellidos:</td><td><input type="text" name="ap" id="ap" autocomplete="off" value="<?php echo $apellido;?>"></td>
                        </tr>
                        <tr>
                            <td>Fecha Nacimiento:</td>
                            <td> <input type="date" name="fnac" id="fnac" value="<?php echo $fechana;?>"></td>
                            <td>Sexo</td>
                            <td>  
                            <?php
                                if ($sexo=="M") {
                            ?>
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="M" checked>
                            <?php
                                   }else{
                             ?>
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="M">
                            <?php        
                                   } 
                            ?>
                                
                                <label class="form-check-label">
                                    Masculino
                                </label>

                            <?php
                                if ($sexo=="F") {
                            ?>
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="F" checked>
                            <?php
                                   }else{
                             ?>
                                <input class="form-check-input" type="radio" name="sexo" id="sexo" value="F">
                            <?php        
                                   } 
                            ?>

                                <label class="form-check-label">
                                    Femenino
                                </label> 
                            </td>                                   
                        </tr>
                        <tr>
                            <td>Cedula:</td><td><input type="number" name="ci" id="ci" autocomplete="off" value="<?php echo $ci;?>"></td>
                            <td>Extensión:</td><td><input type="text" name="ext" id="ext" autocomplete="off" value="<?php echo $ext;?>"></td>
                        </tr>
                        <tr>
                            <td>Estado civil:</td>
                            <td>
                                <?php
                                    if ($estcivil=="S") {
                                    ?>
                                       <input class="form-check-input" type="radio" name="es" id="es" value="S" checked>
                                    <?php
                                    }else{
                                     ?>
                                        <input class="form-check-input" type="radio" name="es" id="es" value="S">
                                    <?php        
                                    } 
                                ?>  
                                 <label class="form-check-label">
                                    Soltero/a
                                  </label>
                                     <?php
                                    if ($estcivil=="C") {
                                    ?>
                                       <input class="form-check-input" type="radio" name="es" id="es" value="C" checked>
                                    <?php
                                    }else{
                                     ?>
                                        <input class="form-check-input" type="radio" name="es" id="es" value="C">
                                    <?php        
                                           } 
                                ?>  
                                    <label class="form-check-label">
                                    Casado/a
                                  </label>                          
                                </div>
                            </td>
                            <td>Telefono:</td><td><input type="text" name="telf" id="telf" autocomplete="off" value="<?php echo $telf;?>"></td>
                        </tr>
                        <tr>
                            <td>Correo:</td><td colspan="3"><input type="text" name="mail" id="mail" autocomplete="off" value="<?php echo $correo;?>"></td>
                        </tr>
                        <tr>
                            <td>Departamento:</td>
                            <td> 
                                <select name="dept" id="dept">
                                   <?php                                          
                                    $q_dep= $bdcon->prepare("SELECT id, nombre FROM departamento");
                                    $q_dep->execute();
                                    while ($depquery = $q_dep->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_dep=$depquery['id'];
                                            $nombredep=$depquery['nombre'];
                                            if ($depar==$id_dep) {
                                                echo "<option value='$id_dep' selected>$nombredep</option>";
                                            }
                                            echo "<option value='$id_dep'>$nombredep</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                            <td>Cargo:</td>
                            <td>
                                 <select name="cargo" id="cargo">
                                   <?php                                          
                                    $q_car= $bdcon->prepare("SELECT id, nombre FROM cargo");
                                    $q_car->execute();
                                    while ($carquery = $q_car->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_car=$carquery['id'];
                                            $nombrecar=$carquery['nombre'];
                                            if ($cargo==$id_car) {
                                                echo "<option value='$id_car' selected>$nombrecar</option>";
                                            }else{
                                                echo "<option value='$id_car'>$nombrecar</option>";     
                                            }
                                                                                 
                                    }
                                    ?>
                                </select> 
                            </td>
                        </tr>
                        <tr>
                            <td>SUCURSAL:</td>
                            <td> 
                                <select name="sucursal" id="sucursal">
                                   <?php                                          
                                    $q_suc= $bdcon->prepare("SELECT id, nombre FROM sucursal");
                                    $q_suc->execute();
                                    while ($sucquery = $q_suc->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_suc=$sucquery['id'];
                                            $nombresuc=$sucquery['nombre'];
                                             if ($sucu==$id_suc) {
                                                echo "<option value='$id_suc' selected>$nombresuc</option>";
                                            }
                                            echo "<option value='$id_suc'>$nombresuc</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                             <td>Fecha de Ingreso:</td>
                            <td> <input type="date" name="fing" id="fing" value="<?php echo $fechaingre;?>"></td>
                        </tr>
                    </table>  
                            <div align="center">
                                <button class="btn btn-success" type="button" onclick="modificar_datos()">MODIFICAR</button>
                            </div>
                    </form>                                              
 