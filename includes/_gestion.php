<?php
    class gestion{
        private $id;
        private $gestion;
        private $estado;
        private $nroanio;

        private $error;

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getGestion() {
            return $this->gestion;
        }

        public function setGestion($gestion) {
            $this->gestion = $gestion;
        }

        public function getEstado() {
            return $this->estado;
        }

        public function setEstado($estado) {
            $this->estado = $estado;
        }
        public function getError() {
            return $this->error;
        }

        public function setError($error) {
            $this->error = $error;
        }

        public function __construct(){
            $this->id=0;
            $this->gestion=0;
            $this->estado=0;
            $this->nroanio=0;
            $this->error='';
        }

        function getgestionactual(){       
            include "conexion.php";
            $q_gestion='';
            try {
                $q_gestion="SELECT * FROM sainc.gestion where opcion =YEAR(now())";
                // $q_gestion="SELECT id, opcion, estado, anio FROM gestion where opcion =  '$anio'";
                $s_gestion = $bdcon->prepare($q_gestion);
                $s_gestion->execute();
                // $this->error .=$q_gestion;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->gestion=$row['opcion'];
                    $this->estado=$row['estado'];
                    $this->nroanio=$row['anio'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getgestion_id($id_gestion){
            include "conexion.php";
            $q_gestion='';
            try {
                $q_gestion="SELECT * FROM sainc.gestion where id=$id_gestion";
                $s_gestion = $bdcon->prepare($q_gestion);
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->gestion=$row['opcion'];
                    $this->estado=$row['estado'];
                    $this->nroanio=$row['anio'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }
        
        function getperiodoactuales(){
            $periodos=array();
            $mes=date("m");
            $anio=$this->getGestion();

            // $periodos[0]=array($anio.'01', $anio.'06', $anio.'03', $anio.'11', $anio.'12', $anio.'09');
            // $periodos[1]=array($anio.'02', $anio.'08', $anio.'04', $anio.'11', $anio.'13', $anio.'10');
           
            // $n=0;
            // if ($mes<8) {
            //     if ($mes<=3) {
            //         $anio=$anio-1;
            //         $n=1;
            //     }else{
            //         $n=0;
            //     }
            // }else{
            //     $n=1;
            // }
            
            $all_periodos=array($anio.'01', $anio.'06', $anio.'03', $anio.'11', $anio.'12', $anio.'09', $anio.'02', $anio.'08', $anio.'04', $anio.'13', $anio.'10', $anio.'05', $anio.'07', $anio.'15');

            //dev
            // $all_periodos=array($anio.'02', $anio.'08', $anio.'04', $anio.'11', $anio.'13', $anio.'10');

            // $this->error.=$n.'<br>';
            // return $periodos[$n];
            return $all_periodos;
        }

        
        function getgestion_especifica($carrera, $anio){
            include "conexion.php";
            $q_gestion='';
            try {
                $q_gestion="SELECT id, opcion, estado, anio FROM gestion where opcion =  '$anio'";
                $s_gestion = $bdcon->prepare($q_gestion);
                $s_gestion->execute();
                // $this->error .=$q_gestion;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->gestion=$row['opcion'];
                    $this->estado=$row['estado'];
                    $this->nroanio=$row['anio'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getperiodoPersonalizados(){
            $_periodos=array();
            $mes=date("m");
            $anio=$this->getGestion();
            
            $_periodos=array($anio.'04', $anio.'03', $anio.'12');

            return $_periodos;
        }

        function getPeriodoIINormal(){
            $_periodos=array();
            $mes=date("m");
            $anio=$this->getGestion();
            
            $_periodos=array($anio.'02', $anio.'08', $anio.'06');

            return $_periodos;
        }
        
        function getPeriodoIINormal_solo(){
            $_periodos=array();
            $mes=date("m");
            $anio=$this->getGestion();
            
            $_periodos=array($anio.'02', $anio.'08');

            return $_periodos;
        }
    }
?>