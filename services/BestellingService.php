<?php

//service/UserService.php
require_once 'data/bestellingDAO.php';

class bestellingService {

    public function addBestelling($klantId, $datum){
        
     
        $bestellingDAO = new BestellingDAO();
        $bestelNr = $bestellingDAO->addBestelling($klantId, $datum);
        return $bestelNr;
    }
    
    public function getAlleBestellingen()
    {
        $bestellinDAO = new BestellingDAO();
        $alleBestellingen = $bestellinDAO->getAlleBestellingen();
        return $alleBestellingen;
    }
    
     public function getBestellingen($klantId)
    {
        $bestellinDAO = new BestellingDAO();
        $alleBestellingen = $bestellinDAO->getBestellingen($klantId);
        return $alleBestellingen;
    }
}
