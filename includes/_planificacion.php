<?php
    class unidad{
        private $id;
        private $nombre;

        public function __construct(){
            $this->id=0;
            $this->nombre='';
        }

        function getId(){
            return $this->id;
        }
        function setId($id){
            $this->id=$id;
        }
        function getNombre(){
            return $this->nombre;
        }
        function setNombre($nombre){
            $this->nombre=$nombre;
        }
    }
    class modalidad{
        private $id;
        private $nombre;

        public function __construct(){
            $this->id=0;
            $this->nombre='';
        }

        function getId(){
            return $this->id;
        }
        function setId($id){
            $this->id=$id;
        }
        function getNombre(){
            return $this->nombre;
        }
        function setNombre($nombre){
            $this->nombre=$nombre;
        }
    }
    class evaluacion{
        private $id;
        private $nombre;

        public function __construct(){
            $this->id=0;
            $this->nombre='';
        }

        function getId(){
            return $this->id;
        }
        function setId($id){
            $this->id=$id;
        }
        function getNombre(){
            return $this->nombre;
        }
        function setNombre($nombre){
            $this->nombre=$nombre;
        }
    }

    class tema{
            private $id_tema;
            private $nro_clase;
            private $fecha;
            private $semana;
            private $tema;
            
            private $modalidad;
            private $evaluacion;
            private $unidad;
            private $avanzado;

            public function __construct(){
                $this->id_tema=0;
                $this->nro_clase=0;
                $this->fecha='00-00-0000';
                $this->semana=0;
                $this->tema='';

                $this->modalidad=new modalidad();
                $this->evaluacion=new evaluacion();
                $this->unidad=new unidad();
                $this->avanzado=0;
            }
            function getId_tema(){
                return $this->id_tema;
            }
            function setId_tema($idtema){
                $this->id_tema=$idtema;
            }
            function getNro_clase(){
                return $this->nro_clase;
            }
            function setNro_clase($nroclase){
                $this->nro_clase=$nroclase;
            }
            function getFecha(){
                return $this->fecha;
            }
            function setFecha($fecha){
                $this->fecha=$fecha;
            }
            function getSemana(){
                return $this->semana;
            }
            function setSemana($semana){
                $this->semana=$semana;
            }
            function getTema(){
                return $this->tema;
            }
            function setTema($tema){
                $this->tema=$tema;
            }

            function getModalidad(){
                return $this->modalidad;
            }
            function setModalidad($modalidad){
                $this->modalidad=$modalidad;
            }
            function getEvaluacion(){
                return $this->evaluacion;
            }
            function setEvaluacion($evaluacion){
                $this->evaluacion=$evaluacion;
            }
            function getUnidad(){
                return $this->unidad;
            }
            function setUnidad($unidad){
                $this->unidad=$unidad;
            }
            function getAvanzado(){
                return $this->avanzado;
            }
            function setAvanzado($avance){
                $this->avanzado=$avance;
            }
    }
    class planificacion{
        public $nro;
        private $temas;

        private $id_grupo;

        public $_unidades;
        public $_modalidades;
        public $_evaluaciones;
        public $_avances;

        public $error;

        public function __construct(){
            $this->nro=0;
            $this->_temas=null;

            $this->id_grupo=0;
            $this->_unidades=null;
            $this->_evaluaciones=null;
            $this->_modalidades=null;
            $this->_avances=null;
            $this->error='';
        }

        function getAllModalidades(){
            include "conexion.php";
            $modalidad=null;

            try {
                $q_modulos="SELECT * FROM aa_modalidad";
                $s_mod = $bdcon->prepare($q_modulos);
                $s_mod->execute();

            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener los modulos de temas ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_mod->fetch(PDO::FETCH_ASSOC)) {
                    $modalidad=new modalidad();
                    $modalidad->setId($row['id']);
                    $modalidad->setNombre($row['nombre']);
                    $this->_modalidades[]=$modalidad;
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getAllEvaluaciones(){
            include "conexion.php";
            $evaluacion=null;

            try {
                $q_evaluaciones="SELECT * FROM aa_evaluacion";
                $s_eval = $bdcon->prepare($q_evaluaciones);
                $s_eval->execute();

            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener los modulos de temas ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_eval->fetch(PDO::FETCH_ASSOC)) {
                    $evaluacion=new modalidad();
                    $evaluacion->setId($row['id']);
                    $evaluacion->setNombre($row['nombre']);
                    $this->_evaluaciones[]=$evaluacion;
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getUnidades($idgrupo){
            include "conexion.php";
            $unidad=null;

            try {
                $q_unidades="SELECT * FROM aa_unidad_por_clase WHERE id_grupo=$idgrupo";
                $s_uni = $bdcon->prepare($q_unidades);
                $s_uni->execute();

            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener los modulos de temas ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_uni->fetch(PDO::FETCH_ASSOC)) {
                    $unidad=new unidad();
                    $unidad->setId($row['id_modulo']);
                    $unidad->setNombre($row['nombre']);
                    $this->_unidades[]=$unidad;
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }
        
        function searchModalidad($id_modalidad){
            $cont=0;
            $modalidad=null;
            try{
                $cont=count($this->_modalidades);
                for($i=0;$i<$cont;$i++){
                    if($this->_modalidades[$i]->getId()==$id_modalidad){
                       $modalidad = $this->_modalidades[$i];
                       break ;
                    }
                }
            }catch(Exception $e){
                $this->error .= 'El intento de sacar la modalidad a tenido un error.' . $e->getMessage().'<br>';
            }
            return $modalidad;
        }

        function searchEvaluacion($id_evaluacion){
            $cont=0;
            $evaluacion=null;
            try{
                $cont=count($this->_evaluaciones);
                for($i=0;$i<$cont;$i++){
                    if($this->_evaluaciones[$i]->getId()==$id_evaluacion){
                       $evaluacion = $this->_evaluaciones[$i];
                       break ;
                    }
                }
            }catch(Exception $e){
                $this->error .= 'El intento de sacar la evaluacion a tenido un error.' . $e->getMessage().'<br>';
            }
            return $evaluacion;
        }

        function searchUnidad($id_unidad){
            $cont=0;
            $unidad=null;
            try{
                $cont=count($this->_unidades);
                for($i=0;$i<$cont;$i++){
                    if($this->_unidades[$i]->getId()==$id_unidad){
                       $unidad = $this->_unidades[$i];
                       break ;
                    }
                }
            }catch(Exception $e){
                $this->error .= 'El intento de sacar la evaluacion a tenido un error.' . $e->getMessage().'<br>';
            }
            return $unidad;
        }


        //pendiente
        function getTemas($idgrupo){
            include "conexion.php";
            $tema=null;

            try {
                $q_temas="SELECT * FROM aa_por_clase WHERE idgrupo=$idgrupo ORDER BY dia";
                $s_tem = $bdcon->prepare($q_temas);
                $s_tem->execute();

            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener los modulos de temas ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_tem->fetch(PDO::FETCH_ASSOC)) {
                    $tema=new tema();
                    $tema->setId_tema($row['id_avance']);
                    $tema->setNro_clase($row['nro_clase']);

                    $tema->setFecha($row['dia']);
                    $tema->setSemana($row['semana']);
                    $tema->setTema($row['tema']);

                    $tema->setModalidad($this->searchModalidad($row['id_modalidad']));
                    $tema->setEvaluacion($this->searchEvaluacion($row['id_evaluacion']));
                    $tema->setUnidad($this->searchUnidad($row['id_unidad']));

                    $tema->setAvanzado($this->getAvance($row['id_avance']));
                    $this->nro=$this->nro+1;
                    $this->_temas[]=$tema;
                    $this->_avances[]=$tema->getId_tema();
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }

        function getAvance($idavance){
            include "conexion.php";
            $porcentaje=0;
            try{
                $q_avance="SELECT * FROM aa_reg_avance WHERE id_avance=$idavance";
                $s_avan = $bdcon->prepare($q_avance);
                $s_avan->execute();
                while ($row = $s_avan->fetch(PDO::FETCH_ASSOC)) {
                    $porcentaje=$row['porcentaje'];
                    if($porcentaje>100){
                        $porcentaje=100;
                    }
                }
            }
            catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            return $porcentaje;
        }
        // function getAvances(){
        //     if(!is_null($_avances)){
        //         $cont=count($_avances);
        //         for($i=0;$i<$cont;$i++){

        //         }
        //     }
        // }
    }
    
?>