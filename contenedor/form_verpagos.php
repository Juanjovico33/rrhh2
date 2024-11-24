<?php   
    include "../includes/conexion.php";
   echo  $codest = $_GET['_codest'];
   echo "<br>";
   echo  $periodo = $_GET['_periodo'];
   echo "<br>";
   echo  $parcial = $_GET['_parcial'];
   echo "<br>";
   $q_colegiatura= $bdcon->prepare("SELECT monto,tipodeuda FROM aca_registroestgest WHERE codest='$codest' and periodo='$periodo' order by cod_reg Desc");
    $q_colegiatura->execute(); 
    $montocole=0;
    $descuentocole=0;
    $becacole=0;
    while ($rowcole= $q_colegiatura->fetch(PDO::FETCH_ASSOC)) {
        $tipocole=$rowcole['tipodeuda'];
        $montosss=$rowcole['monto'];
        if ($tipocole==1) {
           $montocole=$montosss;
        }  
        if ($tipocole==656) {
            $descuentocole=$montosss;
        }
        if ($tipocole>=20 and $tipocole<=34) {
            $becacole=$montosss;
        }
    }
    $colepagar=$montocole-$descuentocole-$becacole;     
    $colemenos=$colepagar/4;
    $q_colepagado= $bdcon->prepare("SELECT sum(bolivianos) as pagado FROM ca_diario WHERE codest='$codest' and semestre='$periodo' and cuenta='38'");
    $q_colepagado->execute();
    while ($rcole= $q_colepagado->fetch(PDO::FETCH_ASSOC)) {
        $pagadocole=$rcole['pagado'];                         
    }
    echo "CUOTA A PAGAR : ".$parcial." TOTAL MONTO ". $colemenos*$parcial;
    echo "<br>";
    echo "TOTAL MONTO PAGADO: ". $pagadocole;
   ?>
   <div class="modal-footer">   
    <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>
</div>