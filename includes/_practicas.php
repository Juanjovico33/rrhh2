<?php
    include "_grupo.php";

    class practica{
        private $id;
        private $error;

        public function getError() {
            return $this->error;
        }

        public function setError($id) {
            $this->error = $error;
        }

        public function __construct(){
            $this->id=0;
            $this->error='';
        }

        public function materias_practicas($array){
            $_pMaterias=null;
            $_smaterias=null;

            $_grupo= new grupo();
            $contando_materias="nothing!";

            if (!is_null($array)) {
                // EXPLORAMOS LOS PERIODOS EN J  --  02-08
                for($j=0;$j<count($array);$j++){
                    $id_periodo=$array[$j]['idperiodo'];
                    $nb_periodo=$array[$j]['nbperiodo'];
                    $materias=$array[$j]['materias_array'];

                    $_smaterias=null;
                    for($i=0;$i<count($materias);$i++){
                        $id_grupo=$materias[$i]['idgrupo'];
                        $cod_materia=$materias[$i]['codmateria'];
                        $grupo_des=$materias[$i]['grupo'];
                        $semestre=$materias[$i]['semestre'];
                        $nb_materia=$materias[$i]['nb_materia'];

                        $_grupo->getDatosGrupo($id_grupo);
                        if(!$_grupo->es_rama()){
                            if($this->tiene_practica($id_grupo)){
                                $_smaterias[]=array(
                                    'idgrupo'=> $id_grupo,
                                    'codmateria'=>$cod_materia,
                                    'grupo'=>$grupo_des,
                                    'semestre'=>$semestre, 
                                    'nb_materia'=>$nb_materia
                                );
                            }
                        }else{
                            $id_gRaiz=$_grupo->getIdramaRaiz();
                            if($id_gRaiz!=0){
                                if($this->tiene_practica($id_gRaiz)){
                                    $_smaterias[]=array(
                                        'idgrupo'=> $id_grupo,
                                        'codmateria'=>$cod_materia,
                                        'grupo'=>$grupo_des,
                                        'semestre'=>$semestre, 
                                        'nb_materia'=>$nb_materia
                                    );
                                }
                            }
                        }
                    }
                    $_pMaterias[] = array(
                        'idperiodo'=>$id_periodo,
                        'nbperiodo'=>$nb_periodo,
                        'materias_array'=>$_smaterias
                    );
                }
            }
            return $_pMaterias;
        }

        function tiene_practica($idgrupo){
            include "conexion.php";

            try {
                $query="SELECT * FROM grupos_sub WHERE idgrupo_padre=$idgrupo AND descripcion=1";
                $_consultando = $bdcon->prepare($query);
                $_consultando->execute();
                
            } catch (PDOException $e) {
                // $this->error.=$this->idgrupo;
                $this->error .= 'La conexión para obtener una consulta a fallado' . $e->getMessage().'<br>';
                return false;
            }
            if($_consultando->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }
    }
?>