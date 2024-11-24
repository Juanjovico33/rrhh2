<?php 
    include "../includes/_files.php";
    include "../includes/_linkMeet.php";
    include "../includes/_grupo.php";
    include "../includes/conexion.php";
    include "../includes/_horarios.php";
    include "../includes/_event_log.php";
    include "class_internado.php";

    $id_grupo=$_REQUEST['_idgrupo']; 
    $codmat=$_REQUEST['_codmat'];
    $per=$_REQUEST['_periodo'];
    $codest=$_REQUEST['_codest'];
    $nb_materia=$_REQUEST['_nbmat'];
    $carr=$_REQUEST['_carr'];
    $nbest="";
    $archivo=new file();
    $o_enl=new linkMeet();
    $grupo=new grupo();
    $grupo_aux=new grupo();
    $horario= new horarios();
    $inter=new internado_class();

    $grupo->getDatosGrupo($id_grupo);
    $_idgrupoRaiz=0;
    if($grupo->es_rama()){
        $_idgrupoRaiz=$grupo->getIdramaRaiz();
          //echo "Es rama ".$_idgrupoRaiz.'<br>';
        $o_enl->getDatosEnlace($_idgrupoRaiz);
    }else if($grupo->esNivelacion()){
        $_idgrupoRaiz=$grupo->nivGetIdgrupoMain();
        if($_idgrupoRaiz==0){
            $_idgrupoRaiz=$grupo->getIdgrupo_otraMateriaMalla();
            if($_idgrupoRaiz!=0){
                $grupo_aux->getDatosGrupo($_idgrupoRaiz);
                if($grupo_aux->es_rama()){
                    $_idgrupoRaiz=$grupo_aux->getIdramaRaiz();
                }
                $o_enl->getDatosEnlace($_idgrupoRaiz);        
            }else{
                $_idgrupoRaiz=0;
                echo "El grupo no esta abierto.<br>";
            }
        }else{
            //   echo "Es nivelacion -".$_idgrupoRaiz."<br>";
            $grupo_aux->getDatosGrupo($_idgrupoRaiz);
            if($grupo_aux->es_rama()){
                $_idgrupoRaiz=$grupo_aux->getIdramaRaiz();
            }
             $o_enl->getDatosEnlace($_idgrupoRaiz);
        }
    }else{
        //echo "NO es rama ".$id_grupo."<br>";//echo "No es nivelación<br>";   //Si no es fusionada y no es nivelación entonces obtener enlace
        $_idgrupoRaiz=0;
        $o_enl->getDatosEnlace($id_grupo);
    }   
    $grupito=0; 
    if ($_idgrupoRaiz=='0') {
        $grupito=$id_grupo;
    }else{
        $grupito=$_idgrupoRaiz;
    }
    $grupito;
    $e = new evento();
    $e->setIdGrupo($id_grupo);
    $e->e_log_inicio_evento($codest, 18);
    // echo $id_grupo."--".$_idgrupoRaiz;
    $tiene_horario=false;
    $msn_horarios=$horario->horario_de_hoy_teorico($grupito);
    if($msn_horarios!="No existe horario para hoy."){
        $tiene_horario=true;
    }
    if ($codmat=='MED1009') {
        $exis=$inter->verificar_registro($codest);
        if ($exis>0) {
            // no hacer nada ya tiene registros
        }else{
            $hosp=$inter->obt_hospitales();
            $nbest=$inter->nb_estudiante($codest);
            $list_h=$inter->obt_hospitales();
            $part_h=explode("|", $list_h);
            $list_dpto=$inter->lista_dpto();
            $list_prov=$inter->lista_provincias();
            ?>
            <div class="page-content">
                <div class="container">
                    <div class="blog-post single-post">
                        <div class="blog-post-content" id="resul_sol">
                            <h3>Solicitud de habilitación al Internado Rotatorio</h3>
                            <form method="post" id="sol_int" onsubmit="guardar_solicitud(); return false;">
                                <input type="hidden" name="codest" value="<?php echo $codest; ?>">
                                <input type="hidden" name="periodo" value="<?php echo $per; ?>">
                                <input type="hidden" name="carrera" value="<?php echo $carr; ?>">
                                <div align="right">Santa Cruz, <?php echo date("m"); ?> de <?php echo date("Y"); ?></div>
                                Yo, <?php echo $nbest; ?> con código <?php echo $codest; ?>, estudiante de la carrera de <?php echo $carr; ?>, informo que habiendo concluido y aprobado las asignaturas requeridas para optar al INTERNADO ROTATORIO y así finalizar mi malla curricular;  que tomo conocimiento de los requisitos internos, así como de los plazos establecidos para la entrega de los mismos, con el propósito de presentar mi postulación para la HABILITACIÓN AL INTERNADO ROTATORIO según cronograma interno. <br>
                                De la misma manera asumo el compromiso de cumplir con el tiempo de cada rotación en total apego a la normativa interna del establecimiento de salud asignado y en plena observancia del Reglamento del Internado Rotatorio emitido por el CRIDAIIC. <br>
                                El incumplimiento de alguna de las rotaciones o la solicitud de futuras modificaciones a lo establecido en el cronograma inicial, me compromete por consecuencia, a asumir las medidas administrativas que la Universidad Nacional Ecológica y el CRIDAIIC, definan como amonestación y punición. <br>
                                Por otro lado, de manera especial solicito:
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="especial" id="flexRadioDefault3" value="1" required>
                                  <label class="form-check-label" for="flexRadioDefault3">
                                    Realizar mi internado rotatorio en otro departamento en Bolivia. <select class="form-control" name="dpto"><option value="0"> - Elija - </option>
                                        <?php 
                                        foreach ($list_dpto as $key => $value) {
                                            if ($value!='') {
                                                echo "<option value='$key'>$value</option>";    
                                            }
                                        }
                                        ?>
                                    </select>
                                  </label>
                                </div> 
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="especial" id="flexRadioDefault1" value="2" required>
                                  <label class="form-check-label" for="flexRadioDefault1">
                                    Realizar la totalidad de mi internado rotatorio en el municipio de Santa Cruz de la Sierra.
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="especial" id="flexRadioDefault2" value="3" required>
                                  <label class="form-check-label" for="flexRadioDefault2">
                                    Realizar mi internado rotatorio en una provincia de la ciudad de Santa Cruz. <select class="form-control" name="prov"> <option value="0"> - Elija - </option>
                                        <?php 
                                        foreach ($list_prov as $key => $value) {
                                            if ($value!='') {
                                                echo "<option value='$key'>$value</option>";    
                                            }
                                        }
                                        ?>
                                    </select>
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="especial" id="flexRadioDefault3" value="4" required>
                                  <label class="form-check-label" for="flexRadioDefault3">
                                    Realizar mi internado rotatorio en el extranjero.
                                  </label>
                                </div>                        
                                <br>
                                Finalmente, por las características particulares de mi postulación solicito de manera especial:
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="rural" id="flexRadioDefault1" value="si">
                                  <label class="form-check-label" for="flexRadioDefault1">
                                    Iniciar el internado rotatorio con la rotación del SERVICIO SOCIAL RURAL OBLIGATORIO (Provincia)
                                  </label>
                                </div>
                                Consciente de la necesidad de sujetarme a la disponibilidad de los cupos dispuestos por el CRIDAIIC en el sistema hospitalario de nuestra región para realizar el Internado Rotatorio, es que solicito por orden de preferencia, realizar mi internado rotatorio en los siguientes hospitales: <br>
                                <div class="form-group">
                                    <label>Opcion 1: </label>
                                    <select class="form-control" name="op1" id="op1">
                                        <option value="0"> -- Elija -- </option>
                                        <?php
                                        foreach ($part_h as $key => $value) {
                                            $dat_h=explode(",", $value);
                                            $cod=$dat_h[0];
                                            $nom=$dat_h[1];
                                            $tip=$dat_h[2];
                                            echo "<option value='$cod'>$nom</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Opcion 2: </label>
                                    <select class="form-control" name="op2" id="op2">
                                        <option value="0"> -- Elija -- </option>
                                        <?php
                                        foreach ($part_h as $key => $value) {
                                            $dat_h=explode(",", $value);
                                            $cod=$dat_h[0];
                                            $nom=$dat_h[1];
                                            $tip=$dat_h[2];
                                            echo "<option value='$cod'>$nom</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Opcion 3: </label>
                                    <select class="form-control" name="op3" id="op3">
                                        <option value="0"> -- Elija -- </option>
                                        <?php
                                        foreach ($part_h as $key => $value) {
                                            $dat_h=explode(",", $value);
                                            $cod=$dat_h[0];
                                            $nom=$dat_h[1];
                                            $tip=$dat_h[2];
                                            echo "<option value='$cod'>$nom</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">REGISTRAR SOLICITUD</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            exit();
        }
    }
?>
<div class="page-content">
            <div class="container">

               <!-- Aqui va la encuesta de codigos_dev -->

               <!-- HORARIOS -->
                <div>
                    <h1> MATERIA : <?php echo $nb_materia; ?> </h1>
                    <b><?=$msn_horarios?></b>
                </div>

                <div class="section-header mb-4">
                    <div class="section-header-left">
                        <nav class="responsive-tab style-4">
                            <ul>
                                <li class="uk-active"><a href="#">Opciones de la materia</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="uk-grid-large" uk-grid>
                    <div class="uk-width-3-4@m">
                    <!-- INSERTAR POR AQUI EL ENLACE MEET -->
                    <?php
                     if($o_enl->getEnlace()!='' && $tiene_horario){
                            
                         $div_enlaces="hidden";
                         $mensaje="";

                        if($horario->enHora_to_Asistencia($grupito)){
                            $div_enlaces="";
                            $mensaje="";
                        }else{
                            $div_enlaces="hidden";
                            $mensaje="<div class='course-card course-card-list'><div class='alert alert-info'>Los enlaces solo se le habilitarán cuando esté dentro de la hora de clases o 10 minutos antes de la hora de ingreso.</div></div>";
                        }
                            ?>
                            <div id="div_googlemeet_link">
                                <?=$mensaje?>
                            </div>
                            <div class="course-card course-card-list" <?=$div_enlaces?>>
                                <div class="course-card-thumbnail">
                                    <?php
                                        if($grupo->getEGrupo()!=7){?>
                                            <a href="<?=$o_enl->getEnlace();?>" onclick="registrar_asistencias_meet(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>)" target="_blank"><img src="img/iconos/googlemeet.png"></a>
                                       <?php }else{
                                           echo '<img src="img/iconos/googlemeet.png">';
                                       }
                                    ?>                                   
                                </div>
                                <div class="course-card-body">                                    
                                        <h4><?php                                            
                                            $_enlaces=$o_enl->getEnlaces();
                                            for($e=0;$e<count($_enlaces);$e++){?>
                                                <a href="<?=$_enlaces[$e];?>" onclick="registrar_asistencias_meet(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>)" style='text-decoration:none;color:#343a40;' target="_blank">Clase virtual (enlace <?=$e+1;?>)</a>
                                                <?php
                                                echo "<br>";
                                            }
                                             // echo "Enlace clase virtual 1<br>Enlace clase virtual 2";
                                        ?></h4>
                                    <p> Este enlace nos redirecciona al Google Meet, debemos tener abierta nuestra cuenta de correo institucional.</p>
                                    <div class="course-details-info">
                                        <ul>
                                            <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                            <li>
                                                <div class="star-rating"><span class="avg"> 5 </span> <span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php 
                    }
                    ?>   
                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/recursos.png">
                                <a href="#" class="play-button-trigger" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>, 0)"></a>
                            </div>
                            <div class="course-card-body" >
                                <a href="#" onclick="ver_clasesvirtuales(<?=$id_grupo;?>, <?=$_idgrupoRaiz?>, <?=$codest;?>, 0)">
                                    <h4>Recursos didacticos </h4>
                                </a>
                                <p>Todos los recursos didacticos, material de apoyo, clases virtuales grabadas en la plataforma de Google Meet!</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                              <img src="https://storage.googleapis.com/une_segmento-one/estudiantes/img/iconos/bibliografia.png">
                              <a href="#" class="play-button-trigger" onclick="ver_biblio(<?=$codest;?>, <?=$_idgrupoRaiz?>)"></a>
                            </div>
                            <div class="course-card-body" >
                                <a href="#" onclick="ver_biblio(<?=$codest;?>, <?=$id_grupo;?>, <?=$_idgrupoRaiz?>)">
                                    <h4>Bibliografía básica </h4>
                                </a>
                                <p>Visualización de la bibliografía que utilizará el docente durante el semestre</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php 
                            if($archivo->getDatosFile($grupito)){
                        ?>
                            <div class="course-card course-card-list">
                                <div class="course-card-thumbnail">
                                    <img src="img/iconos/pdf.jpg">
                                    <a href="#" class="play-button-trigger" onclick="ver_programa_analitico(<?=$grupito?>)"></a>                      
                                </div>
                                <div class="course-card-body" >
                                    <a href="#" onclick="ver_programa_analitico(<?=$grupito?>)">
                                        <h4>Programa analitico</h4>
                                    </a>
                                    <p>Programa analitico de la materia.</p>
                                    <div class="course-details-info">
                                        <ul>
                                            <li> Registrado por <a href="#"> Jefatura de Carrera </a> </li>
                                            <li>
                                                <div class="star-rating"><span class="avg"> 5 </span> 
                                                    <span class="star"></span><span class="star"></span>
                                                    <span class="star"></span><span class="star"></span>
                                                    <span class="star"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            }
                        ?>
                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/planificacion.png">
                                <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$_idgrupoRaiz?>, 0)" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="#" onclick="ver_plamat(<?=$codest;?>, <?=$id_grupo;?>, <?=$_idgrupoRaiz?>, 0)">
                                    <h4> Planificacion academica </h4>
                                </a>
                                <p>Visualizacion de la planificacion academica, cargada por el docente.</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="course-card course-card-list">
                            <div class="course-card-thumbnail">
                                <img src="img/iconos/boletin.png">
                                <a href="#" onclick="abrir_cerrar('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>','<?= $grupito; ?>')" class="play-button-trigger"></a>
                            </div>
                            <div class="course-card-body">
                                <a href="#" onclick="abrir_cerrar('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $id_grupo; ?>','<?= $grupito; ?>')">
                                    <h4> Boletin de Notas</h4>
                                </a>
                                <p>Ver las notas obtenidas en detalle por parcial.</p>
                                <div class="course-details-info">
                                    <ul>
                                        <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                        <li>
                                            <div class="star-rating"><span class="avg"> 5 </span> <span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span><span class="star"></span><span
                                                    class="star"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        $subexiste="";
                        $tiposubgrupo="";
                        $queryverifica=$bdcon->prepare("SELECT cod,idgrupo_padre, descripcion FROM grupos_sub where idgrupo_padre='$grupito' AND descripcion='2' AND grupo LIKE '%investigacion%' LIMIT 1");
                        $queryverifica->execute();
                        while ($rowverifica= $queryverifica->fetch(PDO::FETCH_ASSOC)) {
                             $sub_grupo=$rowverifica['cod'];
                             $subexiste=$rowverifica['idgrupo_padre'];
                             $tiposubgrupo=$rowverifica['descripcion'];         
                        }                       
                    if ($tiposubgrupo==2) {
                         ?>
                            <div class="course-card course-card-list">
                                <div class="course-card-thumbnail">
                                    <img src="img/iconos/practicas.jpg">
                                    <a href="#" onclick="ver_descmat_inv('<?= $codest; ?>','<?= $_idgrupoRaiz; ?>','<?= $id_grupo; ?>','<?= $nb_materia; ?>','<?= $sub_grupo; ?>')" class="play-button-trigger"></a>
                                </div>
                                <div class="course-card-body">
                                    <a href="#" onclick="ver_descmat_inv('<?= $codest; ?>','<?= $_idgrupoRaiz; ?>','<?= $id_grupo; ?>','<?= $nb_materia; ?>','<?= $sub_grupo; ?>')">
                                        <h4>INVESTIGACIÓN</h4>
                                    </a>
                                    <p>Ingresa para ver el módulo completo de investigación donde esta el enlace de la clase virtual las notas de investigación a detalle.</p>
                                    <div class="course-details-info">
                                        <ul>
                                            <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                            <li>
                                                <div class="star-rating"><span class="avg"> 5 </span> <span
                                                     class="star"></span><span class="star"></span><span
                                                     class="star"></span><span class="star"></span><span
                                                     class="star"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php                               
                    }
                    // { 
                        if ($per=='202202') {
                            $grupito=$id_grupo;   
                        }elseif($per=='202208'){
                            $id_grupo_main=$grupo->nivGetIdgrupoMain();
                            // echo "niv1=".$id_grupo_main."<br>";
                            $aux_grupo_raiz=$grupo->getIdramaRaiz();
                            if(($id_grupo_main!=0 && $aux_grupo_raiz!=$id_grupo_main)||($id_grupo_main==0)){
                                $grupito=$aux_grupo_raiz;                               
                            }else{
                                $grupito=$id_grupo_main;
                            }
                        }



                        $subexiste="";
                        $tiposubgrupo="";
                        $queryverifica2=$bdcon->prepare(" SELECT cod, idgrupo_padre, descripcion FROM grupos_sub where idgrupo_padre='$grupito' AND descripcion='1'");
                        $queryverifica2->execute();
                        while ($rowverifica2= $queryverifica2->fetch(PDO::FETCH_ASSOC)) {
                            $sub_grupo=$rowverifica2['cod'];
                            $subexiste=$rowverifica2['idgrupo_padre'];
                            $tiposubgrupo=$rowverifica2['descripcion'];
                        }
                        if ($subexiste<>"" && $tiposubgrupo=='1') {                            
                            $query_sub= $bdcon->prepare("SELECT grupos_sub.idgrupo_padre, grupos_sub.cod_doc, grupos_sub.cod , grupos_sub.grupo FROM (grupos_sub INNER JOIN grupos_sub_listas ON grupos_sub.cod = grupos_sub_listas.cod_subgru) where grupos_sub_listas.codest='$codest' and grupos_sub.idgrupo_padre='$grupito' AND descripcion='1'");
                            $query_sub->execute();
                            $subexiste2="";
                            while ($row = $query_sub->fetch(PDO::FETCH_ASSOC)) {
                                $subexiste2=$row['idgrupo_padre']; 
                                $subgrupo=$row['cod'];
                                $doc_sub=$row['cod_doc'];
                                $subgrup=$row['grupo'];                               
                            }   
                    if ($carr=='MEDICINA') {
                            if ($subexiste2<>"") {
                                ?>
                                <div class="course-card course-card-list">
                                    <div class="course-card-thumbnail">
                                        <img src="img/iconos/practicas.jpg">
                                        <a href="#" onclick="ver_descmat_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')" class="play-button-trigger"></a>
                                    </div>
                                    <div class="course-card-body">
                                        <a href="#" onclick="ver_descmat_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')">
                                            <h4> Ingreso a prácticas</h4>
                                        </a>
                                        <p>Ingresa para ver el módulo completo de prácticas donde esta el enlace de la clase virtual las notas de práctica a detalle.</p>
                                        <div class="course-details-info">
                                            <ul>
                                                <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                                <li>
                                                    <div class="star-rating"><span class="avg"> 5 </span> <span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php                  
                            }                             
                    }else{
                            if ($subexiste2<>"") {
                            ?>
                                <div class="course-card course-card-list">
                                    <div class="course-card-thumbnail">
                                        <img src="img/iconos/practicas.jpg">
                                        <a href="#" onclick="ver_descmat_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')" class="play-button-trigger"></a>
                                    </div>
                                    <div class="course-card-body">
                                        <a href="#" onclick="ver_descmat_sub('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')">
                                            <h4> Ingreso a prácticas</h4>
                                        </a>
                                        <p>Ingresa para ver el módulo completo de prácticas donde esta el enlace de la clase virtual las notas de práctica a detalle.</p>
                                        <div class="course-details-info">
                                            <ul>
                                                <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                                <li>
                                                    <div class="star-rating"><span class="avg"> 5 </span> <span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php      
                            }else{
                            ?>
                                <!--<div class="course-card course-card-list">
                                    <div class="course-card-thumbnail">
                                        <img src="img/iconos/inscripci.jpg">
                                        <a href="#" onclick="registrar_subgrupo('<?=$codest; ?>','<?=$per; ?>','<?=$grupito; ?>','<?=$codmat; ?>')" class="play-button-trigger"></a>
                                    </div>
                                    <div class="course-card-body">
                                        <a href="#" onclick="registrar_subgrupo('<?=$codest; ?>','<?=$per; ?>','<?=$grupito; ?>','<?=$codmat; ?>')">
                                            <h4> Inscripción a prácticas</h4>
                                        </a>
                                        <p>Incríbete a los subgrupos elegí tu grupo presencial o virtual  con los horarios que que te conviene.</p>
                                        <div class="course-details-info">
                                            <ul>
                                                <li> Registrada por <a href="#"> Dpto. de Sistemas </a> </li>
                                                <li>
                                                    <div class="star-rating"><span class="avg"> 5 </span> <span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span><span class="star"></span><span
                                                        class="star"></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>-->
                            <?php
                            }
                    }  
                }
                    $tiene_invertida=false;

                    $query_sub= $bdcon->prepare("SELECT grupos_sub.idgrupo_padre, grupos_sub.cod_doc, grupos_sub.cod , grupos_sub.grupo FROM (grupos_sub INNER JOIN grupos_sub_listas ON grupos_sub.cod = grupos_sub_listas.cod_subgru) where grupos_sub_listas.codest='$codest' and grupos_sub.idgrupo_padre='$grupito' AND descripcion=7");
                    $query_sub->execute();
                    $subexiste2="";
                    while ($row = $query_sub->fetch(PDO::FETCH_ASSOC)) {
                        $subexiste2=$row['idgrupo_padre']; 
                        $subgrupo=$row['cod'];
                        $doc_sub=$row['cod_doc'];
                        $subgrup=$row['grupo'];               
                    }
                    if($subexiste2<>""){
                        $tiene_invertida=true;
                    }else{
                        $query_sub2= $bdcon->prepare("SELECT grupos_sub.idgrupo_padre, grupos_sub.cod_doc, grupos_sub.cod , grupos_sub.grupo FROM (grupos_sub INNER JOIN grupos_sub_listas ON grupos_sub.cod = grupos_sub_listas.cod_subgru) where grupos_sub_listas.codest='$codest' and grupos_sub.idgrupo_padre='$id_grupo' AND descripcion=7");
                        $query_sub2->execute();
                        $subexiste2="";
                        while ($row2 = $query_sub2->fetch(PDO::FETCH_ASSOC)) {
                            $subexiste2=$row2['idgrupo_padre']; 
                            $subgrupo=$row2['cod'];
                            $doc_sub=$row2['cod_doc'];
                            $subgrup=$row2['grupo'];               
                        }

                        if($subexiste2<>""){
                            $grupito=$id_grupo;
                            $tiene_invertida=true;
                        }else if($grupo->esNivelacion()){

                            $grupito=$grupo->nivGetIdgrupoMain();
                            if($grupito!=0){
                                $query_sub3= $bdcon->prepare("SELECT grupos_sub.idgrupo_padre, grupos_sub.cod_doc, grupos_sub.cod, grupos_sub.grupo FROM (grupos_sub INNER JOIN grupos_sub_listas ON grupos_sub.cod = grupos_sub_listas.cod_subgru) where grupos_sub_listas.codest='$codest' and grupos_sub.idgrupo_padre='$grupito' AND descripcion=7");
                                $query_sub3->execute();
                                $subexiste3="";
                                while ($row3 = $query_sub3->fetch(PDO::FETCH_ASSOC)) {
                                    $subexiste3=$row3['idgrupo_padre']; 
                                    $subgrupo=$row3['cod'];
                                    $doc_sub=$row3['cod_doc'];
                                    $subgrup=$row3['grupo'];               
                                }
                                if($subexiste3<>""){
                                    $tiene_invertida=true;
                                }else{
                                    $tiene_invertida=false;
                                }
                            }else{
                                $tiene_invertida=false;
                            }

                        }else{
                            $tiene_invertida=false;
                        }
                    }

                    if($tiene_invertida){
                    ?>
                       <div class="course-card course-card-list">
                           <div class="course-card-thumbnail">
                               <img src="img/iconos/invertida.png">

                               <a href="#" onclick="ver_mat_subinvertida('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')" class="play-button-trigger"></a>
                           </div>
                           <div class="course-card-body">
                               <a href="#" onclick="ver_mat_subinvertida('<?=$codmat ;?>','<?= $codest; ?>','<?= $per; ?>','<?= $grupito; ?>','<?= $subgrupo; ?>','<?= $nb_materia; ?>','<?= $doc_sub; ?>','<?= $subgrup; ?>')">
                                   <h4>Aula invertida</h4>
                               </a>
                               <p>Ingresa para ver el módulo completo de la clase invertida y sus recursos.</p>
                               <div class="course-details-info">
                                   <ul>
                                       <li> Registrada por <a href="#"> Docente de la materia </a> </li>
                                       <li>
                                           <div class="star-rating"><span class="avg"> 5 </span> <span
                                                class="star"></span><span class="star"></span><span
                                                class="star"></span><span class="star"></span><span
                                                class="star"></span>
                                           </div>
                                       </li>
                                   </ul>
                               </div>
                           </div>
                       </div>
                   <?php                          
               }
                    ?>
                </div>
            </div>
        </div>
    </div>