<?php
    class evento{    
        private $codest;
        private $idgrupo;
        private $id_subgrupo;

        private $id_actual;
        private $id_io;
        private $id_evento;
        private $tipo_evento;

        private $id_clase;
        private $momento;
        private $id_biblio;
        private $enlace;

        private $error;

        function __construct(){
            $this->codest=0;
            $this->idgrupo=0;
            $this->id_subgrupo=0;

            $this->id_actual;
            $this->id_io=0;
            $this->id_evento=0;
            $this->tipo_evento=0;

            $this->id_clase=0;
            $this->momento=0;
            $this->id_biblio=0;
            $this->enlace="";

            $this->error='';
        }

        public function getCodest(){
            return $this->codest;
        }
        public function setCodest($_codest){
            $this->codest=$_codest;
        }
        public function getIdGrupo(){
            return $this->idgrupo;
        }
        public function setIdGrupo($_idgrupo){
            $this->idgrupo=$_idgrupo;
        }
        public function getIdSubGrupo(){
            return $this->id_subgrupo;
        }
        public function setIdSubGrupo($_idsubgrupo){
            $this->id_subgrupo=$_idsubgrupo;
        }

        public function getIdActual(){
            return $this->id_actual;
        }
        public function setIdActual($_idActual){
            $this->id_actual=$_idActual;
        }
        public function getIdEntradaSalida(){
            return $this->id_io;
        }
        public function setIdEntradaSalida($_idIO){
            $this->id_io=$_idIO;
        }
        public function getIdEvento(){
            return $this->id_evento;
        }
        public function setIdEvento($_idevento){
            $this->id_evento=$_idevento;
        }
        public function getTipoEvento(){
            return $this->tipo_evento;
        }
        public function setTipoEvento($_tipoevento){
            $this->tipo_evento=$_tipoevento;
        }

        public function getIdClase(){
            return $this->id_clase;
        }
        public function setIdClase($_idClase){
            $this->id_clase=$_idClase;
        }
        public function getMomento(){
            return $this->momento;
        }
        public function setMomento($_momento){
            $this->momento=$_momento;
        }
        public function getIdBibliografia(){
            return $this->id_biblio;
        }
        public function setIdBibliografia($_idBibliografia){
            $this->id_biblio=$_idBibliografia;
        }
        public function getEnlace(){
            return $this->enlace;
        }
        public function setEnlace($_enlace){
            $this->enlace=$_enlace;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function e_log_tieneEventoActual($_codest){
            include 'conexion.php';
            $respuesta=false;

            try {
                $q_eventoActual="SELECT id, id_io, id_ev, tipo_eve FROM e_log_reg_actual where codest=$_codest and fecha=DATE_FORMAT(now(),'%Y-%m-%d') and estado=1 limit 1";
                $s_eventoActual = $bdcon->prepare($q_eventoActual);
                $s_eventoActual->execute();
                
                $row = $s_eventoActual->fetch(PDO::FETCH_ASSOC);
                $this->setCodest($_codest);
                $this->setIdActual($row['id']);
                $this->setIdEntradaSalida($row['id_io']);
                $this->setIdEvento($row['id_ev']);
                $this->setTipoEvento($row['tipo_eve']);

                if($s_eventoActual->rowCount()==1){
                    $respuesta = true;
                }else{
                    $respuesta = false;
                }
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $respuesta = false;
                $this->error .= 'La conexión para obtener el evento actual ha fallado' . $e->getMessage().'<br>';
            }
            return $respuesta;
        }

        function e_log_ingreso($_codest, $_evento){
            include 'conexion.php';
            $respuesta=false;

            if(!$this->e_log_tieneEventoActual($_codest)){
                try {
                    $q_insert="INSERT INTO e_log_ingreso_salida(id,codest,ingreso,salida,duracion,estado) VALUES (0,$_codest,now(),NULL,'00:00:00',1)";
                    $s_insert = $bdcon->prepare($q_insert);
                    $s_insert->execute();
                    $last_id_ent_sal = $bdcon->lastInsertId('id');

                    $q_insert_actual="INSERT INTO e_log_reg_actual(id,codest,fecha,registro,id_io,id_ev,tipo_eve,estado)VALUES(0,$_codest,DATE_FORMAT(now(),'%Y-%m-%d'),now(),$last_id_ent_sal,0,$_evento,1)";
                    $s_insert_actual = $bdcon->prepare($q_insert_actual);
                    $s_insert_actual->execute();

                    if($s_insert_actual->rowCount()==1){
                        $respuesta = true;
                    }else{
                        $respuesta = false;
                    }
                } catch (PDOException $e) {
                    $respuesta=false;
                    $this->error .= 'La conexión para registrar el ingreso ha fallado' . $e->getMessage().'<br>';
                }
            }

            return $respuesta;
        }

        function e_log_salida($_codest, $_evento){
            include 'conexion.php';
            $respuesta=false;

            if($this->e_log_tieneEventoActual($_codest)){
                try {
                    $q_select_horaingreso="SELECT ingreso FROM e_log_ingreso_salida where id=".$this->getIdEntradaSalida()." limit 1";
                    $s_selectIO = $bdcon->prepare($q_select_horaingreso);
                    $s_selectIO->execute();
                    $row = $s_selectIO->fetch(PDO::FETCH_ASSOC);
                    $_hIngreso = $row['ingreso'];

                    $this->e_log_fin_evento_actual(); // añadido, testing

                    $q_update_salida="UPDATE e_log_ingreso_salida SET salida=now(), duracion=SEC_TO_TIME(TIMESTAMPDIFF(SECOND, '$_hIngreso', now())), estado=0 WHERE id=".$this->getIdEntradaSalida()." LIMIT 1";
                    $s_update_salida = $bdcon->prepare($q_update_salida);
                    
                    if($s_update_salida->execute()){
                        $q_update_actual="UPDATE e_log_reg_actual SET id_ev=0, tipo_eve=$_evento, estado=0 WHERE id=".$this->getIdActual();
                        $s_update_actual = $bdcon->prepare($q_update_actual);
                        if($s_update_actual->execute()){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    }else{
                        $respuesta = false;
                    }
                } catch (PDOException $e) {
                    $respuesta=false;
                    $this->error .= 'La conexión para registrar la salida ha fallado' . $e->getMessage().'<br>';
                }
            }

            return $respuesta;
        }

        function e_log_fin_evento_actual(){
            include 'conexion.php';
            $respuesta=false;

            if($this->getTipoEvento()!=1 && $this->getTipoEvento()!=2){
                // ver si sin hora de ingreso funciona esta consulta;
                try{
                    $q_fin_eventoball = "UPDATE e_log_evento SET salida=now(), duracion=SEC_TO_TIME(TIMESTAMPDIFF(SECOND, ingreso, now())), ball=0 WHERE id=".$this->getIdEvento()." LIMIT 1";
                    $s_fin_eventoball = $bdcon->prepare($q_fin_eventoball);
                    if($s_fin_eventoball->execute()){
                        $respuesta = true;
                    }else{
                        $respuesta = false;
                    }
                } catch (PDOException $e) {
                    $respuesta=false;
                    $this->error .= 'La conexión para registrar el fin del evento actual ha fallado' . $e->getMessage().'<br>';
                }
            }else{
                $respuesta = true;
            }

            return $respuesta;
        }

        function e_log_fin_evento($_eventoNuevo){//3
            include 'conexion.php';
            $respuesta=false;
            if($this->getTipoEvento()!=$_eventoNuevo){
                if($this->getTipoEvento()!=1 && $this->getTipoEvento()!=2){
                    // ver si sin hora de ingreso funciona esta consulta;
                    try{
                        $q_fin_eventoball = "UPDATE e_log_evento SET salida=now(), duracion=SEC_TO_TIME(TIMESTAMPDIFF(SECOND, ingreso, now())), ball=0 WHERE id=".$this->getIdEvento()." LIMIT 1";
                        $s_fin_eventoball = $bdcon->prepare($q_fin_eventoball);
                        if($s_fin_eventoball->execute()){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    } catch (PDOException $e) {
                        $respuesta=false;
                        $this->error .= 'La conexión para registrar el fin del evento ha fallado' . $e->getMessage().'<br>';
                    }
                   
                }else{
                    $respuesta = true;
                }
            }else{
                $respuesta = false;
            }
            return $respuesta;
        }

        function e_log_inicio_evento($_codest, $_eventoNuevo){
            include 'conexion.php';
            $respuesta=false;

            if($this->e_log_tieneEventoActual($_codest)){
                // finalizar evento
                if($this->e_log_fin_evento($_eventoNuevo)){
                    try {
                        // insertar el nuevo evento
                        $q_insert_evento_especifico="INSERT INTO e_log_evento (id, id_io, id_tipoevento, id_grupo, id_subgrupo, id_clase, momento, id_biblio_mate, codest, ingreso, salida, duracion, ball, enlace, estado) VALUES (0, ".$this->getIdEntradaSalida().", ".$_eventoNuevo.", ".$this->getIdGrupo().", ".$this->getIdSubGrupo().", ".$this->getIdClase().", ".$this->getMomento().", ".$this->getIdBibliografia().",$_codest, NOW(), NULL, '00:00:00', 1, '".$this->getEnlace()."', 1)";
                        // echo $q_insert_evento_especifico; exit;
                        $s_insert_ee = $bdcon->prepare($q_insert_evento_especifico);
                        $s_insert_ee->execute();
                        $last_id_nuevo_evento = $bdcon->lastInsertId('id');

                        // modificar el evento actual
                        $q_update_actual="UPDATE e_log_reg_actual SET id_ev=$last_id_nuevo_evento, tipo_eve=$_eventoNuevo WHERE id=".$this->getIdActual();
                        $s_update_actual = $bdcon->prepare($q_update_actual);
                        $s_update_actual->execute();
    
                        if($s_update_actual->rowCount()==1){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    } catch (PDOException $e) {
                        $respuesta=false;
                        $this->error .= 'La conexión para registrar un nuevo evento ha fallado' . $e->getMessage().'<br>';
                    }
                }
            }else{
                header("Refresh:0");
            }
            return $respuesta;
        }

        function e_log_inicio_evento_conClase($_codest, $_eventoNuevo){
            include 'conexion.php';
            $respuesta=false;
            $_claseNueva=$this->getIdClase();
            if($this->e_log_tieneEventoActual($_codest)){
                // finalizar evento
                if($this->e_log_fin_evento_conclase($_eventoNuevo,$_claseNueva)){
                    try {
                        // insertar el nuevo evento
                        $q_insert_evento_especifico="INSERT INTO e_log_evento (id, id_io, id_tipoevento, id_grupo, id_subgrupo, id_clase, momento, id_biblio_mate, codest, ingreso, salida, duracion, ball, enlace, estado) VALUES (0, ".$this->getIdEntradaSalida().", ".$_eventoNuevo.", ".$this->getIdGrupo().", ".$this->getIdSubGrupo().", ".$_claseNueva.", ".$this->getMomento().", ".$this->getIdBibliografia().",$_codest, NOW(), NULL, '00:00:00', 1, '".$this->getEnlace()."', 1)";
                        // echo $q_insert_evento_especifico; exit;
                        $s_insert_ee = $bdcon->prepare($q_insert_evento_especifico);
                        $s_insert_ee->execute();
                        $last_id_nuevo_evento = $bdcon->lastInsertId('id');

                        // modificar el evento actual
                        $q_update_actual="UPDATE e_log_reg_actual SET id_ev=$last_id_nuevo_evento, tipo_eve=$_eventoNuevo WHERE id=".$this->getIdActual();
                        $s_update_actual = $bdcon->prepare($q_update_actual);
                        $s_update_actual->execute();
    
                        if($s_update_actual->rowCount()==1){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    } catch (PDOException $e) {
                        $respuesta=false;
                        $this->error .= 'La conexión para registrar un nuevo evento ha fallado' . $e->getMessage().'<br>';
                    }
                }
            }else{
                header("Refresh:0");
            }
            return $respuesta;
        }

        function e_log_fin_evento_conclase($_eventoNuevo, $_claseNueva){//3
            include 'conexion.php';
            $respuesta=false; $_claseActual=0;

            $q_select_idclaseActual="SELECT id_clase FROM sainc.e_log_evento WHERE id=".$this->getIdEvento();
            $s_selectClaseActual = $bdcon->prepare($q_select_idclaseActual);
            $s_selectClaseActual->execute();
            $row = $s_selectClaseActual->fetch(PDO::FETCH_ASSOC);
            $_claseActual = $row['id_clase'];
            // echo $_claseActual."<br>"; exit;
            // echo $this->getTipoEvento()."-".$_eventoNuevo; exit;
            if(($_eventoNuevo==23 || $_eventoNuevo==24) && $_claseNueva!=0 && $_claseNueva!=$_claseActual){

                if(($_claseActual!=$_claseNueva || $_claseActual==0) && $this->getTipoEvento()!=1 && $this->getTipoEvento()!=2){
                    // ver si sin hora de ingreso funciona esta consulta;
                    try{
                        $q_fin_eventoball = "UPDATE e_log_evento SET salida=now(), duracion=SEC_TO_TIME(TIMESTAMPDIFF(SECOND, ingreso, now())), ball=0 WHERE id=".$this->getIdEvento()." LIMIT 1";
                        $s_fin_eventoball = $bdcon->prepare($q_fin_eventoball);
                        if($s_fin_eventoball->execute()){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    } catch (PDOException $e) {
                        $respuesta=false;
                        $this->error .= 'La conexión para registrar el fin del evento ha fallado' . $e->getMessage().'<br>';
                    }
                
                }else{
                    $respuesta = true;
                }

            }else{
                $respuesta = false;
            }
            return $respuesta;
        }
    }
?>