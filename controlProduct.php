<?php

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';
require_once 'services/ProductService.php';

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
    $rang = $login->rang;
   

if($rang !=-1)
    {
//print bestelformulier en geef productenlijst mee
if(isset($_SESSION["winkelmandje"]) AND isset($_SESSION["aantal"]))
{
    $winkelmandje = $_SESSION["winkelmandje"];
    $aantal = $_SESSION["aantal"];
    $viewBestelFormulier = $twig->render('bestelFormulier.twig', array('productLijst' => $productLijst,'login'=>$login,'winkelmandje'=>$winkelmandje,'aantal'=>$aantal));
}
else
{
   $viewBestelFormulier = $twig->render('bestelFormulier.twig', array('productLijst' => $productLijst,'login'=>$login)); 
}

//Bij toevoegen van nieuwe bestelling:
if(isset($_POST["Toevoegen"]))
{
    $count = count($_POST);
    $_SESSION["winkelmandje"] = array();    //start een sessie winkelmandje en aantal
    $_SESSION["aantal"] = array();          //waar productid, product,prijs en aantal besteld worden bijgehouden
    
    foreach ($_POST as $key => $value) 
    {           
        //print $key."=>".$value."<br>";
        
        //haal waardes uit de tabel Producten
        $productId = $key;
        $aantal = $value;
        $product = $productSvc->getProductById($key);
        
        
      
        array_push($_SESSION["winkelmandje"], $product);
        array_push($_SESSION["aantal"], $aantal);        
        
        if (--$count == 1)break;    //stop de lus 1 plaats voor het einde van de array
                                    //op de laatste plaats staat 'Toevoegen'
    }
    header('Location: index.php#bestel');
}
    }
}
else
{
    $login = false;
}