<?php
session_start();

require_once 'library/vendor/Twig/Autoloader.php';
require_once 'services/UserService.php';

Twig_Autoloader::register();

//initialize twig environment
$loader = new Twig_Loader_Filesystem('presentation');
$twig = new Twig_Environment($loader);

if (isset($_SESSION["login"])) 
{
    $login = $_SESSION["login"];
}
else
{
    $login = false;
}


if (isset($_GET["logout"])) {
    $login = null;
    unset($_SESSION["login"]); //SESSION LOGIN WORDT VERWIJDERD
    
    if (isset($_COOKIE["username"])) { //ALS EEN COOKIE BESTAAT WORDT DIE DOORGEGEVEN NAAR DE VOLGENDE PAGINA VIA TWIG
        $cookieUser = $_COOKIE["username"];
        $view = $twig->render('index.twig', array('cookie' => $cookieUser));
    }
    else {
        $view = $twig->render('index.twig');
    }
    print($view);
   
}


if (isset($_POST['login'])) {//ALS POST LOGIN IS DOORGEGEVEN WORDEN DE LOGINGEGEVENS GECHECKT EN COOKIE AANGEPAST
    
    $email = $_POST['email'];
    unset($_COOKIE["email"]);
    setcookie("email", $email, time() + 31556926, '/');
    $_COOKIE["email"] = $email;
    
    $password = sha1($_POST['password']);

    $userSvc = new UserService();
    $loginCheck = $userSvc->checkLogin($email, $password);

    if ($loginCheck == false) {
        $view = $twig->render('index.twig');
        print($view);
        exit(0);
    }
    else {
        $_SESSION["login"] = $loginCheck;
        $login = $loginCheck;

        $view = $twig->render('index.twig', array('login' => $login));
        print($view);
        exit(0);
    }
}




