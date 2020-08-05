<?php    
    // $_user = 'soporte';
    //$_pass = 'usoporte.2020u';
 	//$_dsn = 'mysql:dbname=sainc;unix_socket=/cloudsql/pelagic-pod-279916:southamerica-east1:bdsainco;charset=utf8mb4';
	$_user = 'root';
    $_pass = '';
   	$_dsn = 'mysql:host=localhost;dbname=sainc_stu;charset=UTF8mb4';
    
    try {
        $bdcon = new PDO($_dsn, $_user, $_pass);        
        $bdcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>