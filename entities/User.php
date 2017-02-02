<?php

class User{

    private static $idMap=array();
    public $klantId,$email,$voornaam,$familienaam,$adres,$postcode,$gemeente,$rang;
    
    public function __construct($klantId,$email,$voornaam,$familienaam,$adres,$postcode,$gemeente,$rang){ 
        //Bij aanmaken van object User worden gegevens doorgegeven en aan het object toegevoegd
           
        $this->klantId = $klantId;   
        $this->email = $email;
        
       
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->adres = $adres;
        $this->postcode = $postcode;
        $this->gemeente = $gemeente;     
        $this->rang = $rang;   
    }
    
    public static function create($klantId,$email,$voornaam,$familienaam,$adres,$postcode,$gemeente,$rang)
    {
        if(!isset(self::$idMap[$klantId])) 
        {
            self::$idMap[$klantId] = new User($klantId,$email,$voornaam,$familienaam,$adres,$postcode,$gemeente,$rang);
        }
        return self::$idMap[$klantId];
    }
}
