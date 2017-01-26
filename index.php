<?php

session_start();
//index.php
//Verwijst naar de twig library

require_once 'services/UserService.php';
require_once 'library/vendor/Twig/Autoloader.php';


Twig_Autoloader::register();
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

if (isset($_COOKIE["email"])) 
{
    $cookieUser = $_COOKIE["email"];
    $view = $twig->render('index.twig', array('login' => $login, 'cookie' => $cookieUser));
}
else 
{
      $view = $twig->render('index.twig', array('login' => $login));
}
//toon de pagina
print($view);
