<?php
    class reg_excepciones{
        private $codest;
        private $fecha_actual;
        private $periodos;

        private $error;

        function __contruct(){
            $this->codest=0;
            $this->fecha_actual = "0000-00-00";
            $this->periodos=array();
            $this->error="";
        }

        public function getCodest(){
            return $this->codest;
        }
        public function setCodest($_codest){
            $this->codest=$_codest;
        }

        public function getFechaActual(){
            return $this->fecha_actual;
        }
        public function setFechaActual($_fecha){
            $this->fecha_actual=$_fecha;
        }

        public function getPeriodos(){
            return $this->periodos;
        }
        public function setPeriodos($_periodos){
            $this->periodos=$_periodos;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function getRegExcepcionEst_periodos($_codest){
            include "conexion.php";
            $var_fecha=new DateTime();
            $var_fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $fecha = $var_fecha->format('Y-m-d');
            
            $this->setFechaActual($fecha);
            $this->setCodest($_codest);

            $q_excep="SELECT e.periodo, e.idgestion FROM aca_reg_excepciones e WHERE e.codest=$_codest AND e.estado=1 AND '$fecha'>=e.desde AND '$fecha'<=e.hasta ORDER BY periodo";
            try {
                $_filas = $bdcon->prepare($q_excep);
                $_filas->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la registros/excepciones del archivo ha fallado' . $e->getMessage().'<br>';
            }
            $_pmc=array();
            try{
                while ($row = $_filas->fetch(PDO::FETCH_ASSOC)) {
                    $this->periodos[]=array("periodo"=>$row['periodo'],"idgestion"=>$row['idgestion']);
                }
            }catch (PDOException $e) {
                return false;
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $this->periodos;
        }
        function generarConsulta(){
            $arrayper=$this->periodos;
            $_codest=$this->codest;

            $gestionperiodo="";
            
            if(count($arrayper)==1){
                $per = $arrayper[0]['periodo'];
                $idgest = $arrayper[0]['idgestion'];
                $gestionperiodo="gestion=$idgest and periodo=$per";
            }else{
                $_per;
                $_idgest;
                for($i=0 ;$i<count($arrayper);$i++){
                    $_per[] = $arrayper[$i]['periodo'];
                    $_idgest[] = $arrayper[$i]['idgestion'];
                }
                $u_per=array_unique($_per);
                $u_idgest=array_unique($_idgest);

                $q_periodo=""; $q_idgestion="";
                $contador_idgest=count($u_idgest);
                $contador_per=count($u_per);

                if($contador_idgest==1){
                    $q_idgestion="gestion=".$u_idgest[0];
                }else{
                    $q_idgestion="("; $limite_gest=$contador_idgest-1;
                        for($j=0;$j<$contador_idgest;$j++){
                            if($j==$limite_gest){
                                $q_idgestion.="gestion=".$u_idgest[$j];
                            }else{
                                $q_idgestion.="gestion=".$u_idgest[$j]." OR ";
                            }
                        }
                    $q_idgestion.=")";
                }

                if($contador_per==1){
                    $q_periodo="periodo=".$u_per[0];
                }else{
                    $q_periodo="("; $limite_per=$contador_per-1;
                        for($t=0;$t<$contador_per;$t++){
                            if($t==$limite_per){
                                $q_periodo.="periodo=".$u_per[$t];
                            }else{
                                $q_periodo.="periodo=".$u_per[$t]." OR ";
                            }
                        }
                    $q_periodo.=")";
                }
                
                $gestionperiodo="$q_idgestion AND $q_periodo";
            }
            $q_generated="SELECT periodo, codmateria, grupo, idgrupo from aca_registroestmat where codest=$_codest and $gestionperiodo order by periodo";
            return $q_generated;
        }
    }
?>