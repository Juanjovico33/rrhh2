<?php
    include "_carrera.php";

    class estudiante{
        private $codestudiante;
        private $nombre;
        private $apellido;
        private $nombfull;
        private $gestion;
        private $codest;
        private $planpagos;
        private $nb_carrera;
        private $cod_carrera;
        private $semestre;

        private $error;

        public function __construct($codigo){
            $this->codestudiante=0;
            $this->nombre='';
            $this->apellido='';
            $this->nombfull='';
            $this->gestion=0;
            $this->codest=$codigo;
            $this->error='';
            $this->planpago=0;
            $this->nb_carrera='';
            $this->semestre=0;
            $this->cod_carrera = '';
        }

        public function getCodestudiante() {
            return $this->codestudiante;
        }
    
        public function setCodestudiante($codestudiante) {
            $this->codestudiante = $codestudiante;
        }
    
        public function getNombre() {
            return $this->nombre;
        }
    
        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
    
        public function getApellido() {
            return $this->apellido;
        }
    
        public function setApellido($apellido) {
            $this->apellido = $apellido;
        }
    
        public function getNombfull() {
            return $this->nombfull;
        }
    
        public function setNombfull($nombfull) {
            $this->nombfull = $nombfull;
        }
    
        public function getGestion() {
            return $this->gestion;
        }
    
        public function setGestion($gestion) {
            $this->gestion = $gestion;
        }
    
        public function getCodest() {
            return $this->codest;
        }
    
        public function setCodest($codest) {
            $this->codest = $codest;
        }

        public function getError() {
            return $this->error;
        }

        public function setCodCarrera($codcarrera) {
            $this->cod_carrera = $codcarrera;
        }

        public function getCodCarrera() {
            return $this->cod_carrera;
        }

        public function setPlanpago($planpago) {
            $this->planpago = $planpago;
        }

        public function getPlanpago() {
            return $this->planpago;
        }

        public function setSemestre($semestre) {
            $this->semestre = $semestre;
        }

        public function getSemestre() {
            return $this->semestre;
        }

        function getdatosest($gestion_actual){
            include "conexion.php";
            $carrera = new carrera();
            
            try {
                $student = $bdcon->prepare("SELECT codEstudiante, nombres, apellidos, nombcompleto, gestion, codest, carrera, planpago from estudiante where codEstudiante='$this->codest' or codest='$this->codest' limit 1");
                $student->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para CLASS::Estudiante ha fallado, intente realizar su solicitud nuevamente: ' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $student->fetch(PDO::FETCH_ASSOC)) {
                    $this->codestudiante=$row['codEstudiante'];
                    $this->nombre=$row['nombres'];
                    $this->apellido=$row['apellidos'];
                    $this->nombfull=$row['nombcompleto'];
                    $this->gestion=$row['gestion'];
                    $this->codest=$row['codest'];
                    $this->planpago=$row['planpago'];
                    $this->nb_carrera=$row['carrera'];
                    // $this->semestre=$row['semestre'];
                    $carrera->getcarreraest($this->nb_carrera, $gestion_actual, $this->codest);
                    $this->cod_carrera = $carrera->getCodigo();
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getperiodosregistrados($gestion){
            include "conexion.php";
            try {
                $periodosregistrados = $bdcon->prepare("SELECT periodo FROM aca_registroestgest WHERE (codest='$this->codest' or codest='$this->codestudiante') and gestion='$gestion' group by periodo");
                $periodosregistrados->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para CLASS::Estudiante ha fallado, intente realizar su solicitud nuevamente: ' . $e->getMessage().'<br>';
            }
            return $periodosregistrados;
        }

        function getmateriasporperiodo($periodos, $per, $idgestion){
            include "conexion.php";
            $pMaterias;
            try{
                while ($row = $periodos->fetch(PDO::FETCH_ASSOC)) {
                    $idperiodo=$row['periodo'];
                    //---Oteniendo el nombre del periodo
                    $selectnombreperiodo = $bdcon->prepare("select opcion from periodo where id=$idperiodo;");
                    $selectnombreperiodo->execute();
                    while ($rowper = $selectnombreperiodo->fetch(PDO::FETCH_ASSOC)) {
                        $periodonombre=$rowper['opcion'];
                        
                        $pMaterias[] = array(
                            'idperiodo'=>$idperiodo,
                            'nbperiodo'=>$periodonombre,
                            'materias_array'=>$this->getdatosgrupos("SELECT * from aca_registroestmat inner join aca_pensum on (aca_registroestmat.codmateria=aca_pensum.materia) where (aca_registroestmat.codest='$this->codestudiante' or aca_registroestmat.codest='$this->codest') and (aca_registroestmat.periodo='$idperiodo') and aca_pensum.gestion='$idgestion' and aca_pensum.carrera='$this->cod_carrera'")
                        );
                        // $this->error .="SELECT * from aca_registroestmat inner join aca_pensum on (aca_registroestmat.codmateria=aca_pensum.materia) where (aca_registroestmat.codest='$this->codestudiante' or aca_registroestmat.codest='$this->codest') and (aca_registroestmat.periodo='$per[0]' or aca_registroestmat.periodo='$per[1]' or aca_registroestmat.periodo='$per[2]') and aca_pensum.gestion='$idgestion' and aca_pensum.carrera='$this->cod_carrera';"; //Consulta con errores
                        $this->error .= $this->cod_carrera . '---';
                    }
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $pMaterias;
        }

        function getdatosgrupos($query){
            $materias;
            include "conexion.php";
            try{
                $grupos = $bdcon->prepare($query);
                $grupos->execute();
                    while ($row = $grupos->fetch(PDO::FETCH_ASSOC)) {

                        $idgrupo=$row['idgrupo'];
                        $codmateria=$row['codmateria'];
                        $grupo=$row['grupo'];
                        $semestre=$row['semestre'];
                        $nb_materia='';
                        $cod_carrera=$row['carrera'];
                        $id_resolucion=$row['id_resolucion'];

                        $select_materia=$bdcon->prepare("SELECT Descripcion FROM sainc.materias WHERE CodCarrera='$cod_carrera' AND id_resolucion='$id_resolucion' AND Sigla='$codmateria'");
                        $select_materia->execute();
                        while ($m_row = $select_materia->fetch(PDO::FETCH_ASSOC)) {
                            $nb_materia=$m_row['Descripcion'];}

                            $materias[]=array(
                            'idgrupo'=> $idgrupo,
                            'codmateria'=>$codmateria,
                            'grupo'=>$grupo,
                            'semestre'=>$semestre,
                            'nb_materia'=>$nb_materia
                        );
                    }
            }catch(PDOException $e){
                $this->error .= 'Error al estructurar los datos a base de la conexion : ' . $e->getMessage();
            }
            return $materias;
        }
    }
?>