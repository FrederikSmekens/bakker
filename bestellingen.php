<?php


//index.php
//Verwijst naar de twig library

require_once 'library/vendor/Twig/Autoloader.php';

require_once 'services/BestellingService.php';
require_once 'services/BestelLijnService.php';
require_once 'controlProduct.php';

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);
$twig->addFilter('var_dump', new Twig_Filter_Function('var_dump')); //voor debuggen in twig bv: {{ my_variable | var_dump }}

if (!isset($_SESSION)) {
    session_start();
}


if (isset($_SESSION["login"])) 
{
    
    
    if (isset($_POST['annuleerBestelling'])) 
    {
      
        //echo("<script>console.log('PHP: ".'test'."');</script>"); exit();
        $bestelNr = $_POST['bestelNr'];
        
        $bestellingSVC = new bestellingService();
        $bestellingSVC->deleteBestelling($bestelNr);
        
        $bestellijnSVC = new BestellijnService();
        $bestellijnSVC->deleteBestellijn($bestelNr);
    }
    
    
    $login = $_SESSION["login"];   
    $rang = $login->rang;
    
    if($rang == 1)
    {
        $bestellingSVC = new bestellingService();
        $bestellingen = $bestellingSVC->getAlleBestellingen(); 
        
        $bestellingSVC = new bestellingService();
        $bestellingTotaalprijzen = $bestellingSVC->getTotaalPrijzen();
      
    } 
    else
    {
       $klantId = $login->klantId;
       $bestellingSVC = new bestellingService();
       $bestellingen = $bestellingSVC->getBestellingen($klantId);   
       $bestellingTotaalprijzen = null;
       
       $bestellingSVC = new bestellingService();
       $bestellingTotaalprijzen = $bestellingSVC->getTotaalPrijzenUser($klantId);
    }    

    //print_r($bestellingTotaalprijzen);exit();
    $viewBestellingen = $twig->render('bestellingen.twig', array('login' => $login,'bestellingen'=>$bestellingen,'totaalPrijzen'=>$bestellingTotaalprijzen));   
   
    print($viewBestellingen); 
    
    
  
       
}
else
{
    $login = false;
    header('Location: index.php');
}