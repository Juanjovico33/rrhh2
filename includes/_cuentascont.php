<?php
     class cuentascont{         
        private $gestion;
        private $codest;
        private $planpagos;        
        private $tipodeuda;
        private $montocolegiatura;
        private $montodescuento;
        private $montobeca;
        private $error;

        public function __construct($codigo){
          
            $this->gestion=0;
            $this->codest=$codigo;
            $this->error='';
            $this->planpagos=0;
            $this->tipodeuda=0;  
            $this->montocolegiatura=0; 
            $this->montodescuento=0; 
            $this->montobeca=0;                     
        }

        public function getCodestudiante() {
            return $this->codestudiante;
        }
    
        public function setCodestudiante($codestudiante) {
            $this->codestudiante = $codestudiante;
        }    
       
        
        function getcolegiatura($periodo){
            include "conexion.php";
            $query="SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc";
            $_idgrupo=0;
            // $this->error.=$query.'<br>';            
            try{
                $_qcod = $bdcon->prepare($query);
                $_qcod->execute();
                    while ($row = $_qcod->fetch(PDO::FETCH_ASSOC)) {
                        $montosss=$row['monto'];
                        $tipocole=$row['tipodeuda'];
                         if ($tipocole==1) {
                          $this->montocolegiatura=$montosss;
                        }  
                        if ($tipocole==656) {
                            $this->montodescuento=$montosss;
                        }
                        if ($tipocole>=20 and $tipocole<=34) {
                           $this->montobeca=$montosss;
                        }
                    }
            }catch(PDOException $e){
               
                $this->error .= 'Error al estructurar los datos a base de la conexion : ' . $e->getMessage();
            }
            
        }
         
    }
?>