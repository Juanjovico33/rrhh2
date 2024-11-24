<?php
    class eval_diagnostica{
        private $codest;
        private $fecha_actual;
        private $periodos;
        private $error;
        private $cod_ban;

        function __contruct(){
            $this->codest=0;
            $this->fecha_actual = "0000-00-00";
            $this->error='';
        }
        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }
        public function getCodBanco(){
            return $this->cod_ban;
        }
        public function setCodBanco($cod_ban){
            return $this->cod_ban=$cod_ban;
        }

        function obt_cod_actividad($periodo,$carrera,$sem){
            include "conexion.php";
            $cod_act=0;
            $cod_ban=0;
            $q_evdi="SELECT e.cod, e.banco FROM aca_evaluacion_diagnostica e WHERE e.periodo='$periodo' AND e.carrera='$carrera' AND e.semestre='$sem' LIMIT 1";
            try {
                $_filas = $bdcon->prepare($q_evdi);
                $_filas->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la actividad ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $_filas->fetch(PDO::FETCH_ASSOC)) {
                    $cod_act=$row['cod'];
                    $cod_ban=$row['banco'];
                    $this->cod_ban=$cod_ban;
                }
            }catch (PDOException $e) {
                return false;
                $this->error .= 'Error : ' . $e->getMessage();
            }
            return $cod_act;
        }
        function verif_realizada($codeval,$codest){
            include "conexion.php";
            $cod_reg=0;
            $q_evdi="SELECT cod FROM plat_doc_intentos_est_diag WHERE cod_act='$codeval' AND codest='$codest' LIMIT 1";
            try {
                $_filas = $bdcon->prepare($q_evdi);
                $_filas->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la evaluacion ha fallado ' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $_filas->fetch(PDO::FETCH_ASSOC)) {
                    $cod_reg=$row['cod'];
                }
            }catch (PDOException $e) {
                return false;
                $this->error .= 'Error : ' . $e->getMessage();
            }
            return $cod_reg;
        }
        function cons_diagnos($cod_eval){
            include "conexion.php";
            $q_diag="SELECT id, pregunta, puntuacion, tipo, uov, imagen FROM plat_doc_banco_preg WHERE id_cat='$cod_eval' and pregunta!='' and (tipo='1' or tipo='3') ORDER BY rand() LIMIT 10";
            $_filas = $bdcon->prepare($q_diag);
            return $_filas;
        }
    }
?>