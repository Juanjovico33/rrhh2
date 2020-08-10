<?php
    include "../includes/_estudiante.php";
    include "../includes/_gestion.php";
    $codest = $_GET['_codest'];
    $estudiante =  new estudiante($codest);    
    $gestion = new gestion();
    $gestion->getgestionactual();
    $estudiante->getdatosest($gestion->getGestion());
    $materias=null;
    // $materias=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion->getId()), $gestion->getperiodoactuales(), $gestion->getId()); 
    $materias=$estudiante->getmateriasporperiodo($estudiante->getperiodosregistrados($gestion->getId(), $gestion->getperiodoactuales()), $gestion->getId()); 
    // $estudiante->getperiodosregistrados($gestion->getId(), $gestion->getperiodoactuales());
    // echo $estudiante->getError();
    
?>
    <h1>Materias Registradas</h1>

    <h4> Elija una materia, para ver los materiales, registro de asistencia, planificacion academica y notas </h4>
        <?php   
            if (!is_null($materias)) {
                         # code...                            
            for($j=0;$j<count($materias);$j++){
            ?>
            <?php $per=$materias[$j]['idperiodo'];?>
            <div class="panel-body">
                        <div class="section-small">
                            <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid" uk-grid>
                        <?php                           
                            $limite_mat=count($materias[$j]['materias_array']);
                            for($i=0;$i<$limite_mat;$i++){
                                $id_grupo=$materias[$j]['materias_array'][$i]['idgrupo'];
                                ?>
                                <!--tr align="center">
                                    <th scope="row" class="text-muted small"><?php echo $i+1;?></th>
                                    <td class="text-muted small"><?php echo $codmat=$materias[$j]['materias_array'][$i]['codmateria'];?></td>
                                    <td class="text-muted small"><?php echo $n_mat=$materias[$j]['materias_array'][$i]['nb_materia'];?></td>
                                    <td class="text-muted small"><?php echo $materias[$j]['materias_array'][$i]['grupo'];?></td>
                                    <td class="text-muted small">
                                        <?php  $id_grupo=$materias[$j]['materias_array'][$i]['idgrupo'];?>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                              <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                              <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                              <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" class="btn btn-info" onclick="abrir_cerrar('<?php echo $codmat ?>','<?php echo $codest ?>','<?php echo $per ?>','<?php echo $id_grupo ?>')">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                          <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                          <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
                                        </svg>
                                    </button>
                                    </td>                                    
                                </tr-->
                                <div>
                                    <a href="#" onclick="ver_descmat('<?=$codmat;?>','<?=$codest;?>','<?=$per;?>','<?=$id_grupo;?>', '<?=$n_mat;?>')">
                                        <div class="course-card">
                                            <div class="course-card-thumbnail ">
                                                <img src="img/materias/<?php echo $i+1; ?>.png">
                                                <span class="play-button-trigger"></span>
                                            </div>
                                            <div class="course-card-body">
                                                <div class="course-card-info">
                                                    <div>
                                                        <span class="catagroy"><?php echo $codmat=$materias[$j]['materias_array'][$i]['codmateria'];?></span>
                                                    </div>
                                                    <div>
                                                        <i class="icon-feather-bookmark icon-small"></i>
                                                    </div>
                                                </div>
                                                <h4><?php echo $n_mat=$materias[$j]['materias_array'][$i]['nb_materia'];?> </h4>
                                                <p> <?php echo $materias[$j]['nbperiodo'];?></p>
                                                <div class="course-card-footer">
                                                    <h5> <i class="icon-feather-film"></i> Hrs Pr. </h5>
                                                    <h5> <i class="icon-feather-clock"></i> Hrs Te. </h5>
                                                </div>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        ?>
                            </div>
                        </div>
                    <br>
            </div>
        <?php }
    }else {
        echo "<div class='alert alert-warning alert-danger'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>¡Error! </strong> No tiene materias registradas</div>";
    }  
        ?>