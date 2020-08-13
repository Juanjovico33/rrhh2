<?php

class DB{
    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct(){
        $this->host     = 'unix_socket=/cloudsql/pelagic-pod-279916:southamerica-east1:bdsainco';       
        $this->user     = 'soporte';
        $this->password = "usoporte.2020u";
        // $this->host     = 'mysql:host=localhost;dbname=sainc;charset=UTF8mb4';
        $this->db       = 'sainc';
        // $this->user     = 'root';
        // $this->password = "";
        // $this->charset  = 'utf8mb4';
       
    }

    function connect(){    
        try{            
            $connection = "mysql:" . $this->host . ";dbname=" . $this->db . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($connection, $this->user, $this->password, $options);
            return $pdo;
        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}






?>