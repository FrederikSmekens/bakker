<?php


//index.php
//Verwijst naar de twig library

require_once 'library/vendor/Twig/Autoloader.php';

require_once 'services/BestellingService.php';
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
    $rang = $login->rang;
    
    if($rang == 1)
    {
        $bestellingSVC = new bestellingService();
        $bestellingen = $bestellingSVC->getAlleBestellingen(); 
        //print_r($bestellingen);exit();
    } 
    else
    {
       $klantId = $login->klantId;
       $bestellingSVC = new bestellingService();
       $bestellingen = $bestellingSVC->getBestellingen($klantId);
      
    }
    
    $viewBestellingen = $twig->render('bestellingen.twig', array('login' => $login,'bestellingen'=>$bestellingen));   
   
    print($viewBestellingen); 
       
}
else
{
    $login = false;
    header('Location: index.php');
}