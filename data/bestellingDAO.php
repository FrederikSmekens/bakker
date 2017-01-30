<?php

require_once 'DBConfig.php'; 
require_once 'entities/Bestelling.php';
require_once 'entities/AlleBestellingen.php';
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
    
    public function getAlleBestellingen()
    {
        $sql = "SELECT bestellingen.BestelNr, bestellingen.KlantId,users.voornaam,users.familienaam, bestellingen.Datum, producten.Product,bestellijn.AantalBesteld, producten.Prijs
                FROM bestellingen INNER JOIN bestellijn INNER JOIN producten INNER JOIN users
                WHERE bestellingen.BestelNr = bestellijn.BestelNr AND bestellijn.ProductId = producten.ProductId AND users.klantId = bestellingen.KlantId  
                ORDER BY `bestellingen`.`BestelNr` ASC";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $alleBestellingen = array();
        
        foreach($resultSet as $rij)
        {
           //$bestelling = AlleBestellingen::create($rij["BestelNr"],$rij["KlantId"],$rij["voornaam"],$rij["familienaam"],$rij["Datum"],$rij["Product"],$rij["AantalBesteld"],$rij["Prijs"]);
           array_push($alleBestellingen, $rij);
        }
        $dbh = null;      
        return $alleBestellingen;
    }
    
    public function getBestellingen($klantId)
    { 
        $sql = "SELECT bestellingen.BestelNr, bestellingen.KlantId,users.voornaam,users.familienaam, bestellingen.Datum, producten.Product,bestellijn.AantalBesteld, producten.Prijs
                FROM bestellingen INNER JOIN bestellijn INNER JOIN producten INNER JOIN users
                WHERE bestellingen.BestelNr = bestellijn.BestelNr AND bestellijn.ProductId = producten.ProductId AND users.klantId = bestellingen.KlantId AND users.klantId=:klantId 
                ORDER BY bestellingen.BestelNr ASC";
        
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt=$dbh->prepare($sql);      
        $stmt->execute(array(':klantId'=>$klantId)); 
        $resultSet=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $bestellingen = array();        
       
        
        foreach($resultSet as $rij)
        {
           //$bestelling = AlleBestellingen::create($rij["BestelNr"],$rij["KlantId"],$rij["voornaam"],$rij["familienaam"],$rij["Datum"],$rij["Product"],$rij["AantalBesteld"],$rij["Prijs"]);
           array_push($bestellingen, $rij);
        }
        $dbh = null;      
       
        return $bestellingen;
        }   

}