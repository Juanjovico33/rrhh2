<?php
    class linkMeet{
        private $id;
        private $idgrupo;
        private $enlace;
        private $fecha;

        private $error;

        function __contruct(){
            $this->id=0;
            $this->idgrupo=0;
            $this->enlace='';
            $this->fecha='';
            $this->error='';
        }

        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id=$id;
        }
        public function getIdgrupo(){
            return $this->idgrupo;
        }
        public function setIdgrupo($idgrupo){
            $this->idgrupo=$idgrupo;
        }
        public function getEnlace(){
            return $this->enlace;
        }
        public function setEnlace($enlace){
            $this->enlace=$enlace;
        }
        public function getFecha(){
            return $this->fecha;
        }
        public function setFecha($fecha){
            $this->fecha=$fecha;
        }
        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }

        function getDatosEnlace($_idgrupo){
            include "conexion.php";
            $q_lnk="SELECT * FROM plat_doc_meet WHERE idgrupo=".$_idgrupo;
            try {
                $lnk = $bdcon->prepare($q_lnk);
                $lnk->execute();
            } catch (PDOException $e) {
                // $this->error .= $q_lnk;
                $this->error .= 'La conexión para obtener el enlace MEET ha fallado' . $e->getMessage().'<br>';
            }
            try{
                while ($row = $lnk->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['cod'];
                    $this->idgrupo=$row['idgrupo'];
                    $this->enlace=$row['url'];
                    $this->fecha=$row['fecha_reg'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al desplegar los datos : ' . $e->getMessage();
            }
        }
    }
?>