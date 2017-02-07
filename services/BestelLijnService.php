<?php

require_once 'data/bestelLijnDAO.php';

class BestellijnService {

    public function addBestellijn($bestelNr, $winkelmandje, $aantal) 
    {
        $bestellijnDAO = new BestellijnDAO();
        $bestellijnDAO->addBestellijn($bestelNr, $winkelmandje, $aantal);  
    }
    
    public function deleteBestellijn($bestelNr)
    {
        $bestellijnDAO = new BestellijnDAO();
        $bestellijnDAO->deleteBestellijn($bestelNr);
            
    }
}