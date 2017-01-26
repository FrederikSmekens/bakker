<?php

class User{

    
    public $klantId,$email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente;
    
    public function __construct($klantId,$email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente){ 
        //Bij aanmaken van object User worden gegevens doorgegeven en aan het object toegevoegd
           
        $this->klantId = $klantId;   
        $this->email = $email;
        $this->password = $password;
       
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->adres = $adres;
        $this->postcode = $postcode;
        $this->gemeente = $gemeente;     
    }
}
