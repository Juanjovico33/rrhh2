<?php 
$id_per=$_REQUEST['_persona'];
include "../includes/conexion.php";
    $consulhorario= $bdcon->prepare("SELECT * FROM horario_personal WHERE persona='$id_per'");
    $consulhorario->execute(); 
    if($consulhorario->rowCount()==0){
            ?>
            <div class="alert alert-danger" role="alert">
              No tiene horario Registrado !!!
            </div>
            <?php          
            exit();
           }
    while ($fcons = $consultatipohora->fetch(PDO::FETCH_ASSOC)) {   
        $id_tipo=$fcons['id'];               
        $nombre_hora=$fcons['nombre_horario'];                      
        }
?>
        <div align="center">            
            <div class="alert alert-success" role="alert">
              <?php echo $nombre_hora;?>
            </div>
        </div> 
        <table class="table">
            <thead>
                <small>
                    <tr>
                        <th><strong>DIA</strong></th>
                        <th><strong>ENTRADA</strong></th>
                        <th><strong>SALIDA</strong></th>
                        <th><strong>TURNO</strong></th>                        
                    </tr>
                </small>
            </thead>
            <tbody>
            <?php          
            $conshora= $bdcon->prepare("SELECT * FROM horarios WHERE id_tipo='$id_tipo'");
            $conshora->execute(); 
           

            while ($fconshora = $conshora->fetch(PDO::FETCH_ASSOC)) {                  
                $dia=$fconshora['dia'];
                $entra=$fconshora['entrada'];
                $sale=$fconshora['salida'];
                $turno=$fconshora['turno'];                       
                ?>                 
                  <tr>
                    <td><small><?php echo $dia;?></small></td>
                    <td><small><?php echo $entra;?></small></td>
                    <td><small><?php echo $sale;?></small></td>
                    <td><small><?php echo $turno;?></small></td>                    
                  </tr>
               
              <?php
            }
            ?>
            </tbody>
            </table>
