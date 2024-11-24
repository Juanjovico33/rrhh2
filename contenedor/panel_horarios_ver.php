<?php 
$persona=$_REQUEST['_persona'];
include "../includes/conexion.php";
    $consper= $bdcon->prepare("SELECT * FROM persona WHERE id='$persona'");
    $consper->execute();      
    while ($fcons = $consper->fetch(PDO::FETCH_ASSOC)) {   
        $id_tipo=$fcons['id'];               
        $nombrecom=$fcons['nombcompleto'];                      
        }
?>        
        <table class="table">
            <tr>
                <td>
                    <table class="table">
                        <tr>
                            <td>
                                <form method="post" id="form_horarios">     
                                <input type="hidden" name="persona" id="persona" value="<?php echo $persona;?>">                  
                                <table class="table">
                                    <tr>
                                        <td>DIA:</td>
                                        <td>
                                            <select id="dia" name="dia">
                                                <option value="0">Elegir...</option>
                                                <option value="LUNES">LUNES</option>
                                                <option value="MARTES">MARTES</option>
                                                <option value="MIERCOLES">MIERCOLES</option>
                                                <option value="JUEVES">JUEVES</option>
                                                <option value="VIERNES">VIERNES</option>
                                                <option value="SABADO">SABADO</option>
                                                <option value="DOMINGO">DOMINGO</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ENTRADA:</td>
                                        <td> <input type="time" name="hentrada" id="hentrada"></td>
                                    </tr>
                                    <tr>
                                        <td>SALIDA:</td>
                                        <td><input type="time" name="hsalida" id="hsalida"></td>
                                    </tr>
                                    <tr>
                                        <td>TURNO:</td>
                                        <td>
                                            <select name="turno" id="turno">
                                                <option value="MAÑANA">MAÑANA</option>
                                                <option value="TARDE">TARDE</option>
                                                <option value="NOCHE">NOCHE</option>
                                            </select>
                                        </td>
                                    </tr>                                   
                                </table> 
                                    <div align="center">
                                        <button class="btn btn-success" type="button" onclick="registrar_horario()">REGISTRAR</button>
                                    </div> 
                            </form>  
                            </td>                          
                        </tr>          
                    </table> 
                </td>
                <td>
                     <div id="recargo_horario">
                       <?php
                            $consulhorario= $bdcon->prepare("SELECT * FROM horario_personal WHERE persona='$persona'");
                            $consulhorario->execute(); 
                            if($consulhorario->rowCount()==0){
                            ?>
                                <div class="alert alert-danger" role="alert">
                                  No tiene horario Registrado !!!
                                </div>
                                <?php          
                                exit();
                           }
                           ?>
                            <table class="table">
                                <thead style="background-color:#208018; color: white; ">
                                    <tr>
                                        <th>DIA</th>
                                        <th>ENTRADA</th>
                                        <th>SALIDA</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                           <?php
                            while ($fcons = $consulhorario->fetch(PDO::FETCH_ASSOC)) {   
                                $id_tipo=$fcons['id'];               
                                $h_dia=$fcons['dia'];  
                                $hentrada=$fcons['h_entrada'];  
                                $hsalida=$fcons['h_salida'];
                                $hturno=$fcons['turno'];
                            ?>
                            <tr>
                                <td><?php echo $h_dia;?></td>
                                <td><?php echo $hentrada;?></td>
                                <td><?php echo $hsalida;?></td>
                                <td><?php echo $hturno;?></td>
                            </tr>
                            <?php
                            }
                       ?> 
                       </table>
                    </div>   
                </td>
            </tr>
        </table>
        

        
