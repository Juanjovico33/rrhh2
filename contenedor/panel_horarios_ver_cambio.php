<?php 
$persona=$_REQUEST['_persona'];
include "../includes/conexion.php";
    $consper= $bdcon->prepare("SELECT * FROM persona WHERE id='$persona'");
    $consper->execute();      
    while ($fcons = $consper->fetch(PDO::FETCH_ASSOC)) {   
        $id_tipo=$fcons['id'];               
        $nombrecom=$fcons['nombcompleto'];                      
        }
    $q_gest= $bdcon->prepare("SELECT id, nombre FROM gestion where estado='1'");
    $q_gest->execute();
    while ($gquery = $q_gest->fetch(PDO::FETCH_ASSOC)) {  
        $id_gest = $gquery['id'];
        $nombre_gest = $gquery['nombre'];                                         
    }
?>        
        <table class="table">
            <tr>
                <td>
                    <table class="table">
                        <tr>
                            <td>
                                <form method="post" id="form_horario_cambio">     
                                <input type="hidden" name="persona" id="persona" value="<?php echo $persona;?>">                  
                                <table class="table">
                                    <tr>
                                        <td>FECHA:</td>
                                        <td> <input type="date" name="fechacambio" id="fechacambio"></td>
                                    </tr>
                                    <tr>
                                        <td>ENTRADA:</td>
                                        <td><input type="time" name="hentrada" id="hentrada"></td>
                                    </tr>
                                    <tr>
                                        <td>SALIDA:</td>
                                        <td><input type="time" name="hsalida" id="hsalida"></td>
                                    </tr> 
                                    <tr>
                                        <td>TURNO:</td>
                                        <td>
                                            <select name="turno" id="turno">                                            
                                                <option value="">ELEGIR ....</option>
                                            <?php
                                                $consturno= $bdcon->prepare("SELECT turno FROM horario_personal WHERE persona='$persona' group by turno");
                                                $consturno->execute();      
                                                while ($turcons = $consturno->fetch(PDO::FETCH_ASSOC)) { 
                                                    $turnonb=$turcons['turno']; 
                                                ?>
                                                  <option value="<?php echo $turnonb;?>"><?php echo $turnonb;?></option>  
                                            <?php    
                                                 }
                                            ?>  
                                            </select>
                                        </td>
                                    </tr>                                                                                         
                                    <tr>
                                        <td colspan="2">
                                            <div class="input-group">
                                                <span class="input-group-text">MOTIVO DEL CAMBIO DE HORARIO:</span>
                                                <textarea class="form-control" aria-label="With textarea" name="motivo" id="motivo"></textarea>
                                            </div>
                                        </td>
                                    </tr>                 
                                </table> 
                                    <div align="center">
                                        <button class="btn btn-success" type="button" onclick="registrar_horario_cambio()">REGISTRAR</button>
                                    </div> 
                            </form>  
                            </td>                          
                        </tr>          
                    </table> 
                </td>
                <td>
                        <select name="mes" id="mes" onChange="recargar_cambiohorames(this.value,<?php echo $persona;?>)">  
                            <?php
                            // Obtener el mes actual
                            $mes_actualn = date('m');                            
                            $query_mes= $bdcon->prepare("SELECT * FROM meses");
                            $query_mes->execute(); 
                            while ($fmes = $query_mes->fetch(PDO::FETCH_ASSOC)){ 
                                $idmes=$fmes['id'];  
                                $nbmes=$fmes['mes'];
                                if ($mes_actualn==$idmes) {
                                   ?>
                                    <option value="<?php echo $idmes;?>" selected><?php echo $nbmes;?></option>
                                <?php
                                }else{
                                ?>
                                    <option value="<?php echo $idmes;?>"><?php echo $nbmes;?></option>
                                <?php      
                                }                           
                            }
                            ?>                        
                        </select>  
                    <div id="recargo_horario_cambio"> 
                       <?php        
                             switch ($mes_actualn) {
                                case '1':                                    
                                    $mes_actualn="01";
                                    break;
                                case '2':                                    
                                    $mes_actualn="02";
                                    break;
                                case '3':                                    
                                    $mes_actualn="03";
                                    break;
                                case '4':                                    
                                    $mes_actualn="04";
                                    break;
                                case '5':                                  
                                    $mes_actualn="05";
                                    break;
                                case '6':                                   
                                    $mes_actualn="06";
                                    break;
                                case '7':                                   
                                    $mes_actualn="07";
                                    break;
                                case '8':                                   
                                    $mes_actualn="08";
                                    break;
                                case '9':                                    
                                    $mes_actualn="09";
                                    break;
                                case '10':                                   
                                    break;
                                case '11':                                    
                                    break;
                                case '12':                                   
                                    break;
                                default:
                                    $mesnb='ERROR EN SELECCIONAR MES';
                                    break;
                            }                       
                            $aniomes=$nombre_gest."-".$mes_actualn;                
                            $consulhorario= $bdcon->prepare("SELECT * FROM horario_personal_cambio WHERE id_persona='$persona' and fecha like '$aniomes%' and estado='1'");
                            $consulhorario->execute(); 
                            if($consulhorario->rowCount()==0){
                            ?>
                                <div class="alert alert-danger" role="alert">
                                  No tiene cambio horario Registrado !!!
                                </div>
                                <?php          
                                exit();
                           }
                           ?>
                            <table class="table" style="font-size: 10px;">
                                <thead style="background-color:#208018; color: white; ">
                                    <tr>
                                        <th width="90px">FECHA</th>
                                        <th>DIA</th>
                                        <th>ENTRADA</th>
                                        <th>SALIDA</th>
                                        <th>TURNO</th>
                                        <th>MOTIVO</th>
                                        <th>ELIMINAR</th>
                                    </tr>
                                </thead>
                               <?php
                               $dias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
                                while ($fcons = $consulhorario->fetch(PDO::FETCH_ASSOC)) {  
                                    $idhora=$fcons['id']; 
                                    $fechac=$fcons['fecha'];
                                    $hentrada=$fcons['h_entrada'];  
                                    $hsalida=$fcons['h_salida'];
                                    $turno=$fcons['turno'];
                                    $motivo=$fcons['motivo'];                                    
                                    $dia = $dias[date("w", strtotime($fechac))];
                                    $diam=mb_strtoupper($dia);

                                ?>
                                    <tr>
                                        <td><?php echo $fechac;?></td>
                                        <td><?php echo $dia;?></td>
                                        <td><?php echo $hentrada;?></td>
                                        <td><?php echo $hsalida;?></td>
                                        <td><?php echo $turno;?></td>
                                        <td><?php echo $motivo;?></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_cambiohorario(<?php echo $idhora;?>,<?php echo $persona;?>,<?php echo $mes_actualn;?>)"><span class="icon-material-outline-delete"></span></button>
                                    </tr>
                                <?php
                                }
                       ?> 
                            </table>
                    </div>   
                </td>
            </tr>
        </table>
        

        
