<?php

    include "../includes/_event_log.php";
    include "../includes/user_session.php";
    $error="";

    // tipo_evento, id_grupo, id_subgrupo, id_clase, momento, enlace
    if(isset( $_REQUEST['tipo_evento']) && !empty($_REQUEST['tipo_evento'])) {
        $tipo_evento = $_REQUEST['tipo_evento'];
    } else { $tipo_evento = 0; }

    if(isset( $_REQUEST['id_grupo']) && !empty($_REQUEST['id_grupo'])) {
        $id_grupo = $_REQUEST['id_grupo'];
    } else { $id_grupo = 0; }

    if(isset( $_REQUEST['id_subgrupo']) && !empty($_REQUEST['id_subgrupo'])) {
        $id_subgrupo = $_REQUEST['id_subgrupo'];
    } else { $id_subgrupo = 0; }

    if(isset( $_REQUEST['id_clase']) && !empty($_REQUEST['id_clase'])) {
        $id_clase = $_REQUEST['id_clase'];
    } else { $id_clase = 0; }

    if(isset( $_REQUEST['momento']) && !empty($_REQUEST['momento'])) {
        $momento = $_REQUEST['momento'];
    } else { $momento = 0; }

    if(isset( $_REQUEST['url']) && !empty($_REQUEST['url'])) {
        $enlace = $_REQUEST['url'];
    } else { $enlace = ""; }

    try{
        // $tipo_evento=$_REQUEST['tipo_evento'];
        $user = new UserSession();
        $e = new evento();

        $e->setIdGrupo($id_grupo);
        $e->setIdSubGrupo($id_subgrupo);
        $e->setIdClase($id_clase);
        $e->setMomento($momento);
        $e->setEnlace($enlace);

        if( $e->e_log_inicio_evento($user->getCurrentUser(), $tipo_evento) ){
            $error="Evento  nuevo registrado !!!";
        }else{
            $error="No se pudo registrar el nuevo evento.";
        }
    } catch (Exception $e){
        $error.="<br>ops, no se pudo registrar el evento.";
    }
    
    // echo $error;
?>