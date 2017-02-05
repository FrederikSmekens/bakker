<?php

require_once 'DBConfig.php'; 


class BestellijnDAO{
    
    public function addBestellijn($bestelNr,$winkelmandje,$aantal){
       
        $teller = 0;
        foreach ($winkelmandje as $product) 
        {  
            if(!empty($aantal[$teller])) //lege velden worden niet doorgestuurd
            {
            $sql = "INSERT INTO bestellijn (ProductId,BestelNr,AantalBesteld)
                    values (:ProductId,:BestelNr,:AantalBesteld)";  
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
            $stmt = $dbh->prepare($sql); 
            
            $stmt->execute(array(
                ':ProductId' => $product->ProductId,
                ':BestelNr'=>$bestelNr,
                ':AantalBesteld'=>$aantal[$teller])); 
            }
            $teller++;  
        }   
    } 
}