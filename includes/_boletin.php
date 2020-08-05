<?php 
    class boletin{
        private $primer_parcial;
        private $segundo_parcial;
        private $notas_practicas;
        private $examen_final;
      public function __construct($codigo){
            $this->primer_parcial=0;
            $this->segundo_parcial=0;
            $this->notas_practicas=0;
            $this->examen_final=0;      
            $this->codest=$codigo;          
        }

        public function primerparcial() {
            return $this->primer_parcial;
        }
    
        public function segundoparcial() {
            $this->segundo_parcial;
        }
    
        public function notaspracticas() {
            return $this->notas_practicas;
        }
    
        public function examenfinal() {
            $this->examen_final;
        }    
    }
?>