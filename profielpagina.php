<?php


//index.php
//Verwijst naar de twig library

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';
require_once 'controlProduct.php';
require_once 'entities/User.php';

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);
$twig->addFilter('var_dump', new Twig_Filter_Function('var_dump')); //voor debuggen in twig bv: {{ my_variable | var_dump }}
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];   
    $rang = $login->rang;
    $email = $login->email;
    $klantId = $login->klantId;  

    if (isset($_POST["wijzigGegevens"])) 
    {
        
        $voornaam = $_POST['voornaam'];
        $familienaam = $_POST['familienaam'];
        $adres = $_POST['adres'];
        $postcode = $_POST['postcode'];
        $gemeente = $_POST['gemeente'];

        $userSvc = new UserService();
        $user = $userSvc->updateUser($email, $klantId, $voornaam, $familienaam, $adres, $postcode, $gemeente);

        $_SESSION["login"] = $user;

        header("Refresh:0");
    }   
    
    
    $allUsers = false; 
    if($rang == 1)
    {
        $userSvc = new UserService();
        $allUsers = $userSvc->getAllUsers();
        
        if(isset($_POST["wijzigRang"]))
        {       
            $count = count($_POST);    
            foreach ($_POST as $key => $value) 
            {
            print $key . '=>' . $value."<br>";                      
               
            $userSvc = new UserService();
            $user = $userSvc->updateRang($key,$value);
            
            if (--$count == 1)break;        //stop de lus 1 plaats voor het einde van de array
                                            //op de laatste plaats staat 'Toevoegen'
            }  
            header("Refresh:0");
        } 
    }
    
    $errorPassword='';
    $passwordSucces = '';   
    if(isset($_POST["wijzigPassword"]))
    {
        $oudPassword    = $_POST["oudpassword"];
        $nieuwPassword  = $_POST["nieuwpassword"];
        $nieuwPassword2 = $_POST["nieuwpassword2"];
        
        $userSvc = new UserService();
        $loginCheck = $userSvc->checkLogin($email, $oudPassword);
        
        //var_dump($loginCheck); exit();
        if($loginCheck==true)
        {          
            if ($nieuwPassword == $nieuwPassword2) 
            {          
                unset($_COOKIE['password']);
                setcookie("password", "", time()-3600);
                $userSvc = new UserService();
                $userSvc->updatePassword($klantId, $nieuwPassword);
                $errorPassword='';
                $passwordSucces = 'wachtwoord successvol gewijzigd';
            }
            else
            {
               $errorPassword = 'wachtwoorden komen niet overeen'; 
            }
        }
        else 
        {
            $errorPassword = 'verkeerde wachtwoord';
        }
        
    }
    
    if(isset($_COOKIE["password"]))
    {
        $cookiePassword = $_COOKIE["password"];
        $viewProfiel = $twig->render('profiel.twig', array('login' => $login,'allusers'=>$allUsers,'cookiepassword'=>$cookiePassword,'errorPassword'=>$errorPassword,'passwordSucces'=>$passwordSucces));   
    }
    else 
    {
        $viewProfiel = $twig->render('profiel.twig', array('login' => $login,'allusers'=>$allUsers,'errorPassword'=>$errorPassword,'passwordSucces'=>$passwordSucces));   
    }
    
  

    print($viewProfiel);    
}
else
{
    $login = false;
    header('Location: index.php');
}