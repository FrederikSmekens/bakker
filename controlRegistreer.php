<?php

session_start();
require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/userService.php';
require_once 'entities/ErrorHandler.php';
require_once 'entities/Validatie.php';
 
Twig_Autoloader::register();
//initialize twig environment

$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

if (isset($_POST['registreer'])) 
{
    
    //zend alle posts door met requirements en kijk of ze valideren
        $errorHandler = new ErrorHandler();
        $validatie = new Validatie($errorHandler);       
        $validatie = $validatie->check($_POST, [
        'email' => [
            'required' => true,
            'email' => true,
            'emailExists' => true 
        ],
            
        'voornaam'=> [
            'required'=>true,
            'tekst' => true
        ],
            
        'familienaam'=> [
            'required'=>true,
            'tekst'=>true
        ],
            
        'adres'=> [
            'required'=>true,            
        ],
            
        'postcode'=>[
            'required'=>true,
            'getal' => true
        ],
        
        'gemeente'=>[
            'required'=>true,
            'tekst'=>true
        ]         
            
    ]);
    
    //als de validatie mislukt zend een error terug
    if ($validatie->fails()) 
    {        
          
            $errorEmail         =   $validatie->errors()->eersteError('email');
            $errorVoornaam      =   $validatie->errors()->eersteError('voornaam');
            $errorFamilienaam   =   $validatie->errors()->eersteError('familienaam');    
            $errorAdres         =   $validatie->errors()->eersteError('adres');
            $errorPostcode      =   $validatie->errors()->eersteError('postcode');
            $errorGemeente      =   $validatie->errors()->eersteError('gemeente');
                
            $aTwig["errorEmail"]        = $errorEmail;
            $aTwig["errorVoornaam"]     = $errorVoornaam;
            $aTwig["errorFamilienaam"]  = $errorFamilienaam;
            $aTwig["errorAdres"]        = $errorAdres;
            $aTwig["errorPostcode"]     = $errorPostcode;
            $aTwig["errorGemeente"]     = $errorGemeente;
            
            $view=$twig->render('index.twig',$aTwig);
            
            
            print $view;
    }
    
    //validatie OK -> registreer deze gebruiker
    else 
    {            
   
        
        //maak een paswoord aan
        $userSvc = new UserService();
        $password = makePassword();
        
        //haal gegevens uit de post
        $email = $_POST['email'];
        $voornaam = $_POST['voornaam'];
        $familienaam = $_POST['familienaam'];
        $adres = $_POST['adres'];
        $postcode = $_POST['postcode'];
        $gemeente = $_POST['gemeente'];
        
        //registreer de gebruiker
        $userSvc = new UserService();
        $user = $userSvc->registreerUser($email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente);

        //maak een sessie aan
        $_SESSION["login"] = $user;
        
        //maak een cookie aan van email (om te tonen in login form wanneer uitgelogd)
        setcookie("email", $email, time() + 666666);    
        
        //steek het willekeurig aangemaakte paswoord in een cookie zodat het later opgevraagd kan worden
        setcookie("password",$password, time()+666666);
        
        //ga verder naar het bestel formulier
        header('Location: index.php');  
    }
}

//maak een willekeurig paswoord aan met een lengte van 6 cijfers bestaande uit cijfers en hoofdletters
function makePassword($length = 6) 
{
    return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
