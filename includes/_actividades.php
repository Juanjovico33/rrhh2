<?php
    include "_grupo.php";
    class cvirtual{
        private $item;
        private $id_clase;
        private $titulo;
        private $enl_embed;
        private $fecha_pub;
      
        private $pla_nbtema;
        private $pla_nroclase;
        private $pla_codclase;
        
        private $id_grupo;

        private $momento1;
        private $momento2;
        private $momento3;
        private $momento4;
        private $momento5;

        private $error;

        function __construct(){
            $this->item=0;
            $this->id_clase=0;
            $this->titulo='';
            $this->enl_embed='';
            $this->fecha_pub='';
            
            $this->pla_nbtema='';
            $this->pla_nroclase=0;
            $this->pla_codclase=0;
            $this->id_grupo = 0;
            $this->error='';

            $this->momento1=null;
            $this->momento2=null;
            $this->momento3=null;
            $this->momento4=null;
            $this->momento5=null;

            $this->tmp_momento2_p_r=null;
            $this->tmp_momento4_r=null;
            $this->tmp_momento5_pd=null;
            $this->tmp_momento5_p=null;
        }

        function set_init_cvirtual($_item, $_id_clase, $_titulo, $_enl_embed, $_fecha_pub, $_pla_nb_tema, $_pla_nroclase, $_pla_codclase, $_idgrupo){
            $this->item=$_item;
            $this->id_clase=$_id_clase;
            $this->titulo=$_titulo;
            $this->enl_embed=$_enl_embed;
            $this->fecha_pub=$_fecha_pub;
            
            $this->pla_nbtema=$_pla_nb_tema;
            $this->pla_nroclase=$_pla_nroclase;
            $this->pla_codclase=$_pla_codclase;
            $this->id_grupo = $_idgrupo;

            $this->momento1=$this->getMomento1($this->id_clase);
            $this->momento2=$this->getMomento2($this->id_clase);
            $this->momento3=$this->getMomento3($this->id_clase);
            $this->momento4=$this->getMomento4($this->id_clase);
            $this->momento5=$this->getMomento5($this->id_clase);
        }

        public function getItem() {
            return $this->item;
        }

        public function setItem($item) {
            $this->item = $item;
        }

        public function getId_clase() {
            return $this->id_clase;
        }

        public function setId_clase($id_clase) {
            $this->id_clase = $id_clase;
        }

        public function getTitulo() {
            return $this->titulo;
        }

        public function setTitulo($titulo) {
            $this->titulo = $titulo;
        }

        public function getEnl_embed() {
            return $this->enl_embed;
        }

        public function setEnl_embed($enl_embed) {
            $this->enl_embed = $enl_embed;
        }

        public function getFecha_pub() {
            return $this->fecha_pub;
        }

        public function setFecha_pub($fecha_pub) {
            $this->fecha_pub = $fecha_pub;
        }


        public function getPla_nbtema() {
            return $this->pla_nbtema;
        }

        public function setPla_nbtema($pla_nbtema) {
            $this->pla_nbtema = $pla_nbtema;
        }

        public function getPla_nroclase() {
            return $this->pla_nroclase;
        }

        public function setPla_nroclase($pla_nroclase) {
            $this->pla_nroclase = $pla_nroclase;
        }

        public function getPla_codclase() {
            return $this->pla_codclase;
        }

        public function setPla_codclase($pla_codclase) {
            $this->pla_codclase = $pla_codclase;
        }

        public function getId_grupo() {
            return $this->id_grupo;
        }

        public function setId_grupo($idgrupo) {
            $this->id_grupo = $idgrupo;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }
        
        public function getMomento1($id_clase){
            include "conexion.php";
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m1 where cod_grupo=$this->id_grupo and cod_clase=$id_clase and estado=1");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->momento1[]=array(
                            'id_m1'=>$row['cod'],
                            'url'=>$row['direccion'],
                            'tipo'=>$row['tipo']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->momento1=null;
                    $this->error .= 'Error al obtener el registro de momento 1 : ' . $e->getMessage();
                }
            return $this->momento1;
        }
        public function getMomento2($id_clase){
            include "conexion.php";
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m2 where cod_gru=$this->id_grupo and cod_clase=$id_clase and estado=1");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->momento2[]=array(
                            'id_m2'=>$row['cod'],
                            'nombre_foro'=>$row['nb_foro'],
                            'fecha_pub'=>$row['fec_pub']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->momento2=null;
                    $this->error .= 'Error al obtener el registro de momento 2 : ' . $e->getMessage();
                }
            return $this->momento2;
        }

        public function getMomento2_preYres($cod_foro){
            include "conexion.php";
            $respuesta=null;
            //tmp_momento2_p_r
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m2_preguntas where cod_foro=$cod_foro");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $respuesta=$this->getMomento2_respuesta($row['cod']);
                        $this->tmp_momento2_p_r[]=array(
                            'id_m2_pre'=>$row['cod'],
                            'pregunta'=>$row['pregunta'],
                            'tipo'=>$row['tipo'],
                            $respuesta
                        );
                    }
                }
                catch(PDOException $e){
                    $this->error .= 'Error al obtener el registro  preguntas de momento 2 : ' . $e->getMessage();
                }
                return $this->tmp_momento2_p_r;
        }

        public function getMomento2_respuesta($cod_pregunta){
            include "conexion.php";
            $respuestas=null;
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m2_respuestas where cod_pregunta=$cod_pregunta");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $respuestas[]=array(
                            'id_m2_res'=>$row['cod'],
                            'respuesta'=>$row['respuesta'],
                            'codest'=>$row['codest']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->error .= 'Error al obtener el registro respuesta de momento 2 : ' . $e->getMessage();
                }
            return $respuestas;
        }

        public function getMomento3($id_clase){
            include "conexion.php";
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m3 where cod_gru=$this->id_grupo and cod_cla=$id_clase and estado=1");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->momento3[]=array(
                            'id_m3'=>$row['cod'],
                            'nombre_clase'=>$row['nbcla'],
                            'enl_embed'=>$row['embed'],
                            'resumen'=>$row['resumen'],
                            'fecha_pub'=>$row['fec_pub']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->momento3=null;
                    $this->error .= 'Error al obtener el registro de momento 3 : ' . $e->getMessage();
                }
                return $this->momento3;
        }
        public function getMomento4($id_clase){
            include "conexion.php";
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m4 where cod_gru=$this->id_grupo and cod_clase=$id_clase and estado=1");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->momento4[]=array(
                            'id_m4'=>$row['cod'],
                            'tipo_resp'=>$row['tipo_resp'],
                            'fecha_in'=>$row['fec_desde'],
                            'fecha_pub'=>$row['fec_pub']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->momento4=null;
                    $this->error .= 'Error al obtener el registro de momento 4 : ' . $e->getMessage();
                }
            return $this->momento4;
        }
        public function getMomento5($id_clase){
            include "conexion.php";
            try{
                $class = $bdcon->prepare("SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$this->id_grupo and  cod_clase=$id_clase and estado=1");
                $class->execute();
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->momento5[]=array(
                            'id_m5'=>$row['cod'],
                            'codigo_banco'=>$row['cod_banco'],
                            'fecha_pub'=>$row['fec_pub']
                        );
                    }
                }
                catch(PDOException $e){
                    $this->momento5=null; 
                    $this->error .= 'Error al obtener el registro de momento5 : ' . $e->getMessage();
                }
            return $this->momento5;
        }

        public function get_dev_query_moment(){
            $id_clase=$this->getId_clase();
            $m1="SELECT * FROM aa_clases_virtuales_m1 where cod_grupo=$this->id_grupo and cod_clase=$id_clase and estado=1;";
            $m2="SELECT * FROM aa_clases_virtuales_m2 where cod_gru=$this->id_grupo and cod_clase=$id_clase and estado=1;";
            $m3="SELECT * FROM aa_clases_virtuales_m3 where cod_gru=$this->id_grupo and cod_cla=$id_clase and estado=1;";
            $m4="SELECT * FROM aa_clases_virtuales_m4 where cod_gru=$this->id_grupo and cod_clase=$id_clase and estado=1;";
            $m5="SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$this->id_grupo and  cod_clase=$id_clase and estado=1;";
            $nl="<br>";
            $momentos=$m1.$nl.$m2.$nl.$m3.$nl.$m4.$nl.$m5;
            return $momentos;
        }

        public function getMomento1_vhtml(){
            $m1=null; $html='';
            $m1=$this->momento1;
             if(!is_null($m1)){
                for($j=0;$j<count($m1);$j++){
                    // $html.='<br>m1'.'-'.$m1[$j]['id_m1'].'-'.$m1[$j]['url'].'-'.$m1[$j]['tipo'];
                    if($m1[$j]['url']!=''){
                        $html.="URL: <a href='".$m1[$j]['url']."' target='_blank'>".$m1[$j]['url']."</a><br>";
                    }
                }
            }else{
                $html = "Sin registros";
            }
            return $html;
        }

        public function getMomento2_vhtml($codest, $div){
            $m2=null; $html=''; $tforo=''; $_preguntasyrespuestas=null; $respuestas='';
            $m2=$this->momento2;
            if(!is_null($m2)){
                $html.='<table>';
                for($j=0;$j<count($m2);$j++){
                    // $html.='<br>m2'.'-'.$m2[$j]['id_m2'].'-'.$m2[$j]['nombre_foro'].'-'.$m2[$j]['fecha_pub'];
                    if($m2[$j]['nombre_foro']!=''){
                        $tforo='Nombre Foro: '.$m2[$j]['nombre_foro'].' - '.$m2[$j]['fecha_pub'].'</a><br>';
                    }else{
                        $tforo='Foro sin nombre - '.$m2[$j]['fecha_pub'].'</a><br>';
                    }
                    $_preguntasyrespuestas=$this->getMomento2_preYres($m2[$j]['id_m2']);

                    if(!is_null($_preguntasyrespuestas)){
                        $item='';
                        for($i=0;$i<count($_preguntasyrespuestas);$i++){
                            $num=$i+1; $_todasresp='';
                            if(!is_null($_preguntasyrespuestas[$i])){
                                $_resparray=$_preguntasyrespuestas[$i];
                                for($k=0;$k<count($_resparray[0]);$k++){
                                    // $_todasresp.=count($_resparray).'-'.$i;
                                    $_todasresp.=$_resparray[0][$k]['respuesta'].' - ';
                                }
                            }else{
                                $_todasresp='Sin respuesta';
                            }
                            $item.= $num.' '.$_preguntasyrespuestas[$i]['pregunta'].' R. '.$_todasresp.'<br>';
                        }
                        $respuestas=$item;
                    }else{
                        $respuestas='No existen preguntas en esta clase';
                    }
                    
                    $html.='<tr><td>'.$tforo.'</td></tr>';
                    $html.='<tr><td>'.$respuestas.'</td></tr>';
                    // $html.=var_dump($_preguntasyrespuestas);
                    $html.='<form id="form'.$m2[$j]['id_m2'].'"><tr>&nbsp;</tr><tr><td>Responder:<textarea id="text_pregunta_'.$m2[$j]['id_m2'].'"></textarea></td><td><button onclick="'.
                    'set_M2preguntaforo('.$this->id_grupo.','.$this->id_clase.','.$codest.','.$m2[$j]['id_m2'].','.$div.')">Enviar</button></td></tr></form>';
                }
                $html.='</table>';
                if($html==''){
                    $html = "Sin registros";
                }
            }else{
                $html = "Sin registros";
            }
            return $html;
        }


        public function getMomento3_vhtml(){
            $m3=null; $html='';
            $m3=$this->momento3;
            if(!is_null($m3)){
                $html.='<table>';
                for($j=0;$j<count($m3);$j++){
                    $nb_class='';
                    // $html.='<br>m3'.'-'.$m3[$j]['id_m3'].'-'.$m3[$j]['nombre_clase'].'-'.$m3[$j]['enl_embed'].'-'.$m3[$j]['resumen'].'-'.$m3[$j]['fecha_pub'];
                    if($m3[$j]['nombre_clase']==''){
                        $nb_class='Sin nombre de clase';
                    }else{
                        $nb_class=$m3[$j]['nombre_clase'];
                    }
                    $html.='<tr><td>'.$nb_class.' - '.$m3[$j]['fecha_pub'].'</td></tr>';
                    $tiene_embed=strpos($m3[$j]['enl_embed'], 'embed');
                    if($tiene_embed>0){
                        $html.='<tr><td>'.$m3[$j]['enl_embed'].'</td></tr>';
                    }else{
                        $html.='<tr><td><a href="'.$m3[$j]['enl_embed'].'" target="_blank" >'.$m3[$j]['enl_embed'].'</a></td></tr>';
                    }                 
                    $html.='<tr><td>RESUMEN: '.$m3[$j]['resumen'].'</td></tr>';
                }
                $html.='</table>';
            }else{
                $html = "Sin registros";
            }
            return $html;
        }

        public function getMomento4_vhtml(){
            $m4=null; $html='';
            $m4=$this->momento4;
            if(!is_null($m4)){
                $html.='<table>';
                for($j=0;$j<count($m4);$j++){
                    // $html.='<br>m4'.'-'.$m4[$j]['id_m4'].'-'.$m4[$j]['tipo_resp'].'-'.$m4[$j]['fecha_in'].'-'.$m4[$j]['fecha_pub'];
                    if($m4[$j]['tipo_resp']==0){
                        $html.='<tr>Publicación: Sin publicaciones </tr>';
                    }else{
                        $html.='<tr>Publicación: '.$m4[$j]['tipo_resp'].'</tr>';
                        $html.='<tr>Fecha publicación: '.$m4[$j]['fecha_pub'].'</tr>';
                        $html.='<tr></tr>';
                    }
                }
                $html.='</table>';
            }else{
                $html = "Sin registros";
            }
            return $html;
        }
        public function getMomento5_vhtml(){
            $m5=null; $html='';
            $m5=$this->momento5;
            if(!is_null($m5)){
                $html.='<table>';
                for($j=0;$j<count($m5);$j++){
                    // $html.='<br>m5'.'-'.$m5[$j]['id_m5'].'-'.$m5[$j]['codigo_banco'].'-'.$m5[$j]['fecha_pub'];
                    if($m5[$j]['codigo_banco']==0){
                        $html.='<tr>Banco: Sin de preguntas asignado </tr>';
                    }else{
                        $html.='<tr>Banco: '.$m5[$j]['id_m5'].'</tr>';
                        $html.='<tr>Fecha publicación: '.$m5[$j]['fecha_pub'].'</tr>';
                        $html.='<tr></tr>';
                    }
                }
                $html.='</table>';
            }else{
                $html = "Sin registros";
            }
            return $html;
        }
    }

    class actividad{
        private $clases;
        private $error;

        public function __construct(){
            $this->clases=null;
            $this->error='';
        }
        public function getClases(){
            return $this->clases;
        }
        public function setClases($clases){
            $this->clases=$clases;
        }
        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        public function getClasesVirtuales($_grupos){
            include "conexion.php";
            $clase; $this->item=0;
            $q="SELECT * FROM aa_clases_virtuales where id_grupo=$_grupos and estado=1 order by fecha_pub";
            $d_grupo=new grupo();
            $d_grupo->getDatosGrupo($_grupos);
            
            $_idperiodo=$d_grupo->getPeriodo();

            try{
                $class = $bdcon->prepare($q);
                $class->execute();
                
                if($class->rowCount()<=0){
                    //--------------
                    $cort_gestion=substr($_idperiodo, 0, 4);
                    $cort_nro_periodo=substr($_idperiodo, 4, 5);
                    $peri=0;
                    
                    if ($cort_nro_periodo=='06') {
                        $peri=$cort_gestion."01";

                        $id_gru=$d_grupo->getDatoGrupo("periodo='$peri' and CodCarrera='".$d_grupo->getIdcarrera()."' and CodMateria='".$d_grupo->getIdmateria()."' and Descripcion=".$d_grupo->getIdgrupo()."'",'CodGrupo');
                        
                        // $this->error.='<br>---'."periodo='$peri' and CodCarrera='".$d_grupo->getIdcarrera()."' and CodMateria='".$d_grupo->getIdmateria()."' and Descripcion=".$d_grupo->getIdgrupo()."'".'---';
                        
                        if ($id_gru=='') {
                            $mat_nm=$d_grupo->getDatoEquivalencia("codca_ma='".$d_grupo->getIdcarrera()."' and sigla_ma='".$d_grupo->getIdmateria()."'",'sigla_mn');
                            $car_nm=$d_grupo->getDatoEquivalencia("codca_ma='".$d_grupo->getIdcarrera()."' and sigla_ma='".$d_grupo->getIdmateria()."'",'codca_mn');
                            $id_gru=$d_grupo->getDatoGrupo("periodo='$peri' and CodCarrera='$car_nm' and CodMateria='$mat_nm' and Descripcion='$_grupos'",'CodGrupo');
                        }
                    }else if ($cort_nro_periodo=='08') {
                            $peri=$cort_gestion."02";
                            $_grupos=$d_grupo->getDatoGrupo("periodo='$peri' and CodCarrera='".$d_grupo->getIdcarrera()."' and CodMateria='".$d_grupo->getIdmateria()."' and Descripcion='$_grupos'",'CodGrupo');
                            // $this->error.='<br>Idgrupo='.$_grupos;
                    }

                        $q="SELECT * FROM aa_clases_virtuales where id_grupo=$_grupos and estado=1 order by fecha_pub";
                        $class = $bdcon->prepare($q);
                        $class->execute();
                    //-------------
                }

                if($class->rowCount()){
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->item+=1;
                        $clase = new cvirtual();
                        $clase->set_init_cvirtual($this->item, $row['cod_clase'], $row['titulo'], $row['embed'], $row['fecha_pub'], $row['nb_tema'], $row['nro_clase'], $row['codcla'], $_grupos);
                        $this->clases[]=$clase;
                        //$this->error.=$this->item.'-Step<br>';
                        // $this->error.='<br>CON REGISTROS DE CLASES';
                    }
                }else{
                    // $this->error.='<br>SIN REGISTROS DE CLASES';
                    $this->clases=null;
                }

                

            }
            catch(PDOException $e){
                $this->error .= 'Error al obtener el registro de clases : ' . $e->getMessage();
            }
        }
    }
?>