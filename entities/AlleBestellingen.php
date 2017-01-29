<?php

class AlleBestellingen{  
    private static $idMap=array();
    public $bestelNr,$klantId,$voornaam,$familienaam,$datum,$product,$aantalBesteld,$prijs;
    
    public function __construct($bestelNr,$klantId,$voornaam,$familienaam,$datum,$product,$aantalBesteld,$prijs) 
    {
        $this->BestelNr = $bestelNr;
        $this->KlantId = $klantId;
        $this->Voornaam = $voornaam;
        $this->Familienaam = $familienaam;
        $this->Datum = $datum;
        $this->Product = $product;
        $this->AantalBesteld = $aantalBesteld;
        $this->Prijs = $prijs;
        
    }
    
      public static function create($bestelNr,$klantId,$voornaam,$familienaam,$datum,$product,$aantalBesteld,$prijs)
    {
        if(!isset(self::$idMap[$bestelNr])) 
        {
            self::$idMap[$bestelNr] = new AlleBestellingen($bestelNr,$klantId,$voornaam,$familienaam,$datum,$product,$aantalBesteld,$prijs);
        }
        return self::$idMap[$bestelNr];
    }
}
