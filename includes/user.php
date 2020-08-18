<?php
include 'db.php';
class User extends DB{
    private $nombcompleto;
    private $nombres;
    private $apellidos;
    private $codigo;
    private $carrera;
    private $telefono;
    private $semestre;
    private $correo;
    private $password;
    public function userExists($user, $pass){
       //$md5pass = md5($pass);
        $query = $this->connect()->prepare('SELECT * FROM sainc_stu.userest WHERE usuario = :user AND contrasena = :pass');
        $query->execute(['user' => $user, 'pass' => $pass]);
        if($query->rowCount()){
            return true;
        }else{
            return false;
        }
    }
    public function setUser($user){
        $query = $this->connect()->prepare('SELECT nombres, apellidos, telefono, correo, nombcompleto, codest, carrera FROM sainc_stu.estudiante WHERE codest = :user');
        $query->execute(['user' => $user]);        
        foreach ($query as $currentUser) {
            $this->nombcompleto = $currentUser['nombcompleto'];
            $this->codigo = $currentUser['codest'];
            $this->carrera = $currentUser['carrera'];
            $this->nombres = $currentUser['nombres'];
            $this->apellidos = $currentUser['apellidos'];
            $this->telefono = $currentUser['telefono'];
            $this->getCorreoPlat($currentUser['codest']);
        }
    }
    public function getCorreoPlat($codest){
        $query = $this->connect()->prepare('SELECT * FROM sainc_stu.plat_est_correos WHERE codest = :user');
        $query->execute(['user' => $codest]);  
        foreach ($query as $currentUser) {
            $this->correo = $currentUser['correo'];
            $this->password = $currentUser['password'];           
        }
    }

    public function setSemestre($user){
        $query = $this->connect()->prepare('SELECT semestre FROM sainc_stu.aca_estudiantesemestre WHERE codest = :user ORDER BY semestre');
        $query->execute(['user' => $user]);        
        foreach ($query as $currentUser) {
            $this->semestre = $currentUser['semestre'];           
        }
    }
    public function getNombre(){
        return $this->nombres;
    }
    public function getApellido(){
        return $this->apellidos;
    }
    public function getCodigo(){
        return $this->codigo;
    }   
    public function getCarrera(){
        return $this->carrera;
    }  
    public function getSemestre(){
        return $this->semestre;
    } 
    public function getNombcompleto(){
        return $this->nombcompleto;
    } 
    public function getTelefono(){
        return $this->telefono;
    }
    public function getCorreo(){
        return $this->correo;
    }
    public function getPassword(){
        return $this->password;
    }
}

?>