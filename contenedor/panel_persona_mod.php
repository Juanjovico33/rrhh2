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
                    <h3>MODIFICAR PERSONAL</h3>
                </div>
                <br>
                <div class="container">
                  <div class="row">
                    <div class="icon-set-container"> 
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>">
                        <table class="table">
                            <tr>
                                <td>Persona:</td>
                                <td> 
                                    <select name="persona" id="persona" onChange="recargar_datos(this.value)">
                                        <option value='0'>Elegir personal...</option>
                                       <?php                                          
                                        $q_persona= $bdcon->prepare("SELECT id, nombcompleto FROM persona order by nombcompleto ASC");
                                        $q_persona->execute();
                                        while ($fquery = $q_persona->fetch(PDO::FETCH_ASSOC)) {  
                                                $id=$fquery['id'];
                                                $nombre=$fquery['nombcompleto'];
                                                echo "<option value='$id'>$nombre</option>";                                       
                                        }
                                        ?>
                                    </select> 
                                </td>                       
                            </tr>                                                  
                        </table>  
                     <div id="recargo_datos">
                    </div>                                            
                    </div>
                  </div>
                </div> 
            </div>
        </div>
    </div>
</div>
