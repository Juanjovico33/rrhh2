<?php

class UserSession{

    public function __construct(){
        session_start();
    }

    public function setCurrentUser($user){
        // include "_event_log.php";
        $e = new evento();
        $e->e_log_ingreso($user, 1);
        $_SESSION['user'] = $user;
    }

    public function getCurrentUser(){
        return $_SESSION['user'];
    }

    
    public function closeSession(){
        include "../includes/_event_log.php";
        $e = new evento();
        $e->e_log_salida($this->getCurrentUser(), 2);

        session_unset();
        session_destroy();
    }
}

?>