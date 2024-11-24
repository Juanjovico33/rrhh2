<?php
    include "../includes/_event_log.php";
    include "../includes/user_session.php";
    include "../includes/conexion.php";
    $user = new UserSession();    
    $usuario=$user->getCurrentUser();
    $id_per="";
?>
<div uk-grid>
    <div class="uk-width-6-7@m">
        <div class="blog-post single-post">
            <div class="blog-post-content">
                <div align="center">
                    <h3>CAMBIO DE HORARIO</h3>
                </div>
                <br>  
                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                    <table class="table" >
                        <tr>
                            <td> <div align="right">Personal:</div></td>
                            <td> 
                                <select name="persona" id="persona" onChange="recargar_cambiohora(this.value)">
                                    <option value='0'>Elegir personal...</option>
                                   <?php                                          
                                    $q_persona= $bdcon->prepare("SELECT id, nombcompleto FROM persona order by nombcompleto ASC");
                                    $q_persona->execute();
                                    while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)) {  
                                            $id_per=$fquery['id'];
                                            $nombre=$fquery['nombcompleto'];
                                       
                                            echo "<option value='$id_per'>$nombre</option>";                                       
                                    }
                                    ?>
                                </select> 
                            </td>                           
                        </tr>                                                
                    </table>
                    <div id="recargo_cambio">  
                               
                    </div> 
                    </div>
                </div>
            </div>
        </div>