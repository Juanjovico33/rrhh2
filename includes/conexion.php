<?php    
    //$_user = 'soporte';
    //$_pass = 'usoporte.2021u';
 	//$_dsn = 'mysql:dbname=sainc;unix_socket=/cloudsql/augmented-form-307209:southamerica-east1:dbmysql;charset=utf8mb4';
	$_user = 'root';
    $_pass = '';
   	$_dsn = 'mysql:host=localhost;dbname=sisrrhh;charset=UTF8mb4';
    
    try {
        $bdcon = new PDO($_dsn, $_user, $_pass);        
        $bdcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>