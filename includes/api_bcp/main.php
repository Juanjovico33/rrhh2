<?php
    include "BCPService.php";
    $collector = array( //En este campo se envia datos a guardar en un array
        array(
            "Name" => "Id",
            "Paremeter" =>  "int",
            "Value" => 123
        ),
        array(
            "Name" => "Nombre",
            "Paremeter" =>  "string",
            "Value" => "Prueba"
        ),
        array(
            "Name" => "Livees",
            "Paremeter" =>  "ClasePrueba",
            "Value" => array(
                "Key" => "Value"
            )
        )
    );
    $bcp = new BCPServices();
    // for quest : enable_bank, collectors, correlationId_?

    // GenerateQr(Monto, Moneda, Glosa, collector, expiracion, correlationId_?)
    $qr = $bcp->GeneratedQr(1, "BOB", "GLOSA PSS", $collector, "1/00:00", "123");
    print_r($qr);
    echo '<br>';
    echo '<img src="data:image/png;base64,'.$qr->data->qrImage.'"/>';
    echo '<br>';
    $consult = $bcp->ConsultQr($qr->data->id, "123");
    print_r($consult);
    echo '<br>';
    echo '<img src="data:image/png;base64,'.$consult->data->qrImage.'"/>';