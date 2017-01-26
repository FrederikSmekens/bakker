<?php

session_start();
//index.php
//Verwijst naar de twig library
require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';

Twig_Autoloader::register();
//initialize twig environment
//vertel Twig in welke map onze templates (html paginas) zitten
$loader = new Twig_Loader_Filesystem('presentation');

//laad nieuwe Twig Environment vanuit die map
$twig = new Twig_Environment($loader);


if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];
    print_r($login);
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
