<?php
session_start();

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';

Twig_Autoloader::register();

//initialize twig environment
$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];
}
else
{
    $login = false;
}


if (isset($_GET["logout"])) {
    $login = null;
    unset($_SESSION["login"]); //SESSION LOGIN WORDT VERWIJDERD
}


if (isset($_POST['login'])) {
    
    $email = $_POST['email'];
    unset($_COOKIE["email"]);
    setcookie("email", $email, time() + 6666666);
    $_COOKIE["email"] = $email;
    
    $password = $_POST['password'];

    $userSvc = new UserService();
    $loginCheck = $userSvc->checkLogin($email, $password);
   
    
    if ($loginCheck == true) 
    {
        $_SESSION["login"] = $loginCheck; 
    }    
}
   
    header('Location: ' . 'index.php');



