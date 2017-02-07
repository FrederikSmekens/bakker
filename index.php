<?php


//index.php
//Verwijst naar de twig library

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';
require_once 'controlProduct.php';

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];   
}
else
{
    $login = false;
}

if (isset($_COOKIE["email"])) 
{
    $cookieUser = $_COOKIE["email"];
    if(isset($_COOKIE["password"]))
    {
        $cookiePassword = $_COOKIE["password"];
        $viewIndex = $twig->render('index.twig', array('login' => $login, 'cookie' => $cookieUser, 'cookiepassword' => $cookiePassword)); 
    }
    else 
    {
        $viewIndex = $twig->render('index.twig', array('login' => $login, 'cookie' => $cookieUser));
    }
    
    
    
}
else 
{
    $viewIndex = $twig->render('index.twig', array('login' => $login));
}

print($viewIndex);
if (isset($viewBestelFormulier))
    {print($viewBestelFormulier);}

        
