<?php

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'entities/Product.php';
require_once 'entities/User.php';
require_once 'services/UserService.php';
require_once 'services/ProductService.php';
require_once 'services/BestellingService.php';
require_once 'services/BestelLijnService.php';

session_start();
Twig_Autoloader::register();

//initialize twig environment
$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

$productSvc = new ProductService();
$productLijst = $productSvc->getProductenLijst();

if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];
} 
else
{
    $login = false;
}

if (isset($_GET["Betalen"])) 
{

    $winkelmandje = $_SESSION["winkelmandje"];
    $aantal = $_SESSION["aantal"];
    $datum = $_GET["afhaaldatum"];
 
    if (isset($_SESSION["login"])) {
        $klantId = $login->klantId;
    }
    //print $klantId;

    $bestellingSVC = new bestellingService();
    $bestelNr = $bestellingSVC->addBestelling($klantId, $datum); //voeg bestelling toe en geef bestelnr terug voor de lijn

    $bestellijnSVC = new bestellijnService();
    $bestellijnSVC->addBestellijn($bestelNr,$winkelmandje,$aantal);
    unset($_SESSION["winkelkarretje"]);
    unset($_SESSION["aantal"]);
    
    $bevestiging = $twig->render('bevestigingBestelling.twig',array('login' => $login));
    print $bevestiging;
}