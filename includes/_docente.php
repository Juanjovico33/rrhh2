<?php
    class docente{
        
        private $id_docente;
        private $nombres;
        private $apellidos;
        private $nombCompleto;
        private $error;

        function __construct(){
            $this->id_docente=0;
            $this->nombres='';
            $this->apellidos='';
            $this->nombCompleto='';
            $this->error='';
        }

        public function getId_docente(){
            return $this->id_docente;
        }
        public function setId_docente($iddocente){
            $this->id_docente=$iddocente;
        }
        public function getNombres(){
            return $this->nombres;
        }
        public function setNombres($nombres){
            $this->nombres=$nombres;
        }
        public function getApellidos(){
            return $this->apellidos;
        }
        public function setApellidos($apellidos){
            $this->apellidos=$apellidos;
        }
        public function getNombreCompleto(){
            return $this->nombCompleto;
        }
        public function setNombreCompleto($nombre_completo){
            $this->nombCompleto=$nombre_completo;
        }
        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }


        function getDatosDocente($id_docente){
            include "conexion.php";

            try {
                $q_docente="SELECT id_docente, nombres, apellidos FROM docentes where id_docente=$id_docente";
                $s_doc = $bdcon->prepare($q_docente);
                $s_doc->execute();
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener datos del docente ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $s_doc->fetch(PDO::FETCH_ASSOC)) {
                    $this->id_docente=$row['id_docente'];
                    $this->nombres=$row['nombres'];
                    $this->apellidos=$row['apellidos'];
                    $this->nombCompleto=$this->nombres.' '.$this->apellidos;
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }

        }
    }
?>