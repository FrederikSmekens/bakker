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

//log out
if (isset($_GET["logout"])) {
    $login = null;
    unset($_SESSION["login"]); //sessie login wordt verwijert    
}

//log in
if (isset($_POST['login'])) {
    
    //haal het emailadres op en vernieuw de cookie
    $email = $_POST['email'];
    unset($_COOKIE["email"]);
    setcookie("email", $email, time() + 6666666);
    $_COOKIE["email"] = $email;
    
    //haal ingetypte paswoord op
    $password = $_POST['password'];

    //controlleer of het emailadres+paswoord overeen komen met de database
    $userSvc = new UserService();
    $loginCheck = $userSvc->checkLogin($email, $password);
   
    //als de check klopt maak een nieuwe sessie aan met de teruggegeven waarden
    if ($loginCheck == true) 
    {
        $_SESSION["login"] = $loginCheck; 
    }    
}
    //ga terug naar index.php, als de check klopt ben je nu ingelogd
    header('Location: ' . 'index.php');



