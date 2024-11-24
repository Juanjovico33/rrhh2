<?php 
$codest=$_REQUEST['codest'];
$tramite=$_REQUEST['tramite'];
include "../../includes/conexion.php";
include"class_trammite.php";
$tram=new tramites;
$nb=$tram->nb_tramite($tramite);
$v_pagos=$tram->verificar_pagos($codest);

$fecha=new DateTime();
$periodo=0; $semestre=0; $carrera=0;
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
if ($nbmes<8){
    $periodo=$nbges."01";
}else{
    $periodo=$nbges."02";
}

$q_semcar = $bdcon->prepare("SELECT sem.semestre, e.carrera FROM sainc.estudiante e LEFT JOIN sainc.aca_estudiantesemestre sem ON e.codest=sem.codest where e.codest=$codest and sem.periodo=$periodo limit 1");
$q_semcar->execute();
while(($row = $q_semcar->fetch(PDO::FETCH_ASSOC))){
	$semestre = $row['semestre'];
	$carrera = $row['carrera'];
}


?>
<div class="page-content-inner" id="content_plataforma">
    <div uk-grid>
        <div class="uk-width-2-3@m">
            <div class="card rounded">
                <?php 
                //echo $v_pagos;
                //echo "<br>";
                $switchi=0;
                if ($v_pagos=='') {
                	?>
                	<div class="alert alert-danger" role="alert">
					  <h4 class="alert-heading">Error!</h4>
					  <p>No se encontraron pagos para plataforma de atención.</p>
					  <hr>
					  <p class="mb-0">Tiene que pagar por QR en la opcion: Iniciar Trámite->Nuevo Trámite.</p>
					</div>
                	<?php
                }else{
                	$part=explode("|", $v_pagos);
                	foreach ($part as $key => $value) {
                		//echo $value;
                		//echo "<br>";
                		$hay=$tram->verifica_usados($codest,$value);
                		if ($hay=='') {
                			//registrar el comprobante en historial
                			//echo " ** registrar en historial ** ".$value;
                			//echo "<br>";
                			$reg=$tram->registrar_historial($codest, $value, $tramite);
                			$switchi=0;
                			break;
                		}else{
                			//esta usado no tomar en cuenta
                			//echo " // no tomar en cuenta ya esta usado //";
                			//echo "<br>";
                			$switchi=1;
                		}
                	}
                	if ($switchi=='1') {
                		//no le quedan comprobantes por uso de plataforma, no procede
                		?>
	                	<div class="alert alert-danger" role="alert">
						  <h4 class="alert-heading">Error!</h4>
						  <p>No le quedan pagos por uso de plataforma.</p>
						  <hr>
						  <p class="mb-0">Tiene que pagar por QR en la opcion: Iniciar Trámite->Nuevo Trámite.</p>
						</div>
	                	<?php
                	}else{
                		?>
                		<div class="p-3 d-flex align-items-center justify-content-between">
	                    <h5 class="mb-0"> <?=$nb?> </h5>
		                </div>
		                <hr class="m-0">
	                	<?php
	                	if ($tramite=='1') {
	                		?>
		                	<div class="uk-grid-small p-4" uk-grid>
			                    <div class="uk-width-1-3@m">
			                        <img src="img/iconos/pagosqr.png" class="  rounded" alt="">
			                    </div>
			                    <div class="uk-width-expand">
			                        <h5 class="mb-2"> Solicitud registrada </h5>
			                        <p class="uk-text-small mb-2"> Costo <a href="#"> 0 Bs. </a>
			                        </p>
			                        <p class="mb-0 uk-text-small mt-3">
			                            <!--button class="btn btn-success">PAGAR</button-->
			                        </p>
			                    </div>
			                </div>
		                	<?php
	                	}else{
	                		$d_costos=$tram->costos_tramites($tramite);
			                $part=explode("|", $d_costos);
			                foreach ($part as $key => $value) {
			                	$partir=explode(",", $value);
			                	?>
			                	<div class="uk-grid-small p-4" uk-grid>
				                    <div class="uk-width-1-3@m">
				                        <img src="img/iconos/pagosqr.png" class="  rounded" alt="">
				                    </div>
				                    <div class="uk-width-expand">
				                        <h5 class="mb-2"> <?=$partir[1]?> </h5>
				                        <p class="uk-text-small mb-2"> Costo <a href="#"> <?=$partir[2]?> Bs. </a>
				                        </p>
				                        <p class="mb-0 uk-text-small mt-3">
				                            <button class="btn btn-success" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp('<?=$codest?>','<?=$semestre?>','<?=$carrera?>','355','<?=$partir[2]?>','<?=$tramite?>','<?=$periodo?>')">PAGAR</button>
				                        </p>
				                    </div>
				                </div>
			                	<?php
			                }
	                	}
                	}
                }
                ?>
            </div>
        </div>
    </div>              
</div>
<div id="modal-example2" uk-modal>
	<div class="uk-modal-dialog uk-modal-body"> 
		<div id="mos_qr">                                
		</div> 
	</div>
</div>