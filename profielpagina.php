<?php


//index.php
//Verwijst naar de twig library

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';
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
    $email = $login->email;
    $klantId = $login->klantId;
    $password = $login->password;
    if (isset($_POST["wijzigGegevens"])) 
    {
        
        $voornaam = $_POST['voornaam'];
        $familienaam = $_POST['familienaam'];
        $adres = $_POST['adres'];
        $postcode = $_POST['postcode'];
        $gemeente = $_POST['gemeente'];

        $userSvc = new UserService();
        $user = $userSvc->updateUser($email, $klantId, $password, $voornaam, $familienaam, $adres, $postcode, $gemeente);

        $_SESSION["login"] = $user;

        header("Refresh:0");
    }
    
    if($rang == 1)
    {
        $userSvc = new UserService();
        $allUsers = $userSvc->getAllUsers();
        
        if(isset($_POST["wijzigRang"]))
        {       
            $count = count($_POST);    
            foreach ($_POST as $key => $value) 
            {
            //print $key . '=>' . $value."<br>";
            $userSvc = new UserService();
            $user = $userSvc->updateRang($key,$value);
            
            if (--$count == 1)break;        //stop de lus 1 plaats voor het einde van de array
                                            //op de laatste plaats staat 'Toevoegen'
            }  
            header("Refresh:0");
        } 
    } 
    
    $viewProfiel = $twig->render('profiel.twig', array('login' => $login,'allusers'=>$allUsers));   
    print($viewProfiel);    
}
else
{
    $login = false;
    header('Location: index.php');
}