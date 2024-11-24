<?php
    include "_grupo.php";
    include "_docente.php";
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
        private $id_subgrupo;
        private $docente;
        private $id_modalidad;
        private $modalidad;

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
            $this->id_subgrupo= 0;
            $this->id_subgru_des=0;
            $this->docente = '';
            $this->id_modalidad = 0;
            $this->modalidad = '';
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

        function set_init_cvirtual($_item, $_id_clase, $_titulo, $_enl_embed, $_fecha_pub, $_pla_nb_tema, $_pla_nroclase, $_pla_codclase, $_idgrupo, $_idsubgrupo, $iddocente, $_idmodalidad, $_modalidad){
            $_doc = new docente();

            $this->item=$_item;
            $this->id_clase=$_id_clase;
            $this->titulo=$_titulo;
            $this->enl_embed=$_enl_embed;
            $this->fecha_pub=$_fecha_pub;
            
            $this->pla_nbtema=$_pla_nb_tema;
            $this->pla_nroclase=$_pla_nroclase;
            $this->pla_codclase=$_pla_codclase;
            $this->id_grupo = $_idgrupo;
            $this->id_subgrupo = $_idsubgrupo;
            $this->get_des_subgrupo($_idsubgrupo); // get descripcion

            $_doc->getDatosDocente($iddocente);
            $this->docente = $_doc->getNombreCompleto();
            $this->id_modalidad = $_idmodalidad;
            $this->modalidad = $_modalidad;

            // $this->momento1=$this->getMomento1($this->id_clase);
            // $this->momento2=$this->getMomento2($this->id_clase);
            // $this->momento3=$this->getMomento3($this->id_clase);
            // $this->momento4=$this->getMomento4($this->id_clase);
            // $this->momento5=$this->getMomento5($this->id_clase);
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

        public function setDocente($nb_docente){
            $this->docente=$nb_docente;
        }
        public function getDocente(){
            return $this->docente;
        }
        public function setIdModalidad($_idmodalidad){
            $this->id_modalidad=$_idmodalidad;
        }
        public function getIdModalidad(){
            return $this->id_modalidad;
        }
        public function setModalidad($_modalidad){
            $this->modalidad=$_modalidad;
        }
        public function getModalidad(){
            return $this->modalidad;
        }

        public function getId_grupo() {
            return $this->id_grupo;
        }
        public function setId_grupo($idgrupo) {
            $this->id_grupo = $idgrupo;
        }

        public function getIdSubGrupo() {
            return $this->id_subgrupo;
        }
        public function setIdSubGrupo($idsubgrupo) {
            $this->id_subgrupo = $idsubgrupo;
        }

        public function getSubGrupo_Des() {
            // $this->get_des_subgrupo($this->id_subgrupo);
            return $this->id_subgru_des;
        }
        public function setSubGrupo_Des($_subgrupodes) {
            $this->id_subgru_des = $_subgrupodes;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        // NO test
        public function get_dev_query_moment(){
            $id_clase=$this->getId_clase();
            $m1="SELECT * FROM aa_clases_virtuales_m1 where cod_grupo=$this->id_grupo and cod_subgrupo=$this->id_subgrupo and cod_clase=$id_clase and estado=1;";
            $m2="SELECT * FROM aa_clases_virtuales_m2 where cod_gru=$this->id_grupo and cod_subgru=$this->id_subgrupo and cod_clase=$id_clase and estado=1;";
            $m3="SELECT * FROM aa_clases_virtuales_m3 where cod_gru=$this->id_grupo and cod_subgru=$this->id_subgrupo and cod_cla=$id_clase and estado=1;";
            $m4="SELECT * FROM aa_clases_virtuales_m4 where cod_gru=$this->id_grupo and cod_subgru=$this->id_subgrupo and cod_clase=$id_clase and estado=1;";
            $m5="SELECT * FROM aa_clases_virtuales_m5 where cod_gru=$this->id_grupo and cod_subgru=$this->id_subgrupo and cod_clase=$id_clase and estado=1;";
            $nl="<br>";
            $momentos=$m1.$nl.$m2.$nl.$m3.$nl.$m4.$nl.$m5;
            return $momentos;
        }

        public function get_des_subgrupo($subgrupo){
            include "conexion.php";
            $dato=0;
            try {
                $q_grupo="SELECT descripcion FROM sainc.grupos_sub where cod=".$subgrupo;
                $s_gestion = $bdcon->prepare($q_grupo);
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->setSubGrupo_Des($row['descripcion']);
                }
            }catch (PDOException $e) {
                $this->setSubGrupo_Des(0);
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
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

        public function getClasesVirtuales($_grupos, $_subgrupos){
            include "conexion.php";
            $clase; $this->item=0;

            // Query incluyendo la planificación.
            // $q="SELECT v.cod_clase,v.titulo,v.embed,v.fecha_pub,v.nb_tema,v.nro_clase,v.codcla,v.id_subgrupo,v.cod_doc,p.id_modalidad,m.nombre as modalidad FROM aa_clases_virtuales as v LEFT JOIN aa_por_clase p on v.codcla=p.id_avance left join aa_modalidad m on p.id_modalidad=m.id where v.id_grupo=$_grupos and v.id_subgrupo=$_subgrupos and v.estado=1 and v.id_grupo<>0 order by v.nro_clase, v.fecha_pub";

            // Query sin la planificacion
            $q="SELECT v.cod_clase,v.titulo,v.embed,v.fecha_pub,v.nb_tema,v.nro_clase,v.codcla,v.id_subgrupo,v.cod_doc,v.id_modalidad,m.nombre as modalidad FROM aa_clases_virtuales v left join aa_modalidad m on v.id_modalidad=m.id where v.id_grupo=$_grupos and v.id_subgrupo=$_subgrupos and v.estado=1 and v.id_grupo<>0 order by v.nro_clase, v.fecha_pub";
            // echo $q;
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

                        $q="SELECT v.cod_clase,v.titulo,v.embed,v.fecha_pub,v.nb_tema,v.nro_clase,v.codcla,v.id_subgrupo,v.cod_doc,p.id_modalidad,m.nombre as modalidad FROM aa_clases_virtuales as v LEFT JOIN aa_por_clase p on v.codcla=p.id_avance left join aa_modalidad m on p.id_modalidad=m.id where v.id_grupo=$_grupos and v.id_subgrupo=$_subgrupos and v.estado=1 and v.id_grupo<>0 order by v.fecha_pub";
                        $class = $bdcon->prepare($q);
                        $class->execute();
                    //-------------
                }

                if($class->rowCount()){
                    while ($row = $class->fetch(PDO::FETCH_ASSOC)) {
                        $this->item+=1;
                        $clase = new cvirtual();
                        $clase->set_init_cvirtual($this->item, $row['cod_clase'], $row['titulo'], $row['embed'], $row['fecha_pub'], $row['nb_tema'], $row['nro_clase'], $row['codcla'], $_grupos, $row['id_subgrupo'], $row['cod_doc'], $row['id_modalidad'], $row['modalidad']);
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