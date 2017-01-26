<?php

//service/UserService.php
require_once 'data/UserDAO.php';

class UserService {

    public function registreerUser($email, $password, $voornaam, $familienaam, $adres, $postcode, $gemeente) 
    {
        // Verwijst naar de functie registreerUser in userDAO.php voor het registreren van de user
        // $username, $password, $email, $voornaam, $achternaam, $adres, $postcode, $telefoon, $promo

        $userDAO = new UserDAO();
        $user = $userDAO->registreerUser($email, $password, $voornaam, $familienaam, $adres, $postcode, $gemeente);
        return $user;
    }

    public function updateUser($klantID, $username, $password, $email, $voornaam, $achternaam, $adres, $postcode, $telefoon, $promo) 
    {
        //Verwijst naar de functie updateUser in userDAO.php voor het aanpassen van gegevens van de user
        //$klantID, $username, $password, $email, $voornaam, $achternaam, $adres, $postcode, $telefoon, $promo

        $userDAO = new UserDAO();
        $user = $userDAO->updateUser($klantID, $username, $password, $email, $voornaam, $achternaam, $adres, $postcode, $telefoon, $promo);
        return $user;
    }

    public function checkLogin($username, $password) 
    {
        // Verwijst naar de functie checklogin in userDAO.php voor het checken van de logingegevens
        //$username, $password

        $userDAO = new UserDAO();
        $user = $userDAO->checkLogin($username, $password);
        return $user;
    }

}
