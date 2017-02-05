<?php

//service/UserService.php
require_once 'data/UserDAO.php';

class UserService {

    public function getAllUsers()
    {
        $userDAO = new UserDAO();
        $allUsers = $userDAO->getAllUsers();
        return $allUsers;
    }
    public function registreerUser($email, $password, $voornaam, $familienaam, $adres, $postcode, $gemeente) 
    {
        $userDAO = new UserDAO();
        $user = $userDAO->registreerUser($email, $password, $voornaam, $familienaam, $adres, $postcode, $gemeente);
        return $user;
    }

    public function updateUser($email,$klantId, $voornaam, $familienaam, $adres, $postcode, $gemeente) 
    {

        $userDAO = new UserDAO();
        $user=$userDAO->updateUser($email,$klantId, $voornaam, $familienaam, $adres, $postcode, $gemeente);
        return $user;
    }
    
    public function updateRang($klantId,$rang)
    {
        $userDAO = new UserDAO();
        $user = $userDAO->updateRang($klantId,$rang);
        return $user;
    }
    
    public function checkLogin($email, $password) 
    {
        $userDAO = new UserDAO();
        $user = $userDAO->checkLogin($email, $password);
        return $user;
    }
    
    public function checkEmail($email)
    {
        $userDAO = new UserDAO();
        $check = $userDAO->checkEmail($email);
        return $check;
    }
    

}
