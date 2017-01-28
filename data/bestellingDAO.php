<?php

require_once 'DBConfig.php'; 
require_once 'entities/bestelling.php';

class BestellingDAO{
    
    //voeg bestelling toe en geef bestelNr terug
    public function addBestelling($klantId, $datum){
    $sql = "INSERT INTO 
            bestellingen (KlantId,Datum) 
            values (:KlantId,:Datum)";
          
    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(
        ':KlantId' => $klantId,
        ':Datum'=>$datum       
         ));    
    
    $bestelNr = $dbh->lastInsertId();
    $dbh = null; 
    return $bestelNr; 
    } 
}