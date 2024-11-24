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
    private $ci;
    private $foto;   
    private $codcarrera; 
    private $nb_grupo;
    public function userExists($user, $pass){
       //$md5pass = md5($pass);
        $query = $this->connect()->prepare('SELECT * FROM sisrrhh.usuarios WHERE usuario = :user AND contrasena = :pass');
        $query->execute(['user' => $user, 'pass' => $pass]);
        if($query->rowCount()){
            return true;
        }else{
            return false;
        }
    }
    public function setUser($user){
        $query = $this->connect()->prepare('SELECT nombres, apellidos, telefono, correo, nombcompleto, ci, foto FROM sisrrhh.persona WHERE usuario = :user');
        $query->execute(['user' => $user]);        
        foreach ($query as $currentUser) {
            $this->nombcompleto = $currentUser['nombcompleto']; 
            $this->nombres = $currentUser['nombres'];
            $this->apellidos = $currentUser['apellidos'];
            $this->telefono = $currentUser['telefono'];
            $this->ci= $currentUser['ci'];
            $this->foto= $currentUser['foto'];
            $this->correo= $currentUser['correo'];
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
    public function getci(){
        return $this->ci;
    }
    public function getfoto(){
        return $this->foto;
    }   
}
?>