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
    $viewIndex = $twig->render('index.twig', array('login' => $login, 'cookie' => $cookieUser));
    
}
else 
{
      $viewIndex = $twig->render('index.twig', array('login' => $login));
}

//controlleer of er iets in winkelmandje zit
if (isset($_SESSION["winkelmandje"])) 
{
    $winkelmandje = $_SESSION["winkelmandje"];
    $aantal = $_SESSION["aantal"];     
    $viewIndex = $twig->render('winkelmandje.twig', array('winkelmandje' => $winkelmandje,'aantal'=>$aantal,'login' => $login));
}

print($viewIndex);

