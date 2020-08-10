<?php 
$codest=$_REQUEST['_codest'];
$grupo=$_REQUEST['_grupo'];
$idgestion=$_REQUEST['_id_gestion'];
$codcarrera=$_REQUEST['_codcarreraa'];
$semestre=$_REQUEST['_intsemestre'];
$resolucion=$_REQUEST['_idresol'];
include "../includes/conexion.php";
$periodo="";
    if ((date("m"))>=7){       
        $gest=date("Y");
        $periodo=$gest."02";
    }else{
        $gest=date("Y");
        $periodo=$gest."01";
    }
$fecha=date('Y-m-d');
$time=time();
$hora=date("H:i:s", $time);
    $q_online= $bdcon->prepare("SELECT * FROM aca_registroestmat_online WHERE codest='$codest' and periodo='$periodo'");
    $q_online->execute();  
?>
    <div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content"> 
                    <?php
                    if($q_online->rowCount()>0){
                        echo "<div class='alert alert-info'>Estimad@ Estudiante<br>
                                Ya tiene una solicitud de registro de materias para el periodo 2/2020</div>";
                    }else{
                        /*-------aqui voy a insertar regristro online para adjuntar el deposito*/
                        $query_insert= $bdcon->prepare("INSERT INTO aca_registro_online VALUES ('0','$periodo','$codest','0','$fecha','$hora','0')");
                        $query_insert->execute();
                         /*------------aqui voy a insertar los registros de las materias----- */      
                        $q_pensum= $bdcon->prepare("SELECT materia FROM aca_pensum WHERE gestion='$idgestion' and carrera='$codcarrera' and semestre='$semestre' and id_resolucion='$resolucion' order by cod_pen DESC");
                        $q_pensum->execute();  
                         while ($r_pensum = $q_pensum->fetch(PDO::FETCH_ASSOC)) {
                                $codmateria=$r_pensum['materia'];                               
                                $query_insert= $bdcon->prepare("INSERT INTO aca_registroestmat_online VALUES ('0','$idgestion','$periodo','$codest','$codmateria','$codcarrera','$grupo','$fecha','$hora','0')");
                                $query_insert->execute();
                         }
                    }
                    ?>
                <div class="alert alert-warning">
                    <strong style="color:red;">Nota:</strong>
                    Este registro esta pendiente a revisión de acuerdo al pago realizado por deposito o transferencia bancaria
               </div>
                <div align="center" > 
                <?php
                    $query_foto= $bdcon->prepare("SELECT archivo FROM aca_registro_online WHERE codest='$codest'");
                    $query_foto->execute();
                    while ($r_foto = $query_foto->fetch(PDO::FETCH_ASSOC)) {
                                $foto=$r_foto['archivo']; 
                    }

                    if($foto != "0"){                       
                        ?>
                        <form id="imagen" action="subir_imagen.php" method="POST" enctype=multipart/form-data>
                            <input type="hidden" name="codest" id="codest" value="<?php echo $codest;?>"/>
                            <h1>Deposito Enviado</h1>                               
                                <div class="uk-width-1-3@m uk-width-1-2@s">
                                    <div uk-sticky="offset: 70 ;bottom: true ;media @s">
                                        <div uk-lightbox>
                                            <img class="uk-box-shadow-xlarge" src="<?php echo $foto;?>"/>
                                         </div>
                                    </div>
                                </div>                               
                            <div class="uk-margin" uk-margin>
                                <input type="file" name="file_img" id="file_img"/>
                            </div>
                            <div>
                                <input type="submit" name="enviar" id="enviar" value="Volver a Enviar Deposito"/>
                            </div>
                        </form>                              
                        <?php
                    }else{
                        ?>                       
                          <form id="imagen" action="subir_imagen.php" method="POST" enctype=multipart/form-data>
                                <input type="hidden" name="codest" id="codest" value="<?php echo $codest;?>"/>
                                <h1>Insertar Imagen del Deposito</h1>
                                <div uk-lightbox>
                                        <img class="uk-box-shadow-xlarge" src="archivos/sin.jpg"/>
                                </div>
                                <div class="uk-margin" uk-margin>
                                    <input type="file" name="file_img" id="file_img"/>
                                </div>
                                <div>
                                    <input type="submit" name="enviar" id="enviar" value="Enviar Deposito"/>
                                </div>
                            </form>  
                        <?php
                    }
                ?> 
                </div>
                </div>
            </div>  
        </div> 
    </div>