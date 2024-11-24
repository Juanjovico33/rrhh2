<?php 
$codest=$_REQUEST['_codest'];
$semestre = $_REQUEST['_semestre'];
$carrera = $_REQUEST['_carrera'];

$fecha=new DateTime();
$periodo=0;
$fecha->setTimezone(new DateTimeZone('America/La_Paz'));
$nbges=$fecha->format('Y');
$nbmes=$fecha->format('m');
if ($nbmes<8){
    $periodo=$nbges."01";
}else{
    $periodo=$nbges."02";
}

include "../../includes/conexion.php";
include "class_trammite.php";
$tram=new tramites;
?>
<!-- content -->
    <div class="page-content-inner" id="content_plataforma">

        <div uk-grid>
            <div class="uk-width-1-3@m">

                <nav class="responsive-tab style-3 setting-menu card"
                    uk-sticky="top:30 ; offset:100; media:@m ;bottom:true; animation: uk-animation-slide-top">
                    <h5 class="mb-0 p-3 uk-visible@m"> Iniciar Trámite </h5>
                    <hr class="m-0 uk-visible@m">
                    <p class="ml-2 mr-2">Para empezar un nuevo trámite tiene que cancelar el monto de 10bs.</p>
                        <button class="btn btn-success ml-4 mr-4 mb-2" uk-toggle="target: #modal-example2" onclick="verificar_qr_bcp('<?=$codest?>','<?=$semestre?>','<?=$carrera?>','0','10','355','<?=$periodo?>')"><i class="uil-layers "></i> Nuevo trámite</button>
                    <hr>
                    <h5 align="center">Historial de Trámites</h5>
                    <p class="ml-2 mr-2">
                    	<?php
	                    $qdat=$tram->historial($codest); 
	                    ?>
                    </p>
                </nav>
            </div>

            <div class="uk-width-2-3@m">

                <div class="card rounded">
                    <div class="p-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"> Lista de Trámites </h5> <span> 7 Trámites </span>
                    </div>
                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/pagos.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Auditoría de pagos </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 10 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','1')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/regular.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Certificado alumno regular detallado </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 150 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','2')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/extra.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Certificado alumno regular extranjero </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 150 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','3')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/documentacion.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Copia documentación con Programas analíticos  </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 2,000 Bs.</a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','4')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/examen.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Examen Preinternado </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 800 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','5')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/fuera.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Examen Preinternado fuera de fecha </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 1,000 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','6')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="uk-grid-small p-4" uk-grid>
                        <div class="uk-width-1-3@m">
                            <img src="img/iconos/conmul.png" class="  rounded" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h5 class="mb-2"> Examen Preinternado fuera de fecha mas multa por deudas  </h5>
                            <p class="uk-text-small mb-2"> Costo <a href="#"> 1,300 Bs. </a>
                            </p>
                            <p class="mb-0 uk-text-small mt-3">
                                <span class="mr-3 bg-light p-2 mt-1" onclick="nuevo_tramite('<?=$codest?>','7')" style="cursor: pointer;">Iniciar Trámite</span><span> Última actualización
                                    <?=date('m')?>/<?=date("Y")?> </span>
                            </p>
                        </div>
                    </div>
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