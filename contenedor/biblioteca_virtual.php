<?php
include "../includes/conexion.php";
include "../includes/_event_log.php";
$codest = $_GET['_codest']; 
$carrera = $_GET['_carrera']; 

$e = new evento();
$e->e_log_inicio_evento($codest, 17);

$dirimg="https://storage.googleapis.com/une_segmento-one/estudiantes/img/biblioteca/";
$q_enlaces= $bdcon->prepare("SELECT nombre, enlace, icono from plat_est_biblio_enlaces WHERE estado='1'");
$q_enlaces->execute();

  ?>
 <div uk-grid>
    <div class="uk-width-12">
        <h3 align="center">BIBLIOTECA VIRTUAL</h3>
        <table class="table table-borderless">
            <tr>
                <th><input class="uk-input" id='busca' type="text" placeholder="Buscar libro" autocomplete="off"> </th>
                <th><button type="button" class="btn btn-success" onclick="verlibros(busca.value,'<?php echo $codest?>','<?php echo $carrera?>')">
                                BUSCAR
                    </button></th>
            </tr>
        </table>      
    </div>
    <div class="uk-width-3-5@m">
        <div class="uk-card-default rounded text-center p-4">
                <div id="libros"></div>
        </div>
    </div>
    <div class="uk-width-2-5@m">
        <div class="uk-card-default rounded text-center p-4">                
            <span class="mls"> <strong>ENLACES EXTERNOS</strong></span> <br>                          
                <?php
                while ($f_libros = $q_enlaces->fetch(PDO::FETCH_ASSOC)) {
                    $nb=$f_libros['nombre'];
                    $enlace=$f_libros['enlace'];
                    $imagen=$f_libros['icono'];
                    $imag=$dirimg.$imagen;
                ?>
                    <div class="glyph fs1">
                        <a target="_blank\" href="<?=$enlace;?>">
                            <div class="clearfix bshadow0 pbs">
                                <span ><img src="<?=$imag;?>"></span>
                                <span class="mls"><?=$nb;?></span>
                            </div>
                        </a>
                    </div>    
                <?php
                } 
                ?>                
        </div> 
    </div>       
</div>