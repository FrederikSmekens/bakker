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
    
    public function getTotaalPrijzen()
    {
        $sql = "SELECT bestellingen.BestelNr, bestelLijn.AantalBesteld, producten.Prijs, (bestelLijn.AantalBesteld * producten.Prijs) as product
                FROM bestellingen 
                INNER JOIN bestellijn ON bestellingen.BestelNr = bestellijn.BestelNr
                INNER JOIN producten ON bestellijn.ProductId = producten.ProductId ";
        
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);   
   
        $bestellingenTotaalPrijs = array();
        
        foreach ($resultSet as $key => $value)
        {                   
            $bestelNr = $value['BestelNr'];
            $bestellingenTotaalPrijs[$bestelNr][] = $value['Prijs']*$value['AantalBesteld'];            
        }
        
        $new = array(); 
        foreach($bestellingenTotaalPrijs as $key => $value) 
        {
            //print_r($value);            
            $prijs = array_sum($value);
            $new[$key] = $prijs;                   
        }    
        
        return $new;
    }
    
    public function getTotaalPrijzenUser($klantId)
    {
       
        $sql = "SELECT bestellingen.BestelNr, bestelLijn.AantalBesteld, producten.Prijs, (bestelLijn.AantalBesteld * producten.Prijs) as product
                FROM bestellingen 
                INNER JOIN bestellijn ON bestellingen.BestelNr = bestellijn.BestelNr
                INNER JOIN producten ON bestellijn.ProductId = producten.ProductId 
                WHERE klantId=:klantId";
        
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
      
        $resultSet=$dbh->prepare($sql);      
        $resultSet->execute(array(':klantId'=>$klantId)); 
        
        
        $bestellingenTotaalPrijs = array();
        
        foreach ($resultSet as $key => $value)
        {                
            $bestelNr = $value['BestelNr'];
            $bestellingenTotaalPrijs[$bestelNr][] = $value['Prijs']*$value['AantalBesteld'];            
        }
        
        $new = array(); 
        foreach($bestellingenTotaalPrijs as $key => $value) 
        {
            //print_r($value);            
            $prijs = array_sum($value);
            $new[$key] = $prijs;                   
        }        
        
        return $new;
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
    
    public function getBestelDatums($klantId)
    {
        $sql = "SELECT Datum FROM bestellingen WHERE KlantId=:KlantId ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt=$dbh->prepare($sql);      
        $stmt->execute(array(':KlantId'=>$klantId)); 
        $resultSet=$stmt->fetchAll(PDO::FETCH_COLUMN);     
  
        return $resultSet;
    }
        
    public function deleteBestelling($bestelNr)
    {
        $sql="delete from bestellingen where bestelNr=:bestelNr";
        $dbh=new PDO(DBCONFIG::$DB_CONNSTRING,DBCONFIG::$DB_USERNAME,DBCONFIG::$DB_PASSWORD);
        $stmt=$dbh->prepare($sql);
        $stmt->execute(array(':bestelNr'=>$bestelNr));
        $dbh=null;
    }

}