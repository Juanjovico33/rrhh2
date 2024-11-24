<?php
    class file{
        private $id;
        private $codgrupo;
        private $title;
        private $description;
        private $url;
        private $type;
        private $fecha;
        private $hora;
        private $usuario;

        private $hostremote;
        private $error;

        function __contruct(){
            $this->id=0;
            $this->codgrupo=0;
            $this->title='';
            $this->description='';
            $this->url='';
            $this->type='';
            $this->fecha='0000-00-00';
            $this->hora='00:00:00';
            $this->usuario='';
            $this->hostremote="http://190.186.233.211/";
        }

        public function getUrl(){
            return $this->url;
        }
        public function setUrl($_url){
            $this->url=$_url;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function getDatosFile($_idgrupo){
            include "conexion.php";
            $q_file="SELECT id, codgrupo, title, description, url, type, fecha, hora, usuario FROM files WHERE codgrupo=".$_idgrupo." limit 1";
            try {
                $_filing = $bdcon->prepare($q_file);
                $_filing->execute();
            } catch (PDOException $e) {
                // $this->error .= $q_lnk;
                $this->error .= 'La conexión para obtener la url del archivo ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $_filing->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->codgrupo=$row['codgrupo'];
                    $this->title=$row['title'];
                    $this->description=$row['description'];
                    $this->url=$row['url'];
                    $this->type=$row['type'];
                    $this->fecha=$row['fecha'];
                    $this->hora=$row['hora'];
                    $this->usuario=$row['usuario'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
            if($_filing->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }

    }
?>