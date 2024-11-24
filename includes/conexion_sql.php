<?php
// Parámetros de conexión
$host = '172.16.70.5';
$dbname = 'ZKAccess';
$user = 'sa';
$pass = 'opune24$';

try {
    // Conexión a SQL Server usando PDO
    $bdcon = new PDO("sqlsrv:Server=$host;Database=$dbname", $user, $pass);

    // Configuración de opciones PDO
    $bdcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "Conexión exitosa a SQL Server.";
} catch (PDOException $e) {
    // Captura errores y muestra el mensaje
    echo "Error de conexión: " . $e->getMessage();
}
?>