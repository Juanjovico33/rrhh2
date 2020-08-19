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
        private $error;

        function __construct(){
            $this->idgrupo=0;
            $this->gestion=0;
            $this->periodo='';
            $this->idcarrera='';
            $this->semestre=0;
            $this->idmateria='';
            $this->iddocente=0;
            $this->grupo='';
            $this->error='';
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
        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function getDatosGrupo($_grupo){
            include "conexion.php";

            try {
                $q_grupo="SELECT * FROM grupos where CodGrupo=$_grupo";
                $s_gestion = $bdcon->prepare($q_grupo);
                $s_gestion->execute();
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener la fecha actual ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $this->idgrupo=$row['CodGrupo'];
                    $this->gestion=$row['Gestion'];
                    $this->periodo=$row['periodo'];
                    $this->idcarrera=$row['CodCarrera'];
                    $this->semestre=$row['CodSemestre'];
                    $this->idmateria=$row['CodMateria'];
                    $this->iddocente=$row['CodDocente'];
                    $this->grupo=$row['Descripcion'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }

        }

        function getDatoGrupo($condicion, $campo){
            include "conexion.php";
            $dato='';
            try {
                $s_gestion = $bdcon->prepare("SELECT $campo FROM grupos where $condicion");
                $s_gestion->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_gestion->fetch(PDO::FETCH_ASSOC)) {
                    $dato=$row[$campo];
                }
            }catch (PDOException $e) {
                $dato='';
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
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
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_equi->fetch(PDO::FETCH_ASSOC)) {
                    $dato=$row[$campo];
                }
            }catch (PDOException $e) {
                $dato='';
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $dato;
        }

        function esNivelacion(){
            $per=$this->periodo;
            $percor=substr($per, 4, 5);
            if($percor=='06' || $percor=='08'){
                return true;
            }else{
                return false;
            }
        }
        function esNormal(){
            $per=$this->periodo;
            $percor=substr($per, 4, 5);
            if($percor=='01' || $percor=='02'){
                return true;
            }else{
                return false;
            }
        }

        function nivGetPeriodoMain(){
            $per=$this->periodo;
            $gescor=substr($per, 0, 4);
            $percor=substr($per, 4, 5);
            $_periodo='';
            if($percor=='06'){
                $_periodo=$gescor."01"; 
            }else if($percor=='08'){
                $_periodo=$gescor."02";
            }else{
                $_periodo=$gescor."01"; 
            }
            return $_periodo;
        }
         //Pendiente
         function nivGetIdgrupoMain(){
            include "conexion.php";

            $idgrupo=0;
            $carrera=$this->idcarrera;
            $periodo=$this->nivGetPeriodoMain();
            $materia=$this->idmateria;
            $grupo_letra=$this->grupo;

            try {
                $q_idgrupo="SELECT CodGrupo FROM grupos WHERE periodo=$periodo AND codmateria='$materia' AND Descripcion='$grupo_letra' AND CodCarrera='$carrera'";
                $resul = $bdcon->prepare($q_idgrupo);
                $resul->execute();
                while ($_row = $resul->fetch(PDO::FETCH_ASSOC)) {
                    $idgrupo=$_row['CodGrupo'];
                }
                // $this->error.=$q_idgrupo."<br>";
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            return $idgrupo;
        }

        function getIdgrupo_otraMateriaMalla(){
            include "conexion.php";

            $idgrupo=0;
            $carrera=$this->idcarrera;
            $materia=$this->idmateria;
            $periodo=$this->periodo;
            $grupo=$this->grupo;

            $_codca_mn='';
            $_codma_mn='';
            try {
                
                $q_materiasequivalencia="SELECT * FROM sainc.materias_equivalencia WHERE codca_ma='$carrera' AND sigla_ma='$materia'";
                $resul = $bdcon->prepare($q_materiasequivalencia);
                $resul->execute();
                while ($_row = $resul->fetch(PDO::FETCH_ASSOC)) {
                    $_codca_mn=$_row['codca_mn'];
                    $_codma_mn=$_row['sigla_mn'];
                }

                $q_idgrupo="SELECT CodGrupo FROM grupos WHERE periodo=$periodo AND codmateria='$_codma_mn' AND Descripcion='$grupo' AND CodCarrera='$_codca_mn'";
                $resul = $bdcon->prepare($q_idgrupo);
                $resul->execute();
                while ($_row = $resul->fetch(PDO::FETCH_ASSOC)) {
                    $idgrupo=$_row['CodGrupo'];
                }
                // $this->error.=$q_idgrupo."<br>";
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            return $idgrupo;
        }

        function es_rama(){
            include "conexion.php";

            $carrera=$this->idcarrera;
            $periodo=$this->periodo;
            $materia=$this->idmateria;
            $grupo_letra=$this->grupo;

            try {

                $query="SELECT * FROM grupos_fusionados where codcar_rama='$carrera' AND per_rama=$periodo AND  mat_rama='$materia' AND gru_rama='$grupo_letra'";
                $_consultando = $bdcon->prepare($query);
                $_consultando->execute();
                
            } catch (PDOException $e) {
                // $this->error.=$this->idgrupo;
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            if($_consultando->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }

        function getIdramaRaiz(){
            include "conexion.php";
            $idgrupo=0;
            $carrera=$this->idcarrera;
            $periodo=$this->periodo;
            $materia=$this->idmateria;
            $grupo_letra=$this->grupo;

            try {

                $query="SELECT codcar_raiz, per_raiz, mat_raiz, gru_raiz FROM grupos_fusionados where codcar_rama='$carrera' AND per_rama=$periodo AND  mat_rama='$materia' AND gru_rama='$grupo_letra'";
                $_consultando = $bdcon->prepare($query);
                $_consultando->execute();
                while ($row = $_consultando->fetch(PDO::FETCH_ASSOC)) {
                    $_carrera=$row['codcar_raiz'];
                    $_periodo=$row['per_raiz'];
                    $_materia=$row['mat_raiz'];
                    $_grupo=$row['gru_raiz'];
                }
                $q_idgrupo="SELECT CodGrupo FROM grupos WHERE periodo=$_periodo AND codmateria='$_materia' AND Descripcion='$_grupo' AND CodCarrera='$_carrera'";
                $resul = $bdcon->prepare($q_idgrupo);
                $resul->execute();
                while ($_row = $resul->fetch(PDO::FETCH_ASSOC)) {
                    $idgrupo=$_row['CodGrupo'];
                }
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
            }
            return $idgrupo;
        }
    }
?>