<?php
include_once 'includes/user.php';
include_once 'includes/user_session.php';
$userSession = new UserSession();
$user = new User();
if(isset($_SESSION['user'])){
    //echo "hay sesion";
    $user->setUser($userSession->getCurrentUser()); 
    $user->setSemestre($userSession->getCurrentUser());
    include_once 'vistas/bandeja_principal.php';
}else if(isset($_POST['username']) && isset($_POST['password'])){    
    $userForm = $_POST['username'];
    $passForm = $_POST['password'];
    $user = new User();
    if($user->userExists($userForm, $passForm)){
        //echo "Existe el usuario";
        $userSession->setCurrentUser($userForm);
        $user->setUser($userForm);   
        $user->setSemestre($userForm);    
        include_once 'vistas/bandeja_principal.php';
    }else{
        //echo "No existe el usuario";
        $errorLogin = "Código de usuario y/o contraseña incorrecto";
        include_once 'vistas/login.php';
    }
}else{
    //echo "login";
    include_once 'vistas/login.php';
}
?>