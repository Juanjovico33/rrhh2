<?php
    class grupo{
        
        private $idgrupo;
        private $gestion;
        private $periodo;
        private $idcarrera;
        private $semestre;
        private $idmateria;
        private $iddocente;
        private $grupo;

        function __construct(){
            $this->idgrupo=0;
            $this->gestion=0;
            $this->periodo='';
            $this->idcarrera='';
            $this->semestre=0;
            $this->idmateria='';
            $this->iddocente=0;
            $this->grupo='';
        }

        public function getIdgrupo(){
            return $this->idgrupo;
        }
        public function setIdgrupo($idgrupo){
            $this->idgrupo=$idgrupo;
        }
        public function getGestion(){
            return $this->gestion;
        }
        public function setGestion($gestion){
            $this->gestion=$gestion;
        }
        public function getPeriodo(){
            return $this->periodo;
        }
        public function setPeriodo($periodo){
            $this->periodo=$periodo;
        }
        public function getIdcarrera(){
            return $this->idcarrera;
        }
        public function setIdcarrera($idcarrera){
            $this->idcarrera=$idcarrera;
        }
        public function getSemestre(){
            return $this->semestre;
        }
        public function setSemestre($semestre){
            $this->semestre=$semestre;
        }
        public function getIdmateria(){
            return $this->idmateria;
        }
        public function setIdmateria($idmateria){
            $this->idmateria=$idmateria;
        }
        public function getIddocente(){
            return $this->iddocente;
        }
        public function setIddocente($iddocente){
            $this->iddocente=$iddocente;
        }
        public function getGrupo(){
            return $this->grupo;
        }
        public function setGrupo($grupo){
            $this->grupo=$grupo;
        }

        function getDatosGrupo($_grupo){
            include "conexion.php";

            try {
                $s_gestion = $bdcon->prepare("SELECT * FROM grupos where CodGrupo=$_grupo");
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error += 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->idgrupo=$row['CodGrupo'];
                    $this->gestion=$row['Gestion'];
                    $this->periodo=$row['periodo'];
                    $this->idcarrera=$row['CodCarrera'];
                    $this->semestre=$row['CodSemestre'];
                    $this->idcarrera=$row['CodMateria'];
                    $this->iddocente=$row['CodDocente'];
                    $this->grupo=$row['Descripcion'];
                }
            }catch (PDOException $e) {
                $this->error += 'Error al desplegar los datos : ' . $e->getMessage();
            }

        }

        function getDatoGrupo($condicion, $campo){
            include "conexion.php";
            $dato;
            try {
                $s_gestion = $bdcon->prepare("SELECT $campo FROM grupos where $condicion");
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error += 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $dato=$row[$campo];
                }
            }catch (PDOException $e) {
                $dato='';
                $this->error += 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $dato;
        }
        function getDatoEquivalencia($condicion, $campo){
            include "conexion.php";
            $dato;
            try {
                $s_equi = $bdcon->prepare("SELECT $campo FROM materias_equivalencia where $condicion");
                $s_equi->execute();
            } catch (PDOException $e) {
                $this->error += 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_equi->fetch(PDO::FETCH_ASSOC)) {
                    $dato=$row[$campo];
                }
            }catch (PDOException $e) {
                $dato='';
                $this->error += 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $dato;
        }
    }
?>