<?php
    class validar{
        
        private $codest;
        private $idgrupo;
        private $actividad;

        private $error;

        function __construct(){
            $this->codest=0;
            $this->idgrupo=0;
            $this->actividad=0;

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

        public function getActividad(){
            return $this->actividad;
        }
        public function setActividad($_actividad){
            $this->actividad=$_actividad;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function validarEstudiante($_codest){
            include "conexion.php";
            $respuesta=false;

            try {
                $q_estudiante="SELECT codest, nombcompleto, carrera FROM estudiante where codest=$_codest";
                $r_query = $bdcon->prepare($q_estudiante);
                $r_query->execute();
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener estos datos ha fallado' . $e->getMessage().'<br>';
            }
            try{
                if($r_query->rowCount()>0){
                    while ($row = $r_query->fetch(PDO::FETCH_ASSOC)) {
                        $codigo_est = $row['codest'];
                        $nombre_completo = $row['nombcompleto'];
                        $carrera = $row['carrera'];

                        if($nombre_completo!="" && $carrera!=""){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    }
                }else{
                    $respuesta = false;
                }
                
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
                $respuesta = false;
            }
        return $respuesta;
        }

        function validarGrupo($_idgrupo, $_codest){
            include "conexion.php";
            $respuesta=false;

            try {
                $q_grupo="SELECT periodo, CodCarrera, CodMateria, Descripcion FROM sainc.grupos where CodGrupo=$_idgrupo";
                $quering_grupo = $bdcon->prepare($q_grupo);
                $quering_grupo->execute();
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener estos datos ha fallado' . $e->getMessage().'<br>';
            }
            try{
                if($quering_grupo->rowCount()>0){
                    while ($row = $quering_grupo->fetch(PDO::FETCH_ASSOC)) {
                        $periodo = $row['periodo'];
                        $carrera = $row['CodCarrera'];
                        $materia = $row['CodMateria'];
                        $grupo = $row['Descripcion'];

                        $q_grupoestmat="SELECT codreg FROM aca_registroestmat where periodo=$periodo and codmateria='$materia' and grupo='$grupo' and codest=$_codest";
                        $quering_emat = $bdcon->prepare($q_grupoestmat);
                        $quering_emat->execute();
                        if($quering_emat->rowCount()>0){
                            $respuesta = true;
                        }else{
                            $respuesta = false;
                        }
                    }
                }else{
                    $respuesta = false;
                }
                
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
                $respuesta = false;
            }
        return $respuesta;
        }

        function validarActividad($_idactividad, $_idgrupo){
            include "conexion.php";
            $respuesta=false;

            try {
                $q_actividad="SELECT * FROM sainc.plat_doc_actividades where id=$_idactividad and idgrupo=$_idgrupo and (DATE_FORMAT(now(),'%H:%i:%s') between hora_d and hora_h) and desde=DATE_FORMAT(now(),'%Y-%m-%d')";
                $q_act_actual = $bdcon->prepare($q_actividad);
                $q_act_actual->execute();
                if($q_act_actual->rowCount()>0){
                    $respuesta = true;
                }
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener estos datos ha fallado' . $e->getMessage().'<br>';
                $respuesta = false;
            }
        
        return $respuesta;
        }
    }
?>