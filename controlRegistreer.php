<?php

session_start();
require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/userService.php';
 
Twig_Autoloader::register();
//initialize twig environment

$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

if (isset($_POST['registreer'])) 
{
    $email = $_POST['email'];
    $password = sha1($_POST['password']);  
    $voornaam = $_POST['voornaam'];
    $familienaam = $_POST['familienaam'];
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $gemeente = $_POST['gemeente'];
    
   
    $userSvc = new UserService();
    $user = $userSvc->registreerUser($email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente);
    
    $_SESSION["login"] = $user;
    $login = $_SESSION["login"];
    setcookie("email", $login->email, time() + 666666);
    
    if (isset($_COOKIE["email"])) {
        $cookieUser = $_COOKIE["email"];
        $view = $twig->render('index.twig', array('user' => $user, 'login' => $login, 'cookie' => $cookieUser));
    }
    else {
        $view = $twig->render('index.twig', array('user' => $user, 'login' => $login));
    }

    print($view);
}



