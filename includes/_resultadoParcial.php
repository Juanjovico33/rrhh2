<?php
    // include "conexion.php";

    class examen{
        private $codest;
        private $codbanco;
        private $ponderacion;
        private $nota_reg;
        private $hora_transcurrida;
        private $contador;
        private $items;

        private $error;

        public function __construct(){
            $this->codest=0;
            $this->codbanco=0;
            $this->ponderacion=0;
            $this->nota_reg=0;
            $this->hora_transcurrida;
            $this->contador=0;
            $this->items="";
            $this->error="";
        }

        public function getCodest() {
            return $this->codest;
        }
        public function setCodest($_codest) {
            $this->codest = $_codest;
        }
        public function getCodBanco() {
            return $this->codbanco;
        }
        public function setCodBanco($_codbanco) {
            $this->codbanco = $_codbanco;
        }
        public function getPonderacion() {
            return $this->ponderacion;
        }
        public function setPonderacion($_ponde) {
            $this->ponderacion = $_ponde;
        }

        public function getNota() {
            return $this->nota_reg;
        }
        public function setNota($_nota) {
            $this->nota_reg = $_nota;
        }

        public function getHora_transcurrida() {
            return $this->hora_transcurrida;
        }
        public function setHora_transcurrida($_hora) {
            $this->hora_transcurrida = $_hora;
        }

        public function getContador() {
            return $this->contador;
        }
        public function setContador($_contador) {
            $this->contador = $_contador;
        }
        public function getItems() {
            return $this->items;
        }
        public function setItems($_items) {
            $this->items = $_items;
        }
        
        public function getError() {
            return $this->error;
        }
        public function setError($_error) {
            $this->error .= $_error."<br>";
        }

        
        function cargarBanco($_idbanco, $_codest, $_ponde){ 
            include "conexion.php";
            $nro_item=0;
            $item;
            if($_idbanco!=0){

                try {
                    $query="SELECT ep.cod_preg, p.pregunta, p.imagen FROM sainc.plat_doc_intentos_est_deta ep LEFT JOIN plat_doc_banco_preg p ON ep.cod_preg=p.id where ep.codest=$_codest and ep.cod_banco=$_idbanco GROUP BY ep.cod_preg";
                    // $this->setError($query);
                    $_consultando = $bdcon->prepare($query);
                    $_consultando->execute();

                    while ($row = $_consultando->fetch(PDO::FETCH_ASSOC)) {   
                        $nro_item++;
                        $id_preg = $row['cod_preg'];
                        $pregunta = $row['pregunta'];
                        $imagen = $row['imagen'];
                        if($imagen==""){
                            $_pregunta=$pregunta;
                        }else{
                            $_pregunta=array("pregunta"=>$pregunta, "imagen"=>$imagen);
                        }

                        $_qRes="SELECT er.cod_resp, er.nota, r.eleccion, r.imagen FROM sainc.plat_doc_intentos_est_deta er LEFT JOIN plat_doc_banco_resp r ON er.cod_resp=r.id WHERE er.codest=$_codest AND er.cod_banco=$_idbanco and er.cod_preg=$id_preg GROUP BY er.cod_resp, er.nota";
                        // $this->setError($_qRes);
                        $_consulting = $bdcon->prepare($_qRes);
                        $_consulting->execute();
                        $_respuestas=array();
                        while ($ro = $_consulting->fetch(PDO::FETCH_ASSOC)) {   
                            $id_resp = $ro['cod_resp'];
                            $nota = $ro['nota'];
                            $o_resp = $ro['eleccion'];
                            $o_imagen = $ro['imagen'];
                            if($nota>0){
                                $nota=$nota/100;
                            }else if($id_resp==0){
                                $o_resp="<font color='red'>Sin respuesta<br>seleccionada</font>";
                            }
                            $_respuestas[]=array("imagen"=>$o_imagen, "eleccion"=>$o_resp, "nota"=>$nota);
                        }

                        $item[]=array("nro"=>$nro_item, "pregunta"=>$_pregunta, "respuestas"=>$_respuestas);

                        $this->setItems($item);
                        $this->setContador($_consultando->rowCount());
                        $this->setCodBanco($_idbanco);
                        $this->setCodest($_codest);
                        $this->setPonderacion($_ponde);
                    }
                    
                } catch (PDOException $e) {
                    $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
                    return false;
                }
                if($_consultando->rowCount()>0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        function mostrar_examen(){
            $item=$this->getItems();
            $sumatoria_nota=0;
            echo $this->getDatosEx_encabezado();
            echo "<div><table  class='uk-table uk-table-hover uk-text-small'>";
            echo "<tr><th>Nro<th>Pregunta</th><th>Mis respuestas</th><th>Ptos</th><th>Rev.</th></tr>";
            for($i=0; $i<count($item); $i++){
                $nro=$item[$i]["nro"];
                $pregunta=$item[$i]["pregunta"];
                if(is_array($pregunta)){
                    $pregunta_imagen = $pregunta["imagen"];
                    $_pregunta = $pregunta["pregunta"];

                    $pregunta="<table><tr><img src='https://storage.googleapis.com/une_segmento-one/docentes/$pregunta_imagen'></tr><tr>$_pregunta</tr></table>";
                }
                
                $respuestas=$item[$i]["respuestas"];
                $c_resp=count($respuestas);
                $tr_nota=0;
                $_respuestas=""; $o_respuesta="";
                for($j=0; $j<$c_resp; $j++){
                    $r_imagen=$respuestas[$j]["imagen"];
                    $r_eleccion=$respuestas[$j]["eleccion"];
                    $r_nota=$respuestas[$j]["nota"];
                    $tr_nota+=$r_nota;
                    $o_resp="";
                    if($r_imagen!=""){
                        $o_resp.="<img src='https://storage.googleapis.com/une_segmento-one/docentes/$r_imagen'>".$r_eleccion."<br>";
                    }else{
                        $o_resp.="".$r_eleccion."<br>";
                    }
                    
                }
                
                $sumatoria_nota+=$tr_nota;
                $revision="";
                if($tr_nota>0 && $tr_nota==1){
                    $revision="<p style='color:green;'>&#10003;</p>"; // &#10003;
                }else if($tr_nota>0 && $tr_nota<1){
                    $revision="<p style='color:orange;'>&#9083;</p>";
                }else{
                    $revision="<p style='color:red;'>&#10006;</p>";
                }
                $_respuestas="<td>".$o_resp."</td><td>".$tr_nota."</td><td><font size=5>$revision</font></td>";
                
                echo "<tr><td>$nro</td><td>$pregunta</td>".$_respuestas."</tr>";
            }
            
            $nro_preguntas=$this->getCant_preguntas();
            $ponde=$this->getPonderacion();
            // $nro_preguntas=$ponde; // count($item)
            if($ponde!=0){
                $ex_nota=($ponde*$sumatoria_nota)/$nro_preguntas;
                // $ex_nota=number_format(intval($ex_nota),2);
                // $ex_nota=number_format($ex_nota,2); // nota calculada automaticamente a base de respuestas
                $ex_nota=$this->getNota(); // obteniendo nota registrada
            }else{
                $ex_nota="null";
            }
            
            echo "<tr><td colspan=4 style='text-align:right; font-weight:bold;'>SUMATORIA DE PUNTOS : </td><td>$sumatoria_nota</td><tr>";
            echo "<tr><td colspan=4 style='text-align:right; font-weight:bold;'>PONDERACION : </td><td>$ponde</td><tr>";
            echo "<tr><td colspan=4 style='text-align:right; font-weight:bold;'>NOTA : </td><td style='font-weight:bold;'>$ex_nota</td><tr>";
            
            echo "</table></div>";
        }

        function getCant_preguntas(){
            $ponderacion=$this->getPonderacion();
            $nro_preg=0;
            if($ponderacion==20 || $ponderacion==40 || $ponderacion==60 || $ponderacion==50){
                $nro_preg=20;
            }else if($ponderacion==100){
                $nro_preg=40;
            }else if($ponderacion==70){
                $nro_preg=35;
            }else if($ponderacion==30){
                $nro_preg=15;
            }else{
                $nro_preg=$ponderacion;
            }
            return $nro_preg;
        }

        function getStringParcial($parcial){
            $cadena="";
            if($parcial==1){
                $cadena="1° PARCIAL";
            }else if($parcial==2){
                $cadena="2° PARCIAL";
            }else if($parcial==3){
                $cadena="3° PARCIAL";
            }else if($parcial==4){
                $cadena="4° PARCIAL";
            }else{
                $cadena="SIN DEFINIR EL PARCIAL";
            }
            return $cadena;
        }

        function getDatosEx_encabezado(){
            include "conexion.php";

            $_codest=$this->getCodest();
            $_banco=$this->getCodBanco();

            $resultado="<div class='uk-text-small uk-text-bold uk-text-uppercase'><table align='center'>";
            $string_parcial="";
            $string_fecha="";
            $string_estudiante="";
            $string_materia="";
            $string_inicio_fin="";
            try {
                $q="SELECT fecha, hora, codgrupo, parcial, cod_act, reco FROM plat_doc_intentos_est where cod_ban=$_banco and codest=$_codest and estado=1 limit 1";

                $_consultando = $bdcon->prepare($q);
                $_consultando->execute();

                while ($row = $_consultando->fetch(PDO::FETCH_ASSOC)) {   
                    $fecha = $row['fecha'];
                    $hora = $row['hora'];
                    $codgrupo = $row['codgrupo'];
                    $parcial = $row['parcial'];
                    $id_act = $row['cod_act'];
                    $this->setNota($row['reco']); // nota registrada en db

                    $q_ini_fin="SELECT concat(DATE_FORMAT(hora_d,'%H:%i'), ' - ',DATE_FORMAT(hora_h,'%H:%i'), ' (',desde, ')') as inicio_fin FROM plat_doc_actividades where id=$id_act";
                    $con_if_fecha = $bdcon->prepare($q_ini_fin);
                    $con_if_fecha->execute();
                    $res_ini = $con_if_fecha->fetch(PDO::FETCH_ASSOC);
                    $hora_ini_fin = $res_ini['inicio_fin'];

                    $q_est="SELECT concat(codest, ' - ',nombcompleto) as nombre FROM estudiante where codest=$_codest";
                    $con_est = $bdcon->prepare($q_est);
                    $con_est->execute();
                    $rest = $con_est->fetch(PDO::FETCH_ASSOC);
                    $nombre_completo = $rest['nombre'];

                    $q_mat = "SELECT CONCAT(m.Sigla, ' - ', m.Descripcion, ' GRUPO ', g.Descripcion) as materia FROM grupos g LEFT JOIN materias m ON (g.CodCarrera=m.CodCarrera AND m.Sigla=g.CodMateria) where CodGrupo=$codgrupo";
                    $con_mat = $bdcon->prepare($q_mat);
                    $con_mat->execute();
                    $rmat = $con_mat->fetch(PDO::FETCH_ASSOC);
                    $nombre_materia = $rmat['materia'];

                    $string_parcial="<tr><td>Nombre de parcial </td><td>: ".$this->getStringParcial($parcial)."</td></tr>";
                    $string_inicio_fin="<tr><td>Hora y fecha de examen </td><td>: ".$hora_ini_fin."</td></tr>";
                    $string_fecha="<tr><td>Fecha y hora registrada </td><td>: ".$fecha." ".$hora."</td></tr>";
                    $string_estudiante="<tr><td>Estudiante </td><td>: ".$nombre_completo."</td></tr>";
                    $string_materia="<tr><td>Materia </td><td>: ".$nombre_materia."</td></tr>";
                }
                
                $resultado.= $string_parcial."".$string_inicio_fin."".$string_fecha."".$string_estudiante."".$string_materia."</table></div>";
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
                return false;
            }
            return $resultado;
        }

        function puede_ver(){
            include "conexion.php";

            $_codest=$this->getCodest();
            $_banco=$this->getCodBanco();
            $fecha="";

            try {
                $q_registro="SELECT CONCAT(fecha, ' ',hora) AS registrado FROM plat_doc_intentos_est where cod_ban=$_banco and codest=$_codest and estado=1 limit 1";

                $_consultando = $bdcon->prepare($q_registro);
                $_consultando->execute();

                while ($row = $_consultando->fetch(PDO::FETCH_ASSOC)) {   
                    $fecha = $row['registrado'];
                }

                if($fecha!=""){ 
                     $q="SELECT TIMESTAMPDIFF(hour, '$fecha',now()) as horas";
                     $_cons = $bdcon->prepare($q);
                     $_cons->execute();
                     $rHoras = $_cons->fetch(PDO::FETCH_ASSOC);
                     $this->setHora_transcurrida($rHoras['horas']);
                     if($this->getHora_transcurrida()>=24){
                         return true;
                     }else{
                         return false;
                     }
                }else{
                    return false;
                }

            } catch (PDOException $e) {
                $this->error .= 'La consulta a fallado' . $e->getMessage().'<br>';
                return false;
            }
        }

        function puede_ver_enfecha(){
            include "conexion.php";
            $fecha_apartir="2022-05-19";
            $_codest=$this->getCodest();
            $_banco=$this->getCodBanco();
            $fecha="";

            try {
                $q_registro="SELECT fecha FROM plat_doc_intentos_est where cod_ban=$_banco and codest=$_codest and estado=1 limit 1";

                $_consultando = $bdcon->prepare($q_registro);
                $_consultando->execute();
                while ($row = $_consultando->fetch(PDO::FETCH_ASSOC)) {   
                    $fecha = $row['fecha'];
                }

                if($fecha!=""){ 
                     if($fecha>=$fecha_apartir){
                         return true;
                     }else{
                         return false;
                     }
                }else{
                    return false;
                }

            } catch (PDOException $e) {
                $this->error .= 'La consulta a fallado' . $e->getMessage().'<br>';
                return false;
            }
        }
    }
?>