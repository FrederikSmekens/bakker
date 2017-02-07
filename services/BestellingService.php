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
        $bestellingDAO = new BestellingDAO();
        $alleBestellingen = $bestellingDAO->getAlleBestellingen();
        return $alleBestellingen;
    }
    
    public function getBestellingen($klantId)
    {
        $bestellingDAO = new BestellingDAO();
        $alleBestellingen = $bestellingDAO->getBestellingen($klantId);
        return $alleBestellingen;
    }
    
    public function getTotaalPrijzen()
    {
       
        $bestellingDAO = new BestellingDAO();
        $totaalPrijzen = $bestellingDAO->getTotaalPrijzen();
        return $totaalPrijzen;
    }
    public function getTotaalPrijzenUSer($klantId)
    {
       
        $bestellingDAO = new BestellingDAO();
        $totaalPrijzenUser = $bestellingDAO->getTotaalPrijzenUser($klantId);
        return $totaalPrijzenUser;
    }
    
    public function deleteBestelling($bestelNr)
    {
        $bestellingDAO = new BestellingDAO();
        $bestellingDAO->deleteBestelling($bestelNr);
            
    }
}
