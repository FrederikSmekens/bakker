<?php

//service/UserService.php
require_once 'data/bestellingDAO.php';

class bestellingService {

    public function addBestelling($klantId, $datum){
        
     
        $bestellingDAO = new BestellingDAO();
        $bestelNr = $bestellingDAO->addBestelling($klantId, $datum);
        return $bestelNr;
    }
}
