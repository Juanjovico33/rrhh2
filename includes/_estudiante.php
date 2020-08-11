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

        function getperiodosregistrados($gestion, $per_encurso){
            // function getperiodosregistrados($gestion){
            include "conexion.php";
            $gestion_anterior=0;
            $periodos_consulta='';
            $periodos_consulta=$this->getCondicionORPeriodos($per_encurso);
            $q_perregistrados='';
            try {
                if($this->getCodCarrera()=='02AUD' || $this->getCodCarrera()=='02DER' || $this->getCodCarrera()=='02ADM'){
                    $gestion_anterior=$gestion-1;
                    $q_perregistrados="SELECT periodo FROM aca_registroestgest WHERE (codest='$this->codest' or codest='$this->codestudiante') and (gestion='$gestion' or gestion='$gestion_anterior') and ($periodos_consulta) group by periodo order by periodo";
                    // periodo=$per_encurso[0] or periodo=$per_encurso[1] or periodo=$per_encurso[2] or periodo=$per_encurso[3] or periodo=$per_encurso[4] or periodo=$per_encurso[5]
                }else{
                    $q_perregistrados="SELECT periodo FROM aca_registroestgest WHERE (codest='$this->codest' or codest='$this->codestudiante') and gestion='$gestion' and ($periodos_consulta) group by periodo order by periodo";
                    // periodo=$per_encurso[0] or periodo=$per_encurso[1] or periodo=$per_encurso[2] or periodo=$per_encurso[3] or periodo=$per_encurso[4] or periodo=$per_encurso[5]
                }
                $periodosregistrados = $bdcon->prepare($q_perregistrados);
                $periodosregistrados->execute();
                // $this->error.= $periodos_consulta;
                // $this->error.=$q_perregistrados.'<br>';
            } catch (PDOException $e) {
                $this->error .= 'La conexión para CLASS::Estudiante ha fallado, intente realizar su solicitud nuevamente: ' . $e->getMessage().'<br>';
            }
            return $periodosregistrados;
        }

        function getCondicionORPeriodos($periodos){
            $_consulta='';
            if(!is_null($periodos)){
                try{
                    for($i=0;$i<count($periodos);$i++){
                        if($i==0){
                            $_consulta.='periodo='.$periodos[$i];
                        }else{
                            $_consulta.=' OR periodo='.$periodos[$i];
                        }
                    }
                }catch(Exception $e){
                    $this->error.="Error al obtener los periodos correspondientes:".$e->getMessage();
                }
            }
            return $_consulta;
        }

        function getmateriasporperiodo($periodos, $idgestion){
            // function getmateriasporperiodo($periodos, $per, $idgestion){
            include "conexion.php";
            $q_matxper='';
            $pMaterias=null;
            if(!is_null($periodos)){
                try{
                    while ($row = $periodos->fetch(PDO::FETCH_ASSOC)) {
                        $idperiodo=$row['periodo'];
                        //---Oteniendo el nombre del periodo
                        $selectnombreperiodo = $bdcon->prepare("select opcion from periodo where id=$idperiodo;");
                        $selectnombreperiodo->execute();
                        while ($rowper = $selectnombreperiodo->fetch(PDO::FETCH_ASSOC)) {
                            $periodonombre=$rowper['opcion'];
                            $q_matxper="SELECT * from aca_registroestmat inner join aca_pensum on (aca_registroestmat.codmateria=aca_pensum.materia) where (aca_registroestmat.codest='$this->codestudiante' or aca_registroestmat.codest='$this->codest') and (aca_registroestmat.periodo='$idperiodo') and aca_pensum.gestion='$idgestion' and aca_pensum.carrera='$this->cod_carrera'";
                            $pMaterias[] = array(
                                'idperiodo'=>$idperiodo,
                                'nbperiodo'=>$periodonombre,
                                'materias_array'=>$this->getdatosgrupos($q_matxper)
                            );
                            // $this->error .="SELECT * from aca_registroestmat inner join aca_pensum on (aca_registroestmat.codmateria=aca_pensum.materia) where (aca_registroestmat.codest='$this->codestudiante' or aca_registroestmat.codest='$this->codest') and (aca_registroestmat.periodo='$per[0]' or aca_registroestmat.periodo='$per[1]' or aca_registroestmat.periodo='$per[2]') and aca_pensum.gestion='$idgestion' and aca_pensum.carrera='$this->cod_carrera';"; //Consulta con errores
                            // $this->error .= $q_matxper ;
                        }
                    }
                }catch (PDOException $e) {
                    $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
                }
            }else{
                $pMaterias=null;
            }
            // $this->error.=var_dump($periodos);
            return $pMaterias;
        }

        function getdatosgrupos($query){
            $materias=null;
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