<?php
    function FilePfxToFilePem(string $filePfxBcp, string $passwordPfxBcp, string $filePemBcp) {
        $contenidoPfx = file_get_contents($filePfxBcp);
        if (isset($contenidoPfx)) {
            openssl_pkcs12_read($contenidoPfx, $vectorPEM, $passwordPfxBcp);
            $contenidoPem = $vectorPEM["cert"].$vectorPEM["pkey"];
            file_put_contents($filePemBcp, $contenidoPem);
        } 
    }
    function ConexionApiBCP(string $url, string $method, $header, $body, string $correlationId, string $usuarioEmpresaBCP, string $passwordEmpresaBCP, string $filePemBcp) {
        if($filePemBcp != null && file_exists($filePemBcp)) {
            $conexion = curl_init();
            $header[] = "Correlation-Id:$correlationId";
            $header[] = 'Content-Type:application/json';
            $curlOption = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => "$usuarioEmpresaBCP:$passwordEmpresaBCP",
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_SSLCERTTYPE => "PEM",
                CURLOPT_SSLCERT => $filePemBcp
            );
            curl_setopt_array($conexion, $curlOption);
            $respuesta = curl_exec($conexion);
            return json_decode($respuesta);
        } else {
            echo "actualmente no cuenta con el certificado PEM
                y no se puede proceguir con la conexion a nuestra API";
        }
    }
    function verificate(string $dirCertificatePEM, string $dirCertificatePFX, string $passwordPFX) {
        if (!file_exists($dirCertificatePEM)) {
            FilePfxToFilePem($dirCertificatePFX, $passwordPFX, $dirCertificatePEM); //Se recomienda que el certificado no este un link que el publico pueda verlo
        }
    }