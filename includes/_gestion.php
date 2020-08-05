<?php
    class gestion{
        private $id;
        private $gestion;
        private $estado;
        private $nroanio;
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

        public function __construct(){
            $id=0;
            $gestion=0;
            $estado=0;
            $nroanio=0;
        }

        function getgestionactual(){
            include "conexion.php";
            try {
                $s_gestion = $bdcon->prepare("SELECT * FROM sainc.gestion where opcion =  YEAR(now());");
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error += 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->gestion=$row['opcion'];
                    $this->estado=$row['estado'];
                    $this->nroanio=$row['anio'];
                }
            }catch (PDOException $e) {
                $this->error += 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getperiodoactuales(){
            $periodos=array();
            $mes=date("m");
            $anio=$this->getGestion();

            $periodos[0]=array($anio.'01', $anio.'06', $anio.'04');
            $periodos[1]=array($anio.'02', $anio.'08', $anio.'03');
            $n=0;
            if ($mes<8) {
                if ($mes<=3) {
                    $anio=$anio-1;
                    $n=1;
                }else{
                    $n=0;
                }
            }else{
                $n=1;
            }
            return $periodos[$n];
        }
    }
?>