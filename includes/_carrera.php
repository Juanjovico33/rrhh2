<?php
    class carrera{
        private $codigo;
        private $titulo;
        private $tiempo;
        private $idccosto;
        private $idresolucion;

        public function getCodigo() {
            return $this->codigo;
        }

        public function setCodigo($codigo) {
            $this->codigo = $codigo;
        }

        public function getTitulo() {
            return $this->titulo;
        }

        public function setTitulo($titulo) {
            $this->titulo = $titulo;
        }

        public function getTiempo() {
            return $this->tiempo;
        }

        public function setTiempo($tiempo) {
            $this->tiempo = $tiempo;
        }

        public function getIdccosto() {
            return $this->idccosto;
        }

        public function setIdccosto($idccosto) {
            $this->idccosto = $idccosto;
        }

        public function getIdresolucion() {
            return $this->idresolucion;
        }

        public function setIdresolucion($idresolucion) {
            $this->idresolucion = $idresolucion;
        }

        public function __construct(){
            $codigo='';
            $titulo='';
            $tiempo=0;
            $idccosto=0;
            $idresolucion=0;
        }

        public function getcarrera($nb_carrera, $gestion){
            include "conexion.php";
            try {
                $s_carrera = $bdcon->prepare("SELECT Codigo, Titulo, tiempo, IdCCosto, id_resolucion FROM carreras WHERE Titulo='$nb_carrera' and Requisito='$gestion' limit 1");
                $s_carrera->execute();
                // $this->error="SELECT Codigo, Titulo, tiempo, IdCCosto, id_resolucion FROM carreras WHERE Titulo='$nb_carrera' and gestion='$gestion' limit=1";
            } catch (PDOException $e) {
                $this->error = 'La conexión para CLASS::carreras, intente realizar su solicitud nuevamente: ' . $e->getMessage().'<br>';
            }
            while ($row = $s_carrera->fetch(PDO::FETCH_ASSOC)) {
                $this->codigo=$row['Codigo'];
                $this->titulo=$row['Titulo'];
                $this->tiempo=$row['tiempo'];
                $this->idccosto=$row['IdCCosto'];
                $this->id_resolucion=$row['id_resolucion'];
            }
        }
        public function getcarreraest($nb_carrera, $gestion, $codigoest){
            include "conexion.php";
            try {
                $_carrera=""; $_periodo=""; $_resulocion=0;
                $s_carreraest=$bdcon->prepare("SELECT carrera, periodo, id_resolucion FROM sainc.estudiante_codigo where codigo_estudiante=$codigoest;");
                $s_carreraest->execute();
                while ($srow = $s_carreraest->fetch(PDO::FETCH_ASSOC)) {
                    $_carrera=$srow['carrera'];
                    $_periodo=$srow['periodo'];
                    $_resolucion=$srow['id_resolucion'];
                }

                $s_carrera = $bdcon->prepare("SELECT Codigo, Titulo, tiempo, IdCCosto, id_resolucion FROM carreras WHERE Titulo='$nb_carrera' and Requisito='$gestion' and id_resolucion='$_resolucion' limit 1");
                $s_carrera->execute();
                // $this->error="SELECT Codigo, Titulo, tiempo, IdCCosto, id_resolucion FROM carreras WHERE Titulo='$nb_carrera' and gestion='$gestion' limit=1";
            } catch (PDOException $e) {
                $this->error = 'La conexión para CLASS::carreras, intente realizar su solicitud nuevamente: ' . $e->getMessage().'<br>';
            }
            while ($row = $s_carrera->fetch(PDO::FETCH_ASSOC)) {
                $this->codigo=$row['Codigo'];
                $this->titulo=$row['Titulo'];
                $this->tiempo=$row['tiempo'];
                $this->idccosto=$row['IdCCosto'];
                $this->id_resolucion=$row['id_resolucion'];
            }
        }
    }
?>