<?php
include "../includes/_event_log.php";
    include "../includes/user_session.php";
    include "../includes/conexion.php";
    $user = new UserSession();    
    $usuario=$user->getCurrentUser();
    $q_gest= $bdcon->prepare("SELECT id, nombre FROM gestion where estado='1'");
    $q_gest->execute();
    while ($gquery = $q_gest->fetch(PDO::FETCH_ASSOC)) {  
        $id_gest = $gquery['id'];
        $nombre_gest = $gquery['nombre'];                                         
    }

   if($q_gest->rowCount()==0){
    ?>
        <div class="alert alert-danger" role="alert">No hay gestion habilitada para ver los reportes</div>    
    <?php
          exit();
    }
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
                <div align="center">
                <h3>REPORTES DE MARCACION</h3>
                </div>
                <br>
                <div class="container">
                  <div class="row">
                    <div class="icon-set-container"> 
                        <form id="form1" name="form1" method="post" action="contenedor/reportes_verxpersona.php" target="_blank">
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                        <input type="hidden" name="gestnomb" id="gestnomb" value="<?php echo $nombre_gest;?>">                        
                        <table class="table">                           
                                <tr>
                                <td>Por Persona:</td>
                                <td> 
                                    <select name="persona" id="persona">
                                        <option value='0'>Elegir personal...</option>
                                        <?php                                          
                                            $q_persona= $bdcon->prepare("SELECT id, nombcompleto FROM persona ORDER BY nombcompleto ASC");
                                            $q_persona->execute();
                                            while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)) {  
                                                $id = $fquery['id'];
                                                $nombre = $fquery['nombcompleto'];
                                                echo "<option value='$id'>$nombre</option>";                                       
                                            }
                                        ?>
                                    </select> 
                                </td>              
                                <td>Mes:</td>
                                <td colspan="3"> 
                                    <select name="mes" id="mes">
                                        <?php 
                                            //echo $hoy = date("n"); para ver numero mes
                                        ?>
                                        <option value='0'>Elegir Mes...</option>    
                                        <option value='1'>ENERO</option>  
                                        <option value='2'>FEBRERO</option>  
                                        <option value='3'>MARZO</option>  
                                        <option value='4'>ABRIL</option>                  
                                        <option value='5'>MAYO</option>  
                                        <option value='6'>JUNIO</option>  
                                        <option value='7'>JULIO</option>  
                                        <option value='8'>AGOSTO</option>  
                                        <option value='9'>SEPTIEMBRE</option>  
                                        <option value='10'>OCTUBRE</option>  
                                        <option value='11'>NOVIEMBRE</option>  
                                        <option value='12'>DICIEMBRE</option>  
                                    </select> 
                                </td>
                                <td>
                                    <div align="center">
                                        <button class="btn btn-success" type="submit">VER</button>
                                    </div>
                                </td>  
                            </tr>
                            </table>
                            </form>
                             <form id="form2" name="form2" method="post" action="contenedor/reportes_verxdepartamento.php" target="_blank">
                            <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                            <input type="hidden" name="gestnomb" id="gestnomb" value="<?php echo $nombre_gest;?>">
                            <table class="table">                           
                             <tr>
                                <td>Por Departamento:</td>
                                <td> 
                                    <select name="depar" id="depar">
                                        <option value='0'>Elegir Departamento...</option>
                                        <?php                                          
                                            $q_depar= $bdcon->prepare("SELECT id, nombre FROM departamento");
                                            $q_depar->execute();
                                            while ($dequery = $q_depar->fetch(PDO::FETCH_ASSOC)) {  
                                                $id_de = $dequery['id'];
                                                $nombre_de = $dequery['nombre'];
                                                echo "<option value='$id_de'>$nombre_de</option>";                                       
                                            }
                                        ?>
                                    </select> 
                                </td>  
                                 <td>Sucursal:</td>  
                                 <td> 
                                    <select name="suc" id="suc">
                                        <option value='0'>Elegir Sucursal...</option>
                                        <?php                                          
                                            $q_suc= $bdcon->prepare("SELECT id, nombre FROM sucursal ");
                                            $q_suc->execute();
                                            while ($sucquery = $q_suc->fetch(PDO::FETCH_ASSOC)) {  
                                                $id_suc = $sucquery['id'];
                                                $nombre_suc = $sucquery['nombre'];
                                                if ($nombre_suc=="UNE CENTRO") {
                                                   echo "<option value='$id_suc' selected>$nombre_suc</option>"; 
                                                }else{
                                                    echo "<option value='$id_suc'>$nombre_suc</option>";  
                                                }                                                                                     
                                            }
                                        ?>
                                    </select> 
                                </td>                       
                                <td>Mes:</td>
                                <td> 
                                    <select name="mesdepar" id="mesdepar">
                                        <option value='0'>Elegir Mes...</option>    
                                        <option value='1'>ENERO</option>  
                                        <option value='2'>FEBRERO</option>  
                                        <option value='3'>MARZO</option>  
                                        <option value='4'>ABRIL</option>                  
                                        <option value='5'>MAYO</option>  
                                        <option value='6'>JUNIO</option>  
                                        <option value='7'>JULIO</option>  
                                        <option value='8'>AGOSTO</option>  
                                        <option value='9'>SEPTIEMBRE</option>  
                                        <option value='10'>OCTUBRE</option>  
                                        <option value='11'>NOVIEMBRE</option>  
                                        <option value='12'>DICIEMBRE</option>  
                                    </select> 
                                </td>
                                <td>
                                    <div align="center">
                                        <button class="btn btn-success" type="submit">VER</button>
                                    </div>
                                </td>  
                            </tr>
                        </table>
                    </form>
                    
                     <h3>REPORTES DE MARCACION CRUDO</h3>
                     <form id="form1" name="form1" method="post" action="contenedor/reportes_verxpersona_crudo.php" target="_blank">
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                        <input type="hidden" name="gestnomb" id="gestnomb" value="<?php echo $nombre_gest;?>">
                        <table class="table">
                            <tr>
                                <td>Por Persona:</td>
                                <td> 
                                    <select name="persona" id="persona">
                                        <option value='0'>Elegir personal...</option>
                                        <?php                                          
                                            $q_persona= $bdcon->prepare("SELECT id, nombcompleto FROM persona ORDER BY nombcompleto ASC");
                                            $q_persona->execute();
                                            while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)) {  
                                                $id = $fquery['id'];
                                                $nombre = $fquery['nombcompleto'];
                                                echo "<option value='$id'>$nombre</option>";                                       
                                            }
                                        ?>
                                    </select> 
                                </td>              
                                <td>Mes:</td>
                                <td> 
                                    <select name="mes" id="mes">
                                        <option value='0'>Elegir Mes...</option>    
                                        <option value='1'>ENERO</option>  
                                        <option value='2'>FEBRERO</option>  
                                        <option value='3'>MARZO</option>  
                                        <option value='4'>ABRIL</option>                  
                                        <option value='5'>MAYO</option>  
                                        <option value='6'>JUNIO</option>  
                                        <option value='7'>JULIO</option>  
                                        <option value='8'>AGOSTO</option>  
                                        <option value='9'>SEPTIEMBRE</option>  
                                        <option value='10'>OCTUBRE</option>  
                                        <option value='11'>NOVIEMBRE</option>  
                                        <option value='12'>DICIEMBRE</option>  
                                    </select> 
                                </td>
                                <td>
                                    <div align="center">
                                        <button class="btn btn-success" type="submit">VER</button>
                                    </div>
                                </td>  
                            </tr>
                        </table>
                    </form>
                   <!-- <form id="form1" name="form1" method="post" action="contenedor/reportes_ver.php" target="_blank">
                    <table class="table">
                         <tr>
                            <td>Por sucursal:</td>
                            <td> 
                                <select name="sucur" id="sucur">
                                     <option value='0'>Elegir sucursal...</option>
                                   <?php                                          
                                    $q_sucursal= $bdcon->prepare("SELECT id, nombre FROM sucursal");
                                    $q_sucursal->execute();
                                    while ($querysucu = $q_sucursal->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_sucu=$querysucu['id'];
                                            $nombresucu=$querysucu['nombre'];
                                       
                                            echo "<option value='$id_sucu'>$nombresucu</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>
                            <td>Mes:</td>
                                <td> 
                                    <select name="messuc" id="messuc">
                                        <option value='0'>Elegir Mes...</option>    
                                        <option value='1'>ENERO</option>  
                                        <option value='2'>FEBRERO</option>  
                                        <option value='3'>MARZO</option>  
                                        <option value='4'>ABRIL</option>                  
                                        <option value='5'>MAYO</option>  
                                        <option value='6'>JUNIO</option>  
                                        <option value='7'>JULIO</option>  
                                        <option value='8'>AGOSTO</option>  
                                        <option value='9'>SEPTIEMBRE</option>  
                                        <option value='10'>OCTUBRE</option>  
                                        <option value='11'>NOVIEMBRE</option>  
                                        <option value='12'>DICIEMBRE</option>  
                                    </select> 
                                </td>
                            <td>
                                <div align="center">
                                    <button class="btn btn-success" type="button" onclick="guardar_datos()">VER</button>
                                </div>
                            </td>   
                        </tr>
                    </table>
                    </form>-->
                           
                                                            
                    </div>
                  </div>
                </div> 
            </div>
        </div>
    </div>
</div>
