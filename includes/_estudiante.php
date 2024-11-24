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
                $q_datosest="SELECT codEstudiante, nombres, apellidos, nombcompleto, gestion, codest, carrera, planpago from estudiante where (codEstudiante='$this->codest' or codest='$this->codest') AND codest!=0 limit 1";
                $student = $bdcon->prepare($q_datosest);
                $student->execute();
                // $this->error.=$q_datosest;
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
                    $this->setSemestre($this->getSemestreEst());
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
                    $periodos_consulta.=$this->getCondicionORPeriodos_menosunagest($per_encurso);
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

        function getCondicionORPeriodos_menosunagest($periodos){
            $_consulta='';
            $anio = substr($periodos[0],0,4);
            $anio = $anio -1;
            if(!is_null($periodos)){
                try{
                    for($i=0;$i<count($periodos);$i++){
                        $per2=substr($periodos[$i],4,2);
                        if($per2=="02" || $per2=="08"){
                            $_consulta.=' OR periodo='.$anio.$per2;
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
                            $q_matxper="SELECT  mat.idgrupo, mat.codmateria, mat.periodo, mat.grupo, mat.gestion, pen.carrera, pen.semestre, pen.id_resolucion from aca_registroestmat mat inner join aca_pensum pen on (mat.codmateria=pen.materia) where (mat.codest='$this->codest') and (mat.periodo='$idperiodo') and pen.gestion='$idgestion' and pen.carrera='$this->cod_carrera'";

                            $pMaterias[] = array(
                                'idperiodo'=>$idperiodo,
                                'nbperiodo'=>$periodonombre,
                                'materias_array'=>$this->getdatosgrupos($q_matxper)
                            );

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

        function getmateriaspor_UnSolo_periodo($idperiodo, $idgestion){
            // function getmateriasporperiodo($periodos, $per, $idgestion){
            include "conexion.php";
            $q_matxper='';
            $pMaterias=null;
            try{
                //---Oteniendo el nombre del periodo
                $selectnombreperiodo = $bdcon->prepare("select opcion from periodo where id=$idperiodo;");
                $selectnombreperiodo->execute();
                while ($rowper = $selectnombreperiodo->fetch(PDO::FETCH_ASSOC)) {
                    $periodonombre=$rowper['opcion'];
                    $q_matxper="SELECT  mat.idgrupo, mat.codmateria, mat.periodo, mat.grupo, mat.gestion, pen.carrera, pen.semestre, pen.id_resolucion from aca_registroestmat mat inner join aca_pensum pen on (mat.codmateria=pen.materia) where (mat.codest='$this->codest') and (mat.periodo='$idperiodo') and pen.gestion='$idgestion' and pen.carrera='$this->cod_carrera'";

                    $pMaterias[] = array(
                        'idperiodo'=>$idperiodo,
                        'nbperiodo'=>$periodonombre,
                        'materias_array'=>$this->getdatosgrupos($q_matxper)
                    );

                    // $this->error .= $q_matxper ;
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            // $this->error.=var_dump($periodos);
            return $pMaterias;
        }

        function getdatosgrupos($query){
            $materias=null;
            // $this->error.=$query;
            include "conexion.php";
            try{
                $grupos = $bdcon->prepare($query);
                $grupos->execute();
                    while ($row = $grupos->fetch(PDO::FETCH_ASSOC)) {
                        $idgrupo=$row['idgrupo'];

                        $codmateria=$row['codmateria'];
                        $grupo=$row['grupo'];
                        $periodo=$row['periodo'];//--
                        $gestion=$row['gestion'];//--
                        $cod_carrera=$row['carrera'];
                        $idgrupo=$this->getIdGrupoDb($periodo, $cod_carrera, $codmateria, $grupo);

                        $semestre=$row['semestre'];
                        $this->setSemestre($semestre);
                        $nb_materia='';
                        $id_resolucion=$row['id_resolucion'];

                        $select_materia=$bdcon->prepare("SELECT Descripcion FROM materias WHERE CodCarrera='$cod_carrera' AND id_resolucion='$id_resolucion' AND Sigla='$codmateria'");
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

        function getIdGrupoDb($periodo, $carrera, $materia, $_txgrupo){
            include "conexion.php";
            $query="SELECT CodGrupo FROM grupos WHERE periodo=$periodo AND codmateria='$materia' AND Descripcion='$_txgrupo' AND CodCarrera='$carrera'";
            $_idgrupo=0;
            // $this->error.=$query.'<br>';
            try{
                $_qcod = $bdcon->prepare($query);
                $_qcod->execute();
                    while ($row = $_qcod->fetch(PDO::FETCH_ASSOC)) {
                        $_idgrupo=$row['CodGrupo'];
                    }
            }catch(PDOException $e){
                return 0;
                $this->error .= 'Error al estructurar los datos a base de la conexion : ' . $e->getMessage();
            }
            return $_idgrupo;
        }
        function getSemestreEst(){
            include "conexion.php";
            $query="SELECT semestre FROM aca_estudiantesemestre WHERE codest=$this->codest order by periodo DESC limit 1";
            $this->semestre=0;
            // $this->error.=$query.'<br>';
            try{
                $_qcod = $bdcon->prepare($query);
                $_qcod->execute();
                    while ($row = $_qcod->fetch(PDO::FETCH_ASSOC)) {
                        $this->semestre=$row['semestre'];
                    }
            }catch(PDOException $e){
                return 0;
                $this->error .= 'Error al estructurar los datos a base de la conexion : ' . $e->getMessage();
            }
            return $this->semestre;
        }
    }
?>