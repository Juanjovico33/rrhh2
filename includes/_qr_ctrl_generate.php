<?php
    class qr_ctrl{
        
        private $id;
        private $id_qr_une;
        private $id_qr_bcp;
        private $estado;
        private $error;

        function __construct(){
            $this->id=0;
            $this->id_qr_une="";
            $this->id_qr_bcp=0;
            $this->estado=0;
            $this->error='';
        }
        public function getId(){
            return $this->id;
        }
        public function setId($_Id){
            $this->id=$_Id;
        }
        public function getId_qr_une(){
            return $this->id_qr_une;
        }
        public function setId_qr_une($_IdQrUne){
            $this->id_qr_une=$_IdQrUne;
        }
        public function getId_qr_bcp(){
            return $this->id_qr_bcp;
        }
        public function setId_qr_bcp($_IdQrBcp){
            $this->id_qr_bcp=$_IdQrBcp;
        }
        public function getEstado(){
            return $this->estado;
        }
        public function setEstado($_estado){
            $this->estado=$_estado;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($error){
            $this->error=$error;
        }


        function existe_qr($id_qr_une){
            include "conexion.php";

            try {
                $q_qrgenerated="SELECT id, id_qr_une, id_qr_bcp, estado FROM qr_id_ctrlune where id_qr_une='$id_qr_une' order by id desc limit 1";
                $object_qr = $bdcon->prepare($q_qrgenerated);
                $object_qr->execute();
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener datos del qr ha fallado' . $e->getMessage().'<br>';
                return false;
            }
            try{
                while ($row = $object_qr->fetch(PDO::FETCH_ASSOC)) {
                    $this->id=$row['id'];
                    $this->id_qr_une=$row['id_qr_une'];
                    $this->id_qr_bcp=$row['id_qr_bcp'];
                    $this->estado=$row['estado'];
                }
            }catch (PDOException $e) {
                $this->error .= 'Error al obtener los datos : ' . $e->getMessage();
                return false;
            }

            if($object_qr->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }

        function insert_qrGenerated($id_qr_une, $id_qr_bcp, $expiracion, $codest, $periodo, $cuota, $monto, $estado){
            include "conexion.php";        

            try {
                $q_insert_qr="INSERT INTO qr_id_ctrlune (id,id_qr_une,id_qr_bcp,expiracion,registro,codest,periodo,cuota,monto,estado) VALUES (0,'$id_qr_une', '$id_qr_bcp','$expiracion',now(),$codest,$periodo,$cuota,'$monto','$estado')";
                $obj_qr_insert = $bdcon->prepare($q_insert_qr);
                if($obj_qr_insert->execute()){
                    return true;
                }else{
                    return false;
                }
                // $this->error.=$q_grupo;
            } catch (PDOException $e) {
                $this->error .= 'La conexión para insertar datos del qr ha fallado' . $e->getMessage().'<br>';
                return false;
            }
        }
    }
?>