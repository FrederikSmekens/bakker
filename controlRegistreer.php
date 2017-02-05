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
        ],
            
        'familienaam'=> [
            'required'=>true,
        ],
            
        'adres'=> [
            'required'=>true,
        ],
            
        'postcode'=>[
            'required'=>true,
        ],
        
        'gemeente'=>[
            'required'=>true,
        ]         
            
    ]);
    
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
    else 
    {            
        $email = $_POST['email'];
        $password = $_POST['password'];  
        $voornaam = $_POST['voornaam'];
        $familienaam = $_POST['familienaam'];
        $adres = $_POST['adres'];
        $postcode = $_POST['postcode'];
        $gemeente = $_POST['gemeente'];
        $userSvc = new UserService();
        $user = $userSvc->registreerUser($email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente);

        $_SESSION["login"] = $user;
        $login = $_SESSION["login"];
        setcookie("email", $email, time() + 666666);    

        header('Location: index.php');  
    }
    


}



