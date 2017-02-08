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

if (isset($_SESSION["login"]) AND isset($_SESSION["aantal"]))
{
    $login = $_SESSION["login"];


    //gebruiker wil de producten bestellen:
    if (isset($_GET["Betalen"])) 
    {
        //haal de nodige gegevens op
        $winkelmandje = $_SESSION["winkelmandje"];
        $aantal = $_SESSION["aantal"];
        $datum = $_GET["afhaaldatum"];         
        $date = new DateTime();
        if (isset($_SESSION["login"])) 
            {
            $klantId = $login->klantId;
        }
        //print $klantId;
        
        //de klant mag maar 1 bestelling per dag plaatsen -> controlleer alle datums in db met opgegeven datum
        $bestellingSVC = new bestellingService();
        $bestelDatums = $bestellingSVC->getBestelDatums($klantId);     
        
        //indien datum al in db->error
        if(in_array($datum, $bestelDatums))
        {
          $errorDatum = 'Er is al een bestelling geplaatst op deze dag';
          $viewWinkelmandje = $twig->render('winkelmandje.twig', array('winkelmandje' => $winkelmandje,'aantal'=>$aantal,'login' => $login, 'errorDatum'=>$errorDatum));   
          print $viewWinkelmandje;
        }
        elseif (new DateTime($datum) < new DateTime() )
        {
         $errorDatum = 'Je kan geen bestellingen in het verleden plaatsen';
         $viewWinkelmandje = $twig->render('winkelmandje.twig', array('winkelmandje' => $winkelmandje,'aantal'=>$aantal,'login' => $login, 'errorDatum'=>$errorDatum));   
         print $viewWinkelmandje; 
        }
       
        elseif (new DateTime($datum) >$date->modify('+3 day') )
        {
         $errorDatum = 'Je kan maar tot 3 dagen in de toekomst bestellen, speciale bestellingen kan u aanvragen in de bakkerij';
         $viewWinkelmandje = $twig->render('winkelmandje.twig', array('winkelmandje' => $winkelmandje,'aantal'=>$aantal,'login' => $login, 'errorDatum'=>$errorDatum));   
         print $viewWinkelmandje;
        }
        
     
        
        else
        {        
            //bestel de items en verwijder de sessies
        $bestellingSVC = new bestellingService();
        $bestelNr = $bestellingSVC->addBestelling($klantId, $datum); //voeg bestelling toe en geef bestelnr terug voor de lijn

        $bestellijnSVC = new bestellijnService();
        $bestellijnSVC->addBestellijn($bestelNr,$winkelmandje,$aantal);
        unset($_SESSION["winkelkarretje"]);
        unset($_SESSION["aantal"]);
        $bevestiging = $twig->render('bevestigingBestelling.twig',array('login' => $login));
        print $bevestiging;
        }
      
    }
} 
else
{
    $login = false;
   header('Location: index.php');
}